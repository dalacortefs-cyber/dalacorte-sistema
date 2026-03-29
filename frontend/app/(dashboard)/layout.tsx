'use client'
import { useEffect } from 'react'
import { useRouter } from 'next/navigation'
import Sidebar from '@/components/layout/Sidebar'
import { Bell, Search } from 'lucide-react'
import { useAuth } from '@/hooks/useAuth'
import { getInitials } from '@/lib/utils'

export default function DashboardLayout({ children }: { children: React.ReactNode }) {
  const { user, loading } = useAuth()
  const router = useRouter()

  useEffect(() => {
    if (!loading && !user) router.push('/login')
  }, [user, loading])

  if (loading) return (
    <div className="min-h-screen flex items-center justify-center bg-cream-DEFAULT">
      <div className="flex flex-col items-center gap-4">
        <div className="w-12 h-12 bg-gradient-bronze rounded-2xl flex items-center justify-center animate-pulse">
          <span className="text-white font-bold">D</span>
        </div>
        <div className="w-6 h-6 border-2 border-primary-300 border-t-primary-700 rounded-full animate-spin" />
      </div>
    </div>
  )

  return (
    <div className="flex h-screen bg-gray-50 overflow-hidden">
      <Sidebar />

      <div className="flex-1 flex flex-col overflow-hidden">
        {/* Header */}
        <header className="h-14 bg-white border-b border-gray-100 flex items-center justify-between px-6 shrink-0">
          <div className="relative">
            <Search size={15} className="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" />
            <input
              className="bg-gray-50 border border-gray-200 rounded-xl pl-9 pr-4 py-2 text-sm w-64 focus:outline-none focus:ring-2 focus:ring-primary-300 placeholder:text-gray-400"
              placeholder="Buscar..."
            />
          </div>

          <div className="flex items-center gap-3">
            <button className="relative w-9 h-9 bg-gray-50 rounded-xl flex items-center justify-center text-gray-500 hover:bg-gray-100 transition-colors">
              <Bell size={17} />
              <span className="absolute top-1.5 right-1.5 w-2 h-2 bg-gold-DEFAULT rounded-full" />
            </button>
            <div className="w-9 h-9 bg-gradient-bronze rounded-full flex items-center justify-center text-white text-sm font-bold cursor-pointer shadow-sm">
              {user ? getInitials(user.name) : 'U'}
            </div>
          </div>
        </header>

        {/* Content */}
        <main className="flex-1 overflow-y-auto p-6">
          {children}
        </main>
      </div>
    </div>
  )
}
