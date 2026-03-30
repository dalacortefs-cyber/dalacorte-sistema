'use client'
import { useEffect, useState } from 'react'
import { FileText, TrendingUp, Newspaper, Bot, LogOut, Send } from 'lucide-react'
import api from '@/lib/api'
import { useAuth } from '@/hooks/useAuth'
import { formatCurrency, formatDateShort } from '@/lib/utils'

export default function PortalClientePage() {
  const { user, logout } = useAuth()
  const [tab, setTab] = useState<'resumo'|'extratos'|'noticias'|'ia'>('resumo')
  const [resumo, setResumo] = useState<any>(null)
  const [extratos, setExtratos] = useState<any[]>([])
  const [noticias, setNoticias] = useState<any[]>([])
  const [iaMsg, setIaMsg] = useState('')
  const [iaResp, setIaResp] = useState('')
  const [iaLoading, setIaLoading] = useState(false)

  useEffect(() => {
    if (tab === 'resumo') api.get('/portal/resumo-financeiro').then(r => setResumo(r.data)).catch(() => {})
    if (tab === 'extratos') api.get('/portal/meus-extratos').then(r => setExtratos(r.data.data || [])).catch(() => {})
    if (tab === 'noticias') api.get('/portal/noticias').then(r => setNoticias(r.data.data || [])).catch(() => {})
  }, [tab])

  const perguntarIA = async () => {
    if (!iaMsg.trim()) return
    setIaLoading(true)
    try {
      const { data } = await api.post('/portal/assistente', { pergunta: iaMsg })
      setIaResp(data.resposta)
    } catch { setIaResp('Erro ao conectar com o assistente.') }
    finally { setIaLoading(false) }
  }

  const tabs = [
    { id: 'resumo', label: 'Resumo', icon: TrendingUp },
    { id: 'extratos', label: 'Extratos', icon: FileText },
    { id: 'noticias', label: 'Notícias', icon: Newspaper },
    { id: 'ia', label: 'Assistente IA', icon: Bot },
  ]

  return (
    <div className="min-h-screen bg-cream-DEFAULT">
      {/* Header */}
      <header className="bg-primary-900 text-white py-4 px-6">
        <div className="max-w-4xl mx-auto flex items-center justify-between">
          <div className="flex items-center gap-3">
            <div className="w-9 h-9 bg-gradient-bronze rounded-xl flex items-center justify-center text-white font-bold text-sm shadow-gold">DFS</div>
            <div>
              <p className="font-serif font-bold text-sm">DFS</p>
              <p className="text-gold-DEFAULT text-[10px] tracking-widest">PORTAL DO CLIENTE</p>
            </div>
          </div>
          <div className="flex items-center gap-4">
            <p className="text-white/60 text-sm">Olá, <span className="text-white font-medium">{user?.name?.split(' ')[0]}</span></p>
            <button onClick={logout} className="text-white/60 hover:text-white p-1.5 transition-colors"><LogOut size={16} /></button>
          </div>
        </div>
      </header>

      <div className="max-w-4xl mx-auto px-4 py-8">
        {/* Tabs */}
        <div className="flex gap-2 mb-6 bg-white rounded-2xl p-1.5 shadow-sm">
          {tabs.map(({ id, label, icon: Icon }) => (
            <button
              key={id}
              onClick={() => setTab(id as any)}
              className={`flex-1 flex items-center justify-center gap-2 py-2.5 rounded-xl text-sm font-medium transition-all ${tab === id ? 'bg-primary-700 text-white shadow-md' : 'text-gray-500 hover:text-primary-700'}`}
            >
              <Icon size={15} />{label}
            </button>
          ))}
        </div>

        {/* Resumo */}
        {tab === 'resumo' && (
          <div className="space-y-4 animate-fade-in">
            <h2 className="text-xl font-bold font-serif text-primary-700">Resumo Financeiro</h2>
            {resumo ? (
              <>
                <div className="grid grid-cols-3 gap-4">
                  {[
                    { l: 'Total Entradas', v: formatCurrency(resumo.total_entradas), c: 'text-green-600' },
                    { l: 'Total Saídas', v: formatCurrency(resumo.total_saidas), c: 'text-red-500' },
                    { l: 'Saldo Líquido', v: formatCurrency(resumo.saldo_liquido), c: resumo.saldo_liquido >= 0 ? 'text-green-600' : 'text-red-500' },
                  ].map(({ l, v, c }) => (
                    <div key={l} className="card text-center">
                      <p className={`text-2xl font-bold font-serif ${c}`}>{v}</p>
                      <p className="text-xs text-gray-500 mt-1">{l}</p>
                    </div>
                  ))}
                </div>
                <div className="card">
                  <h3 className="font-semibold text-primary-700 mb-4">Histórico de Extratos</h3>
                  <div className="space-y-2">
                    {resumo.historico?.map((e: any, i: number) => (
                      <div key={i} className="flex items-center justify-between py-2 border-b border-gray-50 last:border-0">
                        <span className="text-sm text-gray-600">{e.banco || 'Extrato'} — {formatDateShort(e.data_inicio)}</span>
                        <span className={`text-sm font-medium ${(e.total_entradas - e.total_saidas) >= 0 ? 'text-green-600' : 'text-red-500'}`}>
                          {formatCurrency(e.total_entradas - e.total_saidas)}
                        </span>
                      </div>
                    ))}
                  </div>
                </div>
              </>
            ) : <div className="card text-center text-gray-400 py-12">Nenhum dado financeiro disponível ainda.</div>}
          </div>
        )}

        {/* Extratos */}
        {tab === 'extratos' && (
          <div className="space-y-4 animate-fade-in">
            <h2 className="text-xl font-bold font-serif text-primary-700">Meus Extratos</h2>
            {extratos.length === 0
              ? <div className="card text-center text-gray-400 py-12">Nenhum extrato disponível.</div>
              : extratos.map(e => (
                <div key={e.id} className="card flex items-center justify-between">
                  <div>
                    <p className="font-medium text-primary-700">{e.nome_arquivo}</p>
                    <p className="text-xs text-gray-400 mt-0.5">{e.banco} — {formatDateShort(e.created_at)}</p>
                  </div>
                  <span className={`badge ${e.status === 'processado' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700'}`}>{e.status}</span>
                </div>
              ))
            }
          </div>
        )}

        {/* Notícias */}
        {tab === 'noticias' && (
          <div className="space-y-4 animate-fade-in">
            <h2 className="text-xl font-bold font-serif text-primary-700">Notícias & Insights</h2>
            {noticias.map(n => (
              <div key={n.id} className="card hover:shadow-card-hover transition-shadow cursor-pointer">
                <span className="badge bg-primary-50 text-primary-600 capitalize text-xs mb-2">{n.categoria}</span>
                <h3 className="font-semibold text-primary-700 mb-1">{n.titulo}</h3>
                <p className="text-gray-500 text-sm line-clamp-2">{n.resumo}</p>
              </div>
            ))}
          </div>
        )}

        {/* IA */}
        {tab === 'ia' && (
          <div className="space-y-4 animate-fade-in">
            <h2 className="text-xl font-bold font-serif text-primary-700 flex items-center gap-2">
              <Bot size={22} className="text-gold-DEFAULT" /> Assistente Financeiro
            </h2>
            <div className="card">
              <p className="text-gray-500 text-sm mb-4">Tire suas dúvidas financeiras e contábeis com nosso assistente de IA.</p>
              <div className="flex gap-3">
                <input className="input flex-1" placeholder="Faça uma pergunta..." value={iaMsg} onChange={e => setIaMsg(e.target.value)} onKeyDown={e => e.key === 'Enter' && perguntarIA()} />
                <button onClick={perguntarIA} disabled={iaLoading} className="btn-primary px-4 flex items-center gap-2">
                  <Send size={15} />
                </button>
              </div>
              {iaLoading && <div className="mt-4 flex gap-1"><div className="w-2 h-2 bg-primary-300 rounded-full animate-bounce" /><div className="w-2 h-2 bg-primary-300 rounded-full animate-bounce" style={{animationDelay:'0.15s'}} /><div className="w-2 h-2 bg-primary-300 rounded-full animate-bounce" style={{animationDelay:'0.3s'}} /></div>}
              {iaResp && <div className="mt-4 bg-gray-50 rounded-xl p-4 text-sm text-gray-700 leading-relaxed">{iaResp}</div>}
            </div>
          </div>
        )}
      </div>
    </div>
  )
}
