'use client'

import { useEffect, useState } from 'react'

interface InventoryItem {
  id: number
  name: string
  category: string
  quantity: number
  unit: string
  min_stock: number
  price?: number
}

export default function InventoryPage() {
  const [items, setItems] = useState<InventoryItem[]>([])
  const [loading, setLoading] = useState(true)

  useEffect(() => {
    fetch('/api/inventory')
      .then(r => r.json())
      .then(data => {
        setItems(data)
        setLoading(false)
      })
      .catch(() => setLoading(false))
  }, [])

  const totalItems = items.length
  const lowStockCount = items.filter(i => i.quantity <= i.min_stock).length
  const totalValue = items.reduce((sum, i) => sum + (i.price || 0) * i.quantity, 0)

  return (
    <div className="p-6 space-y-6">
      <div className="flex items-center justify-between">
        <h1 className="text-2xl font-bold text-zinc-900 dark:text-zinc-50">Inventory</h1>
        <button className="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-lg font-medium transition-colors">
          + Add Item
        </button>
      </div>

      {/* Stats cards */}
      <div className="grid grid-cols-3 gap-4">
        <div className="rounded-xl bg-white dark:bg-zinc-900 p-4 shadow-sm border border-zinc-200 dark:border-zinc-800">
          <p className="text-sm text-zinc-500">Total Items</p>
          <p className="text-2xl font-bold">{totalItems}</p>
        </div>
        <div className="rounded-xl bg-white dark:bg-zinc-900 p-4 shadow-sm border border-zinc-200 dark:border-zinc-800">
          <p className="text-sm text-zinc-500">Low Stock</p>
          <p className="text-2xl font-bold text-amber-500">{lowStockCount}</p>
        </div>
        <div className="rounded-xl bg-white dark:bg-zinc-900 p-4 shadow-sm border border-zinc-200 dark:border-zinc-800">
          <p className="text-sm text-zinc-500">Total Value</p>
          <p className="text-2xl font-bold">₹{totalValue.toLocaleString()}</p>
        </div>
      </div>

      {/* Inventory table */}
      <div className="rounded-xl bg-white dark:bg-zinc-900 shadow-sm border border-zinc-200 dark:border-zinc-800 overflow-hidden">
        <table className="w-full text-sm">
          <thead className="bg-zinc-50 dark:bg-zinc-800/50 text-zinc-500">
            <tr>
              <th className="text-left px-4 py-3 font-medium">Name</th>
              <th className="text-left px-4 py-3 font-medium">Category</th>
              <th className="text-right px-4 py-3 font-medium">Quantity</th>
              <th className="text-right px-4 py-3 font-medium">Unit</th>
              <th className="text-right px-4 py-3 font-medium">Min Stock</th>
              <th className="text-right px-4 py-3 font-medium">Status</th>
            </tr>
          </thead>
          <tbody>
            {items.map(item => (
              <tr key={item.id} className="border-t border-zinc-100 dark:border-zinc-800 hover:bg-zinc-50 dark:hover:bg-zinc-800/50">
                <td className="px-4 py-3 font-medium">{item.name}</td>
                <td className="px-4 py-3 text-zinc-600 dark:text-zinc-400">{item.category}</td>
                <td className="px-4 py-3 text-right">{item.quantity}</td>
                <td className="px-4 py-3 text-right text-zinc-400">{item.unit}</td>
                <td className="px-4 py-3 text-right text-zinc-400">{item.min_stock}</td>
                <td className="px-4 py-3 text-right">
                  {item.quantity <= item.min_stock 
                    ? <span className="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400">Low</span>
                    : <span className="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400">OK</span>}
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </div>
  )
}