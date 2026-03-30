'use client'
import { useState } from 'react'
import {
  Megaphone, Sparkles, Copy, Check, Calendar, Image,
  Instagram, RefreshCw, ChevronDown, ExternalLink, Hash,
  FileText, Layout, Zap, BookOpen
} from 'lucide-react'

// ─── Types ────────────────────────────────────────────────
type Formato = 'post' | 'carrossel' | 'story' | 'reels'
type Topico =
  | 'irpf' | 'mei' | 'abertura_empresa' | 'simples_nacional'
  | 'esocial' | 'planejamento_tributario' | 'educacao_financeira'
  | 'dica_fiscal' | 'novidade_legislacao' | 'institucional' | 'custom'

interface PostGerado {
  legenda: string
  slides?: string[]
  hashtags: string[]
  horario: string
  formato: string
}

// ─── Constantes ───────────────────────────────────────────
const FORMATOS: { value: Formato; label: string; icon: string; desc: string }[] = [
  { value: 'post',      label: 'Post único',  icon: '🖼️', desc: 'Imagem estática + legenda' },
  { value: 'carrossel', label: 'Carrossel',   icon: '📑', desc: 'Múltiplos slides educativos' },
  { value: 'story',     label: 'Story',       icon: '📱', desc: 'Vertical, 24h de duração' },
  { value: 'reels',     label: 'Reels',       icon: '🎬', desc: 'Vídeo curto, alto alcance' },
]

const TOPICOS: { value: Topico; label: string }[] = [
  { value: 'irpf',                 label: 'Imposto de Renda (IRPF)' },
  { value: 'mei',                  label: 'MEI — Microempreendedor' },
  { value: 'abertura_empresa',     label: 'Abertura de empresa' },
  { value: 'simples_nacional',     label: 'Simples Nacional' },
  { value: 'esocial',              label: 'eSocial / Obrigações' },
  { value: 'planejamento_tributario', label: 'Planejamento tributário' },
  { value: 'educacao_financeira',  label: 'Educação financeira' },
  { value: 'dica_fiscal',          label: 'Dica fiscal rápida' },
  { value: 'novidade_legislacao',  label: 'Novidade legislativa' },
  { value: 'institucional',        label: 'Institucional / Apresentação' },
  { value: 'custom',               label: 'Tema personalizado...' },
]

const HORARIOS: Record<Formato, string> = {
  post:      '18h00 – 19h00',
  carrossel: '12h00 – 13h00',
  story:     '08h00 – 09h00',
  reels:     '19h00 – 20h00',
}

const TEMPLATES_CANVA = [
  {
    categoria: 'Posts — Feed Contabilidade',
    desc: 'Templates profissionais para posts únicos no tema contábil/financeiro',
    link: 'https://www.canva.com/templates/?query=contabilidade+instagram',
    cor: 'bg-blue-50 border-blue-200',
    icone: '📊',
  },
  {
    categoria: 'Carrosséis Educativos',
    desc: 'Templates multi-slide para conteúdo didático (checklist, dicas, passo a passo)',
    link: 'https://www.canva.com/templates/?query=carrossel+educativo+instagram',
    cor: 'bg-amber-50 border-amber-200',
    icone: '📚',
  },
  {
    categoria: 'Stories Profissionais',
    desc: 'Templates verticais para stories com design limpo e moderno',
    link: 'https://www.canva.com/templates/?query=story+financeiro+profissional',
    cor: 'bg-green-50 border-green-200',
    icone: '📱',
  },
  {
    categoria: 'Reels — Capa e Thumbnail',
    desc: 'Capas atrativas para vídeos curtos no Instagram',
    link: 'https://www.canva.com/templates/?query=reels+cover+financeiro',
    cor: 'bg-purple-50 border-purple-200',
    icone: '🎬',
  },
]

