'use client'
import { useState } from 'react'
import { useRouter } from 'next/navigation'
import Link from 'next/link'
import { Eye, EyeOff, Lock, Mail, ArrowLeft } from 'lucide-react'
import { useAuth } from '@/hooks/useAuth'
import toast from 'react-hot-toast'

export default function LoginPage() {
  const [email, setEmail] = useState('')
  const [password, setPassword] = useState('')
  const [showPass, setShowPass] = useState(false)
  const [loading, setLoading] = useState(false)
  const { login } = useAuth()
  const router = useRouter()

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault()
    setLoading(true)
    try {
      const user = await login(email, password)
      toast.success(`Bem-vindo, ${user.name.split(' ')[0]}!`)
      if (user.tipo === 'cliente') router.push('/portal')
      else router.push('/dashboard')
    } catch (err: any) {
      toast.error(err.response?.data?.message || 'Credenciais inválidas.')
    } finally {
      setLoading(false)
    }
  }

  return (
    <div className="min-h-screen bg-gradient-hero flex">

      {/* Left — Branding */}
      <div className="hidden lg:flex lg:w-1/2 flex-col justify-between p-12 relative overflow-hidden">
        <div className="absolute inset-0 opacity-10"
          style={{ backgroundImage: 'radial-gradient(rgba(196,163,90,0.8) 1px, transparent 1px)', backgroundSize: '40px 40px' }} />
        <div className="absolute -top-32 -right-32 w-96 h-96 bg-bronze-500/10 rounded-full blur-3xl" />
        <div className="absolute -bottom-32 -left-32 w-96 h-96 bg-gold-DEFAULT/10 rounded-full blur-3xl" />

        <div className="relative">
          <Link href="/" className="flex items-center gap-3 text-white group">
            <ArrowLeft size={16} className="group-hover:-translate-x-1 transition-transform" />
            <span className="text-sm text-white/60">Voltar ao site</span>
          </Link>
        </div>

        <div className="relative">
          <div className="w-16 h-16 bg-gradient-bronze rounded-2xl flex items-center justify-center mb-6 shadow-gold">
            <span className="text-white font-serif font-bold text-2xl">D</span>
          </div>
          <h1 className="font-serif text-5xl font-bold text-white mb-4">
            Bem-vindo de<br />volta
          </h1>
          <p className="text-white/60 text-lg">
            Acesse o sistema de gestão financeira<br />da DFS Financial Solutions.
          </p>

          <div className="mt-12 grid grid-cols-2 gap-4">
            {[
              { v: '500+', l: 'Clientes' },
              { v: 'R$2B+', l: 'Gerenciados' },
              { v: '98%', l: 'Satisfação' },
              { v: '15+', l: 'Anos' },
            ].map(({ v, l }) => (
              <div key={l} className="bg-white/10 rounded-xl p-4 border border-white/10">
                <p className="text-2xl font-bold text-gold-DEFAULT font-serif">{v}</p>
                <p className="text-white/50 text-sm">{l}</p>
              </div>
            ))}
          </div>
        </div>

        <div className="relative">
          <p className="text-white/30 text-xs">© {new Date().getFullYear()} DFS Financial Solutions</p>
        </div>
      </div>

      {/* Right — Form */}
      <div className="w-full lg:w-1/2 flex items-center justify-center p-6 bg-cream-DEFAULT">
        <div className="w-full max-w-md">

          <div className="lg:hidden flex items-center gap-3 mb-8">
            <div className="w-10 h-10 bg-gradient-bronze rounded-full flex items-center justify-center text-white font-bold text-sm shadow-gold">
              DFS
            </div>
            <div>
              <p className="font-serif font-bold text-primary-700">DFS</p>
              <p className="text-bronze-500 text-xs tracking-widest">FINANCIAL SOLUTIONS</p>
            </div>
          </div>

          <h2 className="font-serif text-3xl font-bold text-primary-700 mb-2">Entrar na plataforma</h2>
          <p className="text-gray-500 mb-8">Digite suas credenciais de acesso abaixo.</p>

          <form onSubmit={handleSubmit} className="space-y-5">
            <div>
              <label className="label">E-mail</label>
              <div className="relative">
                <Mail size={16} className="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400" />
                <input
                  className="input pl-11"
                  type="email"
                  placeholder="seu@email.com"
                  value={email}
                  onChange={e => setEmail(e.target.value)}
                  required
                />
              </div>
            </div>

            <div>
              <label className="label">Senha</label>
              <div className="relative">
                <Lock size={16} className="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400" />
                <input
                  className="input pl-11 pr-11"
                  type={showPass ? 'text' : 'password'}
                  placeholder="••••••••"
                  value={password}
                  onChange={e => setPassword(e.target.value)}
                  required
                />
                <button type="button" onClick={() => setShowPass(!showPass)} className="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                  {showPass ? <EyeOff size={16} /> : <Eye size={16} />}
                </button>
              </div>
            </div>

            <button type="submit" disabled={loading} className="btn-primary w-full flex items-center justify-center gap-2">
              {loading ? (
                <><div className="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin" /> Entrando...</>
              ) : 'Entrar na plataforma'}
            </button>
          </form>

          <div className="mt-8 p-4 bg-primary-50 rounded-xl text-xs text-primary-600 space-y-1">
            <p className="font-semibold text-primary-700 mb-2">Credenciais de demonstração:</p>
            <p>Admin: <span className="font-mono">admin@dalacortefs.com.br</span> / <span className="font-mono">Admin@2024</span></p>
            <p>Funcionário: <span className="font-mono">funcionario@dalacortefs.com.br</span></p>
            <p>Cliente: <span className="font-mono">cliente@dalacortefs.com.br</span></p>
          </div>

          <p className="text-center text-sm text-gray-400 mt-6">
            <Link href="/" className="text-primary-600 hover:text-bronze-500 transition-colors">
              ← Voltar ao site
            </Link>
          </p>
        </div>
      </div>
    </div>
  )
}
