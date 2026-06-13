import { NextResponse } from 'next/server'

const mockOilData = [
  { month: 'Jan', total_liters: 42.5 },
  { month: 'Feb', total_liters: 38.2 },
  { month: 'Mar', total_liters: 51.0 },
  { month: 'Apr', total_liters: 47.8 },
  { month: 'May', total_liters: 55.3 },
  { month: 'Jun', total_liters: 49.1 },
]

export async function GET() {
  return NextResponse.json(mockOilData)
}
