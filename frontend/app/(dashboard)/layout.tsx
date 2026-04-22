'use client'
import { useEffect, useState } from 'react'
import { useRouter } from 'next/navigation'
import Sidebar from '@/components/layout/Sidebar'
import { Bell, Search, ChevronDown } from 'lucide-react'
import { useAuth } from '@/hooks/useAuth'
import { getInitials } from '@/lib/utils'

export default function DashboardLayout({ children }: { children: React.ReactNode }) {
  const { user, loading } = useAuth()
  const router = useRouter()
  const [scrolled, setScrolled] = useState(false)

  useEffect(() => {
    if (!loading && !user) router.push('/login')
  }, [user, loading])

  if (loading) return (
    <div className="min-h-screen flex items-center justify-center" style={{ background: '#060f1a' }}>
      <div className="flex flex-col items-center gap-4">
        <div
          className="w-12 h-12 rounded-2xl flex items-center justify-center"
          style={{
            background: 'linear-gradient(135deg, #8B6B3D 0%, #C4A35A 100%)',
            boxShadow: '0 0 30px rgba(196,163,90,0.4)',
            animation: 'pulse 2s ease-in-out infinite',
          }}
        >
          <span className="text-white font-bold font-serif">D</span>
        </div>
        <div
          className="w-5 h-5 rounded-full border-2"
          style={{
            borderColor: 'rgba(196,163,90,0.3)',
            borderTopColor: '#C4A35A',
            animation: 'spin 0.8s linear infinite',
          }}
        />
      </div>
    </div>
  )

  return (
    <div className="flex h-screen overflow-hidden" style={{ background: '#f0f2f5' }}>
      <Sidebar />

      <div className="flex-1 flex flex-col overflow-hidden min-w-0">
        {/* Header */}
        <header
          className="h-14 flex items-center justify-between px-6 shrink-0 transition-all duration-200"
          style={{
            background: 'rgba(255,255,255,0.9)',
            backdropFilter: 'blur(12px)',
            WebkitBackdropFilter: 'blur(12px)',
            borderBottom: '1px solid rgba(0,0,0,0.06)',
            boxShadow: '0 1px 20px rgba(0,0,0,0.04)',
          }}
        >
          {/* Search */}
          <div className="relative">
            <Search size={14} className="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400" />
            <input
              className="bg-gray-100 rounded-xl pl-9 pr-4 py-2 text-sm w-60 focus:outline-none focus:ring-2 focus:w-72 transition-all duration-300 placeholder:text-gray-400 text-gray-700"
              style={{ '--tw-ring-color': 'rgba(196,163,90,0.4)' } as React.CSSProperties}
              placeholder="Buscar..."
            />
          </div>

          {/* Right actions */}
          <div className="flex items-center gap-2">
            {/* Notification bell */}
            <button className="relative w-9 h-9 rounded-xl flex items-center justify-center transition-all hover:bg-gray-100 text-gray-500 hover:text-gray-700">
              <Bell size={17} />
              <span
                className="absolute top-2 right-2 w-2 h-2 rounded-full"
                style={{
                  background: '#C4A35A',
                  boxShadow: '0 0 6px rgba(196,163,90,0.7)',
                }}
              />
            </button>

            {/* Profile */}
            <div className="flex items-center gap-2.5 pl-2 pr-3 py-1.5 rounded-xl cursor-pointer transition-all hover:bg-gray-100 group">
              <div
                className="w-7 h-7 rounded-full flex items-center justify-center text-white text-xs font-bold"
                style={{ background: 'linear-gradient(135deg, #8B6B3D 0%, #C4A35A 100%)' }}
              >
                {user ? getInitials(user.name) : 'U'}
              </div>
              <div className="hidden sm:block">
                <p className="text-xs font-semibold text-gray-700 leading-tight">
                  {user?.name?.split(' ')[0]}
                </p>
                <p className="text-[10px] text-gray-400 capitalize leading-tight">{user?.tipo}</p>
              </div>
              <ChevronDown size={12} className="text-gray-400 group-hover:text-gray-600 transition-colors" />
            </div>
          </div>
        </header>

        {/* Page content */}
        <main className="flex-1 overflow-y-auto p-6">
          {children}
        </main>
      </div>
    </div>
  )
}
