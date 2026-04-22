'use client'
import { useEffect, useState } from 'react'
import {
  Users, FileText, CheckSquare, Target,
  TrendingUp, TrendingDown, AlertTriangle, Brain,
  ArrowUpRight, Sparkles,
} from 'lucide-react'
import {
  AreaChart, Area, XAxis, YAxis, CartesianGrid, Tooltip,
  ResponsiveContainer, BarChart, Bar,
} from 'recharts'
import api from '@/lib/api'
import { formatCurrency } from '@/lib/utils'
import { useAuth } from '@/hooks/useAuth'

interface KPIs {
  total_clientes: number
  clientes_ativos: number
  clientes_novos_mes: number
  receita_mensal_total: number
  extratos_processados: number
  tarefas_pendentes: number
  tarefas_vencidas: number
  leads_total: number
  leads_ativos: number
  conversao_leads: number
}

const chartData = [
  { mes: 'Out', entradas: 45000, saidas: 32000 },
  { mes: 'Nov', entradas: 52000, saidas: 38000 },
  { mes: 'Dez', entradas: 48000, saidas: 35000 },
  { mes: 'Jan', entradas: 61000, saidas: 42000 },
  { mes: 'Fev', entradas: 55000, saidas: 39000 },
  { mes: 'Mar', entradas: 67000, saidas: 45000 },
]

const mockKpis: KPIs = {
  total_clientes: 47, clientes_ativos: 42, clientes_novos_mes: 3,
  receita_mensal_total: 127500, extratos_processados: 134,
  tarefas_pendentes: 12, tarefas_vencidas: 3,
  leads_total: 28, leads_ativos: 15, conversao_leads: 32.1,
}

interface KpiCard {
  label: string
  value: string | number
  sub: string
  icon: React.ElementType
  trend: 'up' | 'down'
  accentColor: string
  bgGradient: string
}

