'use client'
import { useEffect, useState } from 'react'
import { Plus, Eye, Edit, Trash2, Globe, BookOpen } from 'lucide-react'
import api from '@/lib/api'
import { formatDateShort, statusColors, cn } from '@/lib/utils'
import toast from 'react-hot-toast'

interface Noticia { id: number; titulo: string; categoria: string; status: string; destaque: boolean; visualizacoes: number; publicado_em: string; user: { name: string } }

export default function NoticiasPage() {
  const [noticias, setNoticias] = useState<Noticia[]>([])
  const [loading, setLoading] = useState(true)

  useEffect(() => {
    api.get('/noticias?per_page=20')
      .then(r => setNoticias(r.data.data || []))
      .catch(() => setNoticias(mockNoticias))
      .finally(() => setLoading(false))
  }, [])

  const publicar = async (id: number) => {
    await api.patch(`/noticias/${id}/publicar`)
    toast.success('Notícia publicada!')
    setNoticias(prev => prev.map(n => n.id === id ? { ...n, status: 'publicado' } : n))
  }

  return (
    <div className="space-y-6 animate-fade-in">
      <div className="flex items-center justify-between">
        <div>
          <h1 className="text-2xl font-bold font-serif text-primary-700">Notícias</h1>
          <p className="text-gray-500 text-sm">{noticias.length} publicações</p>
        </div>
        <button className="btn-primary flex items-center gap-2"><Plus size={16} /> Nova Notícia</button>
      </div>

      {/* Stats */}
      <div className="grid grid-cols-3 gap-4">
        {[
          { label: 'Publicadas', value: noticias.filter(n => n.status === 'publicado').length, icon: Globe, color: 'bg-green-500' },
          { label: 'Rascunhos', value: noticias.filter(n => n.status === 'rascunho').length, icon: BookOpen, color: 'bg-yellow-500' },
          { label: 'Visualizações', value: noticias.reduce((s, n) => s + n.visualizacoes, 0), icon: Eye, color: 'bg-primary-700' },
        ].map(({ label, value, icon: Icon, color }) => (
          <div key={label} className="card flex items-center gap-4">
            <div className={`w-10 h-10 ${color} rounded-xl flex items-center justify-center`}>
              <Icon size={18} className="text-white" />
            </div>
            <div>
              <p className="text-2xl font-bold text-primary-700 font-serif">{value}</p>
              <p className="text-xs text-gray-500">{label}</p>
            </div>
          </div>
        ))}
      </div>

      {/* Table */}
      <div className="card p-0 overflow-hidden">
        <table className="w-full text-sm">
          <thead className="bg-gray-50 border-b">
            <tr>{['Título', 'Categoria', 'Autor', 'Status', 'Visualizações', 'Data', 'Ações'].map(h => (
              <th key={h} className="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">{h}</th>
            ))}</tr>
          </thead>
          <tbody className="divide-y divide-gray-50">
            {loading ? [...Array(4)].map((_, i) => <tr key={i}><td colSpan={7} className="px-4 py-3"><div className="h-4 bg-gray-100 rounded animate-pulse" /></td></tr>) :
              noticias.map(n => (
                <tr key={n.id} className="hover:bg-gray-50/50">
                  <td className="px-4 py-3">
                    <div className="flex items-center gap-2">
                      {n.destaque && <span className="badge bg-gold-DEFAULT/20 text-gold-dark text-[10px]">Destaque</span>}
                      <p className="font-medium text-primary-700 line-clamp-1">{n.titulo}</p>
                    </div>
                  </td>
                  <td className="px-4 py-3"><span className="badge bg-primary-50 text-primary-600 capitalize">{n.categoria}</span></td>
                  <td className="px-4 py-3 text-gray-500">{n.user?.name}</td>
                  <td className="px-4 py-3"><span className={cn('badge', statusColors[n.status] || 'bg-gray-100')}>{n.status}</span></td>
                  <td className="px-4 py-3 text-gray-500">{n.visualizacoes}</td>
                  <td className="px-4 py-3 text-gray-500">{n.publicado_em ? formatDateShort(n.publicado_em) : '—'}</td>
                  <td className="px-4 py-3">
                    <div className="flex gap-1">
                      {n.status === 'rascunho' && <button onClick={() => publicar(n.id)} className="p-1.5 hover:bg-green-50 rounded-lg text-green-600"><Globe size={14} /></button>}
                      <button className="p-1.5 hover:bg-bronze-50 rounded-lg text-bronze-600"><Edit size={14} /></button>
                      <button className="p-1.5 hover:bg-red-50 rounded-lg text-red-500"><Trash2 size={14} /></button>
                    </div>
                  </td>
                </tr>
              ))}
          </tbody>
        </table>
      </div>
    </div>
  )
}

const mockNoticias: Noticia[] = [
  { id: 1, titulo: 'Novas regras do Simples Nacional para 2024', categoria: 'fiscal', status: 'publicado', destaque: true, visualizacoes: 342, publicado_em: new Date().toISOString(), user: { name: 'Admin' } },
  { id: 2, titulo: 'Como reduzir a carga tributária da sua empresa', categoria: 'financeiro', status: 'publicado', destaque: false, visualizacoes: 218, publicado_em: new Date().toISOString(), user: { name: 'Admin' } },
  { id: 3, titulo: 'eSocial: atualização importante para empresas', categoria: 'trabalhista', status: 'rascunho', destaque: false, visualizacoes: 0, publicado_em: '', user: { name: 'Admin' } },
]
