import { Check, ArrowRight } from 'lucide-react'

const plans = [
  {
    icon: '🌱',
    name: 'Plano Essencial',
    badge: 'Mais indicado para MEI e ME',
    badgeClass: 'bg-gray-100 text-gray-600',
    para: 'MEI, ME e EPP no Simples Nacional',
    featured: false,
    items: [
      'Escrituração contábil completa',
      'Apuração de impostos (DAS, DCTF, etc.)',
      'Folha de pagamento e encargos',
      'Obrigações acessórias (SPED, EFD)',
      'Portal do cliente digital',
      'Atendimento via WhatsApp e e-mail',
    ],
    cta: 'Solicitar proposta',
  },
  {
    icon: '🚀',
    name: 'Plano Estratégico',
    badge: 'Mais popular',
    badgeClass: 'bg-[rgba(196,163,90,0.15)] text-[#A8883A] border border-[rgba(196,163,90,0.3)]',
    para: 'Empresas em crescimento (LP e LR)',
    featured: true,
    items: [
      'Tudo do Plano Essencial',
      'Planejamento tributário personalizado',
      'Relatórios gerenciais mensais (DRE, Balancete, Fluxo de Caixa)',
      'Diagnóstico fiscal e recuperação de tributos',
      'Reuniões estratégicas trimestrais',
      'Dashboard de indicadores (KPIs)',
      'Consultoria trabalhista e eSocial',
    ],
    cta: 'Solicitar proposta',
  },
  {
    icon: '💎',
    name: 'Plano Executivo',
    badge: 'Para empresas consolidadas',
    badgeClass: 'bg-bronze-500/10 text-bronze-500 border border-bronze-500/20',
    para: 'Empresas de médio porte, LP e LR, alta complexidade',
    featured: false,
    items: [
      'Tudo do Plano Estratégico',
      'Contabilidade consultiva com análise profunda',
      'Gestor de conta exclusivo',
      'Relatórios personalizados com prazo sob demanda',
      'Planejamento tributário preventivo e revisional',
      'Suporte a auditorias externas e due diligence',
      'BPO Financeiro completo (contas a pagar/receber)',
      'Atendimento prioritário com SLA garantido',
    ],
    cta: 'Falar com especialista',
  },
]

export default function Plans() {
  return (
    <section id="planos" className="relative py-28 overflow-hidden" style={{ background: 'linear-gradient(180deg, #f8f9fb 0%, #ffffff 100%)' }}>
      <div className="absolute top-0 left-0 right-0 h-[1px] bg-gradient-to-r from-transparent via-primary-100 to-transparent" />

      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {/* Header */}
        <div className="text-center mb-16">
          <span className="text-bronze-500 font-semibold text-xs tracking-[0.18em] uppercase">Planos</span>
          <div className="divider-gold mt-3 mb-5 mx-auto" />
          <h2 className="section-title">
            Planos pensados para cada fase da{' '}
            <span className="gradient-text">sua empresa</span>
          </h2>
          <p className="section-subtitle mx-auto mt-4 text-center max-w-xl">
            Do microempreendedor à empresa consolidada — encontre o plano ideal
          </p>
        </div>

        {/* Cards */}
        <div className="grid grid-cols-1 md:grid-cols-3 gap-6 items-start">
          {plans.map((plan) => (
            <div
              key={plan.name}
              className={`relative rounded-2xl p-8 flex flex-col transition-all duration-300 ${
                plan.featured
                  ? 'text-white shadow-gold-lg md:scale-[1.03] z-10'
                  : 'bg-white border border-gray-100 shadow-card hover:shadow-card-hover hover:-translate-y-1'
              }`}
              style={plan.featured ? {
                background: 'linear-gradient(160deg, #0f2029 0%, #1B3D50 100%)',
                border: '2px solid rgba(196,163,90,0.6)',
              } : {}}
            >
              {/* Featured top bar */}
              {plan.featured && (
                <div
                  className="absolute top-0 left-0 right-0 h-[3px] rounded-t-2xl"
                  style={{ background: 'linear-gradient(90deg, #8B6B3D, #C4A35A)' }}
                />
              )}

              {/* Icon + Badge */}
              <div className="text-3xl mb-3">{plan.icon}</div>
              <span className={`inline-flex self-start px-3 py-1 rounded-full text-xs font-medium mb-4 ${plan.badgeClass}`}>
                {plan.badge}
              </span>

              {/* Name + Para */}
              <h3 className={`font-serif text-xl font-bold mb-1 ${plan.featured ? 'text-white' : 'text-primary-700'}`}>
                {plan.name}
              </h3>
              <p className={`text-xs mb-6 leading-snug ${plan.featured ? 'text-white/50' : 'text-gray-400'}`}>
                Para: {plan.para}
              </p>

              {/* Features */}
              <ul className="space-y-2.5 mb-8 flex-1">
                {plan.items.map(item => (
                  <li key={item} className="flex items-start gap-2.5">
                    <div className={`w-4 h-4 rounded-full flex items-center justify-center shrink-0 mt-0.5 ${
                      plan.featured ? 'bg-[rgba(196,163,90,0.2)]' : 'bg-[rgba(196,163,90,0.1)]'
                    }`}>
                      <Check size={10} className="text-[#C4A35A]" />
                    </div>
                    <span className={`text-sm leading-snug ${plan.featured ? 'text-white/75' : 'text-gray-600'}`}>
                      {item}
                    </span>
                  </li>
                ))}
              </ul>

              {/* CTA */}
              <a
                href="#contato"
                className={`inline-flex items-center justify-center gap-2 w-full py-3 px-6 rounded-xl font-semibold text-sm transition-all duration-300 active:scale-95 group ${
                  plan.featured
                    ? 'text-white shadow-gold hover:shadow-gold-lg'
                    : 'bg-primary-700 text-white hover:bg-primary-800'
                }`}
                style={plan.featured ? { background: 'linear-gradient(135deg, #8B6B3D 0%, #C4A35A 100%)' } : {}}
              >
                {plan.cta}
                <ArrowRight size={15} className="group-hover:translate-x-0.5 transition-transform" />
              </a>
            </div>
          ))}
        </div>

        {/* Footer note */}
        <p className="text-center text-gray-400 text-xs mt-12 max-w-2xl mx-auto leading-relaxed">
          Todos os planos são personalizados. Os valores são definidos conforme o regime tributário,
          número de funcionários e complexidade das operações.{' '}
          <a href="#contato" className="text-bronze-500 hover:underline">Solicite uma proposta sem compromisso.</a>
        </p>
      </div>
    </section>
  )
}
