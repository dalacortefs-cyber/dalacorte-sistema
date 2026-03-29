import Link from 'next/link'
import Image from 'next/image'
import { Mail, Phone, MapPin, Instagram, Linkedin, Facebook } from 'lucide-react'

const navLinks = [
  { label: 'Início',    href: '#inicio' },
  { label: 'Serviços',  href: '#servicos' },
  { label: 'Sobre Nós', href: '#sobre' },
  { label: 'Missão',    href: '#missao' },
  { label: 'Notícias',  href: '#noticias' },
  { label: 'Contato',   href: '#contato' },
]

const contactItems = [
  { icon: Mail,   text: 'contato@dalacortefs.com.br' },
  { icon: Phone,  text: '(38) 99754-1448' },
  { icon: MapPin, text: 'R. Abadia Lemos do Prado, 199\nPrado — Paracatu, MG' },
]

const socials = [
  { icon: Instagram, href: '#' },
  { icon: Linkedin,  href: '#' },
  { icon: Facebook,  href: '#' },
]

export default function Footer() {
  return (
    <footer className="relative overflow-hidden" style={{ background: 'linear-gradient(180deg, #060f1a 0%, #030a10 100%)' }}>

      {/* Top gradient border */}
      <div className="absolute top-0 left-0 right-0 h-[1px] bg-gradient-to-r from-transparent via-gold-DEFAULT/40 to-transparent" />

      {/* Background blobs */}
      <div className="absolute inset-0 pointer-events-none">
        <div className="blob w-[500px] h-[500px] -bottom-32 -left-32 bg-primary-900/30" />
        <div className="blob w-[400px] h-[400px] -top-32 right-0 bg-bronze-900/20" />
      </div>

      <div className="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div className="grid grid-cols-1 md:grid-cols-12 gap-12">

          {/* Brand */}
          <div className="md:col-span-5">
            <div className="flex items-center gap-4 mb-6">
              <div className="shrink-0 flex items-center">
                <Image
                  src="/logo.png"
                  alt="Dalacorte Financial Solutions"
                  width={180}
                  height={60}
                  className="object-contain"
                  style={{ height: '60px', width: 'auto', maxHeight: '60px', background: 'transparent' }}
                />
              </div>
              <div>
                <p className="font-serif font-bold text-xl text-white tracking-wide">DALACORTE</p>
                <p className="text-gold-DEFAULT text-[10px] tracking-[0.22em] uppercase font-medium">Financial Solutions</p>
                <p className="text-white/25 text-xs mt-0.5">CRC MG 120587 O</p>
              </div>
            </div>

            <p className="text-white/45 text-sm leading-relaxed max-w-sm">
              Contabilidade vai além de entregar guias. Desde 2012 oferecemos atendimento especializado, análise profunda e consultoria contábil que auxilia sua empresa nas melhores decisões.
            </p>

            {/* Social */}
            <div className="flex gap-3 mt-7">
              {socials.map(({ icon: Icon, href }, i) => (
                <a
                  key={i}
                  href={href}
                  className="w-9 h-9 rounded-xl flex items-center justify-center text-white/40 hover:text-white transition-all duration-300 hover:bg-white/[0.08] border border-white/[0.07] hover:border-gold-DEFAULT/30"
                >
                  <Icon size={15} />
                </a>
              ))}
            </div>
          </div>

          {/* Links */}
          <div className="md:col-span-3">
            <h4 className="font-semibold text-white/70 mb-5 tracking-[0.12em] uppercase text-xs">Navegação</h4>
            <ul className="space-y-2.5">
              {navLinks.map(item => (
                <li key={item.label}>
                  <a href={item.href} className="text-white/40 hover:text-gold-DEFAULT text-sm transition-colors duration-200 flex items-center gap-2 group">
                    <span className="w-3 h-px bg-white/20 group-hover:bg-gold-DEFAULT/60 group-hover:w-5 transition-all duration-300" />
                    {item.label}
                  </a>
                </li>
              ))}
            </ul>
          </div>

          {/* Contact */}
          <div className="md:col-span-4">
            <h4 className="font-semibold text-white/70 mb-5 tracking-[0.12em] uppercase text-xs">Contato</h4>
            <ul className="space-y-4">
              {contactItems.map(({ icon: Icon, text }) => (
                <li key={text} className="flex items-start gap-3">
                  <div className="w-7 h-7 rounded-lg bg-gold-DEFAULT/10 flex items-center justify-center shrink-0 mt-0.5">
                    <Icon size={13} className="text-gold-DEFAULT" />
                  </div>
                  <span className="text-white/45 text-sm whitespace-pre-line">{text}</span>
                </li>
              ))}
            </ul>

            <div className="mt-5 rounded-xl px-4 py-3 border border-white/[0.07] bg-white/[0.03]">
              <p className="text-white/35 text-[10px] font-semibold uppercase tracking-[0.15em] mb-1.5">Atendimento</p>
              <p className="text-white/50 text-xs">Segunda a Sexta: <span className="text-white/70 font-medium">8h às 18h</span></p>
              <p className="text-white/25 text-xs mt-1">Não atendemos sábados e domingos.</p>
            </div>
          </div>
        </div>

        {/* Bottom bar */}
        <div className="border-t border-white/[0.07] mt-12 pt-8 flex flex-col sm:flex-row justify-between items-center gap-4">
          <p className="text-white/25 text-xs">
            © {new Date().getFullYear()} Dalacorte Financial Solutions. Todos os direitos reservados.
          </p>
          <div className="flex gap-6">
            <a href="#" className="text-white/25 hover:text-white/60 text-xs transition-colors">Privacidade</a>
            <a href="#" className="text-white/25 hover:text-white/60 text-xs transition-colors">Termos de uso</a>
          </div>
        </div>
      </div>
    </footer>
  )
}
