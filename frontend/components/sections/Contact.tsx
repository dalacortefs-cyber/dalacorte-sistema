'use client'
import { useState } from 'react'
import { Mail, Phone, MapPin, Send, CheckCircle, Clock, ArrowRight } from 'lucide-react'
import toast from 'react-hot-toast'

const info = [
  { icon: Mail,   title: 'E-mail',             value: 'contato@dalacortefs.com.br' },
  { icon: Phone,  title: 'Telefone / WhatsApp', value: '(38) 99754-1448' },
  { icon: MapPin, title: 'Endereço',            value: 'R. Abadia Lemos do Prado, 199\nPrado — Paracatu, MG' },
]

export default function Contact() {
  const [sent, setSent]       = useState(false)
  const [loading, setLoading] = useState(false)
  const [form, setForm] = useState({
    nome: '', email: '', telefone: '', empresa: '', mensagem: '', servico: '',
  })

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault()
    setLoading(true)
    await new Promise(r => setTimeout(r, 1200))
    setSent(true)
    toast.success('Mensagem enviada! Em breve entraremos em contato.')
    setLoading(false)
  }

  return (
    <section id="contato" className="relative py-28 overflow-hidden" style={{ background: 'linear-gradient(160deg, #060f1a 0%, #0f2029 50%, #1B3D50 100%)' }}>

      {/* Decorations */}
      <div className="absolute inset-0 pointer-events-none overflow-hidden">
        <div className="blob w-[600px] h-[600px] top-0 right-0 translate-x-1/3 -translate-y-1/4 bg-primary-700/15" />
        <div className="blob w-[400px] h-[400px] bottom-0 left-0 -translate-x-1/4 translate-y-1/4 bg-bronze-700/10" />
        <div className="absolute inset-0 opacity-[0.03]"
          style={{ backgroundImage: 'radial-gradient(rgba(196,163,90,1) 1px, transparent 1px)', backgroundSize: '32px 32px' }}
        />
      </div>

      <div className="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {/* Header */}
        <div className="text-center mb-14">
          <span className="inline-flex items-center gap-2 text-gold-DEFAULT font-semibold text-xs tracking-[0.2em] uppercase mb-4">
            <span className="w-8 h-px bg-gradient-to-r from-transparent to-gold-DEFAULT/60" />
            Entre em contato
            <span className="w-8 h-px bg-gradient-to-l from-transparent to-gold-DEFAULT/60" />
          </span>
          <h2 className="section-title-light mt-1">
            Vamos conversar sobre{' '}
            <span className="gradient-text-gold">o seu negócio</span>
          </h2>
          <p className="section-subtitle-light mx-auto mt-3 text-center">
            Atendimento especializado e personalizado para cada cliente.
          </p>
        </div>

        <div className="grid lg:grid-cols-5 gap-10 items-start">

          {/* ── Left info ── */}
          <div className="lg:col-span-2 space-y-3">
            {info.map(({ icon: Icon, title, value }) => (
              <div key={title} className="glass-card flex items-start gap-4">
                <div className="w-10 h-10 rounded-xl bg-gradient-bronze flex items-center justify-center shrink-0 shadow-gold">
                  <Icon size={17} className="text-white" />
                </div>
                <div>
                  <p className="text-white/45 text-xs font-medium uppercase tracking-widest mb-1">{title}</p>
                  <p className="text-white font-medium text-sm whitespace-pre-line">{value}</p>
                </div>
              </div>
            ))}

            {/* Horário */}
            <div className="glass-card border-gold-DEFAULT/25 group">
              <div className="flex items-center gap-3 mb-3">
                <div className="w-8 h-8 rounded-lg bg-gold-DEFAULT/15 flex items-center justify-center">
                  <Clock size={15} className="text-gold-DEFAULT" />
                </div>
                <p className="text-white font-semibold text-sm">Horário de atendimento</p>
              </div>
              <p className="text-white/55 text-sm">
                Segunda a Sexta:{' '}
                <span className="text-white font-semibold">8h às 18h</span>
              </p>
              <p className="text-white/30 text-xs mt-2">Não atendemos aos sábados e domingos.</p>
            </div>

            {/* WhatsApp CTA */}
            <a
              href="https://wa.me/5538997541448"
              target="_blank"
              rel="noopener noreferrer"
              className="flex items-center justify-between w-full glass-card group hover:border-green-500/40 cursor-pointer"
            >
              <div>
                <p className="text-white font-semibold text-sm">Fale direto pelo WhatsApp</p>
                <p className="text-white/40 text-xs mt-0.5">(38) 99754-1448</p>
              </div>
              <div className="w-9 h-9 rounded-xl bg-green-500/15 flex items-center justify-center group-hover:bg-green-500/25 transition-colors shrink-0">
                <ArrowRight size={16} className="text-green-400" />
              </div>
            </a>
          </div>

          {/* ── Form ── */}
          <div className="lg:col-span-3">
            {sent ? (
              <div className="glass-card flex flex-col items-center justify-center py-16 text-center">
                <div className="w-16 h-16 rounded-full bg-green-500/15 flex items-center justify-center mx-auto mb-5 ring-1 ring-green-500/30">
                  <CheckCircle size={32} className="text-green-400" />
                </div>
                <h3 className="text-xl font-bold text-white mb-2">Mensagem enviada!</h3>
                <p className="text-white/45 text-sm">Nossa equipe entrará em contato em até 24 horas úteis.</p>
              </div>
            ) : (
              <form onSubmit={handleSubmit} className="glass-card space-y-5">
                <div className="grid grid-cols-2 gap-4">
                  <div>
                    <label className="label-dark">Nome *</label>
                    <input className="input-dark" placeholder="Seu nome" value={form.nome} onChange={e => setForm({...form, nome: e.target.value})} required />
                  </div>
                  <div>
                    <label className="label-dark">E-mail *</label>
                    <input className="input-dark" type="email" placeholder="seu@email.com" value={form.email} onChange={e => setForm({...form, email: e.target.value})} required />
                  </div>
                </div>
                <div className="grid grid-cols-2 gap-4">
                  <div>
                    <label className="label-dark">Telefone</label>
                    <input className="input-dark" placeholder="(38) 99000-0000" value={form.telefone} onChange={e => setForm({...form, telefone: e.target.value})} />
                  </div>
                  <div>
                    <label className="label-dark">Empresa</label>
                    <input className="input-dark" placeholder="Nome da empresa" value={form.empresa} onChange={e => setForm({...form, empresa: e.target.value})} />
                  </div>
                </div>
                <div>
                  <label className="label-dark">Serviço de interesse</label>
                  <select
                    className="input-dark"
                    value={form.servico}
                    onChange={e => setForm({...form, servico: e.target.value})}
                  >
                    <option value="" className="bg-[#0f2029]">Selecione um serviço</option>
                    <option className="bg-[#0f2029]">Contabilidade Empresarial</option>
                    <option className="bg-[#0f2029]">Planejamento Tributário</option>
                    <option className="bg-[#0f2029]">Revisão e Recuperação de Tributos</option>
                    <option className="bg-[#0f2029]">Gestão Fiscal e Tributária</option>
                    <option className="bg-[#0f2029]">Departamento Pessoal</option>
                    <option className="bg-[#0f2029]">Consultoria Financeira</option>
                    <option className="bg-[#0f2029]">Contabilidade Consultiva</option>
                    <option className="bg-[#0f2029]">BPO Financeiro</option>
                    <option className="bg-[#0f2029]">Outro</option>
                  </select>
                </div>
                <div>
                  <label className="label-dark">Mensagem *</label>
                  <textarea
                    className="input-dark resize-none"
                    rows={4}
                    placeholder="Conte-nos sobre seu negócio e como podemos ajudar..."
                    value={form.mensagem}
                    onChange={e => setForm({...form, mensagem: e.target.value})}
                    required
                  />
                </div>
                <button
                  type="submit"
                  disabled={loading}
                  className="btn-gold w-full flex items-center justify-center gap-2 group py-3.5"
                >
                  {loading ? (
                    <span className="flex items-center gap-2">
                      <span className="w-4 h-4 rounded-full border-2 border-white/30 border-t-white animate-spin" />
                      Enviando...
                    </span>
                  ) : (
                    <>
                      <Send size={16} />
                      Enviar mensagem
                    </>
                  )}
                </button>
              </form>
            )}
          </div>
        </div>
      </div>
    </section>
  )
}
