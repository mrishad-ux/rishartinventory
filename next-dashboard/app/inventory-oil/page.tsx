'use client'

import { useEffect, useState } from 'react'

interface OilRecord {
  id: number
  date: string
  fryer: string
  old_oil_liters: number
  new_oil_liters: number
  status: string
}

export default function InventoryOilPage() {
  const [records, setRecords] = useState<OilRecord[]>([])
  const [loading, setLoading] = useState(true)

  useEffect(() => {
    fetch('/api/inventory-oil/monthly-report')
      .then(r => r.json())
      .then(data => {
        // Convert array to mock records format
        const mockRecords: OilRecord[] = data.length > 0 ? [
          { id: 1, date: '2026-06-13', fryer: 'Fryer 1', old_oil_liters: 8, new_oil_liters: 10, status: 'Changed' },
          { id: 2, date: '2026-06-10', fryer: 'Fryer 2', old_oil_liters: 9, new_oil_liters: 10, status: 'Changed' },
          { id: 3, date: '2026-06-08', fryer: 'Fryer 1', old_oil_liters: 7, new_oil_liters: 10, status: 'Changed' },
          { id: 4, date: '2026-06-05', fryer: 'Fryer 3', old_oil_liters: 10, new_oil_liters: 10, status: 'Changed' },
        ] : []
        setRecords(mockRecords)
        setLoading(false)
      })
      .catch(() => setLoading(false))
  }, [])

  const totalThisMonth = records.reduce((sum, r) => sum + r.new_oil_liters, 0)
  const avgDaily = records.length > 0 ? (totalThisMonth / 30).toFixed(1) : '0'
  const lastChanged = records.length > 0 ? records[0].date : 'N/A'

  return (
    <div className="p-6 space-y-6">
      <h1 className="text-2xl font-bold text-zinc-900 dark:text-zinc-50">Oil Tracking</h1>

      {/* Stats cards */}
      <div className="grid grid-cols-3 gap-4">
        <div className="rounded-xl bg-white dark:bg-zinc-900 p-4 shadow-sm border border-zinc-200 dark:border-zinc-800">
          <p className="text-sm text-zinc-500">Total This Month</p>
          <p className="text-2xl font-bold">{totalThisMonth} L</p>
        </div>
        <div className="rounded-xl bg-white dark:bg-zinc-900 p-4 shadow-sm border border-zinc-200 dark:border-zinc-800">
          <p className="text-sm text-zinc-500">Avg Daily Usage</p>
          <p className="text-2xl font-bold">{avgDaily} L</p>
        </div>
        <div className="rounded-xl bg-white dark:bg-zinc-900 p-4 shadow-sm border border-zinc-200 dark:border-zinc-800">
          <p className="text-sm text-zinc-500">Last Changed</p>
          <p className="text-2xl font-bold">{lastChanged}</p>
        </div>
      </div>

      {/* Oil records table */}
      <div className="rounded-xl bg-white dark:bg-zinc-900 shadow-sm border border-zinc-200 dark:border-zinc-800 overflow-hidden">
        <table className="w-full text-sm">
          <thead className="bg-zinc-50 dark:bg-zinc-800/50 text-zinc-500">
            <tr>
              <th className="text-left px-4 py-3 font-medium">Date</th>
              <th className="text-left px-4 py-3 font-medium">Fryer</th>
              <th className="text-right px-4 py-3 font-medium">Old Oil (L)</th>
              <th className="text-right px-4 py-3 font-medium">New Oil (L)</th>
              <th className="text-right px-4 py-3 font-medium">Status</th>
            </tr>
          </thead>
          <tbody>
            {records.map(record => (
              <tr key={record.id} className="border-t border-zinc-100 dark:border-zinc-800 hover:bg-zinc-50 dark:hover:bg-zinc-800/50">
                <td className="px-4 py-3">{record.date}</td>
                <td className="px-4 py-3 font-medium">{record.fryer}</td>
                <td className="px-4 py-3 text-right text-zinc-600 dark:text-zinc-400">{record.old_oil_liters}</td>
                <td className="px-4 py-3 text-right">{record.new_oil_liters}</td>
                <td className="px-4 py-3 text-right">
                  <span className="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400">{record.status}</span>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </div>
  )
}