'use client'
import { useState, useEffect } from 'react'
import Link from 'next/link'
import Image from 'next/image'
import { Menu, X } from 'lucide-react'
import { cn } from '@/lib/utils'

const navLinks = [
  { label: 'Início',    href: '#inicio' },
  { label: 'Serviços',  href: '#servicos' },
  { label: 'Sobre',     href: '#sobre' },
  { label: 'Missão',    href: '#missao' },
  { label: 'Notícias',  href: '#noticias' },
  { label: 'Contato',   href: '#contato' },
]

export default function Navbar() {
  const [open, setOpen]       = useState(false)
  const [scrolled, setScrolled] = useState(false)

  useEffect(() => {
    const handler = () => setScrolled(window.scrollY > 30)
    window.addEventListener('scroll', handler)
    return () => window.removeEventListener('scroll', handler)
  }, [])

  return (
    <nav className={cn(
      'fixed top-0 left-0 right-0 z-50 transition-all duration-500',
      scrolled
        ? 'py-2 bg-[#060f1a]/90 backdrop-blur-xl border-b border-white/[0.07] shadow-[0_4px_30px_rgba(0,0,0,0.4)]'
        : 'py-5 bg-transparent'
    )}>
      {/* Gold line at very top */}
      <div className="absolute top-0 left-0 right-0 h-[2px] bg-gradient-to-r from-transparent via-gold-DEFAULT/70 to-transparent" />

      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="flex items-center justify-between">

          {/* Logo */}
          <Link href="/" className="flex items-center gap-3 group">
            <div className="relative h-11 w-11 shrink-0 rounded-full overflow-hidden ring-1 ring-gold-DEFAULT/30 group-hover:ring-gold-DEFAULT/60 transition-all duration-300 shadow-[0_0_15px_rgba(196,163,90,0.2)]">
              <Image
                src="/logo.png"
                alt="Dalacorte Financial Solutions"
                fill
                className="object-contain scale-90"
                priority
              />
            </div>
            <div className="hidden sm:block">
              <p className="text-white font-serif font-bold text-base leading-tight tracking-wide">DALACORTE</p>
              <p className="text-gold-DEFAULT text-[10px] tracking-[0.2em] uppercase font-medium">Financial Solutions</p>
            </div>
          </Link>

          {/* Desktop Links */}
          <div className="hidden md:flex items-center gap-1">
            {navLinks.map(link => (
              <a
                key={link.href}
                href={link.href}
                className="relative text-white/70 hover:text-white px-4 py-2 text-sm font-medium rounded-lg transition-all duration-200 hover:bg-white/[0.06] group"
              >
                {link.label}
                <span className="absolute bottom-1 left-1/2 -translate-x-1/2 w-0 h-[2px] bg-gradient-to-r from-bronze-500 to-gold-DEFAULT rounded-full group-hover:w-4/5 transition-all duration-300" />
              </a>
            ))}
          </div>

          {/* CTA */}
          <div className="hidden md:flex items-center gap-3">
            <Link href="/login" className="text-white/60 hover:text-white/90 text-sm font-medium transition-colors duration-200">
              Área do cliente
            </Link>
            <a
              href="#contato"
              className="btn-gold text-sm py-2 px-5"
            >
              Fale Conosco
            </a>
          </div>

          {/* Mobile toggle */}
          <button
            onClick={() => setOpen(!open)}
            className="md:hidden text-white/80 hover:text-white p-2 rounded-lg hover:bg-white/[0.07] transition-colors"
          >
            {open ? <X size={22} /> : <Menu size={22} />}
          </button>
        </div>

        {/* Mobile menu */}
        {open && (
          <div className="md:hidden mt-3 pb-4 border-t border-white/[0.08] pt-4 space-y-1 animate-fade-in">
            {navLinks.map(link => (
              <a
                key={link.href}
                href={link.href}
                onClick={() => setOpen(false)}
                className="block text-white/70 hover:text-white hover:bg-white/[0.06] px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200"
              >
                {link.label}
              </a>
            ))}
            <div className="pt-3 flex flex-col gap-2">
              <Link href="/login" className="text-center text-white/70 border border-white/20 hover:bg-white/[0.06] py-2.5 px-4 rounded-xl text-sm transition-all">
                Área do cliente
              </Link>
              <a href="#contato" className="btn-gold text-sm text-center">
                Fale Conosco
              </a>
            </div>
          </div>
        )}
      </div>
    </nav>
  )
}