const BRAND = {
  cores: ['#1B3A4B (azul-petróleo)', '#C9A96E (dourado/bronze)', '#FFFFFF (branco)'],
  fontes: ['Playfair Display (títulos)', 'Inter ou Montserrat (corpo)'],
  tom: 'Profissional, acessível, didático. Sem jargões. Foco no problema do cliente.',
}

// ─── Helpers ──────────────────────────────────────────────
function buildPrompt(formato: Formato, topico: Topico, topicoCustom: string): string {
  const temaFinal = topico === 'custom' ? topicoCustom : TOPICOS.find(t => t.value === topico)?.label

  const instrucoes: Record<Formato, string> = {
    post: `Crie um post único para Instagram. Retorne:
1. LEGENDA (máx 150 palavras, tom acessível, CTA para WhatsApp/DM no final)
2. TEXTO DA ARTE (máx 20 palavras para o elemento visual — impactante e direto)
3. HASHTAGS (15 hashtags: mix alta popularidade + nicho contábil + #ParacatuMG #TriânguloMineiro #ContadorParacatu #DalacorteFS)`,

    carrossel: `Crie um carrossel de 5 slides para Instagram. Retorne:
1. LEGENDA (máx 150 palavras, mencione "arrasta ➡️", CTA no final)
2. SLIDE 1 — CAPA: título impactante (máx 10 palavras) + subtítulo (máx 15 palavras)
3. SLIDE 2: título + 3-4 tópicos em bullet points
4. SLIDE 3: título + 3-4 tópicos em bullet points
5. SLIDE 4: título + 3-4 tópicos em bullet points
6. SLIDE 5 — CTA: texto de chamada para WhatsApp/DM + "Dalacorte Financial Solutions | Paracatu-MG"
7. HASHTAGS (15 hashtags: mix alta popularidade + nicho contábil + #ParacatuMG #TriânguloMineiro #ContadorParacatu #DalacorteFS)`,

    story: `Crie um story para Instagram. Retorne:
1. TEXTO PRINCIPAL (máx 8 palavras — grande, impactante)
2. SUBTEXTO (máx 20 palavras — complementa o principal)
3. ENQUETE ou INTERAÇÃO SUGERIDA (ex: "Você já sabia disso? SIM / NÃO")
4. LEGENDA DO LINK (se usar link na bio)
5. HASHTAGS (5 hashtags relevantes)`,

    reels: `Crie um roteiro para Reels de 30-45 segundos. Retorne:
1. HOOK (primeiros 3 segundos — frase de impacto que prende a atenção)
2. ROTEIRO em 3-4 partes (cada uma com 1-2 frases curtas para falar em vídeo)
3. CTA FINAL (máx 10 palavras — chamar no WhatsApp ou salvar o vídeo)
4. LEGENDA (máx 100 palavras + CTA)
5. HASHTAGS (15 hashtags: mix + #ParacatuMG #DalacorteFS)`,
  }

  return `Você é um especialista em marketing digital para escritórios de contabilidade no Brasil.
Crie conteúdo para o Instagram do escritório Dalacorte Financial Solutions, localizado em Paracatu-MG.
Público-alvo: MEI, pequenos empresários e pessoa física na região do Triângulo Mineiro / Norte de MG.
Tom de voz: profissional mas acessível, sem jargões contábeis, foco em resolver o problema do cliente.
Idioma: português brasileiro.

TEMA: ${temaFinal}
FORMATO: ${FORMATOS.find(f => f.value === formato)?.label}

${instrucoes[formato]}

Seja direto, engajante e humanizado. Não use linguagem corporativa fria.`
}

async function chamarGemini(prompt: string, apiKey: string): Promise<string> {
  const res = await fetch(
    `https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=${apiKey}`,
    {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        contents: [{ parts: [{ text: prompt }] }],
        generationConfig: { temperature: 0.8, maxOutputTokens: 2048 },
      }),
    }
  )
  if (!res.ok) throw new Error(`Erro Gemini: ${res.status}`)
  const data = await res.json()
  return data.candidates?.[0]?.content?.parts?.[0]?.text ?? ''
}

