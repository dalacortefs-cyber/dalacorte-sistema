import Link from 'next/link'
import { Mail, Phone, MapPin, Instagram, Linkedin, Facebook } from 'lucide-react'

export default function Footer() {
  return (
    <footer className="bg-primary-900 text-white">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div className="grid grid-cols-1 md:grid-cols-4 gap-12">

          {/* Brand */}
          <div className="md:col-span-2">
            <div className="flex items-center gap-3 mb-4">
              <div className="w-12 h-12 rounded-full bg-gradient-bronze flex items-center justify-center text-white font-bold shadow-gold">
                DFS
              </div>
              <div>
                <p className="font-serif font-bold text-xl">DALACORTE</p>
                <p className="text-gold-DEFAULT text-xs tracking-widest">FINANCIAL SOLUTIONS</p>
              </div>
            </div>
            <p className="text-white/60 text-sm leading-relaxed max-w-sm">
              Transformamos números em decisões estratégicas. Consultoria financeira e contábil de excelência para o crescimento sustentável do seu negócio.
            </p>
            <div className="flex gap-4 mt-6">
              <a href="#" className="w-9 h-9 rounded-full bg-white/10 hover:bg-gold-DEFAULT/20 flex items-center justify-center transition-colors">
                <Instagram size={16} />
              </a>
              <a href="#" className="w-9 h-9 rounded-full bg-white/10 hover:bg-gold-DEFAULT/20 flex items-center justify-center transition-colors">
                <Linkedin size={16} />
              </a>
              <a href="#" className="w-9 h-9 rounded-full bg-white/10 hover:bg-gold-DEFAULT/20 flex items-center justify-center transition-colors">
                <Facebook size={16} />
              </a>
            </div>
          </div>

          {/* Links */}
          <div>
            <h4 className="font-semibold text-gold-DEFAULT mb-4 tracking-wide uppercase text-sm">Navegação</h4>
            <ul className="space-y-2.5">
              {['Início', 'Serviços', 'Sobre Nós', 'Notícias', 'Carreiras', 'Contato'].map(item => (
                <li key={item}>
                  <a href="#" className="text-white/60 hover:text-gold-DEFAULT text-sm transition-colors">{item}</a>
                </li>
              ))}
            </ul>
          </div>

          {/* Contato */}
          <div>
            <h4 className="font-semibold text-gold-DEFAULT mb-4 tracking-wide uppercase text-sm">Contato</h4>
            <ul className="space-y-3">
              <li className="flex items-start gap-3 text-sm text-white/60">
                <Mail size={15} className="mt-0.5 text-gold-DEFAULT shrink-0" />
                contato@dalacortefs.com.br
              </li>
              <li className="flex items-start gap-3 text-sm text-white/60">
                <Phone size={15} className="mt-0.5 text-gold-DEFAULT shrink-0" />
                (11) 99000-0000
              </li>
              <li className="flex items-start gap-3 text-sm text-white/60">
                <MapPin size={15} className="mt-0.5 text-gold-DEFAULT shrink-0" />
                São Paulo, SP — Brasil
              </li>
            </ul>
          </div>
        </div>

        <div className="border-t border-white/10 mt-12 pt-8 flex flex-col sm:flex-row justify-between items-center gap-4">
          <p className="text-white/40 text-sm">
            © {new Date().getFullYear()} Dalacorte Financial Solutions. Todos os direitos reservados.
          </p>
          <div className="flex gap-6">
            <a href="#" className="text-white/40 hover:text-white text-sm transition">Privacidade</a>
            <a href="#" className="text-white/40 hover:text-white text-sm transition">Termos</a>
          </div>
        </div>
      </div>
    </footer>
  )
}
