'use client'
import { useEffect, useState } from 'react'
import { MapPin, Clock, ArrowRight, Briefcase, Send } from 'lucide-react'
import api from '@/lib/api'

interface Vaga {
  id: number
  titulo: string
  departamento: string
  regime: string
  local: string
  remoto: boolean
}

const beneficios = [
  'Ambiente colaborativo com foco em desenvolvimento profissional',
  'Contato direto com planejamento tributário e consultoria real',
  'Capacitação contínua e atualização técnica constante',
  'Atendimento personalizado — você aprende o que importa',
]

export default function Careers() {
  const [vagas, setVagas] = useState<Vaga[]>([])

  useEffect(() => {
    api.get('/vagas?status=aberta')
      .then(r => setVagas(r.data.data || []))
      .catch(() => setVagas([]))
  }, [])

  return (
    <section id="carreiras" className="relative py-28 overflow-hidden bg-white">
      <div className="absolute top-0 left-0 right-0 h-[1px] bg-gradient-to-r from-transparent via-gray-200 to-transparent" />

      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="grid lg:grid-cols-2 gap-16 items-start">

          {/* Content */}
          <div>
            <span className="text-bronze-500 font-semibold text-xs tracking-[0.18em] uppercase">Faça parte do time</span>
            <div className="divider-gold mt-3 mb-5" />
            <h2 className="section-title mb-6">
              Carreiras na <span className="gradient-text">Dalacorte</span>
            </h2>
            <p className="text-gray-500 leading-relaxed mb-8 text-[15px]">
              Valorizamos profissionais apaixonados por contabilidade, números e resultado. Se você quer crescer em um escritório focado em excelência técnica e atendimento especializado, venha fazer parte da nossa equipe em Paracatu — MG.
            </p>
            <ul className="space-y-3 mb-10">
              {beneficios.map(item => (
                <li key={item} className="flex items-start gap-3 group">
                  <div className="w-5 h-5 rounded-full bg-gold-DEFAULT/10 flex items-center justify-center shrink-0 mt-0.5 group-hover:bg-gold-DEFAULT/20 transition-colors">
                    <div className="w-1.5 h-1.5 rounded-full bg-gold-DEFAULT" />
                  </div>
                  <span className="text-gray-600 text-sm leading-relaxed">{item}</span>
                </li>
              ))}
            </ul>

            {/* Quick apply CTA */}
            <a
              href="mailto:contato@dalacortefs.com.br?subject=Candidatura Espontânea"
              className="btn-primary inline-flex items-center gap-2 group"
            >
              <Send size={15} />
              Enviar currículo por e-mail
              <ArrowRight size={15} className="group-hover:translate-x-1 transition-transform" />
            </a>
          </div>

          {/* Vagas */}
          <div>
            <h3 className="font-semibold text-primary-700 mb-5 flex items-center gap-2 text-base">
              <div className="w-7 h-7 rounded-lg bg-gradient-bronze flex items-center justify-center shrink-0">
                <Briefcase size={14} className="text-white" />
              </div>
              Vagas abertas
              {vagas.length > 0 && (
                <span className="ml-1 w-6 h-6 rounded-full bg-gold-DEFAULT/15 text-gold-dark text-xs flex items-center justify-center font-bold">
                  {vagas.length}
                </span>
              )}
            </h3>

            {vagas.length === 0 ? (
              <div className="rounded-2xl p-10 text-center border border-gray-100 bg-gray-50">
                <div className="w-12 h-12 rounded-2xl bg-gradient-bronze flex items-center justify-center mx-auto mb-4 opacity-40">
                  <Briefcase size={20} className="text-white" />
                </div>
                <p className="text-gray-600 font-medium text-sm">Nenhuma vaga aberta no momento.</p>
                <p className="text-gray-400 text-sm mt-1.5">
                  Envie seu currículo para{' '}
                  <a href="mailto:contato@dalacortefs.com.br" className="text-bronze-500 hover:underline">
                    contato@dalacortefs.com.br
                  </a>
                  {' '}e entraremos em contato!
                </p>
              </div>
            ) : (
              <div className="space-y-3">
                {vagas.map(vaga => (
                  <div key={vaga.id} className="card hover:-translate-y-0.5 transition-all duration-300 group cursor-pointer border border-gray-100">
                    <div className="flex justify-between items-start mb-3">
                      <div>
                        <h4 className="font-semibold text-primary-700 group-hover:text-bronze-500 transition-colors text-sm">{vaga.titulo}</h4>
                        <p className="text-gray-400 text-xs mt-0.5">{vaga.departamento}</p>
                      </div>
                      <span className="badge bg-primary-50 text-primary-700 capitalize text-xs">{vaga.regime}</span>
                    </div>
                    <div className="flex items-center gap-4 text-xs text-gray-400">
                      <span className="flex items-center gap-1.5">
                        <MapPin size={11} className="text-bronze-400" />
                        {vaga.remoto ? 'Remoto' : vaga.local}
                      </span>
                      <span className="flex items-center gap-1.5">
                        <Clock size={11} className="text-bronze-400" />
                        CLT/PJ
                      </span>
                    </div>
                  </div>
                ))}
              </div>
            )}
          </div>
        </div>
      </div>
    </section>
  )
}
