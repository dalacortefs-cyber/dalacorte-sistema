const testimonials = [
  {
    quote:
      'A Dalacorte mudou a forma como enxergamos a contabilidade. Hoje temos clareza total do nosso resultado e pagamos muito menos imposto do que antes.',
    name: 'Empresário',
    company: 'Paracatu, MG',
  },
  {
    quote:
      'Atendimento personalizado de verdade. Sinto que tenho um sócio especialista cuidando do financeiro da minha empresa.',
    name: 'Empreendedora',
    company: 'Setor de Serviços',
  },
  {
    quote:
      'Recuperamos créditos tributários que nem sabíamos que existiam. Resultado imediato já no primeiro mês.',
    name: 'Gestor',
    company: 'Setor de Construção',
  },
]

export default function Testimonials() {
  return (
    <section id="depoimentos" className="relative py-28 overflow-hidden bg-white">
      <div className="absolute top-0 left-0 right-0 h-[1px] bg-gradient-to-r from-transparent via-primary-100 to-transparent" />

      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {/* Header */}
        <div className="text-center mb-16">
          <span className="text-bronze-500 font-semibold text-xs tracking-[0.18em] uppercase">Depoimentos</span>
          <div className="divider-gold mt-3 mb-5 mx-auto" />
          <h2 className="section-title">
            O que nossos <span className="gradient-text">clientes dizem</span>
          </h2>
        </div>

        {/* Cards */}
        <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
          {testimonials.map((t, i) => (
            <div
              key={i}
              className="relative card border border-gray-100 hover:shadow-card-hover hover:-translate-y-1 transition-all duration-300 flex flex-col"
            >
              {/* Decorative quote mark */}
              <span
                className="absolute top-5 right-6 font-serif text-7xl leading-none font-bold select-none pointer-events-none"
                style={{ color: '#C4A35A', opacity: 0.12 }}
              >
                "
              </span>

              {/* Stars */}
              <div className="flex gap-0.5 mb-4">
                {[...Array(5)].map((_, j) => (
                  <span key={j} className="text-[#C4A35A] text-sm">★</span>
                ))}
              </div>

              {/* Quote */}
              <p className="text-gray-600 text-sm leading-relaxed italic flex-1 mb-6">
                "{t.quote}"
              </p>

              {/* Author */}
              <div className="flex items-center gap-3 pt-4 border-t border-gray-100">
                <div
                  className="w-9 h-9 rounded-full flex items-center justify-center shrink-0 text-white font-bold text-sm"
                  style={{ background: 'linear-gradient(135deg, #8B6B3D 0%, #C4A35A 100%)' }}
                >
                  {t.name[0]}
                </div>
                <div>
                  <p className="font-semibold text-primary-700 text-sm">— {t.name}</p>
                  <p className="text-gray-400 text-xs">{t.company}</p>
                </div>
              </div>
            </div>
          ))}
        </div>
      </div>
    </section>
  )
}
