import { NextResponse } from 'next/server'

export async function GET() {
  const sales = [
    { id: 1, time: '10:30 AM', items: 'Chicken Wrap, Fries, Coke', total: 280 },
    { id: 2, time: '11:15 AM', items: 'Paneer Wrap, Mango Lassi', total: 220 },
    { id: 3, time: '12:00 PM', items: 'Family Pack (4 wraps), 2L Pepsi', total: 850 },
    { id: 4, time: '12:45 PM', items: 'Chicken Wrap, Falafel Wrap, Fries', total: 380 },
    { id: 5, time: '1:30 PM', items: 'Veg Wrap, Coke', total: 160 },
    { id: 6, time: '2:15 PM', items: '2x Chicken Wraps, 2x Fries, 2x Coke', total: 520 },
    { id: 7, time: '6:00 PM', items: 'Family Pack, Dessert Bundle', total: 950 },
    { id: 8, time: '7:30 PM', items: 'Mixed Wraps (3), Large Pepsi', total: 640 },
    { id: 9, time: '8:15 PM', items: 'Chicken Wrap, Zinger Box', total: 350 },
    { id: 10, time: '9:00 PM', items: '2x Veg Wraps, Fries, Shake', total: 420 },
  ]

  return NextResponse.json(sales)
}