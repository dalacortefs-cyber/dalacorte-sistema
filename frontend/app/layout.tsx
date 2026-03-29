import type { Metadata } from 'next'
import './globals.css'
import { Toaster } from 'react-hot-toast'

export const metadata: Metadata = {
  title: 'Dalacorte Financial Solutions',
  description: 'Consultoria financeira e contábil de excelência. Transformamos números em decisões estratégicas para o crescimento do seu negócio.',
  keywords: 'contabilidade, consultoria financeira, gestão financeira, Dalacorte',
  openGraph: {
    title: 'Dalacorte Financial Solutions',
    description: 'Consultoria financeira e contábil de excelência.',
    type: 'website',
    locale: 'pt_BR',
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
