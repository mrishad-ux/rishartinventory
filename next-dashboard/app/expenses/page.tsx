'use client'

import { useEffect, useState } from 'react'

interface Expense {
  id: number
  date: string
  category: string
  description: string
  amount: number
  status: 'Paid' | 'Pending'
}

export default function ExpensesPage() {
  const [expenses, setExpenses] = useState<Expense[]>([])
  const [loading, setLoading] = useState(true)

  useEffect(() => {
    fetch('/api/expenses')
      .then(r => r.json())
      .then(data => {
        setExpenses(data)
        setLoading(false)
      })
      .catch(() => setLoading(false))
  }, [])

  const totalThisMonth = expenses.reduce((sum, e) => sum + e.amount, 0)
  const categoryTotals: Record<string, number> = {}
  expenses.forEach(e => {
    categoryTotals[e.category] = (categoryTotals[e.category] || 0) + e.amount
  })
  const topCategory = Object.entries(categoryTotals).sort((a, b) => b[1] - a[1])[0]?.[0] || 'N/A'
  const pendingCount = expenses.filter(e => e.status === 'Pending').length

  return (
    <div className="p-6 space-y-6">
      <h1 className="text-2xl font-bold text-zinc-900 dark:text-zinc-50">Expenses</h1>

      {/* Stats cards */}
      <div className="grid grid-cols-3 gap-4">
        <div className="rounded-xl bg-white dark:bg-zinc-900 p-4 shadow-sm border border-zinc-200 dark:border-zinc-800">
          <p className="text-sm text-zinc-500">Total This Month</p>
          <p className="text-2xl font-bold">₹{totalThisMonth.toLocaleString()}</p>
        </div>
        <div className="rounded-xl bg-white dark:bg-zinc-900 p-4 shadow-sm border border-zinc-200 dark:border-zinc-800">
          <p className="text-sm text-zinc-500">Top Category</p>
          <p className="text-2xl font-bold">{topCategory}</p>
        </div>
        <div className="rounded-xl bg-white dark:bg-zinc-900 p-4 shadow-sm border border-zinc-200 dark:border-zinc-800">
          <p className="text-sm text-zinc-500">Pending Payments</p>
          <p className="text-2xl font-bold text-amber-500">{pendingCount}</p>
        </div>
      </div>

      {/* Expenses table */}
      <div className="rounded-xl bg-white dark:bg-zinc-900 shadow-sm border border-zinc-200 dark:border-zinc-800 overflow-hidden">
        <table className="w-full text-sm">
          <thead className="bg-zinc-50 dark:bg-zinc-800/50 text-zinc-500">
            <tr>
              <th className="text-left px-4 py-3 font-medium">Date</th>
              <th className="text-left px-4 py-3 font-medium">Category</th>
              <th className="text-left px-4 py-3 font-medium">Description</th>
              <th className="text-right px-4 py-3 font-medium">Amount (₹)</th>
              <th className="text-right px-4 py-3 font-medium">Status</th>
            </tr>
          </thead>
          <tbody>
            {expenses.map(expense => (
              <tr key={expense.id} className="border-t border-zinc-100 dark:border-zinc-800 hover:bg-zinc-50 dark:hover:bg-zinc-800/50">
                <td className="px-4 py-3">{expense.date}</td>
                <td className="px-4 py-3">
                  <span className="px-2 py-0.5 rounded-full text-xs font-medium bg-zinc-100 dark:bg-zinc-800">{expense.category}</span>
                </td>
                <td className="px-4 py-3">{expense.description}</td>
                <td className="px-4 py-3 text-right font-medium">₹{expense.amount.toLocaleString()}</td>
                <td className="px-4 py-3 text-right">
                  {expense.status === 'Paid' 
                    ? <span className="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400">Paid</span>
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