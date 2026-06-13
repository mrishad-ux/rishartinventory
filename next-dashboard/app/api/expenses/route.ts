import { NextResponse } from 'next/server'

export async function GET() {
  const expenses = [
    { id: 1, date: '2026-06-13', category: 'Supplies', description: 'Vegetable order - Fresh Mart', amount: 2500, status: 'Paid' },
    { id: 2, date: '2026-06-12', category: 'Utilities', description: 'Electricity bill - June', amount: 4200, status: 'Pending' },
    { id: 3, date: '2026-06-11', category: 'Supplies', description: 'Chicken & meat - Premium Meats', amount: 5800, status: 'Paid' },
    { id: 4, date: '2026-06-10', category: 'Maintenance', description: 'Fryer repair - TechFix', amount: 1500, status: 'Paid' },
    { id: 5, date: '2026-06-09', category: 'Supplies', description: 'Spices & condiments - Indian Mart', amount: 1800, status: 'Pending' },
    { id: 6, date: '2026-06-08', category: 'Cleaning', description: 'Cleaning supplies - ABC Corp', amount: 750, status: 'Paid' },
    { id: 7, date: '2026-06-07', category: 'Supplies', description: 'Beverages restock', amount: 2200, status: 'Paid' },
    { id: 8, date: '2026-06-06', category: 'Rent', description: 'June rent payment', amount: 15000, status: 'Paid' },
  ]

  return NextResponse.json(expenses)
}