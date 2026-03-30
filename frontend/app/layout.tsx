import type { Metadata } from 'next'
import './globals.css'
import { Toaster } from 'react-hot-toast'

export const metadata: Metadata = {
  title: 'DFS Financial Solutions — Contabilidade Consultiva em Paracatu, MG',
  description:
    'Escritório de contabilidade consultiva com mais de 13 anos de experiência. Especialistas em planejamento tributário, departamento pessoal, BPO financeiro e recuperação de tributos. CRC MG 120587 O.',
  keywords:
    'contabilidade, consultoria financeira, planejamento tributário, departamento pessoal, BPO financeiro, recuperação de tributos, Paracatu MG, DFS',
  metadataBase: new URL('https://dalacortefs.com.br'),
  openGraph: {
    title: 'DFS Financial Solutions — Contabilidade Consultiva',
    description:
      'Mais de 13 anos transformando contabilidade em vantagem competitiva. Planejamento tributário, consultoria estratégica e BPO financeiro para empresas de todos os portes.',
    type: 'website',
    locale: 'pt_BR',
    url: 'https://dalacortefs.com.br',
    siteName: 'DFS Financial Solutions',
    images: [
      {
        url: 'https://dalacortefs.com.br/logo.png',
        width: 512,
        height: 512,
        alt: 'DFS Financial Solutions',
      },
    ],
  },
  twitter: {
    card: 'summary',
    title: 'DFS Financial Solutions',
    description: 'Contabilidade consultiva com mais de 13 anos de experiência. Paracatu, MG.',
    images: ['https://dalacortefs.com.br/logo.png'],
  },
  robots: {
    index: true,
    follow: true,
  },
}

export default function RootLayout({ children }: { children: React.ReactNode }) {
  return (
    <html lang="pt-BR">
      <body>
        {children}
        <Toaster
          position="top-right"
          toastOptions={{
            duration: 4000,
            style: {
              background: '#1B3D50',
              color: '#fff',
              borderRadius: '12px',
              padding: '12px 16px',
              fontSize: '14px',
            },
            success: { iconTheme: { primary: '#C4A35A', secondary: '#fff' } },
            error: { style: { background: '#dc2626' } },
          }}
        />
      </body>
    </html>
  )
}