export default function DashboardPage() {
  const { user } = useAuth()
  const [kpis, setKpis] = useState<KPIs | null>(null)
  const [loading, setLoading] = useState(true)

  useEffect(() => {
    api.get('/dashboard/kpis')
      .then(r => setKpis(r.data))
      .catch(() => setKpis(mockKpis))
      .finally(() => setLoading(false))
  }, [])

  const cards: KpiCard[] = kpis ? [
    {
      label: 'Clientes Ativos',
      value: kpis.clientes_ativos,
      sub: `+${kpis.clientes_novos_mes} este mês`,
      icon: Users,
      trend: 'up',
      accentColor: '#1B3D50',
      bgGradient: 'linear-gradient(135deg, #1B3D50 0%, #2d6485 100%)',
    },
    {
      label: 'Receita Mensal',
      value: formatCurrency(kpis.receita_mensal_total),
      sub: 'Total gerenciado',
      icon: TrendingUp,
      trend: 'up',
      accentColor: '#8B6B3D',
      bgGradient: 'linear-gradient(135deg, #8B6B3D 0%, #C4A35A 100%)',
    },
    {
      label: 'Tarefas Pendentes',
      value: kpis.tarefas_pendentes,
      sub: kpis.tarefas_vencidas > 0 ? `${kpis.tarefas_vencidas} vencidas` : 'Em dia',
      icon: CheckSquare,
      trend: kpis.tarefas_vencidas > 0 ? 'down' : 'up',
      accentColor: kpis.tarefas_vencidas > 0 ? '#dc2626' : '#16a34a',
      bgGradient: kpis.tarefas_vencidas > 0
        ? 'linear-gradient(135deg, #dc2626 0%, #ef4444 100%)'
        : 'linear-gradient(135deg, #16a34a 0%, #22c55e 100%)',
    },
    {
      label: 'Leads Ativos',
      value: kpis.leads_ativos,
      sub: `${kpis.conversao_leads}% conversão`,
      icon: Target,
      trend: 'up',
      accentColor: '#A8883A',
      bgGradient: 'linear-gradient(135deg, #A8883A 0%, #C4A35A 100%)',
    },
  ] : []

  const today = new Date().toLocaleDateString('pt-BR', { weekday: 'long', day: 'numeric', month: 'long' })
  const firstName = user?.name?.split(' ')[0]

  return (
    <div className="space-y-6 animate-fade-in max-w-[1400px]">

      {/* Page header */}
      <div className="flex items-end justify-between">
        <div>
          <p className="text-xs font-medium text-gray-400 capitalize mb-1">{today}</p>
          <h1 className="text-2xl font-bold font-serif text-gray-900">
            Bom dia, {firstName}
          </h1>
          <p className="text-sm text-gray-500 mt-1">Aqui está um resumo do escritório hoje.</p>
        </div>
        <div
          className="hidden sm:flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-medium"
          style={{
            background: 'linear-gradient(135deg, rgba(196,163,90,0.12) 0%, rgba(139,107,61,0.08) 100%)',
            border: '1px solid rgba(196,163,90,0.25)',
            color: '#8B6B3D',
          }}
        >
          <Sparkles size={13} />
          Sistema operando normalmente
        </div>
      </div>

      {/* KPI Cards */}
      {loading ? (
        <div className="grid grid-cols-2 lg:grid-cols-4 gap-4">
          {[...Array(4)].map((_, i) => (
            <div key={i} className="h-32 bg-white rounded-2xl animate-pulse" />
          ))}
        </div>
      ) : (
        <div className="grid grid-cols-2 lg:grid-cols-4 gap-4">
          {cards.map((card) => {
            const Icon = card.icon
            return (
              <div
                key={card.label}
                className="bg-white rounded-2xl p-5 transition-all duration-300 hover:-translate-y-0.5 cursor-default group"
                style={{
                  boxShadow: '0 2px 16px rgba(0,0,0,0.06)',
                  border: '1px solid rgba(0,0,0,0.05)',
                }}
              >
                <div className="flex items-start justify-between mb-4">
                  <div
                    className="w-10 h-10 rounded-xl flex items-center justify-center"
                    style={{ background: card.bgGradient, boxShadow: `0 4px 14px ${card.accentColor}40` }}
                  >
                    <Icon size={18} className="text-white" />
                  </div>
                  <span
                    className="flex items-center gap-1 text-[11px] font-semibold px-2 py-1 rounded-lg"
                    style={
                      card.trend === 'up'
                        ? { background: 'rgba(22,163,74,0.1)', color: '#16a34a' }
                        : { background: 'rgba(220,38,38,0.1)', color: '#dc2626' }
                    }
                  >
                    {card.trend === 'up'
                      ? <ArrowUpRight size={11} />
                      : <TrendingDown size={11} />
                    }
                    {card.trend === 'up' ? '+' : '↑'}
                  </span>
                </div>
                <p
                  className="text-[26px] font-bold leading-none font-serif mb-1.5"
                  style={{ color: '#111827' }}
                >
                  {card.value}
                </p>
                <p className="text-xs font-semibold text-gray-600">{card.label}</p>
                <p className="text-[11px] text-gray-400 mt-0.5">{card.sub}</p>
              </div>
            )
          })}
        </div>
      )}

      {/* Charts row */}
      <div className="grid lg:grid-cols-3 gap-5">

        {/* Area chart */}
        <div
          className="lg:col-span-2 bg-white rounded-2xl p-6"
          style={{ boxShadow: '0 2px 16px rgba(0,0,0,0.06)', border: '1px solid rgba(0,0,0,0.05)' }}
        >
          <div className="flex items-start justify-between mb-6">
            <div>
              <h3 className="font-semibold text-gray-900 text-sm">Fluxo Financeiro</h3>
              <p className="text-xs text-gray-400 mt-0.5">Entradas vs Saídas — últimos 6 meses</p>
            </div>
            <div className="flex items-center gap-4 text-[11px] text-gray-400">
              <span className="flex items-center gap-1.5">
                <span className="w-2.5 h-2.5 rounded-sm" style={{ background: '#1B3D50' }} />
                Entradas
              </span>
              <span className="flex items-center gap-1.5">
                <span className="w-2.5 h-2.5 rounded-sm" style={{ background: '#C4A35A' }} />
                Saídas
              </span>
            </div>
          </div>
          <ResponsiveContainer width="100%" height={220}>
            <AreaChart data={chartData} margin={{ top: 4, right: 4, bottom: 0, left: 0 }}>
              <defs>
                <linearGradient id="gradEntradas" x1="0" y1="0" x2="0" y2="1">
                  <stop offset="5%" stopColor="#1B3D50" stopOpacity={0.12} />
                  <stop offset="95%" stopColor="#1B3D50" stopOpacity={0} />
                </linearGradient>
                <linearGradient id="gradSaidas" x1="0" y1="0" x2="0" y2="1">
                  <stop offset="5%" stopColor="#C4A35A" stopOpacity={0.12} />
                  <stop offset="95%" stopColor="#C4A35A" stopOpacity={0} />
                </linearGradient>
              </defs>
              <CartesianGrid strokeDasharray="3 3" stroke="#f3f4f6" />
              <XAxis
                dataKey="mes"
                tick={{ fontSize: 11, fill: '#9ca3af' }}
                axisLine={false}
                tickLine={false}
              />
              <YAxis
                tick={{ fontSize: 10, fill: '#9ca3af' }}
                axisLine={false}
                tickLine={false}
                tickFormatter={v => `R$${(v / 1000).toFixed(0)}k`}
              />
              <Tooltip
                formatter={(v: number) => formatCurrency(v)}
                contentStyle={{
                  borderRadius: '12px',
                  border: 'none',
                  boxShadow: '0 4px 24px rgba(0,0,0,0.12)',
                  fontSize: '12px',
                  padding: '10px 14px',
                }}
              />
              <Area
                type="monotone"
                dataKey="entradas"
                stroke="#1B3D50"
                strokeWidth={2.5}
                fill="url(#gradEntradas)"
                name="Entradas"
                dot={false}
                activeDot={{ r: 4, fill: '#1B3D50' }}
              />
              <Area
                type="monotone"
                dataKey="saidas"
                stroke="#C4A35A"
                strokeWidth={2.5}
                fill="url(#gradSaidas)"
                name="Saídas"
                dot={false}
                activeDot={{ r: 4, fill: '#C4A35A' }}
              />
            </AreaChart>
          </ResponsiveContainer>
        </div>

        {/* Right column */}
        <div className="flex flex-col gap-4">

          {/* IA Insight card */}
          <div
            className="rounded-2xl p-5 text-white relative overflow-hidden"
            style={{
              background: 'linear-gradient(135deg, #081623 0%, #1B3D50 50%, #0d2233 100%)',
              boxShadow: '0 4px 24px rgba(27,61,80,0.3)',
            }}
          >
            <div
              className="absolute -top-8 -right-8 w-28 h-28 rounded-full opacity-10"
              style={{ background: '#C4A35A' }}
            />
            <div className="relative">
              <div className="flex items-center gap-2 mb-3">
                <div
                  className="w-7 h-7 rounded-lg flex items-center justify-center"
                  style={{ background: 'rgba(196,163,90,0.2)' }}
                >
                  <Brain size={14} style={{ color: '#C4A35A' }} />
                </div>
                <span className="font-semibold text-sm">Insight IA</span>
                <span
                  className="ml-auto text-[10px] px-2 py-0.5 rounded-full font-medium"
                  style={{ background: 'rgba(196,163,90,0.2)', color: '#C4A35A' }}
                >
                  Novo
                </span>
              </div>
              <p className="text-white/65 text-xs leading-relaxed">
                Crescimento de 12% em receita recorrente este mês. 3 clientes com potencial
                de up-sell identificados. Recomendo contato com Empresa Exemplo LTDA.
              </p>
            </div>
          </div>

          {/* Alerts */}
          <div
            className="bg-white rounded-2xl p-5 flex-1"
            style={{ boxShadow: '0 2px 16px rgba(0,0,0,0.06)', border: '1px solid rgba(0,0,0,0.05)' }}
          >
            <h4 className="font-semibold text-gray-800 mb-3 flex items-center gap-2 text-sm">
              <AlertTriangle size={14} className="text-amber-500" />
              Alertas
            </h4>
            <div className="space-y-2">
              {[
                { text: '3 tarefas vencidas hoje', dot: '#ef4444', bg: '#fef2f2', color: '#dc2626' },
                { text: '2 extratos aguardando análise', dot: '#f59e0b', bg: '#fffbeb', color: '#d97706' },
                { text: '5 leads sem contato há 7 dias', dot: '#f97316', bg: '#fff7ed', color: '#ea580c' },
              ].map(alert => (
                <div
                  key={alert.text}
                  className="flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-xs font-medium"
                  style={{ background: alert.bg, color: alert.color }}
                >
                  <span className="w-1.5 h-1.5 rounded-full shrink-0" style={{ background: alert.dot }} />
                  {alert.text}
                </div>
              ))}
            </div>
          </div>
        </div>
      </div>

      {/* Bar chart */}
      <div
        className="bg-white rounded-2xl p-6"
        style={{ boxShadow: '0 2px 16px rgba(0,0,0,0.06)', border: '1px solid rgba(0,0,0,0.05)' }}
      >
        <div className="flex items-center justify-between mb-6">
          <div>
            <h3 className="font-semibold text-gray-900 text-sm">Novos Clientes por Mês</h3>
            <p className="text-xs text-gray-400 mt-0.5">Evolução dos últimos 6 meses</p>
          </div>
        </div>
        <ResponsiveContainer width="100%" height={160}>
          <BarChart data={chartData} margin={{ top: 0, right: 4, bottom: 0, left: 0 }}>
            <CartesianGrid strokeDasharray="3 3" stroke="#f3f4f6" vertical={false} />
            <XAxis
              dataKey="mes"
              tick={{ fontSize: 11, fill: '#9ca3af' }}
              axisLine={false}
              tickLine={false}
            />
            <YAxis
              tick={{ fontSize: 10, fill: '#9ca3af' }}
              axisLine={false}
              tickLine={false}
            />
            <Tooltip
              contentStyle={{
                borderRadius: '12px',
                border: 'none',
                boxShadow: '0 4px 24px rgba(0,0,0,0.12)',
                fontSize: '12px',
              }}
            />
            <Bar
              dataKey="entradas"
              name="Novos clientes"
              radius={[6, 6, 0, 0]}
              fill="url(#barGrad)"
            />
            <defs>
              <linearGradient id="barGrad" x1="0" y1="0" x2="0" y2="1">
                <stop offset="0%" stopColor="#1B3D50" />
                <stop offset="100%" stopColor="#2d6485" />
              </linearGradient>
            </defs>
          </BarChart>
        </ResponsiveContainer>
      </div>

    </div>
  )
}
