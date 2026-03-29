/** @type {import('next').NextConfig} */
const nextConfig = {
  images: {
    domains: ['dalacortefs.com.br', 'localhost'],
  },
  env: {
    NEXT_PUBLIC_API_URL: process.env.NEXT_PUBLIC_API_URL || 'https://dalacortefs.com.br/api/v1',
  },
}

export default nextConfig
