import { ArrowRight } from 'lucide-react'

const especialidades = [
  {
    icon: '💼',
    title: 'Consultoria Tributária',
    description:
      'Planejamento tributário inteligente, escolha do regime (Simples/LP/LR), revisão de tributos pagos a maior e recuperação de créditos fiscais.',
  },
  {
    icon: '📋',
    title: 'Consultoria em Departamento Pessoal',
    description:
      'Gestão completa da folha de pagamento, admissões, demissões, eSocial, FGTS e compliance trabalhista com o Acordo Coletivo vigente.',
  },
  {
    icon: '📈',
    title: 'Consultoria Financeira',
    description:
      'Análise de fluxo de caixa, indicadores de desempenho (KPIs), gestão de capital de giro e suporte à tomada de decisões estratégicas.',
  },
  {
    icon: '🏢',
    title: 'BPO Contábil/Financeiro',
    description:
      'Terceirização completa do back-office contábil e financeiro: contas a pagar/receber, conciliação bancária, relatórios gerenciais e DRE.',
  },
]

export default function Consultoria() {
  return (
    <section id="consultoria" className="relative py-28 overflow-hidden bg-white">
      <div className="absolute top-0 left-0 right-0 h-[1px] bg-gradient-to-r from-transparent via-primary-100 to-transparent" />

      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {/* Header */}
        <div className="text-center mb-16">
          <span className="text-bronze-500 font-semibold text-xs tracking-[0.18em] uppercase">Especialidades</span>
          <div className="divider-gold mt-3 mb-5 mx-auto" />
          <h2 className="section-title">
            Nossas <span className="gradient-text">especialidades</span>
          </h2>
          <p className="section-subtitle mx-auto mt-4 text-center max-w-xl">
            Soluções completas para cada necessidade do seu negócio
          </p>
        </div>

        {/* Cards */}
        <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
          {especialidades.map((item) => (
            <div
              key={item.title}
              className="group flex gap-5 p-7 rounded-2xl bg-white border border-gray-100 hover:border-bronze-200 hover:shadow-card-hover transition-all duration-300"
              style={{ borderLeft: '4px solid #C4A35A' }}
            >
              {/* Icon */}
              <div className="text-3xl shrink-0 w-12 h-12 flex items-center justify-center rounded-xl bg-primary-50 group-hover:bg-primary-100 transition-colors duration-300">
                {item.icon}
              </div>

              <div className="flex-1 min-w-0">
                <h3 className="font-serif font-bold text-primary-700 mb-2 text-[1.05rem] leading-snug">
                  {item.title}
                </h3>
                <p className="text-gray-500 text-sm leading-relaxed mb-4">{item.description}</p>
                <a
                  href="#contato"
                  className="inline-flex items-center gap-1.5 text-bronze-500 hover:text-bronze-600 text-sm font-semibold transition-colors group/link"
                >
                  Saiba mais
                  <ArrowRight size={14} className="group-hover/link:translate-x-0.5 transition-transform duration-200" />
                </a>
              </div>
            </div>
          ))}
        </div>
      </div>
    </section>
  )
}
