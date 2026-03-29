import { BarChart3, FileText, TrendingUp, Users, Calculator, Brain, Shield, Briefcase, ArrowRight } from 'lucide-react'

const services = [
  {
    icon: Calculator,
    title: 'Contabilidade Empresarial',
    description: 'Escrituração contábil completa, apuração de resultados e demonstrações financeiras dentro dos padrões IFRS e CPC.',
    accent: 'from-primary-500/80 to-primary-700/80',
    glow: 'rgba(27,61,80,0.6)',
  },
  {
    icon: FileText,
    title: 'Gestão Fiscal e Tributária',
    description: 'Planejamento tributário eficiente, apuração de impostos (IRPJ, CSLL, PIS, COFINS, ISS, ICMS) e obrigações acessórias.',
    accent: 'from-bronze-400/80 to-bronze-600/80',
    glow: 'rgba(139,107,61,0.5)',
  },
  {
    icon: TrendingUp,
    title: 'Revisão e Recuperação de Tributos',
    description: 'Identificação de pagamentos indevidos ou a maior, com recuperação de créditos tributários para sua empresa.',
    accent: 'from-gold-dark/80 to-gold-DEFAULT/80',
    glow: 'rgba(196,163,90,0.5)',
  },
  {
    icon: Users,
    title: 'Departamento Pessoal',
    description: 'Folha de pagamento, admissões, demissões, FGTS, INSS e toda a gestão trabalhista da sua empresa.',
    accent: 'from-primary-600/80 to-primary-400/80',
    glow: 'rgba(27,61,80,0.5)',
  },
  {
    icon: BarChart3,
    title: 'Consultoria Financeira',
    description: 'Análise de fluxo de caixa, gestão de capital de giro, indicadores financeiros e estratégias de crescimento.',
    accent: 'from-bronze-500/80 to-gold-DEFAULT/80',
    glow: 'rgba(139,107,61,0.5)',
  },
  {
    icon: Brain,
    title: 'Contabilidade Consultiva',
    description: 'Análise profunda do negócio, insights estratégicos e consultoria contábil para embasar as melhores decisões.',
    accent: 'from-primary-500/80 to-bronze-500/80',
    glow: 'rgba(27,61,80,0.5)',
  },
  {
    icon: Shield,
    title: 'Compliance e Auditoria',
    description: 'Revisão de processos, conformidade regulatória, auditoria interna e controles internos para empresas de todos os portes.',
    accent: 'from-gold-dark/80 to-primary-600/80',
    glow: 'rgba(196,163,90,0.4)',
  },
  {
    icon: Briefcase,
    title: 'BPO Financeiro',
    description: 'Terceirização completa do financeiro: contas a pagar, receber, conciliação bancária e relatórios executivos.',
    accent: 'from-bronze-600/80 to-gold-DEFAULT/80',
    glow: 'rgba(139,107,61,0.5)',
  },
]

export default function Services() {
  return (
    <section id="servicos" className="relative py-28 overflow-hidden" style={{ background: 'linear-gradient(180deg, #060f1a 0%, #0d1d2b 100%)' }}>

      {/* Background decoration */}
      <div className="absolute inset-0 pointer-events-none overflow-hidden">
        <div className="blob w-[700px] h-[700px] top-0 right-0 translate-x-1/2 -translate-y-1/4 bg-primary-800/20" />
        <div className="blob w-[500px] h-[500px] bottom-0 left-0 -translate-x-1/4 translate-y-1/4 bg-bronze-900/20" />
        <div className="absolute top-1/2 left-0 right-0 h-px bg-gradient-to-r from-transparent via-gold-DEFAULT/15 to-transparent" />
      </div>

      <div className="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {/* Header */}
        <div className="text-center mb-16">
          <span className="inline-flex items-center gap-2 text-gold-DEFAULT font-semibold text-xs tracking-[0.2em] uppercase mb-4">
            <span className="w-8 h-px bg-gradient-to-r from-transparent to-gold-DEFAULT/60" />
            O que fazemos
            <span className="w-8 h-px bg-gradient-to-l from-transparent to-gold-DEFAULT/60" />
          </span>
          <h2 className="section-title-light mt-1">
            Serviços completos para seu{' '}
            <span className="gradient-text-gold">negócio prosperar</span>
          </h2>
          <p className="section-subtitle-light mx-auto mt-4 text-center max-w-xl">
            Do básico ao estratégico — soluções contábeis e financeiras integradas para empresas de todos os segmentos.
          </p>
        </div>

        {/* Grid */}
        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
          {services.map((service) => {
            const Icon = service.icon
            return (
              <div key={service.title} className="glass-card group cursor-pointer">
                {/* Icon */}
                <div className={`relative w-12 h-12 rounded-xl bg-gradient-to-br ${service.accent} flex items-center justify-center mb-5 shadow-lg transition-transform duration-300 group-hover:scale-110`}>
                  <Icon size={21} className="text-white" />
                  <div
                    className="absolute -bottom-2 left-1/2 -translate-x-1/2 w-8 h-3 rounded-full blur-md opacity-0 group-hover:opacity-100 transition-opacity duration-300"
                    style={{ background: service.glow }}
                  />
                </div>

                <h3 className="font-semibold text-white/90 mb-2 text-sm leading-snug">{service.title}</h3>
                <p className="text-white/40 text-xs leading-relaxed">{service.description}</p>

                {/* Bottom accent line on hover */}
                <div className={`absolute bottom-0 left-0 h-[2px] w-0 group-hover:w-full bg-gradient-to-r ${service.accent} transition-all duration-500 rounded-b-2xl`} />
              </div>
            )
          })}
        </div>

        {/* CTA */}
        <div className="text-center mt-12">
          <a href="#contato" className="btn-gold inline-flex items-center gap-2 group">
            Solicitar proposta personalizada
            <ArrowRight size={16} className="group-hover:translate-x-1 transition-transform" />
          </a>
        </div>
      </div>
    </section>
  )
}
