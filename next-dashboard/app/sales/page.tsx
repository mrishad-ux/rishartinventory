'use client'

import { useEffect, useState } from 'react'

interface Sale {
  id: number
  time: string
  items: string
  total: number
}

export default function SalesPage() {
  const [sales, setSales] = useState<Sale[]>([])
  const [loading, setLoading] = useState(true)

  useEffect(() => {
    fetch('/api/sales')
      .then(r => r.json())
      .then(data => {
        setSales(data)
        setLoading(false)
      })
      .catch(() => setLoading(false))
  }, [])

  const todayTotal = sales.reduce((sum, s) => sum + s.total, 0)
  const orderCount = sales.length
  const avgOrderValue = orderCount > 0 ? Math.round(todayTotal / orderCount) : 0

  return (
    <div className="p-6 space-y-6">
      <h1 className="text-2xl font-bold text-zinc-900 dark:text-zinc-50">Sales</h1>

      {/* Stats cards */}
      <div className="grid grid-cols-4 gap-4">
        <div className="rounded-xl bg-white dark:bg-zinc-900 p-4 shadow-sm border border-zinc-200 dark:border-zinc-800">
          <p className="text-sm text-zinc-500">Today&apos;s Sales</p>
          <p className="text-2xl font-bold">₹{todayTotal.toLocaleString()}</p>
        </div>
        <div className="rounded-xl bg-white dark:bg-zinc-900 p-4 shadow-sm border border-zinc-200 dark:border-zinc-800">
          <p className="text-sm text-zinc-500">Orders</p>
          <p className="text-2xl font-bold">{orderCount}</p>
        </div>
        <div className="rounded-xl bg-white dark:bg-zinc-900 p-4 shadow-sm border border-zinc-200 dark:border-zinc-800">
          <p className="text-sm text-zinc-500">Avg Order Value</p>
          <p className="text-2xl font-bold">₹{avgOrderValue}</p>
        </div>
        <div className="rounded-xl bg-white dark:bg-zinc-900 p-4 shadow-sm border border-zinc-200 dark:border-zinc-800">
          <p className="text-sm text-zinc-500">vs Yesterday</p>
          <p className="text-2xl font-bold text-emerald-500">+12%</p>
        </div>
      </div>

      {/* Sales table */}
      <div className="rounded-xl bg-white dark:bg-zinc-900 shadow-sm border border-zinc-200 dark:border-zinc-800 overflow-hidden">
        <table className="w-full text-sm">
          <thead className="bg-zinc-50 dark:bg-zinc-800/50 text-zinc-500">
            <tr>
              <th className="text-left px-4 py-3 font-medium">Time</th>
              <th className="text-left px-4 py-3 font-medium">Items</th>
              <th className="text-right px-4 py-3 font-medium">Total (₹)</th>
            </tr>
          </thead>
          <tbody>
            {sales.map(sale => (
              <tr key={sale.id} className="border-t border-zinc-100 dark:border-zinc-800 hover:bg-zinc-50 dark:hover:bg-zinc-800/50">
                <td className="px-4 py-3">{sale.time}</td>
                <td className="px-4 py-3">{sale.items}</td>
                <td className="px-4 py-3 text-right font-medium">₹{sale.total.toLocaleString()}</td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </div>
  )
}