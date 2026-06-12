@extends('layouts.app')

@section('title', 'Backup & Restore')

@section('content')

<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="page-title">Backup & Restore</h1>
        <p class="page-subtitle">Manage your database backups</p>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="card">
        <h2 class="card-title flex items-center gap-2">
            <span class="text-lg">📦</span> Backup Database
        </h2>
        
        <p class="text-sm text-gray-400 mb-4">
            Download a full SQL backup of all FlavorDesk data. This will create a .sql file containing all your tables and data.
        </p>

        @if($lastBackup)
            <p class="text-sm text-gray-400 mb-4">
                Last backup: <span class="text-white">{{ date('M d, Y H:i', $lastBackup) }}</span>
            </p>
        @else
            <p class="text-sm text-yellow-500 mb-4">
                No backup taken yet
            </p>
        @endif

        <form method="POST" action="{{ route('backup.download') }}">
            @csrf
            <button type="submit" class="btn-primary" w-full justify-center">
                <span class="flex items-center justify-center gap-2">
                    <span>⬇️</span> Download Backup
                </span>
            </button>
        </form>
    </div>

    <div class="card">
        <h2 class="card-title flex items-center gap-2">
            <span class="text-lg">⚠️</span> Restore Database
        </h2>
        
        <div class="bg-red-500/10 border border-red-500/30 rounded-lg p-3 mb-4">
            <p class="text-red-400 text-sm font-semibold">
                ⚠️ WARNING: This will overwrite ALL current data. This action cannot be undone.
            </p>
        </div>

        <p class="text-sm text-gray-400 mb-4">
            Upload a .sql file to restore your database. Make sure you have a current backup before restoring.
        </p>

        <form method="POST" action="{{ route('backup.restore') }}" enctype="multipart/form-data" onsubmit="return confirmRestore()">
            @csrf
            
            <div class="form-group">
                <label class="form-label">Backup File (.sql)</label>
                <input type="file" name="backup_file" class="form-input" accept=".sql,.txt" required>
                @error('backup_file')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="btn-danger w-full justify-center">
                <span class="flex items-center justify-center gap-2">
                    <span>🔄</span> Restore Database
                </span>
            </button>
        </form>
    </div>
</div>

@endsection

<script>
function confirmRestore() {
    return confirm('⚠️ WARNING: This will OVERWRITE all current data in your database.\n\nThis action cannot be undone! Are you sure you want to proceed?');
}
</script>
