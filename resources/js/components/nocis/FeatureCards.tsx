import React from 'react';
import { motion } from 'motion/react';

export function FeatureCards() {
  const features = [
    {
      emoji: "🛡️",
      title: "Official Uniform & ID",
      description: "Dapatkan seragam resmi lengkap dengan aksesoris dan ID card digital dengan QR code untuk akses eksklusif ke venue.",
      gradient: "from-blue-500 to-blue-700",
      bgColor: "bg-blue-50",
      delay: 0
    },
    {
      emoji: "📚",
      title: "Intensive Training",
      description: "Program pelatihan komprehensif meliputi protokol internasional, etika profesional, dan cultural awareness.",
      gradient: "from-yellow-400 to-yellow-600",
      bgColor: "bg-yellow-50",
      delay: 0.1
    },
    {
      emoji: "🏆",
      title: "Official Certificate",
      description: "Sertifikat resmi yang diakui secara internasional untuk meningkatkan kredibilitas profesional Anda.",
      gradient: "from-black to-gray-700",
      bgColor: "bg-gray-50",
      delay: 0.2
    },
    {
      emoji: "🌍",
      title: "International Exposure",
      description: "Kesempatan berinteraksi langsung dengan atlet dan delegasi dari berbagai negara untuk memperluas wawasan global.",
      gradient: "from-green-500 to-green-700",
      bgColor: "bg-green-50",
      delay: 0.3
    },
    {
      emoji: "👥",
      title: "Professional Network",
      description: "Bergabung dengan komunitas LO profesional dan bangun koneksi strategis di industri olahraga internasional.",
      gradient: "from-red-500 to-red-700",
      bgColor: "bg-red-50",
      delay: 0.4
    },
    {
      emoji: "📈",
      title: "Career Growth",
      description: "Pengalaman berharga dan jalur karir jelas untuk berkembang di industri event management dan sports administration.",
      gradient: "from-purple-500 to-purple-700",
      bgColor: "bg-purple-50",
      delay: 0.5
    }
  ];

  return (
    <section id="features" className="py-24 relative overflow-hidden">
      {/* Red Background with Athletes */}
      <div className="absolute inset-0 bg-linear-to-br from-[#8B1538] via-[#6B1625] to-[#4A0F1C] z-0"></div>

      {/* Athlete Images Background */}
      <div className="absolute inset-0 z-0 opacity-40">
        {/* Runner - Left Side */}
        <motion.div
          initial={{ x: -200, opacity: 0 }}
          animate={{ x: 0, opacity: 0.6 }}
          transition={{ duration: 1.5 }}
          className="absolute bottom-0 left-0 w-[400px] h-[600px]"
          style={{
            backgroundImage: `url('https://images.unsplash.com/photo-1761225291317-6bbf383011f2?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxhdGhsZXRlJTIwcnVubmluZyUyMHRyYWNrJTIwc3BvcnRzJTIwYWN0aW9ufGVufDF8fHx8MTc2OTg2MjczOHww&ixlib=rb-4.1.0&q=80&w=1080')`,
            backgroundSize: 'cover',
            backgroundPosition: 'center',
            mixBlendMode: 'lighten'
          }}
        />

        {/* Swimmer - Top Right */}
        <motion.div
          initial={{ y: -200, opacity: 0 }}
          animate={{ y: 0, opacity: 0.5 }}
          transition={{ duration: 1.5, delay: 0.2 }}
          className="absolute top-20 right-0 w-[350px] h-[500px]"
          style={{
            backgroundImage: `url('https://images.unsplash.com/photo-1682468156314-b2e7ae438364?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxvbHltcGljJTIwc3dpbW1lciUyMGRpdmluZyUyMHNwb3J0c3xlbnwxfHx8fDE3Njk4NjI3NDF8MA&ixlib=rb-4.1.0&q=80&w=1080')`,
            backgroundSize: 'cover',
            backgroundPosition: 'center',
            mixBlendMode: 'lighten'
          }}
        />

        {/* Basketball Player - Center */}
        <motion.div
          initial={{ scale: 0.5, opacity: 0 }}
          animate={{ scale: 1, opacity: 0.5 }}
          transition={{ duration: 1.5, delay: 0.4 }}
          className="absolute bottom-20 right-1/4 w-[300px] h-[450px]"
          style={{
            backgroundImage: `url('https://images.unsplash.com/photo-1763893312677-f408d814a244?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxiYXNrZXRiYWxsJTIwcGxheWVyJTIwanVtcGxpbmcUyMGFjdGlvbnxlbnwxfHx8fDE3Njk3ODY4MDh8MA&ixlib=rb-4.1.0&q=80&w=1080')`,
            backgroundSize: 'cover',
            backgroundPosition: 'center',
            mixBlendMode: 'lighten'
          }}
        />
      </div>

      {/* Animated Shapes */}
      <motion.div
        className="absolute top-20 left-20 w-96 h-96 bg-[#D4FF00]/10 rounded-full blur-3xl z-0"
        animate={{
          scale: [1, 1.3, 1],
          opacity: [0.1, 0.2, 0.1]
        }}
        transition={{ duration: 8, repeat: Infinity }}
      />
      <motion.div
        className="absolute bottom-40 right-20 w-[500px] h-[500px] bg-white/5 rounded-full blur-3xl z-0"
        animate={{
          scale: [1, 1.4, 1],
          opacity: [0.05, 0.15, 0.05]
        }}
        transition={{ duration: 10, repeat: Infinity }}
      />

      {/* Dot Pattern Overlay */}
      <div className="absolute inset-0 z-0 opacity-10">
        <div className="absolute inset-0" style={{
          backgroundImage: 'radial-gradient(circle at 2px 2px, rgba(255,255,255,0.15) 1px, transparent 0)',
          backgroundSize: '40px 40px'
        }}></div>
      </div>

      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        {/* Header */}
        <motion.div
          initial={{ opacity: 0, y: 20 }}
          whileInView={{ opacity: 1, y: 0 }}
          viewport={{ once: true }}
          className="text-center mb-16"
        >
          <div className="inline-flex items-center gap-2 px-5 py-2.5 bg-[#D4FF00] rounded-full mb-6 shadow-lg">
            <span className="text-[#4A0F1C] font-bold text-sm">EXCLUSIVE BENEFITS</span>
          </div>
          <motion.h2
            className="text-5xl md:text-7xl font-black mb-4"
            initial={{ opacity: 0, scale: 0.9 }}
            whileInView={{ opacity: 1, scale: 1 }}
            viewport={{ once: true }}
          >
            <span className="text-white">What You'll Get as</span>
            <br />
            <span className="text-[#D4FF00]">Liaison Officer</span>
          </motion.h2>
          <p className="text-xl text-gray-200 max-w-3xl mx-auto">
            Fasilitas lengkap, pelatihan profesional, dan pengalaman internasional yang tak terlupakan
          </p>
        </motion.div>

        {/* Features Grid */}
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          {features.map((feature, index) => (
            <motion.div
              key={index}
              initial={{ opacity: 0, y: 50 }}
              whileInView={{ opacity: 1, y: 0 }}
              viewport={{ once: true }}
              transition={{ delay: feature.delay, type: 'spring', stiffness: 100 }}
              whileHover={{ y: -15, rotate: index % 2 === 0 ? 1 : -1 }}
              className="group relative"
            >
              {/* Card with Ultra Unique Design */}
              <div className="relative h-full bg-white rounded-[3rem] overflow-hidden shadow-2xl border-4 border-white hover:border-gray-100 transition-all">

                {/* Large Gradient Background Block - Diagonal */}
                <div className={`absolute -top-20 -right-20 w-64 h-64 bg-linear-to-br ${feature.gradient} opacity-10 group-hover:opacity-20 transition-all rounded-full blur-2xl group-hover:scale-150 duration-700`}></div>
                <div className={`absolute -bottom-10 -left-10 w-40 h-40 bg-linear-to-tr ${feature.gradient} opacity-5 group-hover:opacity-15 transition-all rounded-full blur-xl`}></div>

                {/* Top Decorative Bar with Gradient */}
                <div className={`h-2 bg-linear-to-r ${feature.gradient}`}></div>

                {/* Main Content */}
                <div className="relative p-10 z-10">

                  {/* Large Number Display - Hero Element */}
                  <motion.div
                    className="mb-6 relative"
                    whileHover={{ scale: 1.05 }}
                  >
                    <div className={`text-[120px] font-black leading-none bg-linear-to-br ${feature.gradient} bg-clip-text text-transparent opacity-20 group-hover:opacity-30 transition-all`}>
                      {(index + 1).toString().padStart(2, '0')}
                    </div>
                    {/* Floating Color Block */}
                    <motion.div
                      className={`absolute -top-4 -right-4 w-16 h-16 bg-linear-to-br ${feature.gradient} rounded-2xl shadow-xl`}
                      animate={{
                        rotate: [0, 5, -5, 0],
                        y: [0, -5, 0]
                      }}
                      transition={{ duration: 4, repeat: Infinity }}
                    />
                  </motion.div>

                  {/* Title with Creative Layout */}
                  <div className="mb-5 -mt-20">
                    <h3 className="text-3xl font-black text-gray-800 mb-3 leading-tight group-hover:translate-x-2 transition-transform">
                      {feature.title}
                    </h3>
                    {/* Multi-line Underline */}
                    <div className="space-y-1">
                      <div className={`h-1.5 bg-linear-to-r ${feature.gradient} rounded-full w-20 group-hover:w-32 transition-all duration-500`}></div>
                      <div className={`h-1 bg-linear-to-r ${feature.gradient} opacity-50 rounded-full w-12 group-hover:w-24 transition-all duration-700`}></div>
                    </div>
                  </div>

                  {/* Description */}
                  <p className="text-gray-600 leading-relaxed text-base">
                    {feature.description}
                  </p>

                  {/* Bottom Accent Line */}
                  <div className="mt-6 flex items-center gap-2">
                    <div className={`h-8 w-1.5 bg-linear-to-b ${feature.gradient} rounded-full`}></div>
                    <div className={`h-6 w-1 bg-linear-to-b ${feature.gradient} opacity-60 rounded-full`}></div>
                    <div className={`h-4 w-1 bg-linear-to-b ${feature.gradient} opacity-30 rounded-full`}></div>
                  </div>
                </div>

                {/* Side Vertical Accent */}
                <div className={`absolute right-0 top-1/4 bottom-1/4 w-2 bg-linear-to-b ${feature.gradient} group-hover:w-3 transition-all`}></div>

                {/* Corner Cut Effect */}
                <div className="absolute top-0 left-0 w-24 h-24 overflow-hidden">
                  <div className={`absolute -top-12 -left-12 w-24 h-24 bg-linear-to-br ${feature.gradient} rotate-45 opacity-20`}></div>
                </div>

                {/* Animated Border Glow on Hover */}
                <div className={`absolute inset-0 rounded-[3rem] opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none`}>
                  <div className={`absolute inset-0 rounded-[3rem] bg-linear-to-br ${feature.gradient} opacity-5 blur-xl`}></div>
                </div>
              </div>
            </motion.div>
          ))}
        </div>

        {/* Bottom CTA */}
        <motion.div
          initial={{ opacity: 0, y: 20 }}
          whileInView={{ opacity: 1, y: 0 }}
          viewport={{ once: true }}
          className="text-center mt-16"
        >
          <div className="inline-flex flex-col items-center gap-4 p-8 bg-linear-to-br from-blue-50 to-cyan-50 rounded-3xl shadow-olympic">
            <h3 className="text-2xl font-black text-gray-800">
              Ready to Join Our LO Team?
            </h3>
            <p className="text-gray-600 max-w-xl">
              Dapatkan semua benefits di atas dan mulai perjalanan karir internasional Anda bersama ARISE
            </p>
            <motion.button
              whileHover={{ scale: 1.05 }}
              whileTap={{ scale: 0.95 }}
              className="px-10 py-4 btn-olympic text-white font-bold rounded-full shadow-olympic hover:shadow-olympic-hover flex items-center gap-2"
            >
              Apply Now
            </motion.button>
          </div>
        </motion.div>
      </div>
    </section>
  );
}
