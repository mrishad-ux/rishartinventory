import { NextResponse } from 'next/server'

const mockInventory = [
  { id: 1, name: 'Chicken Fillets', quantity: 45, unit: 'kg', min_stock: 20, category: 'Meat' },
  { id: 2, name: 'Pizza Base (10")', quantity: 80, unit: 'pcs', min_stock: 30, category: 'Dry' },
  { id: 3, name: 'Olive Oil', quantity: 8, unit: 'L', min_stock: 5, category: 'Oil' },
  { id: 4, name: 'Mozzarella', quantity: 12, unit: 'kg', min_stock: 10, category: 'Dairy' },
  { id: 5, name: 'Dough Balls', quantity: 25, unit: 'pcs', min_stock: 15, category: 'Dry' },
  { id: 6, name: 'Tomato Sauce', quantity: 18, unit: 'L', min_stock: 8, category: 'Sauce' },
  { id: 7, name: 'Onion', quantity: 5, unit: 'kg', min_stock: 10, category: 'Veg' },
  { id: 8, name: 'Pepperoni', quantity: 30, unit: 'pcs', min_stock: 15, category: 'Meat' },
]

export async function GET() {
  return NextResponse.json(mockInventory)
}
