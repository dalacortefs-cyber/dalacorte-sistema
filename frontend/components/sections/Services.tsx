import { BarChart3, FileText, TrendingUp, Users, Calculator, Brain, Shield, Briefcase } from 'lucide-react'

const services = [
  {
    icon: Calculator,
    title: 'Contabilidade Empresarial',
    description: 'Escrituração contábil completa, apuração de resultados e demonstrações financeiras dentro dos padrões IFRS e CPC.',
    color: 'from-primary-700 to-primary-500',
  },
  {
    icon: FileText,
    title: 'Gestão Fiscal e Tributária',
    description: 'Planejamento tributário eficiente, apuração de impostos (IRPJ, CSLL, PIS, COFINS, ISS, ICMS) e obrigações acessórias.',
    color: 'from-bronze-600 to-bronze-400',
  },
  {
    icon: Users,
    title: 'Departamento Pessoal',
    description: 'Folha de pagamento, admissões, demissões, FGTS, INSS e toda a gestão trabalhista da sua empresa.',
    color: 'from-primary-600 to-primary-400',
  },
  {
    icon: TrendingUp,
    title: 'Consultoria Financeira',
    description: 'Análise de fluxo de caixa, gestão de capital de giro, indicadores financeiros e estratégias de crescimento.',
    color: 'from-gold-dark to-gold-DEFAULT',
  },
  {
    icon: BarChart3,
    title: 'Análise de Extratos',
    description: 'Processamento inteligente de extratos bancários com categorização automática e relatórios gerenciais.',
    color: 'from-primary-800 to-primary-600',
  },
  {
    icon: Brain,
    title: 'IA Financeira',
    description: 'Análises preditivas, identificação de padrões e insights gerados por inteligência artificial para decisões mais precisas.',
    color: 'from-bronze-700 to-bronze-500',
  },
  {
    icon: Shield,
    title: 'Compliance e Auditoria',
    description: 'Revisão de processos, conformidade regulatória, auditoria interna e controles internos para empresas de todos os portes.',
    color: 'from-primary-700 to-primary-500',
  },
  {
    icon: Briefcase,
    title: 'BPO Financeiro',
    description: 'Terceirização completa do financeiro: contas a pagar, receber, conciliação bancária e relatórios executivos.',
    color: 'from-bronze-600 to-gold-DEFAULT',
  },
]

export default function Services() {
  return (
    <section id="servicos" className="py-24 bg-cream-DEFAULT">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div className="text-center mb-16">
          <span className="text-bronze-500 font-semibold text-sm tracking-widest uppercase">O que fazemos</span>
          <h2 className="section-title mt-2">Serviços completos para<br />seu negócio prosperar</h2>
          <p className="section-subtitle mx-auto mt-4 text-center">
            Do básico ao estratégico — oferecemos soluções contábeis e financeiras integradas para empresas de todos os segmentos.
          </p>
        </div>

        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
          {services.map((service, i) => {
            const Icon = service.icon
            return (
              <div
                key={service.title}
                className="card group cursor-pointer hover:-translate-y-1 transition-transform duration-300"
              >
                <div className={`w-12 h-12 rounded-xl bg-gradient-to-br ${service.color} flex items-center justify-center mb-4 shadow-md group-hover:shadow-lg transition-shadow`}>
                  <Icon size={22} className="text-white" />
                </div>
                <h3 className="font-semibold text-primary-700 mb-2 text-base">{service.title}</h3>
                <p className="text-gray-500 text-sm leading-relaxed">{service.description}</p>
              </div>
            )
          })}
        </div>

        <div className="text-center mt-12">
          <a href="#contato" className="btn-primary inline-flex items-center gap-2">
            Solicitar proposta personalizada
          </a>
        </div>
      </div>
    </section>
  )
}
