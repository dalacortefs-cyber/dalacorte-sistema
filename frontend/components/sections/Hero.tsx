'use client'
import Image from 'next/image'
import { ArrowRight, Shield, BookOpen, TrendingUp, Star } from 'lucide-react'

const stats = [
  { value: 'Desde 2012',  label: 'Experiência consolidada' },
  { value: 'CRC MG',      label: '120587 O — Registrado' },
  { value: 'Tributário',  label: 'Planejamento especializado' },
  { value: 'Consultivo',  label: 'Contabilidade estratégica' },
]

const badges = [
  { icon: Shield,    text: 'CRC Certificado' },
  { icon: TrendingUp, text: 'Planejamento Tributário' },
  { icon: BookOpen,  text: 'Contabilidade Consultiva' },
]

export default function Hero() {
  return (
    <section id="inicio" className="relative min-h-screen flex items-center overflow-hidden bg-gradient-hero">

      {/* Animated background blobs */}
      <div className="absolute inset-0 overflow-hidden pointer-events-none">
        <div className="blob w-[600px] h-[600px] -top-48 -right-32 bg-primary-600/20 animate-float" />
        <div className="blob w-[500px] h-[500px] -bottom-48 -left-32 bg-bronze-600/15 animate-float" style={{ animationDelay: '-3s' }} />
        <div className="blob w-[400px] h-[400px] top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-gold-DEFAULT/5" />

        {/* Grid overlay */}
        <div
          className="absolute inset-0 opacity-[0.04]"
          style={{
            backgroundImage: 'linear-gradient(rgba(196,163,90,1) 1px, transparent 1px), linear-gradient(90deg, rgba(196,163,90,1) 1px, transparent 1px)',
            backgroundSize: '70px 70px',
          }}
        />
        {/* Radial vignette */}
        <div className="absolute inset-0 bg-[radial-gradient(ellipse_80%_80%_at_50%_50%,transparent_40%,#060f1a_100%)]" />
      </div>

      <div className="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-36">
        <div className="grid lg:grid-cols-2 gap-20 items-center">

          {/* ── Left: Content ── */}
          <div className="animate-slide-up space-y-8">

            {/* Tag */}
            <div className="inline-flex items-center gap-2.5 rounded-full px-5 py-2.5 border border-gold-DEFAULT/30 bg-gold-DEFAULT/[0.08] backdrop-blur-sm animate-pulse-gold">
              <Star size={13} className="text-gold-DEFAULT fill-gold-DEFAULT" />
              <span className="text-white/85 text-sm font-medium tracking-wide">Mais de 13 anos transformando contabilidades</span>
            </div>

            {/* Headline */}
            <div>
              <h1 className="font-serif text-5xl md:text-6xl xl:text-[68px] font-bold text-white leading-[1.1] tracking-tight">
                Contabilidade que
                <br />
                <span className="gradient-text-gold">transforma</span>
                <br />
                decisões
              </h1>
            </div>

            {/* Sub */}
            <p className="text-white/55 text-lg md:text-xl leading-relaxed max-w-md">
              Mais de uma década transformando contabilidade em vantagem competitiva. Planejamento tributário, análise profunda e consultoria estratégica para o crescimento do seu negócio.
            </p>

            {/* CTAs */}
            <div className="flex flex-wrap gap-4 pt-2">
              <a href="#contato" className="btn-gold flex items-center gap-2 text-base group">
                Fale com um especialista
                <ArrowRight size={17} className="group-hover:translate-x-1 transition-transform duration-200" />
              </a>
              <a
                href="#servicos"
                className="flex items-center gap-2 text-white/80 hover:text-white text-base font-medium border border-white/20 hover:border-white/40 px-6 py-3 rounded-xl transition-all duration-300 hover:bg-white/[0.06] backdrop-blur-sm"
              >
                Nossos serviços
              </a>
            </div>

            {/* Trust line */}
            <div className="flex flex-wrap items-center gap-6 pt-4 border-t border-white/[0.08]">
              {badges.map(({ icon: Icon, text }) => (
                <div key={text} className="flex items-center gap-2 text-white/50 text-sm hover:text-white/70 transition-colors group">
                  <Icon size={15} className="text-gold-DEFAULT group-hover:scale-110 transition-transform" />
                  {text}
                </div>
              ))}
            </div>
          </div>

          {/* ── Right: Logo + Stats ── */}
          <div className="flex flex-col items-center gap-8 animate-slide-in-right">

            {/* Logo container espelhado */}
            <div className="relative">
              {/* Outer glow ring */}
              <div className="absolute inset-0 rounded-full bg-gold-DEFAULT/10 blur-2xl scale-125 animate-glow-pulse" />

              {/* Ring decoration */}
              <div className="absolute inset-[-12px] rounded-full border border-gold-DEFAULT/20 animate-[spin_20s_linear_infinite]">
                <div className="absolute top-0 left-1/2 -translate-x-1/2 -translate-y-1/2 w-2 h-2 rounded-full bg-gold-DEFAULT" />
              </div>
              <div className="absolute inset-[-24px] rounded-full border border-white/[0.05] animate-[spin_35s_linear_infinite_reverse]">
                <div className="absolute bottom-0 left-1/4 -translate-y-1/2 w-1.5 h-1.5 rounded-full bg-bronze-400/60" />
              </div>

              {/* Logo glass container */}
              <div className="relative w-52 h-52 rounded-full mirror-shine overflow-hidden"
                style={{
                  background: 'radial-gradient(circle at 35% 30%, rgba(196,163,90,0.15) 0%, rgba(27,61,80,0.4) 50%, rgba(6,15,26,0.6) 100%)',
                  border: '1px solid rgba(196,163,90,0.25)',
                  boxShadow: '0 0 40px rgba(196,163,90,0.15), 0 0 80px rgba(27,61,80,0.3), inset 0 1px 0 rgba(255,255,255,0.1)',
                  backdropFilter: 'blur(8px)',
                }}
              >
                <Image
                  src="/logo.png"
                  alt="DFS Financial Solutions"
                  fill
                  className="object-contain p-8 drop-shadow-2xl"
                  priority
                  style={{ mixBlendMode: 'multiply' }}
                />
              </div>
            </div>

            {/* Stats grid */}
            <div className="grid grid-cols-2 gap-3 w-full">
              {stats.map((item) => (
                <div
                  key={item.value}
                  className="glass-card group cursor-default"
                >
                  <p className="text-lg font-bold font-serif text-white group-hover:gradient-text-gold transition-colors leading-tight">{item.value}</p>
                  <p className="text-white/45 text-xs mt-1 leading-snug">{item.label}</p>
                </div>
              ))}
            </div>
          </div>
        </div>
      </div>

      {/* Scroll indicator */}
      <div className="absolute bottom-8 left-1/2 -translate-x-1/2 flex flex-col items-center gap-2">
        <div className="w-px h-10 bg-gradient-to-b from-transparent to-white/30" />
        <div className="w-1.5 h-1.5 rounded-full bg-gold-DEFAULT animate-bounce" />
      </div>
    </section>
  )
}
