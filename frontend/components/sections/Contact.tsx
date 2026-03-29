'use client'
import { useState } from 'react'
import { Mail, Phone, MapPin, Send, CheckCircle } from 'lucide-react'
import toast from 'react-hot-toast'

export default function Contact() {
  const [sent, setSent] = useState(false)
  const [loading, setLoading] = useState(false)
  const [form, setForm] = useState({ nome: '', email: '', telefone: '', empresa: '', mensagem: '', servico: '' })

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault()
    setLoading(true)
    await new Promise(r => setTimeout(r, 1200))
    setSent(true)
    toast.success('Mensagem enviada! Em breve entraremos em contato.')
    setLoading(false)
  }

  return (
    <section id="contato" className="py-24 bg-gradient-hero relative overflow-hidden">
      <div className="absolute inset-0 opacity-5"
        style={{ backgroundImage: 'radial-gradient(rgba(196,163,90,0.8) 1px, transparent 1px)', backgroundSize: '30px 30px' }} />

      <div className="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="text-center mb-12">
          <span className="text-gold-DEFAULT font-semibold text-sm tracking-widest uppercase">Entre em contato</span>
          <h2 className="font-serif text-4xl font-bold text-white mt-2">Vamos conversar sobre<br />o seu negócio</h2>
        </div>

        <div className="grid lg:grid-cols-5 gap-12 items-start">

          {/* Info */}
          <div className="lg:col-span-2 space-y-6">
            {[
              { icon: Mail, title: 'E-mail', value: 'contato@dalacortefs.com.br' },
              { icon: Phone, title: 'Telefone', value: '(11) 99000-0000' },
              { icon: MapPin, title: 'Endereço', value: 'São Paulo, SP — Brasil' },
            ].map(({ icon: Icon, title, value }) => (
              <div key={title} className="flex items-start gap-4 bg-white/10 backdrop-blur-sm rounded-2xl p-5 border border-white/10">
                <div className="w-10 h-10 bg-gradient-bronze rounded-xl flex items-center justify-center shrink-0 shadow-gold">
                  <Icon size={18} className="text-white" />
                </div>
                <div>
                  <p className="text-white/60 text-xs font-medium uppercase tracking-wide mb-1">{title}</p>
                  <p className="text-white font-medium">{value}</p>
                </div>
              </div>
            ))}

            <div className="bg-white/10 backdrop-blur-sm rounded-2xl p-5 border border-gold-DEFAULT/30">
              <p className="text-white font-semibold mb-1">Horário de atendimento</p>
              <p className="text-white/60 text-sm">Segunda a Sexta: 8h às 18h</p>
              <p className="text-white/60 text-sm">Sábado: 8h às 12h</p>
            </div>
          </div>

          {/* Form */}
          <div className="lg:col-span-3">
            {sent ? (
              <div className="bg-white rounded-3xl p-10 text-center">
                <div className="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                  <CheckCircle size={32} className="text-green-600" />
                </div>
                <h3 className="text-xl font-bold text-primary-700 mb-2">Mensagem enviada!</h3>
                <p className="text-gray-500">Nossa equipe entrará em contato em até 24 horas úteis.</p>
              </div>
            ) : (
              <form onSubmit={handleSubmit} className="bg-white rounded-3xl p-8 shadow-2xl space-y-5">
                <div className="grid grid-cols-2 gap-4">
                  <div>
                    <label className="label">Nome *</label>
                    <input className="input" placeholder="Seu nome" value={form.nome} onChange={e => setForm({...form, nome: e.target.value})} required />
                  </div>
                  <div>
                    <label className="label">E-mail *</label>
                    <input className="input" type="email" placeholder="seu@email.com" value={form.email} onChange={e => setForm({...form, email: e.target.value})} required />
                  </div>
                </div>
                <div className="grid grid-cols-2 gap-4">
                  <div>
                    <label className="label">Telefone</label>
                    <input className="input" placeholder="(11) 99000-0000" value={form.telefone} onChange={e => setForm({...form, telefone: e.target.value})} />
                  </div>
                  <div>
                    <label className="label">Empresa</label>
                    <input className="input" placeholder="Nome da empresa" value={form.empresa} onChange={e => setForm({...form, empresa: e.target.value})} />
                  </div>
                </div>
                <div>
                  <label className="label">Serviço de interesse</label>
                  <select className="input" value={form.servico} onChange={e => setForm({...form, servico: e.target.value})}>
                    <option value="">Selecione um serviço</option>
                    <option>Contabilidade Empresarial</option>
                    <option>Gestão Fiscal e Tributária</option>
                    <option>Departamento Pessoal</option>
                    <option>Consultoria Financeira</option>
                    <option>BPO Financeiro</option>
                    <option>Outro</option>
                  </select>
                </div>
                <div>
                  <label className="label">Mensagem *</label>
                  <textarea className="input resize-none" rows={4} placeholder="Conte-nos sobre seu negócio e como podemos ajudar..." value={form.mensagem} onChange={e => setForm({...form, mensagem: e.target.value})} required />
                </div>
                <button type="submit" disabled={loading} className="btn-gold w-full flex items-center justify-center gap-2 group">
                  {loading ? 'Enviando...' : (
                    <><Send size={16} /> Enviar mensagem</>
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
