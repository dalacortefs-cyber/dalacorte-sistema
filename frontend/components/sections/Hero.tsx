'use client'
import { ArrowRight, TrendingUp, Shield, Award } from 'lucide-react'
import { motion } from 'framer-motion'

const stats = [
  { value: '500+', label: 'Clientes atendidos' },
  { value: '15+', label: 'Anos de experiência' },
  { value: '98%', label: 'Satisfação' },
  { value: 'R$ 2B+', label: 'Gerenciados' },
]

export default function Hero() {
  return (
    <section id="inicio" className="relative min-h-screen flex items-center overflow-hidden bg-gradient-hero">

      {/* Background decoration */}
      <div className="absolute inset-0 overflow-hidden">
        <div className="absolute -top-40 -right-40 w-96 h-96 bg-bronze-500/10 rounded-full blur-3xl" />
        <div className="absolute -bottom-40 -left-40 w-96 h-96 bg-primary-500/20 rounded-full blur-3xl" />
        <div className="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-gold-DEFAULT/5 rounded-full blur-3xl" />
        {/* Grid pattern */}
        <div className="absolute inset-0 opacity-5"
          style={{ backgroundImage: 'linear-gradient(rgba(196,163,90,0.3) 1px, transparent 1px), linear-gradient(90deg, rgba(196,163,90,0.3) 1px, transparent 1px)', backgroundSize: '60px 60px' }} />
      </div>

      <div className="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-32">
        <div className="grid lg:grid-cols-2 gap-16 items-center">

          {/* Content */}
          <div className="animate-slide-up">
            <div className="inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm border border-gold-DEFAULT/30 rounded-full px-4 py-2 mb-6">
              <Award size={14} className="text-gold-DEFAULT" />
              <span className="text-white/90 text-sm font-medium">Consultoria Financeira de Excelência</span>
            </div>

            <h1 className="font-serif text-5xl md:text-6xl xl:text-7xl font-bold text-white leading-tight mb-6">
              Números que{' '}
              <span className="gradient-text">transformam</span>{' '}
              negócios
            </h1>

            <p className="text-white/70 text-lg md:text-xl leading-relaxed mb-10 max-w-lg">
              Da gestão contábil à consultoria estratégica — a Dalacorte Financial Solutions é o parceiro que o seu negócio precisa para crescer com segurança.
            </p>

            <div className="flex flex-wrap gap-4">
              <a href="#contato" className="btn-gold flex items-center gap-2 text-base group">
                Começar agora
                <ArrowRight size={18} className="group-hover:translate-x-1 transition-transform" />
              </a>
              <a href="#servicos" className="btn-outline border-white/40 text-white hover:bg-white/10 hover:text-white text-base">
                Nossos serviços
              </a>
            </div>

            {/* Trust badges */}
            <div className="flex flex-wrap gap-6 mt-12 pt-8 border-t border-white/10">
              {[
                { icon: Shield, text: 'CRC Certificado' },
                { icon: TrendingUp, text: 'Gestão Estratégica' },
                { icon: Award, text: 'Top 10 BR 2024' },
              ].map(({ icon: Icon, text }) => (
                <div key={text} className="flex items-center gap-2 text-white/60 text-sm">
                  <Icon size={16} className="text-gold-DEFAULT" />
                  {text}
                </div>
              ))}
            </div>
          </div>

          {/* Stats cards */}
          <div className="grid grid-cols-2 gap-4 animate-slide-in-right">
            {stats.map((stat, i) => (
              <div
                key={stat.label}
                className="bg-white/10 backdrop-blur-sm border border-white/10 rounded-2xl p-6 hover:bg-white/15 transition-all duration-300 hover:border-gold-DEFAULT/30 hover:shadow-gold group"
                style={{ animationDelay: `${i * 0.1}s` }}
              >
                <p className="text-4xl font-bold font-serif text-white group-hover:text-gold-DEFAULT transition-colors">{stat.value}</p>
                <p className="text-white/60 text-sm mt-1">{stat.label}</p>
              </div>
            ))}

            {/* Feature card */}
            <div className="col-span-2 bg-gradient-bronze rounded-2xl p-6 shadow-gold">
              <div className="flex items-center gap-3 mb-3">
                <div className="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                  <TrendingUp size={18} className="text-white" />
                </div>
                <span className="text-white font-semibold">IA Financeira</span>
              </div>
              <p className="text-white/80 text-sm">
                Análise inteligente de extratos bancários e relatórios automatizados com inteligência artificial.
              </p>
            </div>
          </div>
        </div>
      </div>

      {/* Scroll indicator */}
      <div className="absolute bottom-8 left-1/2 -translate-x-1/2 flex flex-col items-center gap-2 animate-bounce">
        <div className="w-px h-12 bg-gradient-to-b from-white/0 to-white/40" />
        <div className="w-1.5 h-1.5 rounded-full bg-gold-DEFAULT" />
      </div>
    </section>
  )
}
