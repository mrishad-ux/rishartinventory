'use client'

import { BarChart as VBarChart } from '@visactor/react-vchart'

interface BarData {
  month: string
  total_liters: number
}

export function BarChart({ data }: { data: BarData[] }) {
  const spec = {
    type: 'bar',
    data: [{ values: data }],
    xField: 'month',
    yField: 'total_liters',
    seriesField: 'month',
    bar: {
      style: {
        fill: '#f59e0b',
        cornerRadius: [4, 4, 0, 0],
      },
    },
    axes: [
      { orient: 'bottom', label: { style: { fill: '#71717a' } } },
      { orient: 'left', label: { style: { fill: '#71717a' } } },
    ],
    tooltip: { visible: true },
  }

  return <VBarChart spec={spec as any} className="w-full h-64" />
}
