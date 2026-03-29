'use client'
import { useEffect, useState } from 'react'
import { Plus, CheckCircle, Clock, AlertTriangle, Circle } from 'lucide-react'
import api from '@/lib/api'
import { formatDateShort, statusColors, cn } from '@/lib/utils'
import toast from 'react-hot-toast'

interface Tarefa {
  id: number; titulo: string; descricao: string; prioridade: string
  status: string; categoria: string; data_vencimento: string
  responsavel?: { name: string }; cliente?: { nome: string }
}

const prioridadeIcon: Record<string, any> = {
  urgente: <AlertTriangle size={13} className="text-red-500" />,
  alta: <AlertTriangle size={13} className="text-orange-500" />,
  media: <Clock size={13} className="text-yellow-500" />,
  baixa: <Circle size={13} className="text-gray-400" />,
}

export default function TarefasPage() {
  const [tarefas, setTarefas] = useState<Tarefa[]>([])
  const [loading, setLoading] = useState(true)
  const [filtro, setFiltro] = useState('')

  useEffect(() => {
    api.get('/tarefas', { params: { status: filtro, per_page: 50 } })
      .then(r => setTarefas(r.data.data || []))
      .catch(() => setTarefas(mockTarefas))
      .finally(() => setLoading(false))
  }, [filtro])

  const concluir = async (id: number) => {
    await api.patch(`/tarefas/${id}/concluir`)
    toast.success('Tarefa concluída!')
    setTarefas(prev => prev.map(t => t.id === id ? { ...t, status: 'concluida' } : t))
  }

  const kanban = {
    pendente: tarefas.filter(t => t.status === 'pendente'),
    em_andamento: tarefas.filter(t => t.status === 'em_andamento'),
    concluida: tarefas.filter(t => t.status === 'concluida'),
  }

  const colLabels = { pendente: 'Pendente', em_andamento: 'Em andamento', concluida: 'Concluída' }
  const colColors = { pendente: 'border-yellow-300', em_andamento: 'border-blue-400', concluida: 'border-green-400' }

  return (
    <div className="space-y-6 animate-fade-in">
      <div className="flex items-center justify-between">
        <div>
          <h1 className="text-2xl font-bold font-serif text-primary-700">Tarefas</h1>
          <p className="text-gray-500 text-sm">{tarefas.filter(t => t.status !== 'concluida').length} tarefas abertas</p>
        </div>
        <button className="btn-primary flex items-center gap-2"><Plus size={16} /> Nova Tarefa</button>
      </div>

      {/* Kanban */}
      <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
        {(Object.keys(kanban) as Array<keyof typeof kanban>).map(col => (
          <div key={col} className={`bg-gray-50 rounded-2xl p-4 border-t-4 ${colColors[col]}`}>
            <div className="flex items-center justify-between mb-4">
              <h3 className="font-semibold text-gray-700 text-sm">{colLabels[col]}</h3>
              <span className="bg-white text-gray-500 text-xs font-medium px-2 py-0.5 rounded-full shadow-sm">{kanban[col].length}</span>
            </div>
            <div className="space-y-3">
              {loading ? [...Array(3)].map((_, i) => <div key={i} className="h-20 bg-white rounded-xl animate-pulse" />) :
                kanban[col].map(t => (
                  <div key={t.id} className="bg-white rounded-xl p-4 shadow-sm hover:shadow-card transition-shadow">
                    <div className="flex items-start justify-between gap-2 mb-2">
                      <p className="font-medium text-primary-700 text-sm line-clamp-2">{t.titulo}</p>
                      {prioridadeIcon[t.prioridade]}
                    </div>
                    {t.cliente && <p className="text-xs text-bronze-500 mb-1">{t.cliente.nome}</p>}
                    {t.data_vencimento && (
                      <p className="text-xs text-gray-400 flex items-center gap-1">
                        <Clock size={10} /> {formatDateShort(t.data_vencimento)}
                      </p>
                    )}
                    {t.status !== 'concluida' && (
                      <button onClick={() => concluir(t.id)} className="mt-2 w-full text-xs text-green-600 hover:bg-green-50 rounded-lg py-1 flex items-center justify-center gap-1 transition-colors">
                        <CheckCircle size={12} /> Concluir
                      </button>
                    )}
                  </div>
                ))
              }
            </div>
          </div>
        ))}
      </div>
    </div>
  )
}

const mockTarefas: Tarefa[] = [
  { id: 1, titulo: 'Entregar balanço anual — Empresa Exemplo', descricao: '', prioridade: 'urgente', status: 'pendente', categoria: 'contabil', data_vencimento: new Date().toISOString(), responsavel: { name: 'Admin' }, cliente: { nome: 'Empresa Exemplo LTDA' } },
  { id: 2, titulo: 'Revisar folha de pagamento março', descricao: '', prioridade: 'alta', status: 'em_andamento', categoria: 'financeiro', data_vencimento: new Date().toISOString(), responsavel: { name: 'Admin' } },
  { id: 3, titulo: 'Enviar DCTF Web', descricao: '', prioridade: 'media', status: 'pendente', categoria: 'fiscal', data_vencimento: new Date().toISOString() },
  { id: 4, titulo: 'Reunião com cliente João Silva', descricao: '', prioridade: 'baixa', status: 'concluida', categoria: 'administrativo', data_vencimento: new Date().toISOString() },
]
