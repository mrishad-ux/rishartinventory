<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;

class BackupController extends Controller
{
    public function index()
    {
        $lastBackup = null;
        $backupPath = storage_path('app/backups');
        if (File::exists($backupPath)) {
            $files = File::files($backupPath);
            if (!empty($files)) {
                usort($files, fn($a, $b) => $b->getMTime() - $a->getMTime());
                $lastBackup = $files[0]->getMTime();
            }
        }

        return view('backup.index', compact('lastBackup'));
    }

    public function backup(Request $request)
    {
        $filename = 'flavordesk_backup_' . date('Y-m-d_H-i') . '.sql';
        $tempPath = storage_path('app/temp_' . $filename);
        $backupPath = storage_path('app/backups');

        if (!File::exists($backupPath)) {
            File::makeDirectory($backupPath, 0755, true);
        }

        $dbHost = config('database.connections.mysql.host', '127.0.0.1');
        $dbUser = config('database.connections.mysql.username', 'root');
        $dbPass = config('database.connections.mysql.password', '');
        $dbName = config('database.connections.mysql.database', 'flavordesk');

        $mysqldumpPath = $this->findMysqldump();

        if ($mysqldumpPath && function_exists('exec')) {
            $command = sprintf(
                '"%s" --user=%s --password=%s --host=%s %s',
                $mysqldumpPath,
                escapeshellarg($dbUser),
                escapeshellarg($dbPass),
                escapeshellarg($dbHost),
                escapeshellarg($dbName)
            );

            exec($command, $output, $returnCode);

            if ($returnCode !== 0 || !File::exists($tempPath)) {
                File::delete($tempPath);
                return back()->with('error', 'Backup failed using mysqldump. Trying PHP method...');
            }
            
            // Prepend SET SESSION sql_mode='' to the backup file
            $sqlModeLine = "SET SESSION sql_mode='';\n";
            $existingContent = File::get($tempPath);
            File::put($tempPath, $sqlModeLine . $existingContent);
        } else {
            $this->createBackupPhp($tempPath, $dbName);
        }

        $finalPath = $backupPath . '/' . $filename;
        File::move($tempPath, $finalPath);

        return response()->download($finalPath, $filename)->deleteFileAfterSend(true);
    }

    private function findMysqldump()
    {
        $paths = [
            'mysqldump',
            'C:\xampp\mysql\bin\mysqldump.exe',
            'C:\wamp\bin\mysql\mysql5.7.31\bin\mysqldump.exe',
            'C:\wamp64\bin\mysql\mysql5.7.31\bin\mysqldump.exe',
            'C:\wamp\bin\mysql\mysql8.0.21\bin\mysqldump.exe',
            'C:\wamp64\bin\mysql\mysql8.0.21\bin\mysqldump.exe',
            '/usr/bin/mysqldump',
            '/usr/local/bin/mysqldump',
            '/opt/lampp/bin/mysqldump',
        ];

        foreach ($paths as $path) {
            if ($path === 'mysqldump') {
                $result = [];
                exec('where mysqldump 2>nul', $result);
                if (!empty($result)) {
                    return trim($result[0]);
                }
            } elseif (file_exists($path)) {
                return $path;
            }
        }

        return null;
    }

    private function createBackupPhp($filePath, $dbName)
    {
        $tables = DB::select('SHOW TABLES');
        $tableKey = 'Tables_in_' . $dbName;

        $sql = "SET SESSION sql_mode='';\n";
        $sql .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

        foreach ($tables as $table) {
            $tableName = $table->$tableKey;

            $createResult = DB::select("SHOW CREATE TABLE `$tableName`");
            $createSql = $createResult[0]->{'Create Table'};

            $sql .= "DROP TABLE IF EXISTS `$tableName`;\n";
            $sql .= $createSql . ";\n\n";

            $rows = DB::select("SELECT * FROM `$tableName`");
            if (!empty($rows)) {
                foreach ($rows as $row) {
                    $columns = [];
                    $values = [];
                    foreach ($row as $key => $value) {
                        // Skip generated/computed columns for inventory_logs table
                        if ($tableName === 'inventory_logs' && in_array($key, ['total', 'closing'])) {
                            continue;
                        }
                        $columns[] = "`$key`";
                        if (is_null($value)) {
                            $values[] = "NULL";
                        } else {
                            $values[] = "'" . addslashes($value) . "'";
                        }
                    }
                    $sql .= "INSERT INTO `$tableName` (" . implode(', ', $columns) . ") VALUES (" . implode(', ', $values) . ");\n";
                }
                $sql .= "\n";
            }
        }

        $sql .= "SET FOREIGN_KEY_CHECKS=1;\n";

        File::put($filePath, $sql);
    }

    public function restore(Request $request)
    {
        $request->validate([
            'backup_file' => 'required|file|mimes:sql,txt|max:51200',
        ]);

        $file = $request->file('backup_file');
        $content = file_get_contents($file->getRealPath());

        if (empty(trim($content))) {
            return back()->with('error', 'The backup file is empty.');
        }

        try {
            DB::unprepared('SET FOREIGN_KEY_CHECKS=0');

            $statements = $this->splitSqlStatements($content);

            foreach ($statements as $statement) {
                $statement = trim($statement);
                if (!empty($statement)) {
                    DB::unprepared($statement);
                }
            }

            DB::unprepared('SET FOREIGN_KEY_CHECKS=1');

            return back()->with('success', 'Database restored successfully. All data has been overwritten.');
        } catch (\Exception $e) {
            DB::unprepared('SET FOREIGN_KEY_CHECKS=1');
            return back()->with('error', 'Restore failed: ' . $e->getMessage());
        }
    }

    private function splitSqlStatements($sql)
    {
        $statements = [];
        $currentStatement = '';
        $inString = false;
        $stringChar = '';
        $len = strlen($sql);

        for ($i = 0; $i < $len; $i++) {
            $char = $sql[$i];
            $nextChar = $i + 1 < $len ? $sql[$i + 1] : '';

            if (!$inString && ($char === '"' || $char === "'")) {
                $inString = true;
                $stringChar = $char;
            } elseif ($inString && $char === $stringChar && $nextChar === $stringChar) {
                $currentStatement .= $char . $nextChar;
                $i++;
                continue;
            } elseif ($inString && $char === $stringChar) {
                $inString = false;
                $currentStatement .= $char;
                continue;
            } elseif (!$inString && $char === ';') {
                $statements[] = $currentStatement;
                $currentStatement = '';
                continue;
            }

            $currentStatement .= $char;
        }

        if (trim($currentStatement)) {
            $statements[] = $currentStatement;
        }

        return $statements;
    }
}
