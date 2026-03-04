import React from 'react';
import { motion } from 'motion/react';

export function DynamicAthletesHero() {
  return (
    <section className="relative min-h-screen overflow-hidden" style={{ background: 'linear-gradient(135deg, #0a0f1e 0%, #0d1b3e 50%, #0a1628 100%)' }}>

      {/* Subtle Background Grid */}
      <div
        className="absolute inset-0 opacity-5"
        style={{
          backgroundImage: `linear-gradient(rgba(255,255,255,0.15) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.15) 1px, transparent 1px)`,
          backgroundSize: '60px 60px'
        }}
      ></div>

      {/* Soft Radial Glow — center */}
      <div
        className="absolute inset-0 pointer-events-none"
        style={{ background: 'radial-gradient(ellipse 70% 60% at 50% 50%, rgba(29,78,216,0.18) 0%, transparent 70%)' }}
      ></div>

      {/* Gold accent shape — top left corner */}
      <motion.div
        initial={{ opacity: 0, x: -80 }}
        animate={{ opacity: 1, x: 0 }}
        transition={{ duration: 1.2, ease: "easeOut" }}
        className="absolute top-0 left-0 w-80 h-80 pointer-events-none"
        style={{
          background: 'linear-gradient(135deg, rgba(245,158,11,0.3) 0%, transparent 60%)',
          clipPath: 'polygon(0 0, 100% 0, 20% 100%, 0 80%)'
        }}
      ></motion.div>

      {/* Blue accent shape — bottom right corner */}
      <motion.div
        initial={{ opacity: 0, x: 80 }}
        animate={{ opacity: 1, x: 0 }}
        transition={{ duration: 1.2, ease: "easeOut", delay: 0.2 }}
        className="absolute bottom-0 right-0 w-96 h-96 pointer-events-none"
        style={{
          background: 'linear-gradient(315deg, rgba(29,78,216,0.35) 0%, transparent 60%)',
          clipPath: 'polygon(80% 0, 100% 0, 100% 100%, 0 100%)'
        }}
      ></motion.div>

      {/* Athlete photo — RIGHT side, clipped */}
      <motion.div
        initial={{ x: 120, opacity: 0 }}
        animate={{ x: 0, opacity: 1 }}
        transition={{ duration: 1.4, ease: [0.43, 0.13, 0.23, 0.96], delay: 0.4 }}
        className="absolute top-0 right-0 h-full w-[45%] pointer-events-none hidden lg:block"
      >
        {/* Photo */}
        <div
          className="absolute inset-0 bg-cover bg-center"
          style={{
            backgroundImage: `url('https://images.unsplash.com/photo-1763639700615-225fe7fdffff?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHx0cmFjayUyMGF0aGxldGUlMjBzcHJpbnRpbmd8ZW58MXx8fHwxNzY5ODU3MDc5fDA&ixlib=rb-4.1.0&q=80&w=1080')`,
          }}
        ></div>
        {/* Consistent dark-blue tint overlay */}
        <div
          className="absolute inset-0"
          style={{ background: 'linear-gradient(to right, #0a0f1e 0%, rgba(10,15,30,0.5) 30%, rgba(10,15,30,0.1) 100%)' }}
        ></div>
        {/* Bottom fade */}
        <div
          className="absolute inset-0"
          style={{ background: 'linear-gradient(to top, #0a0f1e 0%, transparent 40%)' }}
        ></div>
        {/* Gold vertical accent line */}
        <div className="absolute top-1/4 left-8 bottom-1/4 w-1 rounded-full" style={{ background: 'linear-gradient(to bottom, transparent, #f59e0b, transparent)' }}></div>
      </motion.div>

      {/* Main content — LEFT aligned */}
      <div className="relative z-20 flex items-center min-h-screen px-6 sm:px-10 lg:px-20">
        <div className="max-w-2xl w-full">

          {/* Badges */}
          <motion.div
            initial={{ opacity: 0, y: 24 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ delay: 0.6, duration: 0.7 }}
            className="flex gap-2 mb-8 flex-wrap"
          >
            {['Be a Liaison Officer', 'International Experience', 'Sports Events'].map((label, i) => (
              <span
                key={i}
                className="px-4 py-1.5 rounded-full text-xs font-semibold tracking-wide border"
                style={{ background: 'rgba(255,255,255,0.07)', borderColor: 'rgba(255,255,255,0.18)', color: '#cbd5e1' }}
              >
                {label}
              </span>
            ))}
          </motion.div>

          {/* Heading */}
          <motion.h1
            initial={{ opacity: 0, y: 32 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ delay: 0.8, duration: 0.8 }}
            className="mb-6 leading-none"
          >
            <span
              className="block font-black mb-1"
              style={{
                fontSize: 'clamp(3.5rem, 8vw, 6rem)',
                background: 'linear-gradient(90deg, #f59e0b, #fbbf24)',
                WebkitBackgroundClip: 'text',
                WebkitTextFillColor: 'transparent'
              }}
            >
              ARISE
            </span>
            <span
              className="block font-black text-white mb-1"
              style={{ fontSize: 'clamp(1.6rem, 4vw, 3rem)', letterSpacing: '0.05em' }}
            >
              LIAISON OFFICER
            </span>
            <span
              className="block font-bold"
              style={{
                fontSize: 'clamp(1.4rem, 3.5vw, 2.5rem)',
                background: 'linear-gradient(90deg, #60a5fa, #93c5fd)',
                WebkitBackgroundClip: 'text',
                WebkitTextFillColor: 'transparent'
              }}
            >
              Recruitment Platform
            </span>
          </motion.h1>

          {/* Description */}
          <motion.p
            initial={{ opacity: 0 }}
            animate={{ opacity: 1 }}
            transition={{ delay: 1.1, duration: 0.8 }}
            className="text-sm md:text-base mb-10 leading-relaxed max-w-lg"
            style={{ color: '#94a3b8' }}
          >
            Bergabunglah sebagai Liaison Officer dan jadilah jembatan komunikasi antara atlet internasional
            dengan penyelenggara event. Dapatkan pengalaman internasional yang tak terlupakan!
          </motion.p>

          {/* Olympic ring lines */}
          <motion.div
            initial={{ scaleX: 0, originX: 0 }}
            animate={{ scaleX: 1 }}
            transition={{ delay: 1.3, duration: 0.7 }}
            className="flex gap-1.5 mb-10"
          >
            {['#2563eb','#f59e0b','#e5e7eb','#16a34a','#dc2626'].map((color, i) => (
              <div key={i} className="h-1 w-12 md:w-16 rounded-full" style={{ background: color }}></div>
            ))}
          </motion.div>

          {/* CTA Buttons */}
          <motion.div
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ delay: 1.5, duration: 0.7 }}
            className="flex gap-4 flex-wrap"
          >
            <button
              className="px-7 py-3.5 rounded-full font-semibold text-sm transition-all duration-200 hover:scale-105 active:scale-95"
              style={{ background: 'linear-gradient(90deg, #f59e0b, #d97706)', color: '#0a0f1e', boxShadow: '0 4px 24px rgba(245,158,11,0.35)' }}
            >
              Apply as Liaison Officer
            </button>
            <button
              className="px-7 py-3.5 rounded-full font-semibold text-sm transition-all duration-200 hover:scale-105 active:scale-95"
              style={{ background: 'transparent', border: '1.5px solid rgba(255,255,255,0.35)', color: '#e2e8f0' }}
              onMouseEnter={e => (e.currentTarget.style.background = 'rgba(255,255,255,0.08)')}
              onMouseLeave={e => (e.currentTarget.style.background = 'transparent')}
            >
              Learn More
            </button>
          </motion.div>

        </div>
      </div>

      {/* Bottom fade-out */}
      <div
        className="absolute bottom-0 left-0 right-0 h-24 pointer-events-none"
        style={{ background: 'linear-gradient(to bottom, transparent, #0a0f1e)' }}
      ></div>
    </section>
  );
}
