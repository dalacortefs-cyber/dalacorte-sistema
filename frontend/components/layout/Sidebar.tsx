'use client'
import Link from 'next/link'
import { usePathname } from 'next/navigation'
import { useState } from 'react'
import {
  LayoutDashboard, Users, FileText, CheckSquare, Target,
  Brain, Newspaper, Briefcase, Settings, LogOut,
  ChevronLeft, ChevronRight, Bell, User, Megaphone
} from 'lucide-react'
import { useAuth } from '@/hooks/useAuth'
import { cn, getInitials } from '@/lib/utils'

const navItems = [
  { href: '/dashboard', icon: LayoutDashboard, label: 'Dashboard' },
  { href: '/dashboard/clientes', icon: Users, label: 'Clientes' },
  { href: '/dashboard/extratos', icon: FileText, label: 'Extratos' },
  { href: '/dashboard/tarefas', icon: CheckSquare, label: 'Tarefas' },
  { href: '/dashboard/leads', icon: Target, label: 'CRM / Leads' },
  { href: '/dashboard/ia', icon: Brain, label: 'IA Financeira' },
  { href: '/dashboard/noticias', icon: Newspaper, label: 'Notícias' },
  { href: '/dashboard/marketing', icon: Megaphone, label: 'Marketing' },
  { href: '/dashboard/rh', icon: Briefcase, label: 'RH / Vagas' },
  { href: '/dashboard/configuracoes', icon: Settings, label: 'Configurações' },
]

export default function Sidebar() {
  const pathname = usePathname()
  const { user, logout } = useAuth()
  const [collapsed, setCollapsed] = useState(false)

  return (
    <aside className={cn(
      'flex flex-col h-screen bg-primary-900 transition-all duration-300 relative',
      collapsed ? 'w-16' : 'w-64'
    )}>

      {/* Logo */}
      <div className={cn('flex items-center gap-3 p-4 border-b border-white/10', collapsed && 'justify-center')}>
        <div className="w-9 h-9 bg-gradient-bronze rounded-xl flex items-center justify-center text-white font-bold text-sm shadow-gold shrink-0">
          DFS
        </div>
        {!collapsed && (
          <div>
            <p className="text-white font-serif font-bold text-sm leading-tight">DALACORTE</p>
            <p className="text-gold-DEFAULT text-[10px] tracking-widest">FINANCIAL</p>
          </div>
        )}
      </div>

      {/* Navigation */}
      <nav className="flex-1 overflow-y-auto py-4 px-2 space-y-1">
        {navItems.map(({ href, icon: Icon, label }) => {
          const active = pathname === href || (href !== '/dashboard' && pathname.startsWith(href))
          return (
            <Link
              key={href}
              href={href}
              className={cn('sidebar-item', active && 'active', collapsed && 'justify-center px-2')}
              title={collapsed ? label : undefined}
            >
              <Icon size={18} className="shrink-0" />
              {!collapsed && <span>{label}</span>}
            </Link>
          )
        })}
      </nav>

      {/* User */}
      <div className="border-t border-white/10 p-3">
        {!collapsed ? (
          <div className="flex items-center gap-3 p-2 rounded-xl hover:bg-white/5 cursor-pointer group">
            <div className="w-9 h-9 bg-gradient-bronze rounded-full flex items-center justify-center text-white text-sm font-bold shrink-0">
              {user ? getInitials(user.name) : 'U'}
            </div>
            <div className="flex-1 min-w-0">
              <p className="text-white text-sm font-medium truncate">{user?.name?.split(' ')[0]}</p>
              <p className="text-white/40 text-xs capitalize">{user?.tipo}</p>
            </div>
            <button onClick={logout} className="text-white/40 hover:text-red-400 transition-colors p-1" title="Sair">
              <LogOut size={15} />
            </button>
          </div>
        ) : (
          <button onClick={logout} className="w-full flex justify-center text-white/40 hover:text-red-400 transition-colors p-2" title="Sair">
            <LogOut size={18} />
          </button>
        )}
      </div>

      {/* Collapse button */}
      <button
        onClick={() => setCollapsed(!collapsed)}
        className="absolute -right-3 top-1/2 -translate-y-1/2 w-6 h-6 bg-primary-700 border border-white/20 rounded-full flex items-center justify-center text-white hover:bg-gold-DEFAULT transition-colors shadow-md z-10"
      >
        {collapsed ? <ChevronRight size={12} /> : <ChevronLeft size={12} />}
      </button>
    </aside>
  )
}
