'use client'

import Link from 'next/link'
import { usePathname } from 'next/navigation'

const navItems = [
  { href: '/', label: 'Dashboard', icon: '📊' },
  { href: '/inventory', label: 'Inventory', icon: '📦' },
  { href: '/oil-tracking', label: 'Oil Tracking', icon: '🛢️' },
  { href: '/expenses', label: 'Expenses', icon: '💰' },
  { href: '/sales', label: 'Sales', icon: '🧾' },
  { href: '/settlements', label: 'Settlements', icon: '🤝' },
  { href: '/staff', label: 'Staff', icon: '👥' },
]

export default function Sidebar() {
  const pathname = usePathname()

  return (
    <aside className="fixed left-0 top-0 h-full w-60 bg-white dark:bg-zinc-900 border-r border-zinc-200 dark:border-zinc-800 flex flex-col">
      <div className="p-4 border-b border-zinc-200 dark:border-zinc-800">
        <h2 className="text-lg font-bold tracking-tight">RishArt</h2>
        <p className="text-xs text-zinc-400">Inventory Dashboard</p>
      </div>
      <nav className="flex-1 py-2">
        {navItems.map(item => (
          <Link
            key={item.href}
            href={item.href}
            className={`flex items-center gap-3 px-4 py-2.5 text-sm transition-colors ${
              pathname === item.href
                ? 'bg-amber-50 dark:bg-amber-900/20 text-amber-700 dark:text-amber-400 font-medium border-r-2 border-amber-500'
                : 'text-zinc-600 dark:text-zinc-400 hover:bg-zinc-50 dark:hover:bg-zinc-800/50'
            }`}
          >
            <span>{item.icon}</span>
            {item.label}
          </Link>
        ))}
      </nav>
      <div className="p-4 border-t border-zinc-200 dark:border-zinc-800 text-xs text-zinc-400">
        <p>RishArt • Lord of Wraps</p>
        <p className="mt-1">Kerala, India</p>
      </div>
    </aside>
  )
}