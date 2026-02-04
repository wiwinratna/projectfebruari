import React from 'react';
import { motion } from 'motion/react';

export function FlowSection() {
  const steps = [
    {
      emoji: "📝",
      number: "01",
      title: "Registration",
      description: "Daftar melalui platform ARISE dengan melengkapi data pribadi, upload CV, sertifikat bahasa, dan dokumen pendukung lainnya.",
      color: "from-blue-500 to-blue-700",
      bgColor: "bg-blue-50",
      borderColor: "border-blue-300",
      iconColor: "text-blue-600"
    },
    {
      emoji: "✅",
      number: "02",
      title: "Selection & Interview",
      description: "Tim rekrutmen melakukan seleksi berkas dan interview untuk menilai kemampuan komunikasi, bahasa asing, dan pengetahuan olahraga Anda.",
      color: "from-yellow-400 to-yellow-600",
      bgColor: "bg-yellow-50",
      borderColor: "border-yellow-300",
      iconColor: "text-yellow-600"
    },
    {
      emoji: "🎓",
      number: "03",
      title: "Training Program",
      description: "Kandidat terpilih mengikuti training intensif meliputi protokol event, etika LO, cultural awareness, dan praktik lapangan.",
      color: "from-green-500 to-green-700",
      bgColor: "bg-green-50",
      borderColor: "border-green-300",
      iconColor: "text-green-600"
    },
    {
      emoji: "🚀",
      number: "04",
      title: "Event Assignment",
      description: "Setelah lulus training dan mendapat sertifikasi, Anda akan ditugaskan ke event olahraga dan menerima official ID card dengan QR code.",
      color: "from-red-500 to-red-700",
      bgColor: "bg-red-50",
      borderColor: "border-red-300",
      iconColor: "text-red-600"
    }
  ];

  return (
    <section id="flow" className="py-24 relative overflow-hidden">
      {/* Video Background */}
      <div className="absolute inset-0 z-0">
        {/* Animated Gradient Background (Fallback) */}
        <motion.div
          className="absolute inset-0 bg-linear-to-br from-[#8B1538] via-[#6B1625] to-[#4A0F1C]"
          animate={{
            backgroundPosition: ['0% 0%', '100% 100%', '0% 0%'],
          }}
          transition={{ duration: 15, repeat: Infinity }}
          style={{ backgroundSize: '200% 200%' }}
        />

        {/* Sports Images Background */}
        {/* Trophy Celebration - Top Left */}
        <motion.div
          initial={{ opacity: 0, x: -100, rotate: -5 }}
          animate={{ opacity: 0.25, x: 0, rotate: -3 }}
          transition={{ duration: 1.5 }}
          className="absolute top-20 left-10 w-[400px] h-[350px] rounded-3xl overflow-hidden border-4 border-white/20 shadow-2xl"
          style={{
            backgroundImage: `url('https://images.unsplash.com/photo-1659080907059-00adb7e98f3e?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxhdGhsZXRlJTIwdHJvcGh5JTIwY2VsZWJyYXRpb24lMjB3aW5uZXJ8ZW58MXx8fHwxNzY5ODYzMTE2fDA&ixlib=rb-4.1.0&q=80&w=1080')`,
            backgroundSize: 'cover',
            backgroundPosition: 'center',
            transform: 'rotate(-3deg)'
          }}
        />

        {/* Podium Ceremony - Top Right */}
        <motion.div
          initial={{ opacity: 0, x: 100, rotate: 5 }}
          animate={{ opacity: 0.3, x: 0, rotate: 4 }}
          transition={{ duration: 1.5, delay: 0.2 }}
          className="absolute top-10 right-10 w-[450px] h-[380px] rounded-3xl overflow-hidden border-4 border-yellow-300/30 shadow-2xl"
          style={{
            backgroundImage: `url('https://images.unsplash.com/photo-1624365169806-1517fcb873d7?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxwb2RpdW0lMjBvbHltcGljJTIwbWVkYWxzJTIwY2VyZW1vbnl8ZW58MXx8fHwxNzY5ODYzMTE2fDA&ixlib=rb-4.1.0&q=80&w=1080')`,
            backgroundSize: 'cover',
            backgroundPosition: 'center',
            transform: 'rotate(4deg)'
          }}
        />

        {/* Running Athlete - Bottom Left */}
        <motion.div
          initial={{ opacity: 0, y: 100, rotate: 3 }}
          animate={{ opacity: 0.28, y: 0, rotate: 2 }}
          transition={{ duration: 1.5, delay: 0.4 }}
          className="absolute bottom-32 left-20 w-[380px] h-80 rounded-3xl overflow-hidden border-4 border-blue-400/30 shadow-2xl"
          style={{
            backgroundImage: `url('https://images.unsplash.com/photo-1532444458054-01a7dd3e9fca?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxydW5uaW5nJTIwYXRobGV0ZSUyMGNvbXBldGl0aW9uJTIwc3BvcnRzfGVufDF8fHx8MTc2OTg2MzExN3ww&ixlib=rb-4.1.0&q=80&w=1080')`,
            backgroundSize: 'cover',
            backgroundPosition: 'center',
            transform: 'rotate(2deg)'
          }}
        />

        {/* Champion Victory - Bottom Right */}
        <motion.div
          initial={{ opacity: 0, y: 100, rotate: -4 }}
          animate={{ opacity: 0.26, y: 0, rotate: -2 }}
          transition={{ duration: 1.5, delay: 0.6 }}
          className="absolute bottom-20 right-32 w-[420px] h-[360px] rounded-3xl overflow-hidden border-4 border-green-400/30 shadow-2xl"
          style={{
            backgroundImage: `url('https://images.unsplash.com/photo-1759688983881-0742f416a4b4?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxjaGFtcGlvbiUyMHZpY3RvcnklMjBjZWxlYnJhdGlvbiUyMGdvbGR8ZW58MXx8fHwxNzY5ODYzMTIwfDA&ixlib=rb-4.1.0&q=80&w=1080')`,
            backgroundSize: 'cover',
            backgroundPosition: 'center',
            transform: 'rotate(-2deg)'
          }}
        />

        {/* Animated shapes for dynamic effect */}
        <motion.div
          className="absolute top-0 left-0 w-96 h-96 bg-red-900/30 rounded-full blur-3xl"
          animate={{
            x: [0, 100, 0],
            y: [0, 150, 0],
            scale: [1, 1.2, 1]
          }}
          transition={{ duration: 20, repeat: Infinity }}
        />
        <motion.div
          className="absolute bottom-0 right-0 w-[500px] h-[500px] bg-[#D4FF00]/10 rounded-full blur-3xl"
          animate={{
            x: [0, -100, 0],
            y: [0, -150, 0],
            scale: [1, 1.3, 1]
          }}
          transition={{ duration: 18, repeat: Infinity }}
        />
        <motion.div
          className="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-black/20 rounded-full blur-3xl"
          animate={{
            scale: [1, 1.4, 1],
            rotate: [0, 180, 360]
          }}
          transition={{ duration: 25, repeat: Infinity }}
        />

        {/* Pattern Overlay for Texture */}
        <div className="absolute inset-0 opacity-10">
          <div className="absolute inset-0" style={{
            backgroundImage: 'radial-gradient(circle at 2px 2px, rgba(255,255,255,0.15) 1px, transparent 0)',
            backgroundSize: '40px 40px'
          }}></div>
        </div>
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
            <span className="text-[#4A0F1C] font-bold text-sm">HOW IT WORKS</span>
          </div>
          <h2 className="text-5xl md:text-7xl font-black text-white mb-4">
            Recruitment <span className="text-[#D4FF00]">Flow</span>
          </h2>
          <p className="text-xl text-gray-200 max-w-2xl mx-auto">
            Simple 4-step process to become a Liaison Officer
          </p>
        </motion.div>

        {/* Flow Steps */}
        <div className="relative">
          {/* Connection Lines */}
          <div className="hidden lg:block absolute top-1/2 left-0 right-0 h-1 bg-linear-to-r from-red-200 via-cyan-200 to-green-200 -translate-y-1/2 z-0"></div>

          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 relative z-10">
            {steps.map((step, index) => (
              <motion.div
                key={index}
                initial={{ opacity: 0, y: 50 }}
                whileInView={{ opacity: 1, y: 0 }}
                viewport={{ once: true }}
                transition={{ delay: index * 0.2 }}
                className="relative"
              >
                {/* Step Card - Ultra Custom Design */}
                <motion.div
                  className="relative h-full"
                  whileHover={{ y: -12, rotate: index % 2 === 0 ? 2 : -2 }}
                >
                  {/* Main Card */}
                  <div className="relative bg-white rounded-[2.5rem] p-8 shadow-2xl hover:shadow-[0_30px_70px_rgba(0,0,0,0.4)] transition-all group overflow-hidden">

                    {/* Large Number Background - Watermark Style */}
                    <div className={`absolute top-0 right-0 text-[180px] font-black leading-none opacity-5 group-hover:opacity-10 transition-all bg-linear-to-br ${step.color} bg-clip-text text-transparent select-none pointer-events-none`}>
                      {step.number}
                    </div>

                    {/* Top Color Strip - Thick */}
                    <div className={`absolute top-0 left-0 right-0 h-3 bg-linear-to-r ${step.color}`}></div>

                    {/* Diagonal Accent Shape */}
                    <div className={`absolute top-0 right-0 w-32 h-32 bg-linear-to-br ${step.color} opacity-10 group-hover:opacity-20 transition-all`} style={{ clipPath: 'polygon(100% 0, 0 0, 100% 100%)' }}></div>

                    {/* Content Container */}
                    <div className="relative z-10 pt-4">

                      {/* Step Number Badge - Large & Bold */}
                      <motion.div
                        className="mb-6"
                        whileHover={{ scale: 1.1 }}
                      >
                        <div className={`inline-flex items-center justify-center w-20 h-20 bg-linear-to-br ${step.color} rounded-3xl shadow-2xl transform -rotate-3 group-hover:rotate-3 transition-all`}>
                          <span className="text-4xl font-black text-white">{step.number}</span>
                        </div>
                        {/* Glow Effect */}
                        <div className={`absolute w-20 h-20 bg-linear-to-br ${step.color} rounded-3xl blur-2xl opacity-30 -z-10`}></div>
                      </motion.div>

                      {/* Title - Large & Bold */}
                      <h3 className="text-2xl font-black text-gray-800 mb-4 leading-tight group-hover:translate-x-1 transition-transform">
                        {step.title}
                      </h3>

                      {/* Creative Underline System */}
                      <div className="flex items-center gap-1 mb-4">
                        <div className={`h-2 bg-linear-to-r ${step.color} rounded-full w-16 group-hover:w-24 transition-all duration-500`}></div>
                        <div className={`h-1.5 bg-linear-to-r ${step.color} opacity-60 rounded-full w-8 group-hover:w-12 transition-all duration-700`}></div>
                        <div className={`h-1 bg-linear-to-r ${step.color} opacity-30 rounded-full w-4 group-hover:w-6 transition-all duration-900`}></div>
                      </div>

                      {/* Description */}
                      <p className="text-gray-600 leading-relaxed text-sm">
                        {step.description}
                      </p>

                      {/* Bottom Decorative Elements */}
                      <div className="mt-6 flex items-end gap-1.5">
                        <div className={`w-2 h-12 bg-linear-to-t ${step.color} rounded-t-full opacity-80`}></div>
                        <div className={`w-2 h-8 bg-linear-to-t ${step.color} rounded-t-full opacity-60`}></div>
                        <div className={`w-2 h-6 bg-linear-to-t ${step.color} rounded-t-full opacity-40`}></div>
                        <div className={`w-2 h-4 bg-linear-to-t ${step.color} rounded-t-full opacity-20`}></div>
                      </div>
                    </div>

                    {/* Side Gradient Border */}
                    <div className={`absolute left-0 top-1/4 bottom-1/4 w-1.5 bg-linear-to-b ${step.color} rounded-r-full group-hover:w-3 transition-all`}></div>

                    {/* Bottom Corner Color Block */}
                    <div className={`absolute bottom-0 right-0 w-28 h-28 bg-linear-to-tl ${step.color} opacity-5 group-hover:opacity-10 transition-all rounded-tl-[3rem]`}></div>

                    {/* Hover Glow Effect */}
                    <div className={`absolute inset-0 rounded-[2.5rem] bg-linear-to-br ${step.color} opacity-0 group-hover:opacity-5 transition-all blur-xl -z-10`}></div>
                  </div>
                </motion.div>

                {/* Arrow with Animation */}
                {index < steps.length - 1 && (
                  <motion.div
                    className="hidden lg:block absolute top-1/2 -right-4 -translate-y-1/2 z-20"
                    animate={{ x: [0, 8, 0] }}
                    transition={{ duration: 2, repeat: Infinity }}
                  >
                    <div className="relative text-5xl">
                      →
                    </div>
                  </motion.div>
                )}
              </motion.div>
            ))}
          </div>
        </div>

        {/* CTA */}
        <motion.div
          initial={{ opacity: 0, y: 20 }}
          whileInView={{ opacity: 1, y: 0 }}
          viewport={{ once: true }}
          className="text-center mt-16"
        >
          <motion.button
            whileHover={{ scale: 1.05 }}
            whileTap={{ scale: 0.95 }}
            className="px-10 py-5 bg-linear-to-r from-red-600 to-red-700 text-white font-bold rounded-full hover:shadow-2xl transition-all text-lg"
          >
            Start Registration Now
          </motion.button>
        </motion.div>

        {/* Info Box */}
        <motion.div
          initial={{ opacity: 0, y: 20 }}
          whileInView={{ opacity: 1, y: 0 }}
          viewport={{ once: true }}
          className="mt-12 bg-white/10 backdrop-blur-md rounded-3xl p-8 text-center border-2 border-white/20"
        >
          <p className="text-white text-lg">
            <span className="font-black text-[#D4FF00]">Need help?</span> Contact our support team at{' '}
            <a href="mailto:support@arise.com" className="text-[#D4FF00] font-bold hover:underline">
              support@arise.com
            </a>{' '}
            or call{' '}
            <a href="tel:+6281234567890" className="text-[#D4FF00] font-bold hover:underline">
              +62 812-3456-7890
            </a>
          </p>
        </motion.div>
      </div>
    </section>
  );
}
