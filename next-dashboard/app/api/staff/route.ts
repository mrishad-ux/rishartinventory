import { NextResponse } from 'next/server'

export async function GET() {
  const staff = [
    { id: 1, name: 'Rahul Kumar', role: 'Kitchen Head', phone: '+91 98765 43210' },
    { id: 2, name: 'Priya Nair', role: 'Floor Manager', phone: '+91 98765 43211' },
    { id: 3, name: 'Anita Sebastian', role: 'Cashier', phone: '+91 98765 43212' },
    { id: 4, name: 'Mohammed Faisal', role: 'Chef', phone: '+91 98765 43213' },
    { id: 5, name: 'Lakshmi Menon', role: 'Server', phone: '+91 98765 43214' },
    { id: 6, name: 'Rajesh Pillai', role: 'Delivery Partner', phone: '+91 98765 43215' },
  ]

  return NextResponse.json(staff)
}