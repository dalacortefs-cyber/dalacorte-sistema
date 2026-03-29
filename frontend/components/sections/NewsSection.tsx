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
  financeiro:  'bg-primary-700/20 text-primary-300 border border-primary-500/20',
  contabil:    'bg-bronze-700/20 text-bronze-300 border border-bronze-500/20',
  fiscal:      'bg-yellow-900/20 text-yellow-400 border border-yellow-500/20',
  trabalhista: 'bg-purple-900/20 text-purple-300 border border-purple-500/20',
  empresarial: 'bg-green-900/20 text-green-400 border border-green-500/20',
  geral:       'bg-white/10 text-white/60 border border-white/10',
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
    <section id="noticias" className="relative py-28 overflow-hidden bg-white">

      {/* subtle top border */}
      <div className="absolute top-0 left-0 right-0 h-[1px] bg-gradient-to-r from-transparent via-gray-200 to-transparent" />

      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div className="flex flex-col sm:flex-row sm:items-end justify-between mb-12 gap-4">
          <div>
            <span className="text-bronze-500 font-semibold text-xs tracking-[0.18em] uppercase">Fique por dentro</span>
            <div className="divider-gold mt-3 mb-4" />
            <h2 className="section-title">Notícias & <span className="gradient-text">Insights</span></h2>
          </div>
          <Link href="/noticias" className="text-primary-600 hover:text-bronze-500 font-medium text-sm flex items-center gap-1.5 transition-colors group shrink-0">
            Ver todas as notícias
            <ArrowRight size={15} className="group-hover:translate-x-1 transition-transform" />
          </Link>
        </div>

        {loading ? (
          <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {[...Array(4)].map((_, i) => (
              <div key={i} className={`bg-gray-100 rounded-2xl animate-pulse h-64 ${i === 0 ? 'lg:col-span-2 h-full' : ''}`} />
            ))}
          </div>
        ) : (
          <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {/* Featured */}
            {featured && (
              <Link href={`/noticias/${featured.slug}`} className="lg:col-span-2 group">
                <article className="bg-white rounded-2xl overflow-hidden shadow-card hover:shadow-card-hover transition-all duration-300 h-full flex flex-col border border-gray-100 hover:-translate-y-1">
                  <div className="h-56 relative overflow-hidden" style={{ background: 'linear-gradient(135deg, #0f2029 0%, #1B3D50 50%, #2d6485 100%)' }}>
                    <div className="absolute inset-0 bg-gradient-mirror" />
                    <div className="absolute inset-0 flex items-end p-6">
                      <span className={`badge text-xs ${categoriaBg[featured.categoria] || 'bg-white/10 text-white/60'}`}>
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

            {/* Side grid */}
            <div className="flex flex-col gap-4">
              {others.map(n => (
                <Link key={n.id} href={`/noticias/${n.slug}`} className="group">
                  <article className="bg-white rounded-2xl p-5 shadow-card hover:shadow-card-hover transition-all duration-300 flex gap-4 border border-gray-100 hover:-translate-y-0.5">
                    <div className="w-14 h-14 rounded-xl shrink-0 overflow-hidden" style={{ background: 'linear-gradient(135deg, #1B3D50, #2d6485)' }} />
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
  { id: 1, titulo: 'Planejamento tributário: por que fazer antes do fechamento do ano?', resumo: 'Antecipar o planejamento tributário é uma das estratégias mais eficientes para reduzir a carga de impostos de forma legal e estruturada. Saiba como funciona e quando começar.', slug: 'planejamento-tributario', categoria: 'fiscal', publicado_em: new Date().toISOString(), user: { name: 'Equipe Dalacorte' } },
  { id: 2, titulo: 'Revisão de tributos: sua empresa pode ter créditos a recuperar', resumo: 'Muitas empresas pagam impostos a mais por falta de revisão periódica. A recuperação de tributos pagos indevidamente pode representar uma entrada relevante de recursos.', slug: 'revisao-tributos', categoria: 'fiscal', publicado_em: new Date().toISOString(), user: { name: 'Equipe Dalacorte' } },
  { id: 3, titulo: 'Contabilidade consultiva: o que é e por que importa para o seu negócio', resumo: 'Ir além das obrigações fiscais é o que diferencia uma contabilidade comum de uma contabilidade estratégica. Entenda como a análise profunda dos dados pode mudar a gestão da sua empresa.', slug: 'contabilidade-consultiva', categoria: 'contabil', publicado_em: new Date().toISOString(), user: { name: 'Equipe Dalacorte' } },
  { id: 4, titulo: 'Escolha do regime tributário: Simples, Lucro Presumido ou Lucro Real?', resumo: 'A escolha errada do regime tributário pode custar caro. Entenda as diferenças e como um planejamento especializado pode indicar o melhor caminho para o seu negócio.', slug: 'regime-tributario', categoria: 'fiscal', publicado_em: new Date().toISOString(), user: { name: 'Equipe Dalacorte' } },
]
