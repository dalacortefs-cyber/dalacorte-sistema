'use client'
import { useEffect, useState } from 'react'
import { MapPin, Clock, ArrowRight, Briefcase } from 'lucide-react'
import api from '@/lib/api'

interface Vaga {
  id: number
  titulo: string
  departamento: string
  regime: string
  local: string
  remoto: boolean
}

export default function Careers() {
  const [vagas, setVagas] = useState<Vaga[]>([])

  useEffect(() => {
    api.get('/vagas?status=aberta')
      .then(r => setVagas(r.data.data || []))
      .catch(() => setVagas(mockVagas))
  }, [])

  return (
    <section id="carreiras" className="py-24 bg-white">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="grid lg:grid-cols-2 gap-16 items-start">

          {/* Content */}
          <div>
            <span className="text-bronze-500 font-semibold text-sm tracking-widest uppercase">Faça parte do time</span>
            <h2 className="section-title mt-2 mb-6">Carreiras na Dalacorte</h2>
            <p className="text-gray-600 leading-relaxed mb-6">
              Valorizamos profissionais apaixonados por finanças, inovação e resultado. Se você quer crescer em um ambiente dinâmico e desafiador, venha fazer parte da nossa equipe.
            </p>
            <ul className="space-y-3 mb-8">
              {['Ambiente colaborativo e inovador', 'Plano de carreira estruturado', 'Capacitação contínua', 'Benefícios competitivos', 'Trabalho híbrido/remoto'].map(item => (
                <li key={item} className="flex items-center gap-2 text-gray-600 text-sm">
                  <div className="w-1.5 h-1.5 rounded-full bg-gold-DEFAULT" />
                  {item}
                </li>
              ))}
            </ul>
          </div>

          {/* Vagas */}
          <div>
            <h3 className="font-semibold text-primary-700 mb-4 flex items-center gap-2">
              <Briefcase size={18} className="text-bronze-500" />
              Vagas abertas ({vagas.length})
            </h3>

            {vagas.length === 0 ? (
              <div className="bg-cream-DEFAULT rounded-2xl p-8 text-center">
                <p className="text-gray-500 text-sm">Nenhuma vaga aberta no momento.</p>
                <p className="text-gray-400 text-sm mt-1">Envie seu currículo e entraremos em contato!</p>
              </div>
            ) : (
              <div className="space-y-4">
                {vagas.map(vaga => (
                  <div key={vaga.id} className="card hover:-translate-y-0.5 transition-transform group cursor-pointer">
                    <div className="flex justify-between items-start mb-3">
                      <div>
                        <h4 className="font-semibold text-primary-700 group-hover:text-bronze-500 transition-colors">{vaga.titulo}</h4>
                        <p className="text-gray-500 text-sm mt-0.5">{vaga.departamento}</p>
                      </div>
                      <span className="badge bg-primary-50 text-primary-700 capitalize">{vaga.regime}</span>
                    </div>
                    <div className="flex items-center gap-4 text-xs text-gray-400">
                      <span className="flex items-center gap-1"><MapPin size={11} />{vaga.remoto ? 'Remoto' : vaga.local}</span>
                      <span className="flex items-center gap-1"><Clock size={11} />CLT/PJ</span>
                    </div>
                  </div>
                ))}
              </div>
            )}

            <a href="#candidatura" className="btn-primary w-full mt-4 flex items-center justify-center gap-2 group">
              Enviar currículo
              <ArrowRight size={16} className="group-hover:translate-x-1 transition-transform" />
            </a>
          </div>
        </div>
      </div>
    </section>
  )
}

const mockVagas: Vaga[] = [
  { id: 1, titulo: 'Analista Contábil Sênior', departamento: 'Contabilidade', regime: 'clt', local: 'São Paulo, SP', remoto: false },
  { id: 2, titulo: 'Consultor Financeiro', departamento: 'Financeiro', regime: 'clt', local: 'São Paulo, SP', remoto: true },
  { id: 3, titulo: 'Assistente de Departamento Pessoal', departamento: 'RH/DP', regime: 'clt', local: 'São Paulo, SP', remoto: false },
]
