'use client'

import { useEffect, useState } from 'react'

interface Settlement {
  id: number
  date: string
  collector: string
  amount: number
  status: 'Settled' | 'Pending'
}

export default function SettlementsPage() {
  const [settlements, setSettlements] = useState<Settlement[]>([])
  const [loading, setLoading] = useState(true)

  useEffect(() => {
    // Using staff API as a placeholder - settlements would need its own API
    fetch('/api/staff')
      .then(r => r.json())
      .then(() => {
        // Mock settlement data
        setSettlements([
          { id: 1, date: '2026-06-13', collector: 'Rahul', amount: 4500, status: 'Settled' },
          { id: 2, date: '2026-06-12', collector: 'Priya', amount: 3200, status: 'Settled' },
          { id: 3, date: '2026-06-11', collector: 'Rahul', amount: 5100, status: 'Settled' },
          { id: 4, date: '2026-06-10', collector: 'Anita', amount: 2800, status: 'Pending' },
        ])
        setLoading(false)
      })
      .catch(() => setLoading(false))
  }, [])

  const totalCollected = settlements.filter(s => s.status === 'Settled').reduce((sum, s) => sum + s.amount, 0)
  const pendingCount = settlements.filter(s => s.status === 'Pending').length
  const lastSettlement = settlements.find(s => s.status === 'Settled')?.date || 'N/A'

  return (
    <div className="p-6 space-y-6">
      <h1 className="text-2xl font-bold text-zinc-900 dark:text-zinc-50">Settlements</h1>

      {/* Stats cards */}
      <div className="grid grid-cols-3 gap-4">
        <div className="rounded-xl bg-white dark:bg-zinc-900 p-4 shadow-sm border border-zinc-200 dark:border-zinc-800">
          <p className="text-sm text-zinc-500">Total Collected</p>
          <p className="text-2xl font-bold">₹{totalCollected.toLocaleString()}</p>
        </div>
        <div className="rounded-xl bg-white dark:bg-zinc-900 p-4 shadow-sm border border-zinc-200 dark:border-zinc-800">
          <p className="text-sm text-zinc-500">Pending</p>
          <p className="text-2xl font-bold text-amber-500">{pendingCount}</p>
        </div>
        <div className="rounded-xl bg-white dark:bg-zinc-900 p-4 shadow-sm border border-zinc-200 dark:border-zinc-800">
          <p className="text-sm text-zinc-500">Last Settlement</p>
          <p className="text-2xl font-bold">{lastSettlement}</p>
        </div>
      </div>

      {/* Settlements table */}
      <div className="rounded-xl bg-white dark:bg-zinc-900 shadow-sm border border-zinc-200 dark:border-zinc-800 overflow-hidden">
        <table className="w-full text-sm">
          <thead className="bg-zinc-50 dark:bg-zinc-800/50 text-zinc-500">
            <tr>
              <th className="text-left px-4 py-3 font-medium">Date</th>
              <th className="text-left px-4 py-3 font-medium">Collector</th>
              <th className="text-right px-4 py-3 font-medium">Amount (₹)</th>
              <th className="text-right px-4 py-3 font-medium">Status</th>
            </tr>
          </thead>
          <tbody>
            {settlements.map(settlement => (
              <tr key={settlement.id} className="border-t border-zinc-100 dark:border-zinc-800 hover:bg-zinc-50 dark:hover:bg-zinc-800/50">
                <td className="px-4 py-3">{settlement.date}</td>
                <td className="px-4 py-3 font-medium">{settlement.collector}</td>
                <td className="px-4 py-3 text-right font-medium">₹{settlement.amount.toLocaleString()}</td>
                <td className="px-4 py-3 text-right">
                  {settlement.status === 'Settled' 
                    ? <span className="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400">Settled</span>
                    : <span className="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400">Pending</span>}
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </div>
  )
}