'use client'
import { useState, useRef, useEffect } from 'react'
import { Send, Bot, User, Sparkles, RotateCcw } from 'lucide-react'
import api from '@/lib/api'
import { useAuth } from '@/hooks/useAuth'

interface Message { role: 'user' | 'assistant'; content: string }

const sugestoes = [
  'Analise o fluxo de caixa do último trimestre',
  'Quais clientes têm maior risco de inadimplência?',
  'Sugira estratégias para redução tributária',
  'Como melhorar a margem de lucro dos meus clientes?',
]

export default function IaPage() {
  const { user } = useAuth()
  const [messages, setMessages] = useState<Message[]>([])
  const [input, setInput] = useState('')
  const [loading, setLoading] = useState(false)
  const bottomRef = useRef<HTMLDivElement>(null)

  useEffect(() => { bottomRef.current?.scrollIntoView({ behavior: 'smooth' }) }, [messages])

  const send = async (texto?: string) => {
    const msg = texto || input.trim()
    if (!msg || loading) return
    setInput('')
    const userMsg: Message = { role: 'user', content: msg }
    setMessages(prev => [...prev, userMsg])
    setLoading(true)

    try {
      const { data } = await api.post('/ia/chat', {
        mensagem: msg,
        historico: messages.slice(-10),
        system_prompt: 'Você é o assistente financeiro da Dalacorte Financial Solutions. Responda em português brasileiro de forma objetiva e profissional.',
      })
      setMessages(prev => [...prev, { role: 'assistant', content: data.resposta }])
    } catch {
      setMessages(prev => [...prev, { role: 'assistant', content: 'Desculpe, ocorreu um erro ao processar sua mensagem. Tente novamente.' }])
    } finally {
      setLoading(false)
    }
  }

  return (
    <div className="flex flex-col h-full max-h-[calc(100vh-8rem)] animate-fade-in">

      {/* Header */}
      <div className="flex items-center justify-between mb-4">
        <div>
          <h1 className="text-2xl font-bold font-serif text-primary-700 flex items-center gap-2">
            <Sparkles size={22} className="text-gold-DEFAULT" />
            IA Financeira
          </h1>
          <p className="text-gray-500 text-sm mt-0.5">Assistente especializado em finanças e contabilidade</p>
        </div>
        <button onClick={() => setMessages([])} className="btn-outline py-2 px-4 text-sm flex items-center gap-2">
          <RotateCcw size={14} /> Nova conversa
        </button>
      </div>

      {/* Chat */}
      <div className="flex-1 flex flex-col bg-white rounded-2xl shadow-card overflow-hidden">

        {/* Messages */}
        <div className="flex-1 overflow-y-auto p-6 space-y-4">
          {messages.length === 0 && (
            <div className="flex flex-col items-center justify-center h-full gap-6 text-center">
              <div className="w-16 h-16 bg-gradient-bronze rounded-2xl flex items-center justify-center shadow-gold animate-float">
                <Bot size={28} className="text-white" />
              </div>
              <div>
                <h3 className="font-serif text-xl font-bold text-primary-700 mb-2">Como posso ajudar?</h3>
                <p className="text-gray-400 text-sm max-w-sm">Sou o assistente IA da Dalacorte. Posso analisar dados financeiros, gerar insights e responder dúvidas contábeis.</p>
              </div>
              <div className="grid grid-cols-2 gap-3 w-full max-w-lg">
                {sugestoes.map(s => (
                  <button key={s} onClick={() => send(s)} className="text-left p-3 bg-gray-50 hover:bg-primary-50 hover:text-primary-700 rounded-xl text-sm text-gray-600 transition-all border border-gray-100 hover:border-primary-200">
                    {s}
                  </button>
                ))}
              </div>
            </div>
          )}

          {messages.map((msg, i) => (
            <div key={i} className={`flex gap-3 ${msg.role === 'user' ? 'flex-row-reverse' : ''}`}>
              <div className={`w-8 h-8 rounded-full flex items-center justify-center shrink-0 ${msg.role === 'user' ? 'bg-primary-700' : 'bg-gradient-bronze shadow-gold'}`}>
                {msg.role === 'user' ? <User size={14} className="text-white" /> : <Bot size={14} className="text-white" />}
              </div>
              <div className={`max-w-[75%] rounded-2xl px-4 py-3 text-sm leading-relaxed ${msg.role === 'user' ? 'bg-primary-700 text-white rounded-tr-none' : 'bg-gray-50 text-gray-700 rounded-tl-none'}`}>
                {msg.content}
              </div>
            </div>
          ))}

          {loading && (
            <div className="flex gap-3">
              <div className="w-8 h-8 rounded-full bg-gradient-bronze flex items-center justify-center">
                <Bot size={14} className="text-white" />
              </div>
              <div className="bg-gray-50 rounded-2xl rounded-tl-none px-4 py-3">
                <div className="flex gap-1">
                  {[0,1,2].map(i => <div key={i} className="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style={{ animationDelay: `${i*0.15}s` }} />)}
                </div>
              </div>
            </div>
          )}
          <div ref={bottomRef} />
        </div>

        {/* Input */}
        <div className="border-t border-gray-100 p-4">
          <div className="flex gap-3">
            <input
              className="input flex-1 py-2.5"
              placeholder="Faça uma pergunta sobre finanças, contabilidade ou seus clientes..."
              value={input}
              onChange={e => setInput(e.target.value)}
              onKeyDown={e => e.key === 'Enter' && !e.shiftKey && send()}
              disabled={loading}
            />
            <button onClick={() => send()} disabled={loading || !input.trim()} className="btn-gold px-4 py-2.5 disabled:opacity-50 disabled:cursor-not-allowed">
              <Send size={16} />
            </button>
          </div>
        </div>
      </div>
    </div>
  )
}
