'use client'

import { useEffect } from 'react'

export default function LandingPageDFS() {

  useEffect(() => {
    // 1. Fade-up ao scroll
    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach(e => {
          if (e.isIntersecting) e.target.classList.add('visible')
        })
      },
      { threshold: 0.12 }
    )
    document.querySelectorAll('.fade-up').forEach(el => observer.observe(el))

    // 2. Contadores animados
    const targets = [100, 500, 1200, 13, 0]
    const suffixes = ['+', '+', '+', '', '%']
    const prefixes = ['', '', '', '', '#']
    const els = ['c0','c1','c2','c3','c4']
    let counted = false

    function animCount(id: string, target: number, suffix: string, prefix: string, duration: number) {
      const el = document.getElementById(id)
      if (!el) return
      let start = 0
      const step = target / (duration / 16)
      const timer = setInterval(() => {
        start += step
        if (start >= target) {
          el.textContent = prefix + target + suffix
          clearInterval(timer)
        } else {
          el.textContent = prefix + Math.floor(start) + suffix
        }
      }, 16)
    }

    const grid = document.getElementById('statsGrid')
    if (grid) {
      const statObs = new IntersectionObserver((entries) => {
        if (entries[0].isIntersecting && !counted) {
          counted = true
          els.forEach((id, i) => animCount(id, targets[i], suffixes[i], prefixes[i], 2000))
          statObs.disconnect()
        }
      }, { threshold: 0.3 })
      statObs.observe(grid)
    }

    // 3. Formulário de contato
    const form = document.getElementById('contactForm') as HTMLFormElement | null
    if (form) {
      const handleSubmit = (e: Event) => {
        e.preventDefault()
        const btn = document.getElementById('formBtn') as HTMLButtonElement | null
        if (!btn) return
        btn.textContent = 'Enviando...'
        btn.disabled = true
        setTimeout(() => {
          btn.textContent = 'Mensagem enviada! Entraremos em contato em breve.'
          btn.style.background = 'linear-gradient(135deg,#16a34a,#22c55e)'
        }, 1400)
      }
      form.addEventListener('submit', handleSubmit)
    }

    // 4. Navbar scroll
    const nav = document.querySelector('nav')
    const onScroll = () => {
      if (nav) {
        nav.style.background = window.scrollY > 40
          ? 'rgba(4,10,18,0.97)'
          : 'rgba(6,15,26,0.90)'
      }
    }
    window.addEventListener('scroll', onScroll)

    return () => {
      observer.disconnect()
      window.removeEventListener('scroll', onScroll)
    }
  }, [])

  return (
    <>
      <style>{`

*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
html{scroll-behavior:smooth}
body{font-family:'Inter',sans-serif;background:#060f1a;color:#fff;overflow-x:hidden;line-height:1.6}
a{text-decoration:none;color:inherit}
img{display:block;max-width:100%}
:root{
  --primary:#1B4A52;--primary-dk:#0f2a30;--primary-lt:#2a6575;
  --gold:#C4A35A;--gold-dk:#8B6914;--gold-lt:#d9bc80;
  --dark:#060f1a;--dark-2:#0f2029;
}
.container{max-width:1200px;margin:0 auto;padding:0 24px}

/* LOGO — sem recorte circular, preserva formato original */
.logo-round{
  object-fit:contain;
  background:transparent;
  filter:drop-shadow(0 2px 10px rgba(196,163,90,0.2));
}
.logo-nav{width:50px;height:50px}
.logo-hero{width:82%;height:82%;object-fit:contain;padding:0;filter:drop-shadow(0 4px 20px rgba(196,163,90,0.35))}
.logo-footer{width:68px;height:68px;object-fit:contain;background:transparent;filter:drop-shadow(0 2px 10px rgba(196,163,90,0.2))}

/* TOKENS DE COR */
.grad-gold{background:linear-gradient(135deg,var(--gold-dk),var(--gold),var(--gold-lt));-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text}
.section-tag{display:inline-flex;align-items:center;gap:8px;font-size:11px;font-weight:600;letter-spacing:.18em;text-transform:uppercase;color:var(--gold);margin-bottom:12px}
.section-tag::before,.section-tag::after{content:'';display:block;width:28px;height:1px;background:linear-gradient(90deg,transparent,rgba(196,163,90,.6))}
.section-tag::after{transform:scaleX(-1)}
.divider-gold{width:48px;height:2px;margin:8px auto 0;background:linear-gradient(90deg,var(--gold-dk),var(--gold));border-radius:2px}
.btn-gold{display:inline-flex;align-items:center;gap:8px;padding:14px 28px;border-radius:12px;background:linear-gradient(135deg,var(--gold-dk),var(--gold));color:#fff;font-weight:600;font-size:15px;border:none;cursor:pointer;box-shadow:0 4px 20px rgba(139,105,20,.35);transition:transform .2s,box-shadow .2s}
.btn-gold:hover{transform:translateY(-2px);box-shadow:0 8px 30px rgba(139,105,20,.5)}
.btn-outline{display:inline-flex;align-items:center;gap:8px;padding:14px 28px;border-radius:12px;background:transparent;color:rgba(255,255,255,.8);font-weight:500;font-size:15px;border:1px solid rgba(255,255,255,.2);cursor:pointer;transition:all .2s}
.btn-outline:hover{border-color:rgba(255,255,255,.4);background:rgba(255,255,255,.05)}

/* NAVBAR */
nav{position:fixed;top:0;left:0;right:0;z-index:100;padding:12px 0;background:rgba(6,15,26,.9);border-bottom:1px solid rgba(196,163,90,.12);backdrop-filter:blur(20px)}
.nav-inner{display:flex;align-items:center;justify-content:space-between}
.nav-brand{display:flex;align-items:center;gap:14px}
.nav-brand-text p:first-child{font-family:'Playfair Display',serif;font-weight:700;font-size:15px;color:#fff;letter-spacing:.04em}
.nav-brand-text p:last-child{font-size:9px;font-weight:600;letter-spacing:.2em;text-transform:uppercase;color:var(--gold);margin-top:2px}
.nav-links{display:flex;gap:30px}
.nav-links a{font-size:13px;font-weight:500;color:rgba(255,255,255,.55);transition:color .2s}
.nav-links a:hover{color:#fff}
.nav-cta{padding:10px 22px;border-radius:10px;background:linear-gradient(135deg,var(--gold-dk),var(--gold));font-size:13px;font-weight:600;color:#fff;transition:transform .2s,box-shadow .2s}
.nav-cta:hover{transform:translateY(-1px);box-shadow:0 6px 20px rgba(139,105,20,.4)}

/* HERO */
#inicio{min-height:100vh;background:linear-gradient(135deg,#030a10 0%,#0a1e28 40%,#1B4A52 100%);display:flex;align-items:center;padding-top:80px;position:relative;overflow:hidden}
#inicio::before{content:'';position:absolute;inset:0;background-image:linear-gradient(rgba(196,163,90,.04) 1px,transparent 1px),linear-gradient(90deg,rgba(196,163,90,.04) 1px,transparent 1px);background-size:72px 72px}
.hero-inner{position:relative;z-index:2;display:grid;grid-template-columns:1fr 1fr;gap:72px;align-items:center;padding:60px 0}
.hero-badge{display:inline-flex;align-items:center;gap:8px;padding:10px 18px;border-radius:40px;border:1px solid rgba(196,163,90,.3);background:rgba(196,163,90,.07);font-size:13px;font-weight:500;color:rgba(255,255,255,.8);margin-bottom:24px}
.hero-badge::before{content:'★';font-size:11px;color:var(--gold)}
.hero-title{font-family:'Playfair Display',serif;font-size:clamp(40px,5vw,62px);font-weight:700;line-height:1.1;color:#fff;letter-spacing:-.02em;margin-bottom:20px}
.hero-sub{font-size:17px;line-height:1.75;color:rgba(255,255,255,.55);max-width:440px;margin-bottom:28px}
.hero-pilares{display:flex;flex-wrap:wrap;gap:20px;margin-bottom:36px}
.hero-pilar{display:flex;align-items:center;gap:8px;font-size:13px;color:rgba(255,255,255,.5)}
.hero-pilar-dot{width:7px;height:7px;border-radius:50%;background:var(--gold)}
.hero-btns{display:flex;gap:14px;flex-wrap:wrap}
.hero-stats-row{display:grid;grid-template-columns:repeat(4,1fr);gap:10px;margin-top:36px}
.hero-stat-card{padding:14px 10px;border-radius:12px;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);text-align:center}
.hero-stat-val{font-family:'Playfair Display',serif;font-size:15px;font-weight:700;color:#fff;margin-bottom:3px}
.hero-stat-lbl{font-size:9px;color:rgba(255,255,255,.35);line-height:1.4}

/* Hero visual */
.hero-visual{display:flex;flex-direction:column;align-items:center;gap:22px}
.hero-logo-wrapper{position:relative;width:230px;height:230px;display:flex;align-items:center;justify-content:center}
.hero-logo-wrapper::before{content:'';position:absolute;inset:-18px;border-radius:50%;border:1px solid rgba(196,163,90,.2);animation:spin 28s linear infinite}
.hero-logo-wrapper::after{content:'';position:absolute;inset:-36px;border-radius:50%;border:1px dashed rgba(255,255,255,.05);animation:spin 45s linear infinite reverse}
@keyframes spin{from{transform:rotate(0deg)}to{transform:rotate(360deg)}}
.hero-logo-circle{width:230px;height:230px;border-radius:50%;background:radial-gradient(circle at 40% 35%,rgba(27,74,82,.7) 0%,rgba(6,15,26,.95) 100%);border:2px solid rgba(196,163,90,.3);box-shadow:0 0 60px rgba(196,163,90,.15),0 0 120px rgba(27,74,82,.25);display:flex;align-items:center;justify-content:center;overflow:visible}

/* Dashboard mockup */
.dashboard-mockup{width:100%;background:rgba(15,32,41,.92);border:1px solid rgba(196,163,90,.2);border-radius:18px;padding:20px;box-shadow:0 20px 60px rgba(0,0,0,.4)}
.mockup-header{display:flex;align-items:center;gap:10px;margin-bottom:16px;padding-bottom:12px;border-bottom:1px solid rgba(255,255,255,.07)}
.mockup-dots{display:flex;gap:6px}
.mockup-dots span{width:8px;height:8px;border-radius:50%}
.mockup-dots span:nth-child(1){background:#ff5f57}
.mockup-dots span:nth-child(2){background:#febc2e}
.mockup-dots span:nth-child(3){background:#28c840}
.mockup-title-txt{font-size:11px;color:rgba(255,255,255,.3);margin-left:8px}
.mockup-kpis{display:grid;grid-template-columns:repeat(3,1fr);gap:10px;margin-bottom:14px}
.mockup-kpi{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.06);border-radius:10px;padding:12px 10px}
.mockup-kpi-val{font-family:'Playfair Display',serif;font-size:18px;font-weight:700;color:var(--gold)}
.mockup-kpi-lbl{font-size:9px;color:rgba(255,255,255,.3);margin-top:2px}
.mockup-bar-row{display:flex;flex-direction:column;gap:8px}
.mockup-bar-item{display:flex;align-items:center;gap:10px}
.mockup-bar-lbl{font-size:9px;color:rgba(255,255,255,.35);width:80px}
.mockup-bar-track{flex:1;height:5px;background:rgba(255,255,255,.07);border-radius:99px;overflow:hidden}
.mockup-bar-fill{height:100%;border-radius:99px;background:linear-gradient(90deg,var(--gold-dk),var(--gold))}

/* ESTATÍSTICAS */
#estatisticas{background:var(--primary);padding:8px 0;position:relative;overflow:hidden}
#estatisticas::before{content:'';position:absolute;inset:0;background-image:linear-gradient(rgba(196,163,90,.04) 1px,transparent 1px),linear-gradient(90deg,rgba(196,163,90,.04) 1px,transparent 1px);background-size:60px 60px}
.top-line{position:absolute;top:0;left:0;right:0;height:1px;background:linear-gradient(90deg,transparent,rgba(196,163,90,.45),transparent)}
.bot-line{position:absolute;bottom:0;left:0;right:0;height:1px;background:linear-gradient(90deg,transparent,rgba(196,163,90,.2),transparent)}
.stats-grid{position:relative;z-index:1;display:grid;grid-template-columns:repeat(5,1fr)}
.stat-item{padding:36px 20px;text-align:center;border-right:1px solid rgba(255,255,255,.1)}
.stat-item:last-child{border-right:none}
.stat-num{font-family:'Playfair Display',serif;font-size:clamp(30px,4vw,44px);font-weight:700;color:var(--gold);line-height:1}
.stat-label{font-size:12px;color:rgba(255,255,255,.5);margin-top:10px;line-height:1.4;max-width:110px;margin-inline:auto}

/* PLANOS */
#planos{padding:100px 0;background:linear-gradient(180deg,#f8f9fb 0%,#fff 100%)}
.section-header{text-align:center;margin-bottom:60px}
.section-title{font-family:'Playfair Display',serif;font-size:clamp(28px,4vw,42px);font-weight:700;color:#0f2029;line-height:1.2;margin-bottom:14px}
.section-title .accent{background:linear-gradient(135deg,var(--gold-dk),var(--gold));-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text}
.section-sub{font-size:16px;color:#6b7280;max-width:500px;margin:0 auto;line-height:1.6}
.plans-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:20px;align-items:start}
.plan-card{border-radius:20px;padding:34px 28px;display:flex;flex-direction:column;transition:transform .3s,box-shadow .3s}
.plan-card:hover{transform:translateY(-4px)}
.plan-card.default{background:#fff;border:1px solid #e5e7eb;box-shadow:0 4px 20px rgba(0,0,0,.06)}
.plan-card.default:hover{box-shadow:0 16px 40px rgba(0,0,0,.1)}
.plan-card.featured{background:linear-gradient(160deg,#0f2029 0%,var(--primary) 100%);border:2px solid rgba(196,163,90,.6);box-shadow:0 8px 40px rgba(27,74,82,.35);transform:scale(1.03);position:relative;overflow:hidden}
.plan-card.featured::before{content:'';position:absolute;top:0;left:0;right:0;height:3px;background:linear-gradient(90deg,var(--gold-dk),var(--gold))}
.plan-card.featured:hover{transform:scale(1.03) translateY(-4px)}
.plan-emoji{font-size:28px;margin-bottom:10px}
.plan-badge{display:inline-flex;padding:4px 12px;border-radius:40px;font-size:11px;font-weight:600;margin-bottom:16px}
.default-badge{background:#f3f4f6;color:#6b7280}
.featured-badge{background:rgba(196,163,90,.15);border:1px solid rgba(196,163,90,.3);color:var(--gold-dk)}
.exec-badge{background:rgba(139,105,20,.1);color:var(--gold-dk);border:1px solid rgba(139,105,20,.2)}
.plan-name{font-family:'Playfair Display',serif;font-size:21px;font-weight:700;margin-bottom:6px}
.plan-card.default .plan-name{color:var(--primary)}
.plan-card.featured .plan-name{color:#fff}
.plan-para{font-size:12px;margin-bottom:22px;line-height:1.4}
.plan-card.default .plan-para{color:#9ca3af}
.plan-card.featured .plan-para{color:rgba(255,255,255,.45)}
.plan-features{list-style:none;margin-bottom:28px;flex:1;display:flex;flex-direction:column;gap:9px}
.plan-feature{display:flex;align-items:flex-start;gap:10px;font-size:13px;line-height:1.5}
.plan-card.default .plan-feature{color:#4b5563}
.plan-card.featured .plan-feature{color:rgba(255,255,255,.75)}
.check-icon{width:17px;height:17px;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:1px;font-size:10px;color:var(--gold)}
.plan-card.default .check-icon{background:rgba(196,163,90,.12)}
.plan-card.featured .check-icon{background:rgba(196,163,90,.2)}
.plan-cta{display:flex;align-items:center;justify-content:center;gap:8px;width:100%;padding:13px 22px;border-radius:12px;font-weight:600;font-size:14px;cursor:pointer;border:none;transition:all .2s}
.plan-cta.dark{background:var(--primary);color:#fff}
.plan-cta.dark:hover{background:var(--primary-lt)}
.plan-cta.gold{background:linear-gradient(135deg,var(--gold-dk),var(--gold));color:#fff;box-shadow:0 4px 16px rgba(139,105,20,.35)}
.plan-cta.gold:hover{box-shadow:0 8px 28px rgba(139,105,20,.5);transform:translateY(-1px)}
.plans-note{text-align:center;font-size:13px;color:#9ca3af;margin-top:40px;max-width:600px;margin-inline:auto}
.plans-note a{color:var(--gold-dk)}

/* DIFERENCIAIS */
#diferenciais{padding:100px 0;background:linear-gradient(160deg,var(--dark) 0%,var(--dark-2) 50%,#1a2f3a 100%);position:relative;overflow:hidden}
#diferenciais::before{content:'';position:absolute;inset:0;background-image:radial-gradient(rgba(196,163,90,.05) 1px,transparent 1px);background-size:30px 30px}
.diferenciais-grid{display:grid;grid-template-columns:1fr 1fr;gap:60px;align-items:center;position:relative;z-index:1}
.diferencial-headline{font-family:'Playfair Display',serif;font-size:clamp(26px,4vw,40px);font-weight:700;color:#fff;line-height:1.2;margin-bottom:18px}
.diferencial-desc{font-size:16px;color:rgba(255,255,255,.5);line-height:1.75;margin-bottom:28px}
.diferencial-items{display:flex;flex-direction:column;gap:14px}
.diferencial-item{display:flex;align-items:flex-start;gap:14px;padding:18px;border-radius:14px;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.07);transition:border-color .3s}
.diferencial-item:hover{border-color:rgba(196,163,90,.25)}
.diferencial-icon{width:42px;height:42px;border-radius:12px;background:linear-gradient(135deg,var(--gold-dk),var(--gold));display:flex;align-items:center;justify-content:center;font-size:20px;flex-shrink:0}
.diferencial-text h4{font-size:14px;font-weight:600;color:#fff;margin-bottom:4px}
.diferencial-text p{font-size:13px;color:rgba(255,255,255,.45);line-height:1.5}
.diferenciais-pilares{display:flex;flex-direction:column;gap:18px}
.pilar-card{padding:26px;border-radius:18px;border:1px solid rgba(255,255,255,.08);background:rgba(255,255,255,.03);position:relative;overflow:hidden;transition:transform .3s,border-color .3s}
.pilar-card:hover{transform:translateX(6px);border-color:rgba(196,163,90,.3)}
.pilar-card::before{content:'';position:absolute;left:0;top:0;bottom:0;width:3px;background:linear-gradient(180deg,var(--gold-dk),var(--gold))}
.pilar-card h3{font-family:'Playfair Display',serif;font-size:18px;font-weight:700;color:#fff;margin-bottom:7px}
.pilar-card p{font-size:13px;color:rgba(255,255,255,.45);line-height:1.6}
.pilar-number{position:absolute;top:14px;right:18px;font-family:'Playfair Display',serif;font-size:38px;font-weight:800;color:rgba(196,163,90,.07);line-height:1}

/* DEPOIMENTOS */
#depoimentos{padding:100px 0;background:#f8f9fb}
.depo-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:24px}
.depo-card{background:#fff;border-radius:18px;padding:28px;border:1px solid #e5e7eb;box-shadow:0 4px 16px rgba(0,0,0,.05);transition:transform .3s,box-shadow .3s}
.depo-card:hover{transform:translateY(-4px);box-shadow:0 12px 32px rgba(0,0,0,.1)}
.depo-quote{font-size:38px;line-height:1;color:var(--gold);font-family:Georgia,serif;margin-bottom:12px}
.depo-text{font-size:14px;color:#4b5563;line-height:1.7;margin-bottom:20px;font-style:italic}
.depo-author{display:flex;align-items:center;gap:12px}
.depo-avatar{width:44px;height:44px;border-radius:50%;background:linear-gradient(135deg,var(--primary),var(--primary-lt));display:flex;align-items:center;justify-content:center;font-family:'Playfair Display',serif;font-size:15px;font-weight:700;color:var(--gold);flex-shrink:0}
.depo-name{font-size:14px;font-weight:600;color:#111827}
.depo-role{font-size:12px;color:#9ca3af}
.depo-stars{color:var(--gold);font-size:13px;letter-spacing:2px;margin-top:3px}
.depo-placeholder{display:flex;flex-direction:column;align-items:center;justify-content:center;gap:12px;padding:40px 24px;text-align:center;background:linear-gradient(135deg,var(--primary),var(--primary-lt));border-radius:18px;border:1px dashed rgba(196,163,90,.4)}
.depo-placeholder p{font-size:14px;color:rgba(255,255,255,.6);line-height:1.6}
.depo-placeholder strong{color:rgba(255,255,255,.9)}

/* CONTATO */
#contato{padding:100px 0;background:linear-gradient(160deg,var(--dark) 0%,var(--dark-2) 50%,#1a2f3a 100%);position:relative;overflow:hidden}
#contato::before{content:'';position:absolute;inset:0;background-image:radial-gradient(rgba(196,163,90,.04) 1px,transparent 1px);background-size:32px 32px}
.contact-grid{position:relative;z-index:1;display:grid;grid-template-columns:2fr 3fr;gap:44px;align-items:start}
.contact-info{display:flex;flex-direction:column;gap:12px}
.contact-info-card{display:flex;align-items:flex-start;gap:14px;padding:16px;border-radius:14px;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08)}
.contact-icon{width:38px;height:38px;border-radius:10px;background:linear-gradient(135deg,var(--gold-dk),var(--gold));display:flex;align-items:center;justify-content:center;font-size:15px;flex-shrink:0}
.contact-info-text label{display:block;font-size:10px;font-weight:600;letter-spacing:.14em;text-transform:uppercase;color:rgba(255,255,255,.35);margin-bottom:3px}
.contact-info-text p{font-size:13px;font-weight:500;color:#fff;white-space:pre-line}
.contact-whatsapp{display:flex;align-items:center;justify-content:space-between;padding:16px;border-radius:14px;background:rgba(37,211,102,.06);border:1px solid rgba(37,211,102,.2);text-decoration:none;transition:border-color .3s}
.contact-whatsapp:hover{border-color:rgba(37,211,102,.4)}
.contact-whatsapp-left p:first-child{font-size:14px;font-weight:600;color:#fff}
.contact-whatsapp-left p:last-child{font-size:12px;color:rgba(255,255,255,.4);margin-top:2px}
.wa-icon{width:34px;height:34px;border-radius:10px;background:rgba(37,211,102,.15);display:flex;align-items:center;justify-content:center;font-size:17px}
.contact-map{border-radius:14px;overflow:hidden;border:1px solid rgba(196,163,90,.2);margin-top:12px}
.contact-map iframe{width:100%;height:195px;border:0;display:block;filter:grayscale(40%) contrast(1.1)}
.contact-form{padding:34px;border-radius:20px;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.09)}
.form-row{display:grid;grid-template-columns:1fr 1fr;gap:12px}
.form-group{margin-bottom:12px}
.form-group label{display:block;font-size:10px;font-weight:600;letter-spacing:.12em;text-transform:uppercase;color:rgba(255,255,255,.4);margin-bottom:7px}
.form-group input,.form-group select,.form-group textarea{width:100%;padding:11px 14px;border-radius:10px;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);color:#fff;font-size:14px;font-family:'Inter',sans-serif;outline:none;transition:border-color .2s}
.form-group input::placeholder,.form-group textarea::placeholder{color:rgba(255,255,255,.25)}
.form-group input:focus,.form-group select:focus,.form-group textarea:focus{border-color:rgba(196,163,90,.5);background:rgba(255,255,255,.08)}
.form-group select option{background:#0f2029}
.form-group textarea{resize:vertical;min-height:105px}
.form-submit{width:100%;padding:15px 22px;border-radius:12px;background:linear-gradient(135deg,var(--gold-dk),var(--gold));color:#fff;font-weight:600;font-size:15px;font-family:'Inter',sans-serif;border:none;cursor:pointer;box-shadow:0 4px 20px rgba(139,105,20,.35);display:flex;align-items:center;justify-content:center;gap:8px;transition:transform .2s,box-shadow .2s;margin-top:6px}
.form-submit:hover{transform:translateY(-2px);box-shadow:0 8px 32px rgba(139,105,20,.5)}

/* FOOTER */
footer{background:linear-gradient(180deg,#040c14 0%,#020810 100%);padding:72px 0 0;position:relative}
footer::before{content:'';position:absolute;top:0;left:0;right:0;height:1px;background:linear-gradient(90deg,transparent,rgba(196,163,90,.4),transparent)}
.footer-grid{display:grid;grid-template-columns:5fr 3fr 4fr;gap:56px;padding-bottom:52px}
.footer-brand-row{display:flex;align-items:center;gap:16px;margin-bottom:16px}
.footer-brand-name p:first-child{font-family:'Playfair Display',serif;font-size:19px;font-weight:700;color:#fff;letter-spacing:.04em}
.footer-brand-name p:nth-child(2){font-size:10px;font-weight:600;letter-spacing:.2em;text-transform:uppercase;color:var(--gold)}
.footer-brand-name p:last-child{font-size:11px;color:rgba(255,255,255,.25);margin-top:2px}
.footer-desc{font-size:14px;color:rgba(255,255,255,.4);line-height:1.7;max-width:340px;margin-bottom:22px}
.footer-socials{display:flex;gap:10px}
.footer-social{width:36px;height:36px;border-radius:10px;border:1px solid rgba(255,255,255,.08);display:flex;align-items:center;justify-content:center;color:rgba(255,255,255,.35);transition:all .2s}
.footer-social:hover{border-color:rgba(196,163,90,.3);color:var(--gold);background:rgba(196,163,90,.07)}
.footer-col h4{font-size:11px;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:rgba(255,255,255,.5);margin-bottom:18px}
.footer-links{list-style:none;display:flex;flex-direction:column;gap:10px}
.footer-links li a{font-size:14px;color:rgba(255,255,255,.35);display:flex;align-items:center;gap:8px;transition:color .2s}
.footer-links li a::before{content:'';display:block;width:12px;height:1px;background:rgba(255,255,255,.2);transition:all .3s}
.footer-links li a:hover{color:var(--gold)}
.footer-links li a:hover::before{background:rgba(196,163,90,.6);width:18px}
.footer-contact-items{display:flex;flex-direction:column;gap:14px}
.footer-contact-item{display:flex;align-items:flex-start;gap:12px}
.footer-contact-icon{width:28px;height:28px;border-radius:8px;background:rgba(196,163,90,.1);color:var(--gold);display:flex;align-items:center;justify-content:center;font-size:13px;flex-shrink:0;margin-top:1px}
.footer-contact-item p{font-size:13px;color:rgba(255,255,255,.4);line-height:1.5;white-space:pre-line}
.footer-hours{margin-top:12px;padding:12px 14px;border-radius:10px;border:1px solid rgba(255,255,255,.07);background:rgba(255,255,255,.02)}
.footer-hours p:first-child{font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.14em;color:rgba(255,255,255,.3);margin-bottom:5px}
.footer-hours p:last-child{font-size:12px;color:rgba(255,255,255,.4)}
.footer-hours span{color:rgba(255,255,255,.7);font-weight:600}
.footer-bar{border-top:1px solid rgba(255,255,255,.07);padding:22px 0;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px}
.footer-bar p{font-size:12px;color:rgba(255,255,255,.2)}
.footer-bar-links{display:flex;gap:22px}
.footer-bar-links a{font-size:12px;color:rgba(255,255,255,.2);transition:color .2s}
.footer-bar-links a:hover{color:rgba(255,255,255,.5)}
.whatsapp-float{position:fixed;bottom:28px;right:28px;z-index:9999;width:56px;height:56px;border-radius:50%;background:#25D366;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 20px rgba(37,211,102,.45);transition:transform .2s,box-shadow .2s}
.whatsapp-float:hover{transform:scale(1.1);box-shadow:0 6px 28px rgba(37,211,102,.65)}
@keyframes fadeUp{from{opacity:0;transform:translateY(22px)}to{opacity:1;transform:translateY(0)}}
.fade-up{animation:fadeUp .7s ease both}
.fade-up-d1{animation-delay:.1s}.fade-up-d2{animation-delay:.2s}.fade-up-d3{animation-delay:.3s}
@media(max-width:1024px){
  .hero-inner{grid-template-columns:1fr;gap:40px}
  .hero-visual{display:none}
  .stats-grid{grid-template-columns:repeat(3,1fr)}
  .plans-grid{grid-template-columns:1fr;max-width:460px;margin:0 auto}
  .plan-card.featured{transform:none}
  .diferenciais-grid{grid-template-columns:1fr}
  .contact-grid{grid-template-columns:1fr}
  .footer-grid{grid-template-columns:1fr 1fr}
  .nav-links{display:none}
}
@media(max-width:640px){
  .depo-grid{grid-template-columns:1fr}
  .stats-grid{grid-template-columns:repeat(2,1fr)}
  .footer-grid{grid-template-columns:1fr}
  .form-row{grid-template-columns:1fr}
  .hero-stats-row{grid-template-columns:repeat(2,1fr)}
}


/* Fade-up */
.fade-up {
  opacity: 0;
  transform: translateY(28px);
  transition: opacity 0.7s ease, transform 0.7s ease;
}
.fade-up.visible {
  opacity: 1;
  transform: translateY(0);
}
.fade-up-d1 { transition-delay: 0.1s }
.fade-up-d2 { transition-delay: 0.2s }
.fade-up-d3 { transition-delay: 0.35s }
      `}</style>



{/* NAVBAR */}
<nav>
  <div className="container">
    <div className="nav-inner">
      <div className="nav-brand">
        <img src="/logo.png" alt="Dalacorte Financial Solutions" className="logo-round logo-nav"/>
        <div className="nav-brand-text">
          <p>Dalacorte Financial Solutions</p>
          <p>Contabilidade Consultiva · CRC MG 120587 O</p>
        </div>
      </div>
      <div className="nav-links">
        <a href="#inicio">Início</a>
        <a href="#planos">Planos</a>
        <a href="#diferenciais">Diferenciais</a>
        <a href="#depoimentos">Depoimentos</a>
        <a href="#contato">Contato</a>
      </div>
      <a href="#contato" className="nav-cta">Fale com um especialista</a>
    </div>
  </div>
</nav>

{/* 1. HERO */}
<section id="inicio">
  <div className="container">
    <div className="hero-inner">
      <div>
        <div className="hero-badge fade-up">Mais de 13 anos transformando contabilidades</div>
        <h1 className="hero-title fade-up fade-up-d1">
          Contabilidade que<br />
          <span className="grad-gold">transforma</span><br />
          decisões
        </h1>
        <p className="hero-sub fade-up fade-up-d2">
          Há mais de uma década unimos tradição contábil, análise estratégica e inteligência automatizada para impulsionar o crescimento do seu neg&oacute;cio.
        </p>
        <div className="hero-pilares fade-up fade-up-d2">
          <div className="hero-pilar"><div className="hero-pilar-dot"></div> Resultados mensuráveis</div>
          <div className="hero-pilar"><div className="hero-pilar-dot"></div> Processos otimizados com automação</div>
          <div className="hero-pilar"><div className="hero-pilar-dot"></div> Parceria estratégica permanente</div>
        </div>
        <div className="hero-btns fade-up fade-up-d3">
          <a href="#contato" className="btn-gold">Fale com um especialista →</a>
          <a href="#planos" className="btn-outline">Conheça os planos</a>
        </div>
        <div className="hero-stats-row fade-up fade-up-d3">
          <div className="hero-stat-card"><div className="hero-stat-val">Desde 2012</div><div className="hero-stat-lbl">Experiência consolidada</div></div>
          <div className="hero-stat-card"><div className="hero-stat-val">CRC MG</div><div className="hero-stat-lbl">120587 O — Registrado</div></div>
          <div className="hero-stat-card"><div className="hero-stat-val">Tributário</div><div className="hero-stat-lbl">Planejamento especializado</div></div>
          <div className="hero-stat-card"><div className="hero-stat-val">Consultivo</div><div className="hero-stat-lbl">Contabilidade estratégica</div></div>
        </div>
      </div>
      <div className="hero-visual">
        <div className="hero-logo-wrapper">
          <div className="hero-logo-circle">
            <img src="/logo.png" alt="Dalacorte" className="logo-hero"/>
          </div>
        </div>
        <div className="dashboard-mockup">
          <div className="mockup-header">
            <div className="mockup-dots"><span></span><span></span><span></span></div>
            <span className="mockup-title-txt">Sistema de Gestão — Dalacorte Financial Solutions</span>
          </div>
          <div className="mockup-kpis">
            <div className="mockup-kpi"><div className="mockup-kpi-val">13+</div><div className="mockup-kpi-lbl">Anos experiência</div></div>
            <div className="mockup-kpi"><div className="mockup-kpi-val">100%</div><div className="mockup-kpi-lbl">Obrigações</div></div>
            <div className="mockup-kpi"><div className="mockup-kpi-val">500+</div><div className="mockup-kpi-lbl">Declarações</div></div>
          </div>
          <div className="mockup-bar-row">
            <div className="mockup-bar-item"><div className="mockup-bar-lbl">Planej. Fiscal</div><div className="mockup-bar-track"><div className="mockup-bar-fill" style={{width: "88%"}}></div></div></div>
            <div className="mockup-bar-item"><div className="mockup-bar-lbl">Depart. Pessoal</div><div className="mockup-bar-track"><div className="mockup-bar-fill" style={{width: "74%"}}></div></div></div>
            <div className="mockup-bar-item"><div className="mockup-bar-lbl">BPO Financeiro</div><div className="mockup-bar-track"><div className="mockup-bar-fill" style={{width: "62%"}}></div></div></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

{/* 2. ESTATISTICAS */}
<section id="estatisticas">
  <div className="top-line"></div><div className="bot-line"></div>
  <div className="container">
    <div className="stats-grid" id="statsGrid">
      <div className="stat-item"><div className="stat-num" id="c0">0+</div><div className="stat-label">Anos de experiência</div></div>
      <div className="stat-item"><div className="stat-num" id="c1">0%</div><div className="stat-label">Clientes satisfeitos</div></div>
      <div className="stat-item"><div className="stat-num" id="c2">0</div><div className="stat-label">&Aacute;reas de atuação</div></div>
      <div className="stat-item"><div className="stat-num" id="c3">0+</div><div className="stat-label">Declarações processadas</div></div>
      <div className="stat-item"><div className="stat-num" id="c4">#0</div><div className="stat-label">Escrit&oacute;rio consultivo em Paracatu</div></div>
    </div>
  </div>
</section>

{/* 3. PLANOS */}
<section id="planos">
  <div className="container">
    <div className="section-header">
      <div className="section-tag">Planos</div>
      <div className="divider-gold"></div>
      <h2 className="section-title" style={{marginTop: "16px"}}>Planos pensados para cada fase da<br /><span className="accent">sua empresa</span></h2>
      <p className="section-sub">Do microempreendedor &agrave; empresa consolidada — encontre o plano ideal para o seu momento.</p>
    </div>
    <div className="plans-grid">
      <div className="plan-card default">
        <div className="plan-emoji">&#127807;</div>
        <span className="plan-badge default-badge">Mais indicado para MEI e ME</span>
        <h3 className="plan-name">Plano Essencial</h3>
        <p className="plan-para">Para: MEI, ME e EPP no Simples Nacional</p>
        <ul className="plan-features">
          <li className="plan-feature"><div className="check-icon">&#10003;</div> Escrituração contábil completa</li>
          <li className="plan-feature"><div className="check-icon">&#10003;</div> Apuração de impostos (DAS, DCTF etc.)</li>
          <li className="plan-feature"><div className="check-icon">&#10003;</div> Folha de pagamento e encargos</li>
          <li className="plan-feature"><div className="check-icon">&#10003;</div> Obrigações acess&oacute;rias (SPED, EFD)</li>
          <li className="plan-feature"><div className="check-icon">&#10003;</div> Portal do cliente digital</li>
          <li className="plan-feature"><div className="check-icon">&#10003;</div> Atendimento via WhatsApp e e-mail</li>
        </ul>
        <a href="#contato" className="plan-cta dark">Solicitar proposta →</a>
      </div>
      <div className="plan-card featured">
        <div className="plan-emoji">&#128640;</div>
        <span className="plan-badge featured-badge">&#11088; Mais popular</span>
        <h3 className="plan-name">Plano Estratégico</h3>
        <p className="plan-para">Para: Empresas em crescimento (Lucro Presumido e Real)</p>
        <ul className="plan-features">
          <li className="plan-feature"><div className="check-icon">&#10003;</div> Tudo do Plano Essencial</li>
          <li className="plan-feature"><div className="check-icon">&#10003;</div> Planejamento tributário personalizado</li>
          <li className="plan-feature"><div className="check-icon">&#10003;</div> Relat&oacute;rios gerenciais mensais (DRE, Balancete, Fluxo de Caixa)</li>
          <li className="plan-feature"><div className="check-icon">&#10003;</div> Diagn&oacute;stico fiscal e recuperação de tributos</li>
          <li className="plan-feature"><div className="check-icon">&#10003;</div> Reuniões estratégicas trimestrais</li>
          <li className="plan-feature"><div className="check-icon">&#10003;</div> Painel de indicadores de desempenho</li>
          <li className="plan-feature"><div className="check-icon">&#10003;</div> Consultoria trabalhista e eSocial</li>
        </ul>
        <a href="#contato" className="plan-cta gold">Solicitar proposta →</a>
      </div>
      <div className="plan-card default">
        <div className="plan-emoji">&#128142;</div>
        <span className="plan-badge exec-badge">Para empresas consolidadas</span>
        <h3 className="plan-name">Plano Executivo</h3>
        <p className="plan-para">Para: Médio porte, Lucro Presumido e Real, alta complexidade</p>
        <ul className="plan-features">
          <li className="plan-feature"><div className="check-icon">&#10003;</div> Tudo do Plano Estratégico</li>
          <li className="plan-feature"><div className="check-icon">&#10003;</div> Contabilidade consultiva com análise profunda</li>
          <li className="plan-feature"><div className="check-icon">&#10003;</div> Gestor de conta exclusivo</li>
          <li className="plan-feature"><div className="check-icon">&#10003;</div> Planejamento tributário preventivo e revisional</li>
          <li className="plan-feature"><div className="check-icon">&#10003;</div> Suporte a auditorias externas e due diligence</li>
          <li className="plan-feature"><div className="check-icon">&#10003;</div> BPO Financeiro completo (contas a pagar/receber)</li>
          <li className="plan-feature"><div className="check-icon">&#10003;</div> Atendimento prioritário com prazo garantido</li>
          <li className="plan-feature"><div className="check-icon">&#10003;</div> Relat&oacute;rios personalizados sob demanda</li>
        </ul>
        <a href="#contato" className="plan-cta dark">Falar com especialista →</a>
      </div>
    </div>
    <p className="plans-note">Todos os planos são personalizados. Os valores são definidos conforme o regime tributário, número de funcionários e complexidade das operações. <a href="#contato">Solicite uma proposta sem compromisso.</a></p>
  </div>
</section>

{/* 4. DIFERENCIAIS */}
<section id="diferenciais">
  <div className="container">
    <div className="diferenciais-grid">
      <div>
        <div className="section-tag">Por que escolher a Dalacorte</div>
        <h2 className="diferencial-headline">Tecnologia e<br /><span className="grad-gold">inteligência automatizada</span><br />a serviço da sua empresa</h2>
        <p className="diferencial-desc">Combinamos mais de 13 anos de experiência contábil com sistemas de gestão modernos e processos otimizados — para que você tome decisões com dados precisos, em tempo real.</p>
        <div className="diferencial-items">
          <div className="diferencial-item">
            <div className="diferencial-icon">&#129302;</div>
            <div className="diferencial-text"><h4>Processos otimizados com automação</h4><p>Rotinas fiscais e tributárias automatizadas eliminam erros manuais e reduzem o prazo de entrega das suas obrigações.</p></div>
          </div>
          <div className="diferencial-item">
            <div className="diferencial-icon">&#128202;</div>
            <div className="diferencial-text"><h4>Análise contábil inteligente</h4><p>Relat&oacute;rios com análise automática de tendências — você recebe informações já interpretadas, não apenas números.</p></div>
          </div>
          <div className="diferencial-item">
            <div className="diferencial-icon">&#128737;</div>
            <div className="diferencial-text"><h4>Portal exclusivo do cliente</h4><p>Acesse documentos, declarações e relat&oacute;rios a qualquer momento pelo portal digital seguro da Dalacorte.</p></div>
          </div>
        </div>
      </div>
      <div className="diferenciais-pilares">
        <div className="pilar-card"><div className="pilar-number">01</div><h3>Resultados reais</h3><p>Planejamento tributário que reduz carga fiscal dentro da legalidade — com embasamento técnico e jurídico em cada decisão.</p></div>
        <div className="pilar-card"><div className="pilar-number">02</div><h3>Processos otimizados</h3><p>Integramos seu neg&oacute;cio a ferramentas de gestão modernas. Menos retrabalho, mais agilidade e controle total das obrigações.</p></div>
        <div className="pilar-card"><div className="pilar-number">03</div><h3>Parceria estratégica</h3><p>Não somos apenas um escrit&oacute;rio contábil. Somos parceiros de crescimento — presentes nas decisões que moldam o futuro da sua empresa.</p></div>
      </div>
    </div>
  </div>
</section>

{/* 5. DEPOIMENTOS */}
<section id="depoimentos">
  <div className="container">
    <div className="section-header">
      <div className="section-tag" style={{color: "#8B6914"}}>Depoimentos</div>
      <div className="divider-gold"></div>
      <h2 className="section-title" style={{marginTop: "16px", color: "#0f2029"}}>O que nossos clientes<br /><span className="accent">dizem sobre n&oacute;s</span></h2>
      <p className="section-sub">A satisfação dos nossos clientes é o nosso maior resultado.</p>
    </div>
    <div className="depo-grid">
      <div className="depo-card">
        <div className="depo-quote">&ldquo;</div>
        <p className="depo-text">A Dalacorte transformou a gestão contábil da nossa empresa. O planejamento tributário reduziu significativamente nossa carga fiscal, e os relat&oacute;rios mensais nos dão clareza para tomar decisões com segurança.</p>
        <div className="depo-author"><div className="depo-avatar">MR</div><div><div className="depo-name">Marcos R.</div><div className="depo-role">Empresário — Comércio e Serviços</div><div className="depo-stars">&#9733;&#9733;&#9733;&#9733;&#9733;</div></div></div>
      </div>
      <div className="depo-card">
        <div className="depo-quote">&ldquo;</div>
        <p className="depo-text">Desde que contratamos o Plano Estratégico, finalmente temos controle real dos nossos números. A equipe é proativa — não esperam os problemas chegarem para agir.</p>
        <div className="depo-author"><div className="depo-avatar">AS</div><div><div className="depo-name">Ana S.</div><div className="depo-role">S&oacute;cia-Diretora — Prestação de Serviços</div><div className="depo-stars">&#9733;&#9733;&#9733;&#9733;&#9733;</div></div></div>
      </div>
      <div className="depo-placeholder">
        <div style={{fontSize: "34px"}}>&#128172;</div>
        <p><strong>Seu depoimento aqui</strong><br />Esta seção está reservada para avaliações reais dos nossos clientes.</p>
        <a href="#contato" className="btn-gold" style={{fontSize: "13px", padding: "10px 20px"}}>Seja nosso cliente →</a>
      </div>
    </div>
  </div>
</section>

{/* 6. CONTATO */}
<section id="contato">
  <div className="container">
    <div className="section-header" style={{marginBottom: "52px"}}>
      <div className="section-tag">Entre em contato</div>
      <h2 style={{fontFamily: "'Playfair Display',serif", fontSize: "clamp(28px,4vw,40px)", fontWeight: "700", color: "#fff", marginTop: "14px", lineHeight: "1.2"}}>Vamos conversar sobre<br /><span className="grad-gold">o seu neg&oacute;cio</span></h2>
      <p style={{fontSize: "16px", color: "rgba(255,255,255,.45)", marginTop: "12px"}}>Atendimento especializado e personalizado para cada cliente.</p>
    </div>
    <div className="contact-grid">
      <div className="contact-info">
        <div className="contact-info-card"><div className="contact-icon">&#9993;</div><div className="contact-info-text"><label>E-mail</label><p>contato@dalacortefs.com.br</p></div></div>
        <div className="contact-info-card"><div className="contact-icon">&#128222;</div><div className="contact-info-text"><label>Telefone / WhatsApp</label><p>(38) 99754-1448</p></div></div>
        <div className="contact-info-card"><div className="contact-icon">&#128205;</div><div className="contact-info-text"><label>Endereço</label><p>R. Abadia Lemos do Prado, 199&#10;Prado — Paracatu, MG</p></div></div>
        <div className="contact-info-card"><div className="contact-icon">&#128336;</div><div className="contact-info-text"><label>Horário de atendimento</label><p>Segunda a Sexta: <strong>8h &agrave;s 18h</strong></p></div></div>
        <a href="https://wa.me/5538997541448" target="_blank" rel="noopener noreferrer" className="contact-whatsapp">
          <div className="contact-whatsapp-left"><p>Fale pelo WhatsApp</p><p>(38) 99754-1448</p></div>
          <div className="wa-icon">&#128172;</div>
        </a>
        <div className="contact-map">
          <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d30298.12!2d-46.8761!3d-17.2213!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x94af1a4285a8c0d9%3A0xa0bad0de38febaee!2sParacatu%2C%20MG!5e0!3m2!1spt-BR!2sbr!4v1" allowFullScreen loading="lazy" referrerPolicy="no-referrer-when-downgrade" title="Paracatu, MG"></iframe>
        </div>
      </div>
      <div className="contact-form">
        <form id="contactForm">
          <div className="form-row">
            <div className="form-group"><label>Nome *</label><input type="text" placeholder="Seu nome completo" required/></div>
            <div className="form-group"><label>E-mail *</label><input type="email" placeholder="seu@email.com" required/></div>
          </div>
          <div className="form-row">
            <div className="form-group"><label>Telefone / WhatsApp</label><input type="tel" placeholder="(38) 99000-0000"/></div>
            <div className="form-group"><label>Empresa</label><input type="text" placeholder="Nome da empresa"/></div>
          </div>
          <div className="form-group"><label>CNPJ</label><input type="text" placeholder="00.000.000/0000-00"/></div>
          <div className="form-row">
            <div className="form-group"><label>Regime tributário</label><select><option value="">Selecione...</option><option>Simples Nacional</option><option>Lucro Presumido</option><option>Lucro Real</option><option>Não sei</option></select></div>
            <div className="form-group"><label>Plano de interesse</label><select><option value="">Selecione...</option><option>Plano Essencial</option><option>Plano Estratégico</option><option>Plano Executivo</option><option>Quero indicação</option></select></div>
          </div>
          <div className="form-group"><label>Mensagem *</label><textarea placeholder="Conte-nos sobre seu neg&oacute;cio e como podemos ajudar..." required></textarea></div>
          <button type="submit" className="form-submit" id="formBtn">&#9993; Enviar mensagem</button>
        </form>
      </div>
    </div>
  </div>
</section>

{/* 7. FOOTER */}
<footer>
  <div className="container">
    <div className="footer-grid">
      <div>
        <div className="footer-brand-row">
          <img src="/logo.png" alt="Dalacorte Financial Solutions" className="logo-footer"/>
          <div className="footer-brand-name">
            <p>Dalacorte</p>
            <p>Financial Solutions</p>
            <p>CRC MG 120587 O</p>
          </div>
        </div>
        <p className="footer-desc">Contabilidade vai além de entregar guias. Desde 2012 oferecemos atendimento especializado, análise profunda e consultoria contábil que auxilia sua empresa nas melhores decisões.</p>
        <div className="footer-socials">
          <a href="https://www.instagram.com/dalacorte.contador" target="_blank" className="footer-social" title="@dalacorte.contador">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
          </a>
          <a href="#" className="footer-social" title="LinkedIn">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
          </a>
          <a href="#" className="footer-social" title="Facebook">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
          </a>
        </div>
      </div>
      <div className="footer-col">
        <h4>Navegação</h4>
        <ul className="footer-links">
          <li><a href="#inicio">Início</a></li>
          <li><a href="#planos">Planos e Serviços</a></li>
          <li><a href="#diferenciais">Diferenciais</a></li>
          <li><a href="#depoimentos">Depoimentos</a></li>
          <li><a href="#contato">Contato</a></li>
          <li><a href="/login">&Aacute;rea do Cliente</a></li>
        </ul>
      </div>
      <div className="footer-col">
        <h4>Contato</h4>
        <div className="footer-contact-items">
          <div className="footer-contact-item"><div className="footer-contact-icon">&#9993;</div><p>contato@dalacortefs.com.br</p></div>
          <div className="footer-contact-item"><div className="footer-contact-icon">&#128222;</div><p>(38) 99754-1448</p></div>
          <div className="footer-contact-item"><div className="footer-contact-icon">&#128205;</div><p>R. Abadia Lemos do Prado, 199&#10;Prado — Paracatu, MG</p></div>
        </div>
        <div className="footer-hours"><p>Atendimento</p><p>Segunda a Sexta: <span>8h &agrave;s 18h</span></p></div>
      </div>
    </div>
    <div className="footer-bar">
      <p>© 2026 Dalacorte Financial Solutions. Todos os direitos reservados. | CNPJ: XX.XXX.XXX/XXXX-XX</p>
      <div className="footer-bar-links"><a href="#">Política de Privacidade</a><a href="#">Termos de Uso</a></div>
    </div>
  </div>
</footer>

<a href="https://wa.me/5538997541448" target="_blank" rel="noopener noreferrer" className="whatsapp-float" title="Fale pelo WhatsApp">
  <svg viewBox="0 0 24 24" fill="white" width="28" height="28"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
</a>



    </>
  )
}
