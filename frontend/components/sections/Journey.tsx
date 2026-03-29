import { ArrowRight } from 'lucide-react'

const steps = [
  {
    number: '01',
    icon: '🔍',
    title: 'Diagnóstico gratuito',
    description:
      'Conversamos sobre o seu negócio, regime tributário atual e necessidades.',
  },
  {
    number: '02',
    icon: '📄',
    title: 'Proposta personalizada',
    description:
      'Elaboramos uma proposta sob medida para o seu momento e perfil de empresa.',
  },
  {
    number: '03',
    icon: '🚀',
    title: 'Transição sem burocracia',
    description:
      'Cuidamos de toda a migração com seu contador anterior. Você foca no negócio.',
  },
]

export default function Journey() {
  return (
    <section id="jornada" className="relative py-28 overflow-hidden" style={{ background: 'linear-gradient(180deg, #060f1a 0%, #0d1d2b 100%)' }}>

      {/* Background blobs */}
      <div className="absolute inset-0 pointer-events-none overflow-hidden">
        <div className="blob w-[500px] h-[500px] -top-32 -right-32 bg-primary-800/20" />
        <div className="blob w-[400px] h-[400px] -bottom-32 -left-32 bg-bronze-900/15" />
      </div>

      <div className="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {/* Header */}
        <div className="text-center mb-16">
          <span className="inline-flex items-center gap-2 text-gold-DEFAULT font-semibold text-xs tracking-[0.2em] uppercase mb-4">
            <span className="w-8 h-px bg-gradient-to-r from-transparent to-[rgba(196,163,90,0.6)]" />
            Jornada do cliente
            <span className="w-8 h-px bg-gradient-to-l from-transparent to-[rgba(196,163,90,0.6)]" />
          </span>
          <h2 className="section-title-light mt-1">
            Como se tornar cliente{' '}
            <span className="gradient-text-gold">Dalacorte</span>
          </h2>
        </div>

        {/* Steps */}
        <div className="grid grid-cols-1 md:grid-cols-3 gap-6 mb-14 relative">

          {/* Connecting lines (desktop only) */}
          <div className="hidden md:block absolute top-[4.5rem] left-[calc(33.333%+1rem)] right-[calc(33.333%+1rem)] h-[1px]"
            style={{ background: 'linear-gradient(90deg, rgba(196,163,90,0.4), rgba(196,163,90,0.2), rgba(196,163,90,0.4))' }}
          />

          {steps.map((step) => (
            <div key={step.number} className="glass-card group cursor-default text-center">
              <div className="text-4xl mb-2">{step.icon}</div>
              <p
                className="font-serif text-5xl font-bold mb-4 leading-none"
                style={{ color: '#C4A35A' }}
              >
                {step.number}
              </p>
              <h3 className="font-serif font-bold text-white text-xl mb-3">
                {step.title}
              </h3>
              <p className="text-white/50 text-sm leading-relaxed">{step.description}</p>
            </div>
          ))}
        </div>

        {/* CTA */}
        <div className="text-center">
          <a
            href="https://wa.me/5538997541448"
            target="_blank"
            rel="noopener noreferrer"
            className="btn-gold inline-flex items-center gap-2 group text-base"
          >
            Quero iniciar agora
            <ArrowRight size={17} className="group-hover:translate-x-1 transition-transform duration-200" />
          </a>
          <p className="text-white/30 text-xs mt-4">
            Diagnóstico 100% gratuito e sem compromisso
          </p>
        </div>
      </div>
    </section>
  )
}
