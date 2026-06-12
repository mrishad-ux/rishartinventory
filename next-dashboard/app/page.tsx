'use client'

import { useEffect, useState } from 'react'
import { BarChart } from '@/components/BarChart'

interface InventoryItem {
  id: number
  name: string
  quantity: number
  unit: string
  min_stock: number
}

export default function DashboardPage() {
  const [inventory, setInventory] = useState<InventoryItem[]>([])
  const [oilData, setOilData] = useState<{ month: string; total_liters: number }[]>([])
  const [loading, setLoading] = useState(true)

  useEffect(() => {
    Promise.all([
      fetch('http://localhost:8000/api/inventory').then(r => r.json()).catch(() => []),
      fetch('http://localhost:8000/api/inventory-oil/monthly-report').then(r => r.json()).catch(() => [])
    ]).then(([inv, oil]) => {
      setInventory(inv)
      setOilData(oil)
      setLoading(false)
    })
  }, [])

  if (loading) return <div className="p-8 text-zinc-500">Loading...</div>

  return (
    <div className="p-6 space-y-8">
      <h1 className="text-2xl font-bold text-zinc-900 dark:text-zinc-50">Rishart Inventory</h1>
      
      {/* Stats cards */}
      <div className="grid grid-cols-3 gap-4">
        <div className="rounded-xl bg-white dark:bg-zinc-900 p-4 shadow-sm border border-zinc-200 dark:border-zinc-800">
          <p className="text-sm text-zinc-500">Total Items</p>
          <p className="text-2xl font-bold">{inventory.length}</p>
        </div>
        <div className="rounded-xl bg-white dark:bg-zinc-900 p-4 shadow-sm border border-zinc-200 dark:border-zinc-800">
          <p className="text-sm text-zinc-500">Low Stock</p>
          <p className="text-2xl font-bold text-amber-500">{inventory.filter(i => i.quantity <= i.min_stock).length}</p>
        </div>
        <div className="rounded-xl bg-white dark:bg-zinc-900 p-4 shadow-sm border border-zinc-200 dark:border-zinc-800">
          <p className="text-sm text-zinc-500">Oil (This Month)</p>
          <p className="text-2xl font-bold">{oilData[0]?.total_liters.toFixed(1) || 0} L</p>
        </div>
      </div>

      {/* Inventory table */}
      <div className="rounded-xl bg-white dark:bg-zinc-900 shadow-sm border border-zinc-200 dark:border-zinc-800 overflow-hidden">
        <div className="px-4 py-3 border-b border-zinc-200 dark:border-zinc-800 font-semibold">Inventory Items</div>
        <table className="w-full text-sm">
          <thead className="bg-zinc-50 dark:bg-zinc-800/50 text-zinc-500">
            <tr>
              <th className="text-left px-4 py-2">Name</th>
              <th className="text-right px-4 py-2">Qty</th>
              <th className="text-right px-4 py-2">Unit</th>
              <th className="text-right px-4 py-2">Min</th>
              <th className="text-right px-4 py-2">Status</th>
            </tr>
          </thead>
          <tbody>
            {inventory.map(item => (
              <tr key={item.id} className="border-t border-zinc-100 dark:border-zinc-800">
                <td className="px-4 py-2 font-medium">{item.name}</td>
                <td className="px-4 py-2 text-right">{item.quantity}</td>
                <td className="px-4 py-2 text-right text-zinc-400">{item.unit}</td>
                <td className="px-4 py-2 text-right text-zinc-400">{item.min_stock}</td>
                <td className="px-4 py-2 text-right">
                  {item.quantity <= item.min_stock 
                    ? <span className="text-amber-500 font-medium">Low</span>
                    : <span className="text-emerald-500">OK</span>}
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>

      {/* Oil consumption chart */}
      {oilData.length > 0 && (
        <div className="rounded-xl bg-white dark:bg-zinc-900 p-4 shadow-sm border border-zinc-200 dark:border-zinc-800">
          <h2 className="font-semibold mb-4">Monthly Oil Consumption</h2>
          <BarChart data={oilData} />
        </div>
      )}
    </div>
  )
}