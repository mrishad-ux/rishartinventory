'use client'

import { useEffect, useState } from 'react'

interface Staff {
  id: number
  name: string
  role: string
  phone: string
  photo?: string
}

export default function StaffPage() {
  const [staff, setStaff] = useState<Staff[]>([])
  const [loading, setLoading] = useState(true)

  useEffect(() => {
    fetch('/api/staff')
      .then(r => r.json())
      .then(data => {
        setStaff(data)
        setLoading(false)
      })
      .catch(() => setLoading(false))
  }, [])

  return (
    <div className="p-6 space-y-6">
      <h1 className="text-2xl font-bold text-zinc-900 dark:text-zinc-50">Staff</h1>

      {/* Stats cards */}
      <div className="grid grid-cols-1 gap-4 max-w-xs">
        <div className="rounded-xl bg-white dark:bg-zinc-900 p-4 shadow-sm border border-zinc-200 dark:border-zinc-800">
          <p className="text-sm text-zinc-500">Total Staff</p>
          <p className="text-2xl font-bold">{staff.length}</p>
        </div>
      </div>

      {/* Staff grid */}
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        {staff.map(member => (
          <div key={member.id} className="rounded-xl bg-white dark:bg-zinc-900 p-4 shadow-sm border border-zinc-200 dark:border-zinc-800">
            <div className="flex items-center gap-4">
              <div className="w-16 h-16 rounded-full bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center text-white text-xl font-bold">
                {member.name.charAt(0)}
              </div>
              <div>
                <h3 className="font-semibold text-lg">{member.name}</h3>
                <p className="text-sm text-zinc-500">{member.role}</p>
                <p className="text-sm text-zinc-400">{member.phone}</p>
              </div>
            </div>
          </div>
        ))}
      </div>
    </div>
  )
}