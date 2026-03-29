import Image from 'next/image'
import { CheckCircle, ArrowRight, MapPin, Award, Calendar } from 'lucide-react'

const diferenciais = [
  'Especialista em planejamento tributário e recuperação de tributos',
  'Contabilidade consultiva com análise profunda do seu negócio',
  'Atendimento personalizado — você não é só mais um cliente',
  'Suporte na tomada de decisões estratégicas e financeiras',
  'Integração com sistemas Onvio, Domínio e outros ERPs',
  'Portal digital com acesso aos seus documentos a qualquer hora',
]

export default function About() {
  return (
    <section id="sobre" className="relative py-28 overflow-hidden bg-white">

      {/* subtle top border */}
      <div className="absolute top-0 left-0 right-0 h-[1px] bg-gradient-to-r from-transparent via-primary-100 to-transparent" />

      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="grid lg:grid-cols-2 gap-16 items-center">

          {/* ── Visual panel ── */}
          <div className="relative">

            {/* Main card */}
            <div className="relative rounded-3xl overflow-hidden text-white mirror-shine"
              style={{
                background: 'linear-gradient(145deg, #0f2029 0%, #1B3D50 45%, #2d6485 100%)',
                border: '1px solid rgba(196,163,90,0.2)',
                boxShadow: '0 24px 60px rgba(6,15,26,0.35), 0 0 0 1px rgba(255,255,255,0.04), inset 0 1px 0 rgba(255,255,255,0.08)',
              }}
            >
              {/* Reflection blob inside card */}
              <div className="absolute top-0 right-0 w-72 h-72 rounded-full bg-gold-DEFAULT/[0.06] blur-3xl -translate-y-1/3 translate-x-1/3 pointer-events-none" />
              <div className="absolute bottom-0 left-0 w-56 h-56 rounded-full bg-bronze-500/[0.08] blur-3xl translate-y-1/3 -translate-x-1/3 pointer-events-none" />

              <div className="relative z-10 p-10">

                {/* Logo */}
                <div className="relative w-16 h-16 mb-6 rounded-full overflow-hidden ring-1 ring-gold-DEFAULT/30"
                  style={{ background: 'rgba(255,255,255,0.05)', backdropFilter: 'blur(8px)' }}
                >
                  <Image
                    src="/logo.png"
                    alt="Dalacorte Financial Solutions"
                    fill
                    className="object-contain p-2"
                  />
                </div>

                <h3 className="font-serif text-3xl font-bold mb-4 leading-snug">
                  Uma história construída sobre{' '}
                  <span className="gradient-text-gold">confiança</span>
                </h3>
                <p className="text-white/60 leading-relaxed mb-8 text-sm">
                  Contador formado e registrado no CRC MG 120587 O, com atuação desde 2012. Ao longo de mais de uma década entendemos que a contabilidade vai muito além de entregar guias — ela deve ser a base estratégica do seu negócio.
                </p>

                {/* Info badges */}
                <div className="space-y-3">
                  <div className="flex items-center gap-3 rounded-xl px-4 py-3 bg-white/[0.06] border border-white/[0.08]">
                    <div className="w-8 h-8 rounded-lg bg-gold-DEFAULT/20 flex items-center justify-center shrink-0">
                      <Award size={15} className="text-gold-DEFAULT" />
                    </div>
                    <div>
                      <p className="text-white font-semibold text-sm">CRC MG 120587 O</p>
                      <p className="text-white/45 text-xs">Contador registrado — ativo desde 2012</p>
                    </div>
                  </div>
                  <div className="flex items-center gap-3 rounded-xl px-4 py-3 bg-white/[0.06] border border-white/[0.08]">
                    <div className="w-8 h-8 rounded-lg bg-gold-DEFAULT/20 flex items-center justify-center shrink-0">
                      <MapPin size={15} className="text-gold-DEFAULT" />
                    </div>
                    <div>
                      <p className="text-white font-semibold text-sm">Paracatu — MG</p>
                      <p className="text-white/45 text-xs">R. Abadia Lemos do Prado, 199 — Prado</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            {/* Floating experience badge */}
            <div className="absolute -bottom-5 -right-5 rounded-2xl px-5 py-4 bg-white border border-gray-100 shadow-[0_12px_40px_rgba(27,61,80,0.15)]">
              <div className="flex items-center gap-3">
                <div className="w-10 h-10 rounded-xl bg-gradient-bronze flex items-center justify-center shrink-0">
                  <Calendar size={17} className="text-white" />
                </div>
                <div>
                  <p className="text-2xl font-bold font-serif text-primary-700 leading-none">+13</p>
                  <p className="text-gray-500 text-xs mt-0.5">Anos de experiência</p>
                </div>
              </div>
            </div>
          </div>

          {/* ── Content ── */}
          <div>
            <span className="text-bronze-500 font-semibold text-xs tracking-[0.18em] uppercase">Sobre nós</span>
            <div className="divider-gold mt-3 mb-5" />
            <h2 className="section-title mb-6">
              Por que escolher a{' '}
              <span className="gradient-text">Dalacorte?</span>
            </h2>
            <p className="text-gray-500 leading-relaxed mb-8 text-[15px]">
              Somos especialistas em planejamento tributário, revisão e recuperação de tributos. Mais do que um escritório contábil, somos parceiros estratégicos que entendem profundamente o seu negócio e trabalham para que você tome as melhores decisões.
            </p>

            <ul className="space-y-3 mb-10">
              {diferenciais.map(item => (
                <li key={item} className="flex items-start gap-3 group">
                  <div className="w-5 h-5 rounded-full bg-gold-DEFAULT/10 flex items-center justify-center shrink-0 mt-0.5 group-hover:bg-gold-DEFAULT/20 transition-colors">
                    <CheckCircle size={13} className="text-gold-DEFAULT" />
                  </div>
                  <span className="text-gray-600 text-sm leading-relaxed">{item}</span>
                </li>
              ))}
            </ul>

            <a href="#contato" className="btn-primary inline-flex items-center gap-2 group">
              Entre em contato
              <ArrowRight size={16} className="group-hover:translate-x-1 transition-transform" />
            </a>
          </div>
        </div>
      </div>
    </section>
  )
}
