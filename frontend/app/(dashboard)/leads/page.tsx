'use client'
import { useEffect, useState } from 'react'
import { Plus, Phone, Mail, Building, ArrowRight } from 'lucide-react'
import api from '@/lib/api'
import { formatCurrency, statusColors, cn } from '@/lib/utils'

interface Lead { id: number; nome: string; email: string; empresa: string; telefone: string; origem: string; status: string; servico_interesse: string; valor_estimado: number }

const funil = ['novo', 'contato', 'proposta', 'negociacao', 'ganho', 'perdido']
const funilLabel: Record<string, string> = { novo: 'Novo', contato: 'Contato', proposta: 'Proposta', negociacao: 'Negociação', ganho: 'Ganho', perdido: 'Perdido' }
const funilColor: Record<string, string> = { novo: 'bg-blue-500', contato: 'bg-indigo-500', proposta: 'bg-purple-500', negociacao: 'bg-amber-500', ganho: 'bg-green-500', perdido: 'bg-red-400' }

export default function LeadsPage() {
  const [leads, setLeads] = useState<Lead[]>([])
  const [loading, setLoading] = useState(true)

  useEffect(() => {
    api.get('/leads?per_page=50')
      .then(r => setLeads(r.data.data || []))
      .catch(() => setLeads(mockLeads))
      .finally(() => setLoading(false))
  }, [])

  const porStatus = (s: string) => leads.filter(l => l.status === s)

  return (
    <div className="space-y-6 animate-fade-in">
      <div className="flex items-center justify-between">
        <div>
          <h1 className="text-2xl font-bold font-serif text-primary-700">CRM — Leads</h1>
          <p className="text-gray-500 text-sm">{leads.filter(l => !['ganho','perdido'].includes(l.status)).length} leads ativos</p>
        </div>
        <button className="btn-primary flex items-center gap-2"><Plus size={16} /> Novo Lead</button>
      </div>

      {/* Funil visual */}
      <div className="grid grid-cols-6 gap-1 bg-gray-100 rounded-2xl p-1">
        {funil.map(s => {
          const count = porStatus(s).length
          return (
            <div key={s} className={`rounded-xl p-3 text-center ${count > 0 ? funilColor[s] : 'bg-white'}`}>
              <p className={`text-xs font-semibold ${count > 0 ? 'text-white' : 'text-gray-400'}`}>{funilLabel[s]}</p>
              <p className={`text-xl font-bold ${count > 0 ? 'text-white' : 'text-gray-300'}`}>{count}</p>
            </div>
          )
        })}
      </div>

      {/* Kanban */}
      <div className="overflow-x-auto">
        <div className="flex gap-4 min-w-max pb-2">
          {funil.slice(0, 4).map(s => (
            <div key={s} className="w-72 bg-gray-50 rounded-2xl p-4">
              <div className="flex items-center gap-2 mb-4">
                <div className={`w-2.5 h-2.5 rounded-full ${funilColor[s]}`} />
                <h3 className="font-semibold text-gray-700 text-sm">{funilLabel[s]}</h3>
                <span className="ml-auto bg-white text-gray-500 text-xs px-2 py-0.5 rounded-full">{porStatus(s).length}</span>
              </div>
              <div className="space-y-3">
                {loading ? [...Array(2)].map((_, i) => <div key={i} className="h-24 bg-white rounded-xl animate-pulse" />) :
                  porStatus(s).map(lead => (
                    <div key={lead.id} className="bg-white rounded-xl p-4 shadow-sm hover:shadow-card transition-all cursor-pointer group">
                      <div className="flex items-start justify-between mb-2">
                        <p className="font-semibold text-primary-700 text-sm">{lead.nome}</p>
                        <ArrowRight size={13} className="text-gray-300 group-hover:text-bronze-500 transition-colors mt-0.5" />
                      </div>
                      {lead.empresa && <p className="text-xs text-gray-500 flex items-center gap-1 mb-2"><Building size={10} />{lead.empresa}</p>}
                      <div className="flex gap-3 text-xs text-gray-400">
                        {lead.telefone && <span className="flex items-center gap-1"><Phone size={10} />{lead.telefone}</span>}
                      </div>
                      {lead.valor_estimado > 0 && (
                        <p className="text-xs font-semibold text-bronze-500 mt-2">{formatCurrency(lead.valor_estimado)}/mês</p>
                      )}
                    </div>
                  ))
                }
              </div>
            </div>
          ))}
        </div>
      </div>
    </div>
  )
}

const mockLeads: Lead[] = [
  { id: 1, nome: 'Maria Santos', empresa: 'Santos Comércio ME', email: 'maria@santos.com', telefone: '(11) 99000-1111', origem: 'indicacao', status: 'novo', servico_interesse: 'contabilidade', valor_estimado: 1500 },
  { id: 2, nome: 'Pedro Lima', empresa: 'PL Consultoria', email: 'pedro@pl.com', telefone: '(11) 99000-2222', origem: 'linkedin', status: 'proposta', servico_interesse: 'financeiro', valor_estimado: 3000 },
  { id: 3, nome: 'Ana Ferreira', empresa: 'AF Tech', email: 'ana@aftech.com', telefone: '(11) 99000-3333', origem: 'site', status: 'negociacao', servico_interesse: 'consultoria', valor_estimado: 5000 },
  { id: 4, nome: 'Carlos Mendes', empresa: '', email: 'carlos@email.com', telefone: '', origem: 'whatsapp', status: 'contato', servico_interesse: 'folha', valor_estimado: 800 },
]
