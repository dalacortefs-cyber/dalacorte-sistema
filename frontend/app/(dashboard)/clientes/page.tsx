'use client'
import { useEffect, useState } from 'react'
import { Plus, Search, Filter, Eye, Edit, Trash2, RefreshCw } from 'lucide-react'
import api from '@/lib/api'
import { formatCpfCnpj, formatCurrency, statusColors, cn } from '@/lib/utils'
import toast from 'react-hot-toast'

interface Cliente {
  id: number; nome: string; email: string; cpf_cnpj: string
  tipo_pessoa: string; status: string; cidade: string; estado: string
  receita_mensal: number; telefone: string
}

export default function ClientesPage() {
  const [clientes, setClientes] = useState<Cliente[]>([])
  const [loading, setLoading] = useState(true)
  const [busca, setBusca] = useState('')
  const [status, setStatus] = useState('')

  const carregar = () => {
    setLoading(true)
    api.get('/clientes', { params: { busca, status, per_page: 20 } })
      .then(r => setClientes(r.data.data || []))
      .catch(() => setClientes(mockClientes))
      .finally(() => setLoading(false))
  }

  useEffect(() => { carregar() }, [busca, status])

  const excluir = async (id: number) => {
    if (!confirm('Confirmar exclusão?')) return
    await api.delete(`/clientes/${id}`)
    toast.success('Cliente removido.')
    carregar()
  }

  return (
    <div className="space-y-6 animate-fade-in">
      <div className="flex items-center justify-between">
        <div>
          <h1 className="text-2xl font-bold font-serif text-primary-700">Clientes</h1>
          <p className="text-gray-500 text-sm mt-0.5">{clientes.length} clientes cadastrados</p>
        </div>
        <button className="btn-primary flex items-center gap-2">
          <Plus size={16} /> Novo Cliente
        </button>
      </div>

      {/* Filters */}
      <div className="card p-4 flex flex-wrap gap-3">
        <div className="relative flex-1 min-w-48">
          <Search size={15} className="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" />
          <input className="input pl-9 py-2 text-sm" placeholder="Buscar por nome, e-mail ou CPF/CNPJ..." value={busca} onChange={e => setBusca(e.target.value)} />
        </div>
        <select className="input py-2 text-sm w-40" value={status} onChange={e => setStatus(e.target.value)}>
          <option value="">Todos os status</option>
          <option value="ativo">Ativo</option>
          <option value="inativo">Inativo</option>
          <option value="prospecto">Prospecto</option>
        </select>
        <button onClick={carregar} className="btn-outline py-2 px-4 text-sm flex items-center gap-2">
          <RefreshCw size={14} /> Atualizar
        </button>
      </div>

      {/* Table */}
      <div className="card p-0 overflow-hidden">
        <div className="overflow-x-auto">
          <table className="w-full text-sm">
            <thead className="bg-gray-50 border-b border-gray-100">
              <tr>
                {['Cliente', 'CPF/CNPJ', 'Tipo', 'Cidade', 'Receita Mensal', 'Status', 'Ações'].map(h => (
                  <th key={h} className="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">{h}</th>
                ))}
              </tr>
            </thead>
            <tbody className="divide-y divide-gray-50">
              {loading ? (
                [...Array(5)].map((_, i) => (
                  <tr key={i}><td colSpan={7} className="px-4 py-3"><div className="h-4 bg-gray-100 rounded animate-pulse" /></td></tr>
                ))
              ) : clientes.map(c => (
                <tr key={c.id} className="hover:bg-gray-50/50 transition-colors">
                  <td className="px-4 py-3">
                    <div>
                      <p className="font-medium text-primary-700">{c.nome}</p>
                      <p className="text-xs text-gray-400">{c.email}</p>
                    </div>
                  </td>
                  <td className="px-4 py-3 text-gray-600 font-mono text-xs">{formatCpfCnpj(c.cpf_cnpj)}</td>
                  <td className="px-4 py-3">
                    <span className="badge bg-primary-50 text-primary-600 capitalize">{c.tipo_pessoa}</span>
                  </td>
                  <td className="px-4 py-3 text-gray-500">{c.cidade}/{c.estado}</td>
                  <td className="px-4 py-3 font-medium text-primary-700">{formatCurrency(c.receita_mensal || 0)}</td>
                  <td className="px-4 py-3">
                    <span className={cn('badge', statusColors[c.status] || 'bg-gray-100 text-gray-500')}>{c.status}</span>
                  </td>
                  <td className="px-4 py-3">
                    <div className="flex items-center gap-1">
                      <button className="p-1.5 hover:bg-primary-50 rounded-lg text-primary-600 transition-colors"><Eye size={14} /></button>
                      <button className="p-1.5 hover:bg-bronze-50 rounded-lg text-bronze-600 transition-colors"><Edit size={14} /></button>
                      <button onClick={() => excluir(c.id)} className="p-1.5 hover:bg-red-50 rounded-lg text-red-500 transition-colors"><Trash2 size={14} /></button>
                    </div>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </div>
    </div>
  )
}

const mockClientes: Cliente[] = [
  { id: 1, nome: 'Empresa Exemplo LTDA', email: 'contato@empresa.com.br', cpf_cnpj: '12345678000190', tipo_pessoa: 'juridica', status: 'ativo', cidade: 'São Paulo', estado: 'SP', receita_mensal: 15000, telefone: '' },
  { id: 2, nome: 'João Silva', email: 'joao@email.com', cpf_cnpj: '12345678900', tipo_pessoa: 'fisica', status: 'ativo', cidade: 'Campinas', estado: 'SP', receita_mensal: 5000, telefone: '' },
  { id: 3, nome: 'Comércio ABC ME', email: 'financeiro@abc.com.br', cpf_cnpj: '98765432000110', tipo_pessoa: 'juridica', status: 'prospecto', cidade: 'Rio de Janeiro', estado: 'RJ', receita_mensal: 8000, telefone: '' },
]
