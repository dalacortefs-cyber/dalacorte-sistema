import { Target, Eye, Heart } from 'lucide-react'

const pilares = [
  {
    icon: Target,
    gradient: 'from-primary-700 to-primary-500',
    glow: 'rgba(27,61,80,0.7)',
    label: 'Missão',
    titulo: 'Ser a base estratégica do seu negócio',
    descricao:
      'Nossa missão é transformar a contabilidade em uma ferramenta real de crescimento. Após anos atuando no mercado, entendemos que vai muito além de entregar guias — entregamos análise profunda, planejamento tributário inteligente e consultoria que auxilia você a tomar as melhores decisões.',
  },
  {
    icon: Eye,
    gradient: 'from-bronze-500 to-bronze-400',
    glow: 'rgba(139,107,61,0.7)',
    label: 'Visão',
    titulo: 'Referência em contabilidade consultiva no Brasil Central',
    descricao:
      'Ser o escritório que o empreendedor consulta antes de qualquer decisão importante. Aquele que antecipa cenários, identifica oportunidades, aponta riscos e está presente em cada etapa da jornada — não apenas no fechamento de balancetes.',
  },
  {
    icon: Heart,
    gradient: 'from-gold-dark to-gold-DEFAULT',
    glow: 'rgba(196,163,90,0.7)',
    label: 'Valores',
    titulo: 'Os princípios que guiam cada decisão',
    descricao: '',
    valores: [
      { v: 'Atendimento especializado', d: 'Cada cliente recebe atenção personalizada.' },
      { v: 'Transparência',             d: 'Comunicação clara e honesta em tudo.' },
      { v: 'Comprometimento',           d: 'Seu negócio é tratado como se fosse o nosso.' },
      { v: 'Expertise técnica',         d: 'Conhecimento profundo e atualização constante.' },
      { v: 'Ética e integridade',       d: 'Orientações corretas, sem atalhos.' },
    ],
  },
]

export default function Missao() {
  return (
    <section id="missao" className="relative py-28 overflow-hidden" style={{ background: 'linear-gradient(180deg, #0d1d2b 0%, #060f1a 100%)' }}>

      {/* Decorative elements */}
      <div className="absolute inset-0 pointer-events-none overflow-hidden">
        <div className="blob w-[600px] h-[600px] -top-32 left-1/2 -translate-x-1/2 bg-primary-900/40" />
        <div className="absolute inset-0 opacity-[0.025]"
          style={{
            backgroundImage: 'linear-gradient(rgba(196,163,90,1) 1px, transparent 1px), linear-gradient(90deg, rgba(196,163,90,1) 1px, transparent 1px)',
            backgroundSize: '80px 80px',
          }}
        />
      </div>

      <div className="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {/* Header */}
        <div className="text-center mb-16">
          <span className="inline-flex items-center gap-2 text-gold-DEFAULT font-semibold text-xs tracking-[0.2em] uppercase mb-4">
            <span className="w-8 h-px bg-gradient-to-r from-transparent to-gold-DEFAULT/60" />
            Nossa essência
            <span className="w-8 h-px bg-gradient-to-l from-transparent to-gold-DEFAULT/60" />
          </span>
          <h2 className="section-title-light mt-1">Missão, Visão e{' '}
            <span className="gradient-text-gold">Valores</span>
          </h2>
          <p className="section-subtitle-light mx-auto mt-4 max-w-2xl text-center">
            Depois de mais de uma década na contabilidade, aprendemos que o nosso papel vai muito além de cumprir obrigações fiscais. Aqui, a contabilidade é consultiva — feita para servir ao crescimento do seu negócio.
          </p>
        </div>

        {/* Pilares */}
        <div className="grid lg:grid-cols-3 gap-5">
          {pilares.map(({ icon: Icon, gradient, glow, label, titulo, descricao, valores }) => (
            <div key={label} className="glass-card group cursor-default">

              {/* Icon with glow */}
              <div className="relative mb-6">
                <div className={`w-14 h-14 rounded-2xl bg-gradient-to-br ${gradient} flex items-center justify-center shadow-lg transition-transform duration-300 group-hover:scale-105`}>
                  <Icon size={26} className="text-white" />
                </div>
                <div
                  className="absolute -bottom-1 left-2 w-10 h-4 rounded-full blur-lg opacity-0 group-hover:opacity-60 transition-opacity duration-500"
                  style={{ background: glow }}
                />
              </div>

              {/* Label */}
              <span className="text-[10px] font-bold uppercase tracking-[0.2em] text-gold-DEFAULT/80 mb-2 block">{label}</span>

              {/* Title */}
              <h3 className="font-serif text-xl font-bold text-white mb-4 leading-snug">{titulo}</h3>

              {/* Body */}
              {descricao ? (
                <p className="text-white/45 text-sm leading-relaxed">{descricao}</p>
              ) : (
                <ul className="space-y-3">
                  {valores?.map(({ v, d }) => (
                    <li key={v} className="flex items-start gap-3">
                      <div className="w-1.5 h-1.5 rounded-full bg-gold-DEFAULT mt-2 shrink-0" />
                      <div>
                        <span className="text-white/80 font-semibold text-sm">{v}:</span>{' '}
                        <span className="text-white/40 text-sm">{d}</span>
                      </div>
                    </li>
                  ))}
                </ul>
              )}
            </div>
          ))}
        </div>

        {/* Quote banner */}
        <div className="mt-14 relative rounded-3xl overflow-hidden p-[1px]"
          style={{ background: 'linear-gradient(135deg, rgba(196,163,90,0.4) 0%, rgba(27,61,80,0.2) 50%, rgba(196,163,90,0.3) 100%)' }}
        >
          <div className="rounded-[23px] px-8 py-12 text-center relative overflow-hidden"
            style={{ background: 'linear-gradient(135deg, #0f2029 0%, #1B3D50 50%, #0f2029 100%)' }}
          >
            {/* reflection blob */}
            <div className="absolute top-0 left-1/2 -translate-x-1/2 w-[500px] h-[250px] bg-gold-DEFAULT/[0.05] rounded-full blur-3xl -translate-y-1/2" />

            <div className="relative z-10">
              <p className="font-serif text-2xl md:text-3xl font-bold text-white leading-relaxed max-w-3xl mx-auto">
                "Contabilidade não é obrigação — é a{' '}
                <span className="gradient-text-gold">inteligência</span>{' '}
                que sustenta as melhores decisões do seu negócio."
              </p>
              <p className="text-white/35 mt-5 text-sm tracking-wide">
                — Dalacorte Financial Solutions · CRC MG 120587 O · Paracatu, MG
              </p>
            </div>
          </div>
        </div>
      </div>
    </section>
  )
}
