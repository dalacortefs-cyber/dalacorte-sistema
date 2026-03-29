import { CheckCircle, ArrowRight } from 'lucide-react'

const diferenciais = [
  'Equipe certificada com CRC e especializações em IFRS',
  'Atendimento personalizado com gestor dedicado',
  'Relatórios gerenciais em tempo real via portal digital',
  'Integração com sistemas Onvio, Domínio e outros ERPs',
  'Análise de extratos com inteligência artificial',
  'Disponibilidade 24h para dúvidas via portal do cliente',
]

export default function About() {
  return (
    <section id="sobre" className="py-24 bg-white overflow-hidden">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="grid lg:grid-cols-2 gap-16 items-center">

          {/* Visual */}
          <div className="relative">
            <div className="relative bg-gradient-dalacorte rounded-3xl p-10 text-white overflow-hidden">
              <div className="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/2" />
              <div className="absolute bottom-0 left-0 w-48 h-48 bg-bronze-500/20 rounded-full translate-y-1/2 -translate-x-1/2" />

              <div className="relative z-10">
                <div className="w-16 h-16 bg-gradient-bronze rounded-2xl flex items-center justify-center mb-6 shadow-gold">
                  <span className="text-white font-serif font-bold text-2xl">D</span>
                </div>
                <h3 className="font-serif text-3xl font-bold mb-4">
                  Uma empresa construída sobre confiança
                </h3>
                <p className="text-white/70 leading-relaxed mb-8">
                  Fundada com a missão de democratizar a consultoria financeira de alto nível, a Dalacorte Financial Solutions alia expertise técnica à tecnologia de ponta para entregar resultados reais.
                </p>

                {/* Numbers */}
                <div className="grid grid-cols-3 gap-4">
                  {[
                    { v: '15+', l: 'Anos' },
                    { v: '500+', l: 'Clientes' },
                    { v: '50+', l: 'Especialistas' },
                  ].map(({ v, l }) => (
                    <div key={l} className="text-center bg-white/10 rounded-xl p-3">
                      <p className="text-2xl font-bold font-serif text-gold-DEFAULT">{v}</p>
                      <p className="text-white/60 text-xs">{l}</p>
                    </div>
                  ))}
                </div>
              </div>
            </div>

            {/* Floating badge */}
            <div className="absolute -bottom-6 -right-6 bg-white rounded-2xl shadow-card-hover p-4 border border-gray-100">
              <p className="text-3xl font-bold font-serif text-primary-700">98%</p>
              <p className="text-gray-500 text-xs mt-1">Clientes satisfeitos</p>
            </div>
          </div>

          {/* Content */}
          <div>
            <span className="text-bronze-500 font-semibold text-sm tracking-widest uppercase">Sobre nós</span>
            <h2 className="section-title mt-2 mb-6">
              Por que escolher a Dalacorte?
            </h2>
            <p className="text-gray-600 leading-relaxed mb-8">
              Somos mais do que um escritório contábil. Somos parceiros estratégicos comprometidos com o crescimento do seu negócio. Combinamos conhecimento técnico profundo com tecnologia de ponta para oferecer uma experiência única.
            </p>

            <ul className="space-y-3 mb-10">
              {diferenciais.map(item => (
                <li key={item} className="flex items-start gap-3">
                  <CheckCircle size={18} className="text-gold-DEFAULT mt-0.5 shrink-0" />
                  <span className="text-gray-600 text-sm">{item}</span>
                </li>
              ))}
            </ul>

            <a href="#contato" className="btn-primary inline-flex items-center gap-2 group">
              Conheça nossa equipe
              <ArrowRight size={16} className="group-hover:translate-x-1 transition-transform" />
            </a>
          </div>
        </div>
      </div>
    </section>
  )
}
