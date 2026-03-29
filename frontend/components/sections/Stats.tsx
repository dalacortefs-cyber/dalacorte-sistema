'use client'
import { useEffect, useRef, useState } from 'react'

const stats = [
  { value: 13,  suffix: '+', prefix: '',  label: 'Anos de experiência' },
  { value: 100, suffix: '%', prefix: '',  label: 'Clientes satisfeitos' },
  { value: 8,   suffix: '',  prefix: '',  label: 'Áreas de atuação' },
  { value: 500, suffix: '+', prefix: '',  label: 'Declarações processadas' },
  { value: 1,   suffix: '',  prefix: '#', label: 'Escritório consultivo em Paracatu' },
]

function useCountUp(target: number, duration: number, active: boolean) {
  const [count, setCount] = useState(0)
  useEffect(() => {
    if (!active) return
    let current = 0
    const totalSteps = Math.ceil(duration / 16)
    const step = target / totalSteps
    const timer = setInterval(() => {
      current += step
      if (current >= target) {
        setCount(target)
        clearInterval(timer)
      } else {
        setCount(Math.floor(current))
      }
    }, 16)
    return () => clearInterval(timer)
  }, [active, target, duration])
  return count
}

function StatItem({
  value, suffix, prefix, label, active,
}: { value: number; suffix: string; prefix: string; label: string; active: boolean }) {
  const count = useCountUp(value, 2000, active)
  return (
    <div className="flex flex-col items-center text-center px-4 py-8">
      <p className="font-serif text-4xl md:text-5xl font-bold leading-none" style={{ color: '#C4A35A' }}>
        {prefix}{count}{suffix}
      </p>
      <p className="text-white/65 text-sm mt-3 leading-snug max-w-[120px]">{label}</p>
    </div>
  )
}

export default function Stats() {
  const ref = useRef<HTMLElement>(null)
  const [active, setActive] = useState(false)

  useEffect(() => {
    const observer = new IntersectionObserver(
      ([entry]) => {
        if (entry.isIntersecting) {
          setActive(true)
          observer.disconnect()
        }
      },
      { threshold: 0.25 }
    )
    if (ref.current) observer.observe(ref.current)
    return () => observer.disconnect()
  }, [])

  return (
    <section ref={ref} id="estatisticas" className="relative py-4 overflow-hidden" style={{ background: '#1B3D50' }}>

      {/* Grid overlay */}
      <div className="absolute inset-0 opacity-[0.04] pointer-events-none"
        style={{
          backgroundImage: 'linear-gradient(rgba(196,163,90,1) 1px, transparent 1px), linear-gradient(90deg, rgba(196,163,90,1) 1px, transparent 1px)',
          backgroundSize: '60px 60px',
        }}
      />

      {/* Top/bottom border lines */}
      <div className="absolute top-0 left-0 right-0 h-[1px] bg-gradient-to-r from-transparent via-[rgba(196,163,90,0.4)] to-transparent" />
      <div className="absolute bottom-0 left-0 right-0 h-[1px] bg-gradient-to-r from-transparent via-[rgba(196,163,90,0.2)] to-transparent" />

      <div className="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5">
          {stats.map((s, i) => (
            <div
              key={s.label}
              className={i < stats.length - 1 ? 'border-r border-white/10' : ''}
            >
              <StatItem {...s} active={active} />
            </div>
          ))}
        </div>
      </div>
    </section>
  )
}
