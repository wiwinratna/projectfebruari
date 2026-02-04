import React from 'react';
import { motion } from 'motion/react';

export function AboutSection() {
  const stats = [
    {
      emoji: "",
      number: "2,500+",
      label: "Active LO Members",
      color: "from-blue-500 to-blue-700",
      bgColor: "bg-blue-50",
      iconColor: "text-blue-600"
    },
    {
      emoji: "",
      number: "150+",
      label: "Events Covered",
      color: "from-yellow-400 to-yellow-600",
      bgColor: "bg-yellow-50",
      iconColor: "text-yellow-600"
    },
    {
      emoji: "",
      number: "98%",
      label: "Satisfaction Rate",
      color: "from-green-500 to-green-700",
      bgColor: "bg-green-50",
      iconColor: "text-green-600"
    },
    {
      emoji: "",
      number: "35+",
      label: "Countries Delegations",
      color: "from-red-500 to-red-700",
      bgColor: "bg-red-50",
      iconColor: "text-red-600"
    }
  ];

  return (
    <section id="about" className="py-24 bg-linear-to-b from-white via-gray-50 to-white relative overflow-hidden">
      {/* Olympic Rings Pattern - Background */}
      <div className="absolute inset-0 opacity-[0.12]">
        <svg className="absolute top-20 left-10 w-96 h-64" viewBox="0 0 300 200">
          <circle cx="50" cy="50" r="40" fill="none" stroke="#0085C7" strokeWidth="10" />
          <circle cx="130" cy="50" r="40" fill="none" stroke="#000000" strokeWidth="10" />
          <circle cx="210" cy="50" r="40" fill="none" stroke="#EE334E" strokeWidth="10" />
          <circle cx="90" cy="90" r="40" fill="none" stroke="#FCB131" strokeWidth="10" />
          <circle cx="170" cy="90" r="40" fill="none" stroke="#00A651" strokeWidth="10" />
        </svg>
        <svg className="absolute bottom-40 right-20 w-96 h-64 -rotate-12" viewBox="0 0 300 200">
          <circle cx="50" cy="50" r="40" fill="none" stroke="#0085C7" strokeWidth="10" />
          <circle cx="130" cy="50" r="40" fill="none" stroke="#000000" strokeWidth="10" />
          <circle cx="210" cy="50" r="40" fill="none" stroke="#EE334E" strokeWidth="10" />
          <circle cx="90" cy="90" r="40" fill="none" stroke="#FCB131" strokeWidth="10" />
          <circle cx="170" cy="90" r="40" fill="none" stroke="#00A651" strokeWidth="10" />
        </svg>
        <svg className="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-80 opacity-40" viewBox="0 0 300 200">
          <circle cx="50" cy="50" r="40" fill="none" stroke="#0085C7" strokeWidth="10" />
          <circle cx="130" cy="50" r="40" fill="none" stroke="#000000" strokeWidth="10" />
          <circle cx="210" cy="50" r="40" fill="none" stroke="#EE334E" strokeWidth="10" />
          <circle cx="90" cy="90" r="40" fill="none" stroke="#FCB131" strokeWidth="10" />
          <circle cx="170" cy="90" r="40" fill="none" stroke="#00A651" strokeWidth="10" />
        </svg>
      </div>

      {/* Flying Garuda Silhouettes */}
      <motion.div
        className="absolute top-32 left-1/4 w-40 h-40 opacity-[0.15]"
        animate={{
          x: [0, 300, 600, 900],
          y: [0, -40, -20, -60],
          rotate: [0, 8, -8, 0]
        }}
        transition={{ duration: 35, repeat: Infinity, ease: "linear" }}
      >
        <svg viewBox="0 0 100 100" fill="#0085C7">
          <path d="M50,20 Q30,30 20,50 Q30,55 50,60 Q70,55 80,50 Q70,30 50,20 M35,45 L25,55 L35,50 M65,45 L75,55 L65,50" />
        </svg>
      </motion.div>

      <motion.div
        className="absolute top-1/3 right-1/4 w-36 h-36 opacity-[0.15]"
        animate={{
          x: [0, -200, -400, -600],
          y: [0, 30, 15, 45],
          rotate: [0, -10, 5, -5]
        }}
        transition={{ duration: 28, repeat: Infinity, ease: "linear", delay: 5 }}
      >
        <svg viewBox="0 0 100 100" fill="#EE334E">
          <path d="M50,20 Q30,30 20,50 Q30,55 50,60 Q70,55 80,50 Q70,30 50,20 M35,45 L25,55 L35,50 M65,45 L75,55 L65,50" />
        </svg>
      </motion.div>

      <motion.div
        className="absolute bottom-1/3 left-1/3 w-44 h-44 opacity-[0.15]"
        animate={{
          x: [0, 350, 700],
          y: [0, -50, -100],
          rotate: [0, 12, -8]
        }}
        transition={{ duration: 40, repeat: Infinity, ease: "linear", delay: 12 }}
      >
        <svg viewBox="0 0 100 100" fill="#FCB131">
          <path d="M50,20 Q30,30 20,50 Q30,55 50,60 Q70,55 80,50 Q70,30 50,20 M35,45 L25,55 L35,50 M65,45 L75,55 L65,50" />
        </svg>
      </motion.div>

      {/* Athletic Silhouettes */}
      <motion.div
        className="absolute top-1/4 right-16 opacity-[0.12]"
        animate={{
          y: [0, -25, 0],
          rotate: [0, 8, 0]
        }}
        transition={{ duration: 5, repeat: Infinity }}
      >
        <svg width="140" height="170" viewBox="0 0 100 150" fill="#0085C7">
          <ellipse cx="50" cy="15" rx="12" ry="15" />
          <path d="M50,30 L50,70 M50,45 L30,60 M50,45 L70,55 M50,70 L35,110 M50,70 L65,105" strokeWidth="8" stroke="#0085C7" fill="none" />
        </svg>
      </motion.div>

      <motion.div
        className="absolute bottom-1/4 left-12 opacity-[0.12]"
        animate={{
          x: [0, 20, 0],
          rotate: [0, -12, 0]
        }}
        transition={{ duration: 6, repeat: Infinity }}
      >
        <svg width="130" height="160" viewBox="0 0 100 150" fill="#00A651">
          <ellipse cx="50" cy="20" rx="12" ry="15" />
          <path d="M50,35 L50,65 M50,45 L25,50 M50,45 L75,50 M50,65 L40,95 M50,65 L60,95" strokeWidth="8" stroke="#00A651" fill="none" />
        </svg>
      </motion.div>

      <motion.div
        className="absolute top-2/3 right-1/3 opacity-[0.12]"
        animate={{
          y: [0, 15, 0],
          x: [0, -10, 0]
        }}
        transition={{ duration: 4, repeat: Infinity }}
      >
        <svg width="125" height="155" viewBox="0 0 100 150" fill="#EE334E">
          <ellipse cx="50" cy="18" rx="12" ry="15" />
          <path d="M50,33 L50,68 M50,48 L28,58 M50,48 L72,58 M50,68 L38,100 M50,68 L62,100" strokeWidth="8" stroke="#EE334E" fill="none" />
        </svg>
      </motion.div>

      {/* Geometric Sport Patterns */}
      <div className="absolute top-0 left-0 w-full h-full opacity-[0.08]">
        <motion.svg
          className="absolute top-1/3 left-16"
          width="140"
          height="140"
          animate={{ rotate: [0, 360] }}
          transition={{ duration: 20, repeat: Infinity, ease: "linear" }}
        >
          <polygon points="70,15 110,40 110,90 70,115 30,90 30,40" fill="none" stroke="#0085C7" strokeWidth="5" />
        </motion.svg>
        <motion.svg
          className="absolute bottom-1/4 right-1/4"
          width="120"
          height="120"
          animate={{ rotate: [0, -360] }}
          transition={{ duration: 25, repeat: Infinity, ease: "linear" }}
        >
          <polygon points="60,10 95,25 95,65 60,80 25,65 25,25" fill="none" stroke="#FCB131" strokeWidth="5" />
        </motion.svg>
        <motion.svg
          className="absolute top-1/2 left-1/4"
          width="110"
          height="110"
          animate={{ rotate: [0, 360] }}
          transition={{ duration: 18, repeat: Infinity, ease: "linear" }}
        >
          <polygon points="55,12 85,28 85,62 55,78 25,62 25,28" fill="none" stroke="#00A651" strokeWidth="5" />
        </motion.svg>
      </div>

      {/* Subtle Floating Glows */}
      <motion.div
        className="absolute top-32 right-20 w-80 h-80 bg-blue-300/40 rounded-full blur-3xl"
        animate={{
          scale: [1, 1.3, 1],
          opacity: [0.3, 0.5, 0.3]
        }}
        transition={{ duration: 10, repeat: Infinity }}
      />
      <motion.div
        className="absolute bottom-32 left-20 w-96 h-96 bg-yellow-300/40 rounded-full blur-3xl"
        animate={{
          scale: [1, 1.4, 1],
          opacity: [0.3, 0.5, 0.3]
        }}
        transition={{ duration: 12, repeat: Infinity }}
      />
      <motion.div
        className="absolute top-1/2 right-1/3 w-72 h-72 bg-red-300/30 rounded-full blur-3xl"
        animate={{
          scale: [1, 1.2, 1],
          opacity: [0.2, 0.4, 0.2]
        }}
        transition={{ duration: 15, repeat: Infinity }}
      />

      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div className="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
          {/* Left Content */}
          <motion.div
            initial={{ opacity: 0, x: -50 }}
            whileInView={{ opacity: 1, x: 0 }}
            viewport={{ once: true }}
            className="space-y-6"
          >
            <div className="inline-flex items-center gap-2 px-5 py-2.5 bg-linear-to-r from-blue-50 to-blue-100 rounded-full shadow-lg">
              <span className="text-blue-700 font-bold text-sm">ABOUT ARISE</span>
            </div>

            <h2 className="text-5xl md:text-6xl font-black text-gray-900">
              Building Olympic Dreams,<br />
              <span className="text-olympic-gradient">One Liaison at a Time</span>
            </h2>

            <p className="text-lg text-gray-600 leading-relaxed">
              ARISE adalah platform recruitment terdepan yang menghubungkan talenta muda Indonesia
              dengan kesempatan berkarir sebagai <span className="font-bold text-blue-600">Liaison Officer</span> di event
              olahraga internasional. Kami menyediakan pelatihan profesional, sertifikasi, dan penugasan
              langsung ke berbagai kompetisi olahraga prestisius.
            </p>

            <div className="flex flex-wrap gap-4">
              <motion.div
                whileHover={{ scale: 1.05 }}
                className="flex items-center gap-2 px-4 py-2 bg-white rounded-full shadow-md border-2 border-blue-100"
              >
                <span className="font-bold text-gray-700">Certified Training</span>
              </motion.div>
              <motion.div
                whileHover={{ scale: 1.05 }}
                className="flex items-center gap-2 px-4 py-2 bg-white rounded-full shadow-md border-2 border-yellow-100"
              >
                <span className="font-bold text-gray-700">International Events</span>
              </motion.div>
              <motion.div
                whileHover={{ scale: 1.05 }}
                className="flex items-center gap-2 px-4 py-2 bg-white rounded-full shadow-md border-2 border-green-100"
              >
                <span className="font-bold text-gray-700">Career Growth</span>
              </motion.div>
            </div>

            <motion.button
              whileHover={{ scale: 1.05 }}
              whileTap={{ scale: 0.95 }}
              className="px-8 py-4 btn-olympic text-white font-bold rounded-full shadow-olympic hover:shadow-olympic-hover flex items-center gap-2"
            >
              Join Our LO Community →
            </motion.button>
          </motion.div>

          {/* Right Side - Stats Grid */}
          <motion.div
            initial={{ opacity: 0, x: 50 }}
            whileInView={{ opacity: 1, x: 0 }}
            viewport={{ once: true }}
            transition={{ duration: 0.8 }}
            className="grid grid-cols-2 gap-6"
          >
            {stats.map((stat, index) => (
              <motion.div
                key={index}
                initial={{ opacity: 0, y: 30 }}
                whileInView={{ opacity: 1, y: 0 }}
                viewport={{ once: true }}
                transition={{ delay: index * 0.1 }}
                whileHover={{ y: -10, scale: 1.05 }}
                className="group relative bg-white rounded-3xl p-8 shadow-lg hover:shadow-2xl transition-all"
              >
                {/* Icon */}
                <div className={`mb-4 w-14 h-14 bg-linear-to-br ${stat.color} rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform shadow-lg text-3xl`}>
                  {stat.emoji}
                </div>

                {/* Number */}
                <div className={`text-4xl font-black mb-2 bg-linear-to-br ${stat.color} bg-clip-text text-transparent`}>
                  {stat.number}
                </div>

                {/* Label */}
                <div className="text-gray-600 font-bold text-sm">
                  {stat.label}
                </div>

                {/* Decorative Line */}
                <div className={`absolute bottom-0 left-0 right-0 h-1 bg-linear-to-r ${stat.color} rounded-b-3xl opacity-0 group-hover:opacity-100 transition-opacity`}></div>
              </motion.div>
            ))}
          </motion.div>
        </div>

        {/* Mission & Vision */}
        <div className="grid grid-cols-1 md:grid-cols-2 gap-8 mt-16">
          <motion.div
            initial={{ opacity: 0, y: 30 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            className="bg-linear-to-br from-red-50 to-red-100 rounded-3xl p-8 border-2 border-red-200"
          >
            <h3 className="text-3xl font-black text-red-600 mb-4">
             Our Mission
            </h3>
            <p className="text-gray-700 leading-relaxed">
              Merekrut dan melatih Liaison Officer terbaik yang mampu memberikan pelayanan prima kepada
              delegasi internasional, serta menjadi duta bangsa dalam setiap event olahraga yang diselenggarakan.
            </p>
          </motion.div>

          <motion.div
            initial={{ opacity: 0, y: 30 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            transition={{ delay: 0.2 }}
            className="bg-linear-to-br from-blue-50 to-blue-100 rounded-3xl p-8 border-2 border-blue-200"
          >
            <h3 className="text-3xl font-black text-blue-600 mb-4">
            Our Vision
            </h3>
            <p className="text-gray-700 leading-relaxed">
              Menjadi platform recruitment LO terkemuka di Asia Pasifik yang menghasilkan Liaison Officer
              profesional dan berpengalaman untuk event olahraga internasional.
            </p>
          </motion.div>
        </div>
      </div>
    </section>
  );
}
