import Navbar from '@/components/layout/Navbar'
import Footer from '@/components/layout/Footer'
import Hero from '@/components/sections/Hero'
import Services from '@/components/sections/Services'
import About from '@/components/sections/About'
import Missao from '@/components/sections/Missao'
import NewsSection from '@/components/sections/NewsSection'
import Careers from '@/components/sections/Careers'
import Contact from '@/components/sections/Contact'

export default function HomePage() {
  return (
    <>
      <Navbar />
      <main>
        <Hero />
        <Services />
        <About />
        <Missao />
        <NewsSection />
        <Careers />
        <Contact />
      </main>
      <Footer />
    </>
  )
}
