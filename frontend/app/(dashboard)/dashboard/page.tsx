'use client'
import { useEffect, useState } from 'react'
import { Users, FileText, CheckSquare, Target, TrendingUp, TrendingDown, AlertTriangle, Brain } from 'lucide-react'
import { AreaChart, Area, XAxis, YAxis, CartesianGrid, Tooltip, ResponsiveContainer, BarChart, Bar } from 'recharts'
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

  const cards = kpis ? [
    { label: 'Clientes Ativos', value: kpis.clientes_ativos, sub: `+${kpis.clientes_novos_mes} este mês`, icon: Users, color: 'bg-primary-700', trend: 'up' },
    { label: 'Receita Mensal', value: formatCurrency(kpis.receita_mensal_total), sub: 'Total gerenciado', icon: TrendingUp, color: 'bg-bronze-500', trend: 'up' },
    { label: 'Tarefas Pendentes', value: kpis.tarefas_pendentes, sub: kpis.tarefas_vencidas > 0 ? `${kpis.tarefas_vencidas} vencidas` : 'Em dia', icon: CheckSquare, color: kpis.tarefas_vencidas > 0 ? 'bg-red-500' : 'bg-green-600', trend: kpis.tarefas_vencidas > 0 ? 'down' : 'up' },
    { label: 'Leads Ativos', value: kpis.leads_ativos, sub: `${kpis.conversao_leads}% conversão`, icon: Target, color: 'bg-gold-dark', trend: 'up' },
  ] : []

  return (
    <div className="space-y-6 animate-fade-in">

      {/* Header */}
      <div>
        <h1 className="text-2xl font-bold font-serif text-primary-700">
          Bom dia, {user?.name?.split(' ')[0]} 👋
        </h1>
        <p className="text-gray-500 text-sm mt-1">Aqui está um resumo do sistema hoje.</p>
      </div>

      {/* KPI Cards */}
      {loading ? (
        <div className="grid grid-cols-2 lg:grid-cols-4 gap-4">
          {[...Array(4)].map((_, i) => <div key={i} className="h-28 bg-white rounded-2xl animate-pulse" />)}
        </div>
      ) : (
        <div className="grid grid-cols-2 lg:grid-cols-4 gap-4">
          {cards.map((card) => {
            const Icon = card.icon
            return (
              <div key={card.label} className="card">
                <div className="flex items-start justify-between mb-3">
                  <div className={`w-10 h-10 ${card.color} rounded-xl flex items-center justify-center shadow-sm`}>
                    <Icon size={18} className="text-white" />
                  </div>
                  {card.trend === 'up'
                    ? <TrendingUp size={14} className="text-green-500" />
                    : <TrendingDown size={14} className="text-red-500" />
                  }
                </div>
                <p className="text-2xl font-bold text-primary-700 font-serif">{card.value}</p>
                <p className="text-xs text-gray-500 mt-1">{card.label}</p>
                <p className="text-xs text-gray-400 mt-0.5">{card.sub}</p>
              </div>
            )
          })}
        </div>
      )}

      {/* Charts */}
      <div className="grid lg:grid-cols-3 gap-6">

        {/* Area chart */}
        <div className="lg:col-span-2 card">
          <div className="flex items-center justify-between mb-6">
            <div>
              <h3 className="font-semibold text-primary-700">Fluxo Financeiro</h3>
              <p className="text-xs text-gray-400 mt-0.5">Entradas vs Saídas — últimos 6 meses</p>
            </div>
          </div>
          <ResponsiveContainer width="100%" height={220}>
            <AreaChart data={chartData}>
              <defs>
                <linearGradient id="entradas" x1="0" y1="0" x2="0" y2="1">
                  <stop offset="5%" stopColor="#1B3D50" stopOpacity={0.15} />
                  <stop offset="95%" stopColor="#1B3D50" stopOpacity={0} />
                </linearGradient>
                <linearGradient id="saidas" x1="0" y1="0" x2="0" y2="1">
                  <stop offset="5%" stopColor="#8B6B3D" stopOpacity={0.15} />
                  <stop offset="95%" stopColor="#8B6B3D" stopOpacity={0} />
                </linearGradient>
              </defs>
              <CartesianGrid strokeDasharray="3 3" stroke="#f0f0f0" />
              <XAxis dataKey="mes" tick={{ fontSize: 12, fill: '#9ca3af' }} axisLine={false} tickLine={false} />
              <YAxis tick={{ fontSize: 11, fill: '#9ca3af' }} axisLine={false} tickLine={false} tickFormatter={v => `R$${(v/1000).toFixed(0)}k`} />
              <Tooltip formatter={(v: number) => formatCurrency(v)} contentStyle={{ borderRadius: '12px', border: 'none', boxShadow: '0 4px 24px rgba(0,0,0,0.1)', fontSize: '13px' }} />
              <Area type="monotone" dataKey="entradas" stroke="#1B3D50" strokeWidth={2.5} fill="url(#entradas)" name="Entradas" />
              <Area type="monotone" dataKey="saidas" stroke="#8B6B3D" strokeWidth={2.5} fill="url(#saidas)" name="Saídas" />
            </AreaChart>
          </ResponsiveContainer>
        </div>

        {/* Sidebar stats */}
        <div className="space-y-4">

          {/* IA Card */}
          <div className="bg-gradient-dalacorte rounded-2xl p-5 text-white">
            <div className="flex items-center gap-2 mb-3">
              <Brain size={18} className="text-gold-DEFAULT" />
              <span className="font-semibold text-sm">Insight IA</span>
            </div>
            <p className="text-white/70 text-xs leading-relaxed">
              Crescimento de 12% em receita recorrente este mês. 3 clientes com potencial de up-sell identificados. Recomendo contato com Empresa Exemplo LTDA.
            </p>
          </div>

          {/* Alerts */}
          <div className="card">
            <h4 className="font-semibold text-primary-700 mb-3 flex items-center gap-2 text-sm">
              <AlertTriangle size={15} className="text-amber-500" />
              Alertas
            </h4>
            <div className="space-y-2">
              {[
                { text: '3 tarefas vencidas hoje', color: 'bg-red-50 text-red-600' },
                { text: '2 extratos aguardando análise', color: 'bg-yellow-50 text-yellow-600' },
                { text: '5 leads sem contato há 7 dias', color: 'bg-orange-50 text-orange-600' },
              ].map(alert => (
                <div key={alert.text} className={`${alert.color} rounded-lg px-3 py-2 text-xs font-medium`}>
                  {alert.text}
                </div>
              ))}
            </div>
          </div>
        </div>
      </div>

      {/* Bar chart */}
      <div className="card">
        <h3 className="font-semibold text-primary-700 mb-6">Clientes por mês</h3>
        <ResponsiveContainer width="100%" height={180}>
          <BarChart data={chartData}>
            <CartesianGrid strokeDasharray="3 3" stroke="#f0f0f0" />
            <XAxis dataKey="mes" tick={{ fontSize: 12, fill: '#9ca3af' }} axisLine={false} tickLine={false} />
            <YAxis tick={{ fontSize: 11, fill: '#9ca3af' }} axisLine={false} tickLine={false} />
            <Tooltip contentStyle={{ borderRadius: '12px', border: 'none', boxShadow: '0 4px 24px rgba(0,0,0,0.1)', fontSize: '13px' }} />
            <Bar dataKey="entradas" fill="#1B3D50" radius={[6, 6, 0, 0]} name="Novos clientes" />
          </BarChart>
        </ResponsiveContainer>
      </div>
    </div>
  )
}

const mockKpis: KPIs = {
  total_clientes: 47, clientes_ativos: 42, clientes_novos_mes: 3,
  receita_mensal_total: 127500, extratos_processados: 134,
  tarefas_pendentes: 12, tarefas_vencidas: 3,
  leads_total: 28, leads_ativos: 15, conversao_leads: 32.1,
}
