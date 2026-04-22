'use client'
import Link from 'next/link'
import { usePathname } from 'next/navigation'
import { useState } from 'react'
import {
  LayoutDashboard, Users, FileText, CheckSquare, Target,
  Brain, Newspaper, Briefcase, Settings, LogOut,
  ChevronLeft, ChevronRight, Megaphone
} from 'lucide-react'
import { useAuth } from '@/hooks/useAuth'
import { cn, getInitials } from '@/lib/utils'

const navSections = [
  {
    label: 'Principal',
    items: [
      { href: '/dashboard', icon: LayoutDashboard, label: 'Dashboard' },
    ],
  },
  {
    label: 'Gestão',
    items: [
      { href: '/dashboard/clientes', icon: Users, label: 'Clientes' },
      { href: '/dashboard/extratos', icon: FileText, label: 'Extratos' },
      { href: '/dashboard/tarefas', icon: CheckSquare, label: 'Tarefas' },
      { href: '/dashboard/leads', icon: Target, label: 'CRM / Leads' },
    ],
  },
  {
    label: 'Ferramentas',
    items: [
      { href: '/dashboard/ia', icon: Brain, label: 'IA Financeira' },
      { href: '/dashboard/noticias', icon: Newspaper, label: 'Notícias' },
      { href: '/dashboard/marketing', icon: Megaphone, label: 'Marketing' },
      { href: '/dashboard/rh', icon: Briefcase, label: 'RH / Vagas' },
    ],
  },
  {
    label: 'Sistema',
    items: [
      { href: '/dashboard/configuracoes', icon: Settings, label: 'Configurações' },
    ],
  },
]

export default function Sidebar() {
  const pathname = usePathname()
  const { user, logout } = useAuth()
  const [collapsed, setCollapsed] = useState(false)

  return (
    <aside
      className={cn(
        'flex flex-col h-screen transition-all duration-300 relative shrink-0',
        collapsed ? 'w-[68px]' : 'w-[240px]'
      )}
      style={{
        background: 'linear-gradient(180deg, #081623 0%, #0d1f2e 50%, #081623 100%)',
        borderRight: '1px solid rgba(255,255,255,0.05)',
      }}
    >
      {/* Logo */}
      <div className={cn('flex items-center gap-3 px-4 py-[18px] shrink-0', collapsed && 'justify-center px-2')}>
        <div
          className="w-9 h-9 rounded-xl flex items-center justify-center text-white font-bold text-[11px] shrink-0"
          style={{
            background: 'linear-gradient(135deg, #8B6B3D 0%, #C4A35A 100%)',
            boxShadow: '0 4px 16px rgba(196,163,90,0.35)',
          }}
        >
          DFS
        </div>
        {!collapsed && (
          <div className="leading-none">
            <p className="text-white font-serif font-bold text-sm tracking-wide">DALACORTE</p>
            <p className="text-[9px] tracking-[0.22em] font-medium mt-0.5" style={{ color: '#C4A35A' }}>
              FINANCIAL
            </p>
          </div>
        )}
      </div>

      {/* Divider */}
      <div className="mx-3 mb-2" style={{ height: '1px', background: 'rgba(255,255,255,0.05)' }} />

      {/* Navigation */}
      <nav className="flex-1 overflow-y-auto px-2 space-y-3 py-2">
        {navSections.map((section) => (
          <div key={section.label}>
            {!collapsed && (
              <p
                className="px-3 pt-1 pb-1.5 text-[10px] font-semibold tracking-[0.18em] uppercase select-none"
                style={{ color: 'rgba(255,255,255,0.2)' }}
              >
                {section.label}
              </p>
            )}
            <div className="space-y-0.5">
              {section.items.map(({ href, icon: Icon, label }) => {
                const active =
                  pathname === href || (href !== '/dashboard' && pathname.startsWith(href))
                return (
                  <Link
                    key={href}
                    href={href}
                    title={collapsed ? label : undefined}
                    className={cn(
                      'flex items-center gap-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 relative',
                      collapsed ? 'justify-center px-0 mx-1' : 'px-3',
                      !active && 'hover:bg-white/[0.04] hover:text-white/75'
                    )}
                    style={
                      active
                        ? {
                            background:
                              'linear-gradient(135deg, rgba(196,163,90,0.14) 0%, rgba(196,163,90,0.04) 100%)',
                            borderLeft: collapsed ? 'none' : '2px solid #C4A35A',
                            paddingLeft: collapsed ? undefined : '10px',
                            color: '#fff',
                          }
                        : { color: 'rgba(255,255,255,0.38)' }
                    }
                  >
                    <Icon
                      size={17}
                      className="shrink-0"
                      style={{ color: active ? '#C4A35A' : undefined }}
                    />
                    {!collapsed && <span className="truncate">{label}</span>}
                    {active && !collapsed && (
                      <span
                        className="absolute right-3 w-1.5 h-1.5 rounded-full"
                        style={{ background: '#C4A35A' }}
                      />
                    )}
                  </Link>
                )
              })}
            </div>
          </div>
        ))}
      </nav>

      {/* User section */}
      <div className="shrink-0 px-2 py-3" style={{ borderTop: '1px solid rgba(255,255,255,0.05)' }}>
        {!collapsed ? (
          <div className="flex items-center gap-2.5 px-3 py-2.5 rounded-xl cursor-pointer group transition-all hover:bg-white/[0.04]">
            <div
              className="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-bold shrink-0"
              style={{ background: 'linear-gradient(135deg, #8B6B3D 0%, #C4A35A 100%)' }}
            >
              {user ? getInitials(user.name) : 'U'}
            </div>
            <div className="flex-1 min-w-0">
              <p className="text-white text-xs font-semibold truncate leading-tight">
                {user?.name?.split(' ')[0]}
              </p>
              <p className="text-[10px] capitalize mt-0.5" style={{ color: 'rgba(255,255,255,0.3)' }}>
                {user?.tipo}
              </p>
            </div>
            <button
              onClick={logout}
              className="p-1.5 rounded-lg transition-all opacity-40 hover:opacity-100 hover:bg-red-500/15 hover:text-red-400 text-white"
              title="Sair"
            >
              <LogOut size={13} />
            </button>
          </div>
        ) : (
          <button
            onClick={logout}
            className="w-full flex justify-center py-2.5 rounded-xl transition-all opacity-30 hover:opacity-80 hover:bg-red-500/10 hover:text-red-400 text-white"
            title="Sair"
          >
            <LogOut size={16} />
          </button>
        )}
      </div>

      {/* Collapse toggle */}
      <button
        onClick={() => setCollapsed(!collapsed)}
        className="absolute -right-3 top-[22px] w-6 h-6 rounded-full flex items-center justify-center text-white/80 shadow-lg z-10 transition-all hover:scale-110"
        style={{
          background: 'linear-gradient(135deg, #1B3D50 0%, #2d6485 100%)',
          border: '1px solid rgba(255,255,255,0.1)',
        }}
      >
        {collapsed ? <ChevronRight size={11} /> : <ChevronLeft size={11} />}
      </button>
    </aside>
  )
}
