'use client'
import { useEffect, useState } from 'react'
import Link from 'next/link'
import { ArrowRight, Calendar, Tag } from 'lucide-react'
import api from '@/lib/api'
import { formatDate } from '@/lib/utils'

interface Noticia {
  id: number
  titulo: string
  resumo: string
  slug: string
  categoria: string
  publicado_em: string
  imagem_capa?: string
  user: { name: string }
}

const categoriaLabel: Record<string, string> = {
  financeiro: 'Financeiro', contabil: 'Contábil', fiscal: 'Fiscal',
  trabalhista: 'Trabalhista', empresarial: 'Empresarial', geral: 'Geral',
}

const categoriaBg: Record<string, string> = {
  financeiro: 'bg-primary-100 text-primary-700',
  contabil: 'bg-bronze-100 text-bronze-700',
  fiscal: 'bg-yellow-100 text-yellow-700',
  trabalhista: 'bg-purple-100 text-purple-700',
  empresarial: 'bg-green-100 text-green-700',
  geral: 'bg-gray-100 text-gray-600',
}

export default function NewsSection() {
  const [noticias, setNoticias] = useState<Noticia[]>([])
  const [loading, setLoading] = useState(true)

  useEffect(() => {
    api.get('/portal/noticias?per_page=6')
      .then(r => setNoticias(r.data.data || []))
      .catch(() => setNoticias(mockNoticias))
      .finally(() => setLoading(false))
  }, [])

  const featured = noticias[0]
  const others = noticias.slice(1, 5)

  return (
    <section id="noticias" className="py-24 bg-cream-DEFAULT">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div className="flex flex-col sm:flex-row sm:items-end justify-between mb-12 gap-4">
          <div>
            <span className="text-bronze-500 font-semibold text-sm tracking-widest uppercase">Fique por dentro</span>
            <h2 className="section-title mt-2">Notícias & Insights</h2>
          </div>
          <Link href="/noticias" className="text-primary-700 hover:text-bronze-500 font-medium text-sm flex items-center gap-1.5 transition-colors group shrink-0">
            Ver todas as notícias
            <ArrowRight size={15} className="group-hover:translate-x-1 transition-transform" />
          </Link>
        </div>

        {loading ? (
          <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {[...Array(4)].map((_, i) => (
              <div key={i} className={`bg-white rounded-2xl animate-pulse h-64 ${i === 0 ? 'lg:col-span-2 lg:row-span-2 h-full' : ''}`} />
            ))}
          </div>
        ) : (
          <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {/* Featured */}
            {featured && (
              <Link href={`/noticias/${featured.slug}`} className="lg:col-span-2 group">
                <article className="bg-white rounded-2xl overflow-hidden shadow-card hover:shadow-card-hover transition-all duration-300 h-full flex flex-col">
                  <div className="h-64 bg-gradient-dalacorte relative overflow-hidden">
                    <div className="absolute inset-0 flex items-end p-6">
                      <span className={`badge ${categoriaBg[featured.categoria] || 'bg-gray-100 text-gray-600'}`}>
                        {categoriaLabel[featured.categoria]}
                      </span>
                    </div>
                  </div>
                  <div className="p-6 flex-1 flex flex-col">
                    <h3 className="font-serif text-xl font-bold text-primary-700 group-hover:text-bronze-500 transition-colors line-clamp-2 mb-3">
                      {featured.titulo}
                    </h3>
                    <p className="text-gray-500 text-sm leading-relaxed line-clamp-3 flex-1">{featured.resumo}</p>
                    <div className="flex items-center gap-4 mt-4 pt-4 border-t border-gray-100 text-xs text-gray-400">
                      <span className="flex items-center gap-1"><Calendar size={12} />{formatDate(featured.publicado_em)}</span>
                      <span>{featured.user?.name}</span>
                    </div>
                  </div>
                </article>
              </Link>
            )}

            {/* Grid */}
            <div className="flex flex-col gap-6">
              {others.map(n => (
                <Link key={n.id} href={`/noticias/${n.slug}`} className="group">
                  <article className="bg-white rounded-2xl p-5 shadow-card hover:shadow-card-hover transition-all duration-300 flex gap-4">
                    <div className="w-16 h-16 rounded-xl bg-gradient-dalacorte shrink-0" />
                    <div className="flex-1 min-w-0">
                      <span className={`badge text-xs mb-1.5 ${categoriaBg[n.categoria] || 'bg-gray-100 text-gray-600'}`}>
                        {categoriaLabel[n.categoria]}
                      </span>
                      <h4 className="font-semibold text-primary-700 group-hover:text-bronze-500 transition-colors text-sm line-clamp-2">
                        {n.titulo}
                      </h4>
                      <p className="text-xs text-gray-400 mt-1 flex items-center gap-1">
                        <Calendar size={10} />{formatDate(n.publicado_em)}
                      </p>
                    </div>
                  </article>
                </Link>
              ))}
            </div>
          </div>
        )}
      </div>
    </section>
  )
}

const mockNoticias: Noticia[] = [
  { id: 1, titulo: 'Novas regras do Simples Nacional para 2024', resumo: 'O governo federal publicou as novas regras para empresas optantes pelo Simples Nacional, com mudanças importantes nas alíquotas e limites de faturamento.', slug: 'simples-nacional-2024', categoria: 'fiscal', publicado_em: new Date().toISOString(), user: { name: 'Equipe Dalacorte' } },
  { id: 2, titulo: 'Como reduzir a carga tributária da sua empresa', resumo: 'Estratégias legais de planejamento tributário que podem economizar até 30% nos impostos.', slug: 'reducao-tributaria', categoria: 'financeiro', publicado_em: new Date().toISOString(), user: { name: 'Equipe Dalacorte' } },
  { id: 3, titulo: 'Gestão de fluxo de caixa em tempos de incerteza', resumo: 'Boas práticas para manter a saúde financeira do negócio mesmo em cenários adversos.', slug: 'fluxo-caixa', categoria: 'financeiro', publicado_em: new Date().toISOString(), user: { name: 'Equipe Dalacorte' } },
  { id: 4, titulo: 'eSocial: o que mudou em 2024', resumo: 'Guia completo com todas as atualizações do eSocial e como se adequar às novas obrigações.', slug: 'esocial-2024', categoria: 'trabalhista', publicado_em: new Date().toISOString(), user: { name: 'Equipe Dalacorte' } },
]