function parsearResposta(texto: string, formato: Formato): PostGerado {
  const linhas = texto.split('\n').filter(l => l.trim())

  // Extrai hashtags (linhas com #)
  const hashtagLine = linhas.find(l => l.includes('#ContadorParacatu') || l.includes('#DalacorteFS') || (l.match(/#\w/g) || []).length > 5)
  const hashtags = hashtagLine
    ? (hashtagLine.match(/#[\wÀ-ú]+/g) || [])
    : ['#ContabilidadeParacatu', '#ParacatuMG', '#DalacorteFS', '#IRPF2026', '#ContadorMG']

  // Extrai slides para carrossel
  const slides: string[] = []
  if (formato === 'carrossel') {
    for (let i = 1; i <= 6; i++) {
      const idx = linhas.findIndex(l => l.toLowerCase().includes(`slide ${i}`) || l.match(new RegExp(`^${i}[.)]\\s*(slide|capa|cta)`, 'i')))
      if (idx >= 0) {
        const conteudo = linhas.slice(idx, idx + 4).join(' | ').replace(/^\d+[.)]\s*/, '')
        slides.push(conteudo)
      }
    }
  }

  return {
    legenda: texto.slice(0, 800),
    slides: slides.length > 0 ? slides : undefined,
    hashtags: hashtags.slice(0, 20),
    horario: HORARIOS[formato],
    formato: FORMATOS.find(f => f.value === formato)?.label ?? formato,
  }
}

// ─── Componente principal ─────────────────────────────────
export default function MarketingPage() {
  const [aba, setAba] = useState<'gerador' | 'templates' | 'marca' | 'agenda'>('gerador')
  const [formato, setFormato] = useState<Formato>('carrossel')
  const [topico, setTopico] = useState<Topico>('irpf')
  const [topicoCustom, setTopicoCustom] = useState('')
  const [apiKey, setApiKey] = useState('')
  const [resultado, setResultado] = useState<PostGerado | null>(null)
  const [textoRaw, setTextoRaw] = useState('')
  const [gerando, setGerando] = useState(false)
  const [erro, setErro] = useState('')
  const [copiado, setCopiado] = useState<string | null>(null)
  const [mostrarApiKey, setMostrarApiKey] = useState(false)

  const copiar = (texto: string, id: string) => {
    navigator.clipboard.writeText(texto)
    setCopiado(id)
    setTimeout(() => setCopiado(null), 2000)
  }

  const gerar = async () => {
    if (!apiKey) { setMostrarApiKey(true); return }
    if (topico === 'custom' && !topicoCustom.trim()) {
      setErro('Digite o tema personalizado'); return
    }
    setErro('')
    setGerando(true)
    setResultado(null)
    setTextoRaw('')
    try {
      const prompt = buildPrompt(formato, topico, topicoCustom)
      const raw = await chamarGemini(prompt, apiKey)
      setTextoRaw(raw)
      setResultado(parsearResposta(raw, formato))
    } catch (e: any) {
      setErro(e.message || 'Erro ao gerar conteúdo. Verifique sua API Key do Gemini.')
    } finally {
      setGerando(false)
    }
  }

  return (
    <div className="animate-fade-in space-y-6">

      {/* Header */}
      <div className="flex items-center justify-between">
        <div>
          <h1 className="text-2xl font-bold font-serif text-primary-700 flex items-center gap-2">
            <Megaphone size={22} className="text-gold-DEFAULT" />
            Agente de Marketing
          </h1>
          <p className="text-gray-500 text-sm mt-0.5">
            Gerador de conteúdo para Instagram · Dalacorte Financial Solutions
          </p>
        </div>
        <div className="flex items-center gap-2 text-xs text-gray-400 bg-gray-50 rounded-xl px-3 py-2 border border-gray-200">
          <Instagram size={14} className="text-pink-500" />
          @dalacorte.contador
        </div>
      </div>

      {/* Abas */}
      <div className="flex gap-1 bg-gray-100 rounded-xl p-1 w-fit">
        {[
          { id: 'gerador',   label: 'Gerador',   icon: Sparkles },
          { id: 'templates', label: 'Templates', icon: Layout },
          { id: 'marca',     label: 'Identidade',icon: Image },
          { id: 'agenda',    label: 'Agenda',    icon: Calendar },
        ].map(({ id, label, icon: Icon }) => (
          <button
            key={id}
            onClick={() => setAba(id as any)}
            className={`flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium transition-all ${
              aba === id ? 'bg-white text-primary-700 shadow-sm' : 'text-gray-500 hover:text-gray-700'
            }`}
          >
            <Icon size={14} />
            {label}
          </button>
        ))}
      </div>

      {/* ── ABA: GERADOR ──────────────────────────────────── */}
      {aba === 'gerador' && (
        <div className="grid grid-cols-1 lg:grid-cols-5 gap-6">

          {/* Painel esquerdo — configuração */}
          <div className="lg:col-span-2 space-y-4">

            {/* API Key */}
            <div className="bg-white rounded-2xl shadow-card p-5 space-y-3">
              <div className="flex items-center justify-between">
                <h3 className="font-semibold text-primary-700 flex items-center gap-2">
                  <Zap size={16} className="text-gold-DEFAULT" />
                  Gemini API
                </h3>
                <a
                  href="https://aistudio.google.com/app/apikey"
                  target="_blank"
                  rel="noopener noreferrer"
                  className="text-xs text-blue-500 hover:underline flex items-center gap-1"
                >
                  Obter chave grátis <ExternalLink size={11} />
                </a>
              </div>
              <input
                type={mostrarApiKey ? 'text' : 'password'}
                className="input w-full text-sm"
                placeholder="Cole sua API Key do Google AI Studio..."
                value={apiKey}
                onChange={e => { setApiKey(e.target.value); setMostrarApiKey(false) }}
              />
              {!apiKey && (
                <p className="text-xs text-amber-600 bg-amber-50 rounded-lg px-3 py-2">
                  Acesse <strong>aistudio.google.com</strong> → Get API Key → Create API Key → copie e cole aqui. É gratuito.
                </p>
              )}
            </div>

            {/* Formato */}
            <div className="bg-white rounded-2xl shadow-card p-5 space-y-3">
              <h3 className="font-semibold text-primary-700">Formato</h3>
              <div className="grid grid-cols-2 gap-2">
                {FORMATOS.map(f => (
                  <button
                    key={f.value}
                    onClick={() => setFormato(f.value)}
                    className={`p-3 rounded-xl border text-left transition-all ${
                      formato === f.value
                        ? 'border-primary-500 bg-primary-50 text-primary-700'
                        : 'border-gray-200 hover:border-gray-300 text-gray-600'
                    }`}
                  >
                    <div className="text-lg mb-1">{f.icon}</div>
                    <div className="text-sm font-medium">{f.label}</div>
                    <div className="text-xs text-gray-400">{f.desc}</div>
                  </button>
                ))}
              </div>
            </div>

            {/* Tópico */}
            <div className="bg-white rounded-2xl shadow-card p-5 space-y-3">
              <h3 className="font-semibold text-primary-700">Tema</h3>
              <div className="relative">
                <select
                  className="input w-full appearance-none pr-8 text-sm"
                  value={topico}
                  onChange={e => setTopico(e.target.value as Topico)}
                >
                  {TOPICOS.map(t => (
                    <option key={t.value} value={t.value}>{t.label}</option>
                  ))}
                </select>
                <ChevronDown size={14} className="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none" />
              </div>
              {topico === 'custom' && (
                <input
                  className="input w-full text-sm"
                  placeholder="Ex: prazos de março, pro-labore, FGTS..."
                  value={topicoCustom}
                  onChange={e => setTopicoCustom(e.target.value)}
                />
              )}
            </div>

            {/* Botão gerar */}
            <button
              onClick={gerar}
              disabled={gerando}
              className="btn-gold w-full py-3 flex items-center justify-center gap-2 text-base font-semibold disabled:opacity-60 disabled:cursor-not-allowed"
            >
              {gerando ? (
                <><RefreshCw size={18} className="animate-spin" /> Gerando...</>
              ) : (
                <><Sparkles size={18} /> Gerar conteúdo</>
              )}
            </button>

            {erro && (
              <div className="bg-red-50 border border-red-200 rounded-xl px-4 py-3 text-sm text-red-600">
                {erro}
              </div>
            )}
          </div>

          {/* Painel direito — resultado */}
          <div className="lg:col-span-3 space-y-4">
            {!resultado && !gerando && (
              <div className="bg-white rounded-2xl shadow-card p-10 flex flex-col items-center justify-center text-center gap-4 min-h-[400px]">
                <div className="w-16 h-16 bg-gradient-bronze rounded-2xl flex items-center justify-center shadow-gold">
                  <Megaphone size={28} className="text-white" />
                </div>
                <div>
                  <h3 className="font-serif text-xl font-bold text-primary-700 mb-2">Agente pronto</h3>
                  <p className="text-gray-400 text-sm max-w-xs">
                    Configure o formato e tema ao lado, insira sua API Key do Gemini e clique em Gerar.
                  </p>
                </div>
              </div>
            )}

            {gerando && (
              <div className="bg-white rounded-2xl shadow-card p-10 flex flex-col items-center justify-center gap-4 min-h-[400px]">
                <div className="w-12 h-12 border-4 border-gold-DEFAULT border-t-transparent rounded-full animate-spin" />
                <p className="text-gray-500 text-sm">Gerando conteúdo profissional...</p>
              </div>
            )}

            {resultado && !gerando && (
              <div className="space-y-4">

                {/* Info rápida */}
                <div className="bg-white rounded-2xl shadow-card p-4 flex items-center gap-6 text-sm">
                  <div className="flex items-center gap-2 text-gray-600">
                    <FileText size={14} className="text-gold-DEFAULT" />
                    <span className="font-medium">{resultado.formato}</span>
                  </div>
                  <div className="flex items-center gap-2 text-gray-600">
                    <Calendar size={14} className="text-gold-DEFAULT" />
                    <span>Melhor horário: <strong>{resultado.horario}</strong></span>
                  </div>
                </div>

                {/* Conteúdo raw completo */}
                <div className="bg-white rounded-2xl shadow-card p-5 space-y-3">
                  <div className="flex items-center justify-between">
                    <h3 className="font-semibold text-primary-700 flex items-center gap-2">
                      <BookOpen size={16} />
                      Conteúdo gerado
                    </h3>
                    <button
                      onClick={() => copiar(textoRaw, 'raw')}
                      className="flex items-center gap-1.5 text-xs bg-gray-100 hover:bg-gray-200 rounded-lg px-3 py-1.5 transition-colors"
                    >
                      {copiado === 'raw' ? <Check size={12} className="text-green-500" /> : <Copy size={12} />}
                      {copiado === 'raw' ? 'Copiado!' : 'Copiar tudo'}
                    </button>
                  </div>
                  <div className="bg-gray-50 rounded-xl p-4 text-sm text-gray-700 leading-relaxed whitespace-pre-wrap max-h-72 overflow-y-auto font-mono text-xs">
                    {textoRaw}
                  </div>
                </div>

                {/* Hashtags */}
                <div className="bg-white rounded-2xl shadow-card p-5 space-y-3">
                  <div className="flex items-center justify-between">
                    <h3 className="font-semibold text-primary-700 flex items-center gap-2">
                      <Hash size={16} />
                      Hashtags
                    </h3>
                    <button
                      onClick={() => copiar(resultado.hashtags.join(' '), 'hashtags')}
                      className="flex items-center gap-1.5 text-xs bg-gray-100 hover:bg-gray-200 rounded-lg px-3 py-1.5 transition-colors"
                    >
                      {copiado === 'hashtags' ? <Check size={12} className="text-green-500" /> : <Copy size={12} />}
                      {copiado === 'hashtags' ? 'Copiado!' : 'Copiar'}
                    </button>
                  </div>
                  <div className="flex flex-wrap gap-2">
                    {resultado.hashtags.map((h, i) => (
                      <span key={i} className="bg-primary-50 text-primary-700 text-xs px-2.5 py-1 rounded-full border border-primary-200">
                        {h}
                      </span>
                    ))}
                  </div>
                </div>

                {/* Dica de uso */}
                <div className="bg-amber-50 border border-amber-200 rounded-2xl p-4 text-sm text-amber-800">
                  <strong>Como usar:</strong> Copie o conteúdo acima → abra um template profissional no Canva (aba Templates) →
                  substitua apenas os textos → mantenha o design do template intacto → aplique sua logo e cores da marca.
                </div>
              </div>
            )}
          </div>
        </div>
      )}

      {/* ── ABA: TEMPLATES ────────────────────────────────── */}
      {aba === 'templates' && (
        <div className="space-y-6">
          <div className="bg-blue-50 border border-blue-200 rounded-2xl p-4 text-sm text-blue-800">
            <strong>Por que templates e não IA de imagem?</strong> Templates profissionais mantêm sua identidade visual intacta.
            A IA gera layouts aleatórios que cortam logos e distorcem cores. Use os templates abaixo + o gerador de texto para resultado profissional.
          </div>

          <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
            {TEMPLATES_CANVA.map((t, i) => (
              <div key={i} className={`bg-white rounded-2xl shadow-card p-5 border ${t.cor} space-y-3`}>
                <div className="flex items-start gap-3">
                  <div className="text-3xl">{t.icone}</div>
                  <div>
                    <h3 className="font-semibold text-primary-700">{t.categoria}</h3>
                    <p className="text-sm text-gray-500 mt-1">{t.desc}</p>
                  </div>
                </div>
                <a
                  href={t.link}
                  target="_blank"
                  rel="noopener noreferrer"
                  className="flex items-center gap-2 text-sm font-medium text-primary-600 hover:text-primary-800 transition-colors"
                >
                  <ExternalLink size={14} />
                  Abrir templates no Canva
                </a>
              </div>
            ))}
          </div>

          <div className="bg-white rounded-2xl shadow-card p-6 space-y-4">
            <h3 className="font-semibold text-primary-700 text-lg">Fluxo de trabalho recomendado</h3>
            <ol className="space-y-3">
              {[
                { n: '1', txt: 'No Agente de Marketing (aba Gerador), escolha o formato e tema e clique em Gerar' },
                { n: '2', txt: 'Copie o conteúdo gerado (legenda + texto dos slides + hashtags)' },
                { n: '3', txt: 'Abra um dos templates acima no Canva e selecione o que mais combina' },
                { n: '4', txt: 'Substitua APENAS os textos do template — não mude cores, fontes ou layout' },
                { n: '5', txt: 'Adicione sua logo no local indicado do template' },
                { n: '6', txt: 'Baixe e agende no Meta Business Suite no horário recomendado' },
              ].map(({ n, txt }) => (
                <li key={n} className="flex gap-3 text-sm text-gray-600">
                  <span className="w-7 h-7 bg-primary-700 text-white rounded-full flex items-center justify-center text-xs font-bold shrink-0">{n}</span>
                  {txt}
                </li>
              ))}
            </ol>
          </div>
        </div>
      )}

      {/* ── ABA: IDENTIDADE VISUAL ────────────────────────── */}
      {aba === 'marca' && (
        <div className="space-y-4">
          <div className="grid grid-cols-1 md:grid-cols-3 gap-4">

            {/* Cores */}
            <div className="bg-white rounded-2xl shadow-card p-5 space-y-3">
              <h3 className="font-semibold text-primary-700">Paleta de cores</h3>
              <div className="space-y-2">
                <div className="flex items-center gap-3">
                  <div className="w-10 h-10 rounded-xl shadow" style={{ background: '#1B3A4B' }} />
                  <div>
                    <p className="text-sm font-medium text-gray-700">Azul-petróleo</p>
                    <p className="text-xs text-gray-400 font-mono">#1B3A4B</p>
                  </div>
                </div>
                <div className="flex items-center gap-3">
                  <div className="w-10 h-10 rounded-xl shadow" style={{ background: '#C9A96E' }} />
                  <div>
                    <p className="text-sm font-medium text-gray-700">Dourado/Bronze</p>
                    <p className="text-xs text-gray-400 font-mono">#C9A96E</p>
                  </div>
                </div>
                <div className="flex items-center gap-3">
                  <div className="w-10 h-10 rounded-xl shadow border border-gray-200" style={{ background: '#FFFFFF' }} />
                  <div>
                    <p className="text-sm font-medium text-gray-700">Branco</p>
                    <p className="text-xs text-gray-400 font-mono">#FFFFFF</p>
                  </div>
                </div>
              </div>
            </div>

            {/* Fontes */}
            <div className="bg-white rounded-2xl shadow-card p-5 space-y-3">
              <h3 className="font-semibold text-primary-700">Tipografia</h3>
              <div className="space-y-3">
                <div className="p-3 bg-gray-50 rounded-xl">
                  <p className="text-xs text-gray-400 mb-1">Títulos — Serifa elegante</p>
                  <p className="font-serif text-primary-700 text-lg font-bold">Playfair Display</p>
                  <p className="text-xs text-gray-400 mt-1">Gratuita no Google Fonts</p>
                </div>
                <div className="p-3 bg-gray-50 rounded-xl">
                  <p className="text-xs text-gray-400 mb-1">Corpo — Sans-serif limpa</p>
                  <p className="font-sans text-primary-700 text-base font-medium">Montserrat</p>
                  <p className="text-xs text-gray-400 mt-1">Gratuita no Google Fonts</p>
                </div>
              </div>
            </div>

            {/* Tom de voz */}
            <div className="bg-white rounded-2xl shadow-card p-5 space-y-3">
              <h3 className="font-semibold text-primary-700">Tom de voz</h3>
              <div className="space-y-2">
                {[
                  { emoji: '✅', txt: 'Acessível, sem jargões' },
                  { emoji: '✅', txt: 'Foco no problema do cliente' },
                  { emoji: '✅', txt: 'Didático e direto' },
                  { emoji: '✅', txt: 'Humanizado e próximo' },
                  { emoji: '❌', txt: 'Corporativo e frio' },
                  { emoji: '❌', txt: 'Termos técnicos sem explicação' },
                ].map(({ emoji, txt }, i) => (
                  <div key={i} className="flex items-center gap-2 text-sm text-gray-600">
                    <span>{emoji}</span> {txt}
                  </div>
                ))}
              </div>
            </div>
          </div>

          {/* Brand Kit Canva */}
          <div className="bg-white rounded-2xl shadow-card p-6 space-y-4">
            <h3 className="font-semibold text-primary-700">Configurar Brand Kit no Canva (1 vez)</h3>
            <p className="text-sm text-gray-500">Com o Brand Kit configurado, todas as cores e fontes ficam disponíveis com 1 clique em qualquer template.</p>
            <ol className="space-y-2 text-sm text-gray-600">
              {[
                'Acesse canva.com → clique no seu perfil → Brand Hub',
                'Clique em "Criar brand kit"',
                'Em Cores: adicione #1B3A4B e #C9A96E',
                'Em Fontes: adicione Playfair Display (título) e Montserrat (corpo)',
                'Em Logo: faça upload da logo DFS (disponível na pasta do projeto)',
                'Salve — agora o kit aparece em todos os templates automaticamente',
              ].map((step, i) => (
                <li key={i} className="flex gap-3">
                  <span className="w-6 h-6 bg-gold-DEFAULT text-white rounded-full flex items-center justify-center text-xs font-bold shrink-0">{i + 1}</span>
                  {step}
                </li>
              ))}
            </ol>
            <a
              href="https://www.canva.com/brand/"
              target="_blank"
              rel="noopener noreferrer"
              className="inline-flex items-center gap-2 btn-outline py-2 px-4 text-sm"
            >
              <ExternalLink size={14} /> Abrir Brand Hub no Canva
            </a>
          </div>
        </div>
      )}

      {/* ── ABA: AGENDA ───────────────────────────────────── */}
      {aba === 'agenda' && (
        <div className="space-y-4">
          <div className="bg-white rounded-2xl shadow-card overflow-hidden">
            <div className="bg-primary-700 px-6 py-4">
              <h3 className="font-semibold text-white">Calendário editorial — Semana IRPF 2026</h3>
              <p className="text-white/60 text-xs mt-1">Posts prontos para agendar no Meta Business Suite</p>
            </div>
            <div className="divide-y divide-gray-100">
              {[
                { dia: 'Segunda', data: '31/03', horario: '18h00', formato: 'Reels / Post',   tema: 'Urgência — Prazo IR 2026',           status: 'pronto' },
                { dia: 'Terça',   data: '01/04', horario: '12h00', formato: 'Carrossel',       tema: 'Quem é obrigado a declarar',         status: 'pronto' },
                { dia: 'Quarta',  data: '02/04', horario: '19h00', formato: 'Carrossel',       tema: 'Checklist de documentos',            status: 'pronto' },
                { dia: 'Quinta',  data: '03/04', horario: '12h00', formato: 'Carrossel',       tema: 'Deduções que a maioria não usa',     status: 'pronto' },
                { dia: 'Sexta',   data: '04/04', horario: '11h00', formato: 'Post / Reels',    tema: 'Como evitar a malha fina',           status: 'pronto' },
                { dia: 'Sábado',  data: '05/04', horario: '09h00', formato: 'Carrossel',       tema: 'Restituição — receba primeiro',      status: 'pronto' },
                { dia: 'Domingo', data: '06/04', horario: '18h00', formato: 'Post único',      tema: 'Institucional — apresentação',       status: 'pronto' },
              ].map((item, i) => (
                <div key={i} className="flex items-center gap-4 px-6 py-4 hover:bg-gray-50 transition-colors">
                  <div className="w-16 text-center shrink-0">
                    <p className="text-xs text-gray-400">{item.dia}</p>
                    <p className="font-bold text-primary-700">{item.data}</p>
                  </div>
                  <div className="w-16 text-center shrink-0">
                    <span className="text-xs bg-gold-DEFAULT/10 text-amber-700 px-2 py-1 rounded-lg font-medium">
                      {item.horario}
                    </span>
                  </div>
                  <div className="flex-1">
                    <p className="text-sm font-medium text-gray-700">{item.tema}</p>
                    <p className="text-xs text-gray-400">{item.formato}</p>
                  </div>
                  <div>
                    <span className="text-xs bg-green-100 text-green-700 px-2.5 py-1 rounded-full font-medium">
                      Conteúdo pronto
                    </span>
                  </div>
                </div>
              ))}
            </div>
          </div>

          <div className="bg-amber-50 border border-amber-200 rounded-2xl p-4 text-sm text-amber-800 space-y-1">
            <p><strong>Para agendar:</strong></p>
            <ol className="list-decimal list-inside space-y-1 text-amber-700">
              <li>Acesse <strong>business.facebook.com</strong></li>
              <li>Criar publicação → selecione Instagram</li>
              <li>Cole a legenda e hashtags do arquivo <code>semana-ir-2026.html</code></li>
              <li>Adicione a imagem criada no Canva</li>
              <li>Clique em Agendar → selecione data e horário acima</li>
            </ol>
          </div>
        </div>
      )}

    </div>
  )
}
