'use client'
import { useState, useEffect } from 'react'
import Link from 'next/link'
import Image from 'next/image'
import { Menu, X, ChevronDown } from 'lucide-react'
import { cn } from '@/lib/utils'

const navLinks = [
  { label: 'Início', href: '#inicio' },
  { label: 'Serviços', href: '#servicos' },
  { label: 'Sobre', href: '#sobre' },
  { label: 'Notícias', href: '#noticias' },
  { label: 'Carreiras', href: '#carreiras' },
  { label: 'Contato', href: '#contato' },
]

export default function Navbar() {
  const [open, setOpen] = useState(false)
  const [scrolled, setScrolled] = useState(false)

  useEffect(() => {
    const handler = () => setScrolled(window.scrollY > 20)
    window.addEventListener('scroll', handler)
    return () => window.removeEventListener('scroll', handler)
  }, [])

  return (
    <nav className={cn(
      'fixed top-0 left-0 right-0 z-50 transition-all duration-300',
      scrolled ? 'bg-primary-900/95 backdrop-blur-md shadow-xl py-3' : 'bg-transparent py-5'
    )}>
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="flex items-center justify-between">

          {/* Logo */}
          <Link href="/" className="flex items-center gap-3 group">
            <div className="w-10 h-10 rounded-full bg-gradient-bronze flex items-center justify-center text-white font-bold text-sm shadow-gold">
              DFS
            </div>
            <div className="hidden sm:block">
              <p className="text-white font-serif font-bold text-lg leading-tight">DALACORTE</p>
              <p className="text-gold-DEFAULT text-xs tracking-widest uppercase">Financial Solutions</p>
            </div>
          </Link>

          {/* Desktop Links */}
          <div className="hidden md:flex items-center gap-1">
            {navLinks.map(link => (
              <a
                key={link.href}
                href={link.href}
                className="text-white/80 hover:text-gold-DEFAULT px-4 py-2 text-sm font-medium rounded-lg hover:bg-white/5 transition-all"
              >
                {link.label}
              </a>
            ))}
          </div>

          {/* CTA */}
          <div className="hidden md:flex items-center gap-3">
            <Link href="/login" className="text-white/80 hover:text-white text-sm font-medium transition">
              Entrar
            </Link>
            <a href="#contato" className="btn-gold text-sm py-2 px-5">
              Fale Conosco
            </a>
          </div>

          {/* Mobile Menu */}
          <button onClick={() => setOpen(!open)} className="md:hidden text-white p-2">
            {open ? <X size={24} /> : <Menu size={24} />}
          </button>
        </div>

        {/* Mobile Dropdown */}
        {open && (
          <div className="md:hidden mt-4 pb-4 border-t border-white/10 pt-4 space-y-1 animate-fade-in">
            {navLinks.map(link => (
              <a
                key={link.href}
                href={link.href}
                onClick={() => setOpen(false)}
                className="block text-white/80 hover:text-gold-DEFAULT px-4 py-2.5 text-sm font-medium rounded-lg hover:bg-white/5 transition"
              >
                {link.label}
              </a>
            ))}
            <div className="pt-3 flex flex-col gap-2">
              <Link href="/login" className="btn-outline border-white text-white text-sm text-center">
                Entrar
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
