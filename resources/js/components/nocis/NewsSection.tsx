import React from 'react';
import { motion } from 'motion/react';

export function NewsSection() {
  const news = [
    {
      id: 1,
      image: "https://images.unsplash.com/photo-1461896836934-ffe607ba8211?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&q=80&w=1080",
      category: "Event Update",
      date: "January 28, 2026",
      title: "500 Liaison Officers Successfully Deployed at Asian Games 2026",
      excerpt: "NOCIS berhasil merekrut dan menempatkan 500 Liaison Officer profesional untuk mendampingi delegasi dari 45 negara di Asian Games 2026.",
      color: "from-blue-500 to-blue-700",
      categoryBg: "bg-blue-100",
      categoryText: "text-blue-700"
    },
    {
      id: 2,
      image: "https://images.unsplash.com/photo-1517649763962-0c623066013b?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&q=80&w=1080",
      category: "Training",
      date: "January 25, 2026",
      title: "New Intensive Training Program for LO Candidates Launched",
      excerpt: "Program training baru dengan modul protokol internasional, bahasa asing, dan cultural awareness untuk calon Liaison Officer.",
      color: "from-yellow-400 to-yellow-600",
      categoryBg: "bg-yellow-100",
      categoryText: "text-yellow-700"
    },
    {
      id: 3,
      image: "https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&q=80&w=1080",
      category: "Success Story",
      date: "January 22, 2026",
      title: "From LO to IOC Staff: Inspiring Career Journey",
      excerpt: "Kisah inspiratif alumni ARISE LO yang kini bekerja sebagai staff resmi International Olympic Committee di Swiss.",
      color: "from-green-500 to-green-700",
      categoryBg: "bg-green-100",
      categoryText: "text-green-700"
    },
    {
      id: 4,
      image: "https://images.unsplash.com/photo-1487466365202-1afdb86c764e?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&q=80&w=1080",
      category: "Achievement",
      date: "January 20, 2026",
      title: "NOCIS LO Program Receives Excellence Award 2026",
      excerpt: "Program Liaison Officer NOCIS meraih penghargaan Best Sports Workforce Program dari Sports Management Association.",
      color: "from-red-500 to-red-700",
      categoryBg: "bg-red-100",
      categoryText: "text-red-700"
    },
    {
      id: 5,
      image: "https://images.unsplash.com/photo-1502945015378-0e284ca1a5be?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&q=80&w=1080",
      category: "Recruitment",
      date: "January 18, 2026",
      title: "Open Recruitment: 200 LO Positions for SEA Games 2027",
      excerpt: "ARISE membuka pendaftaran untuk 200 posisi Liaison Officer yang akan bertugas di SEA Games 2027. Daftar sekarang!",
      color: "from-purple-500 to-purple-700",
      categoryBg: "bg-purple-100",
      categoryText: "text-purple-700"
    },
    {
      id: 6,
      image: "https://images.unsplash.com/photo-1517649763962-0c623066013b?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&q=80&w=1080",
      category: "Interview",
      date: "January 15, 2026",
      title: "A Day in the Life of a Liaison Officer: Behind The Scenes",
      excerpt: "Wawancara eksklusif dengan senior LO tentang pengalaman, tantangan, dan keseruan menjadi Liaison Officer profesional.",
      color: "from-orange-500 to-orange-700",
      categoryBg: "bg-orange-100",
      categoryText: "text-orange-700"
    }
  ];

  return (
    <section id="news" className="py-24 bg-white relative overflow-hidden">
      {/* Newspaper Background Textures */}
      <div className="absolute inset-0 z-0">
        {/* Newspaper Clipping - Top Left */}
        <motion.div
          initial={{ opacity: 0, rotate: -5, x: -100 }}
          animate={{ opacity: 0.15, rotate: -3, x: 0 }}
          transition={{ duration: 1.5 }}
          className="absolute top-10 left-10 w-[500px] h-[400px] rounded-2xl"
          style={{
            backgroundImage: `url('https://images.unsplash.com/photo-1701200241941-44c0a4dd0c60?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxuZXdzcGFwZXIlMjB2aW50YWdlJTIwYmFja2dyb3VuZHxlbnwxfHx8fDE3Njk4NjI5MDF8MA&ixlib=rb-4.1.0&q=80&w=1080')`,
            backgroundSize: 'cover',
            backgroundPosition: 'center',
            transform: 'rotate(-3deg)',
            border: '3px solid rgba(0,0,0,0.1)',
            boxShadow: '0 4px 20px rgba(0,0,0,0.1)'
          }}
        />

        {/* Newspaper Text - Top Right */}
        <motion.div
          initial={{ opacity: 0, rotate: 5, x: 100 }}
          animate={{ opacity: 0.18, rotate: 4, x: 0 }}
          transition={{ duration: 1.5, delay: 0.2 }}
          className="absolute top-20 right-10 w-[450px] h-[350px] rounded-2xl"
          style={{
            backgroundImage: `url('https://images.unsplash.com/photo-1762992414454-76407e9db33b?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxuZXdzcGFwZXIlMjB0ZXh0JTIwcHJpbnQlMjBtZWRpYXxlbnwxfHx8fDE3Njk4NjI5MDV8MA&ixlib=rb-4.1.0&q=80&w=1080')`,
            backgroundSize: 'cover',
            backgroundPosition: 'center',
            transform: 'rotate(4deg)',
            border: '3px solid rgba(0,0,0,0.1)',
            boxShadow: '0 4px 20px rgba(0,0,0,0.1)'
          }}
        />

        {/* Newspaper Clipping - Bottom Left */}
        <motion.div
          initial={{ opacity: 0, rotate: 3, y: 100 }}
          animate={{ opacity: 0.14, rotate: 2, y: 0 }}
          transition={{ duration: 1.5, delay: 0.4 }}
          className="absolute bottom-40 left-20 w-[400px] h-[300px] rounded-2xl"
          style={{
            backgroundImage: `url('https://images.unsplash.com/photo-1701200241941-44c0a4dd0c60?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxuZXdzcGFwZXIlMjB2aW50YWdlJTIwYmFja2dyb3VuZHxlbnwxfHx8fDE3Njk4NjI5MDF8MA&ixlib=rb-4.1.0&q=80&w=1080')`,
            backgroundSize: 'cover',
            backgroundPosition: 'center',
            transform: 'rotate(2deg)',
            border: '3px solid rgba(0,0,0,0.1)',
            boxShadow: '0 4px 20px rgba(0,0,0,0.1)'
          }}
        />

        {/* Newspaper Text - Bottom Right */}
        <motion.div
          initial={{ opacity: 0, rotate: -4, y: 100 }}
          animate={{ opacity: 0.16, rotate: -2, y: 0 }}
          transition={{ duration: 1.5, delay: 0.6 }}
          className="absolute bottom-20 right-32 w-[380px] h-80 rounded-2xl"
          style={{
            backgroundImage: `url('https://images.unsplash.com/photo-1762992414454-76407e9db33b?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxuZXdzcGFwZXIlMjB0ZXh0JTIwcHJpbnQlMjBtZWRpYXxlbnwxfHx8fDE3Njk4NjI5MDV8MA&ixlib=rb-4.1.0&q=80&w=1080')`,
            backgroundSize: 'cover',
            backgroundPosition: 'center',
            transform: 'rotate(-2deg)',
            border: '3px solid rgba(0,0,0,0.1)',
            boxShadow: '0 4px 20px rgba(0,0,0,0.1)'
          }}
        />

        {/* Text Snippets Overlays */}
        <motion.div
          initial={{ opacity: 0 }}
          animate={{ opacity: 0.12 }}
          transition={{ duration: 2, delay: 0.8 }}
          className="absolute top-1/4 left-1/3 w-[350px] p-6 bg-gray-900/10 backdrop-blur-sm rounded-xl transform -rotate-2 border-2 border-gray-200"
        >
          <div className="text-xs leading-relaxed text-gray-800 font-serif">
            <div className="font-black text-lg mb-2">BREAKING NEWS</div>
            <div className="space-y-1">
              <p>"NOCIS successfully deployed 500 Liaison Officers..."</p>
              <p>"Training program receives international recognition..."</p>
              <p>"New recruitment opens for SEA Games 2027..."</p>
            </div>
          </div>
        </motion.div>

        <motion.div
          initial={{ opacity: 0 }}
          animate={{ opacity: 0.11 }}
          transition={{ duration: 2, delay: 1 }}
          className="absolute bottom-1/3 right-1/4 w-[320px] p-6 bg-gray-900/10 backdrop-blur-sm rounded-xl transform rotate-3 border-2 border-gray-200"
        >
          <div className="text-xs leading-relaxed text-gray-800 font-serif">
            <div className="font-black text-lg mb-2">SPORTS HEADLINES</div>
            <div className="space-y-1">
              <p>"LO Program achieves excellence award 2026..."</p>
              <p>"International exposure for young professionals..."</p>
              <p>"Career growth in sports management industry..."</p>
            </div>
          </div>
        </motion.div>

        {/* Decorative Lines */}
        <div className="absolute inset-0 opacity-10">
          <div className="absolute top-0 left-0 w-full h-px bg-linear-to-r from-transparent via-gray-400 to-transparent"></div>
          <div className="absolute bottom-0 left-0 w-full h-px bg-linear-to-r from-transparent via-gray-400 to-transparent"></div>
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
          <div className="inline-flex items-center gap-2 px-4 py-2 bg-orange-100 rounded-full mb-4">
            <span className="text-orange-600 font-bold text-sm"> LATEST UPDATES</span>
          </div>
          <h2 className="text-5xl md:text-7xl font-black mb-4">
            <span className="text-gray-800">News &</span>{' '}
            <span className="text-red-600">Updates</span>
          </h2>
          <p className="text-xl text-gray-600 max-w-2xl mx-auto">
            Stay informed with the latest news, updates, and announcements from ARISE
          </p>
        </motion.div>

        {/* News Grid */}
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          {news.map((item, index) => (
            <motion.article
              key={item.id}
              initial={{ opacity: 0, y: 30 }}
              whileInView={{ opacity: 1, y: 0 }}
              viewport={{ once: true }}
              transition={{ delay: index * 0.1 }}
              whileHover={{ y: -15, scale: 1.02 }}
              className="group relative"
            >
              {/* Card Container - Premium Magazine Style */}
              <div className="relative h-full bg-white rounded-[2.5rem] overflow-hidden shadow-xl hover:shadow-[0_25px_60px_rgba(0,0,0,0.2)] transition-all">

                {/* Image Container */}
                <div className="relative h-72 overflow-hidden">
                  <div
                    className="absolute inset-0 bg-cover bg-center group-hover:scale-110 transition-transform duration-700"
                    style={{ backgroundImage: `url('${item.image}')` }}
                  ></div>

                  {/* Strong Gradient Overlay */}
                  <div className={`absolute inset-0 bg-linear-to-t ${item.color} opacity-40 group-hover:opacity-60 transition-opacity`}></div>

                  {/* Top Thick Stripe */}
                  <div className={`absolute top-0 left-0 right-0 h-3 bg-linear-to-r ${item.color}`}></div>

                  {/* Category Badge - Floating Style */}
                  <div className="absolute top-5 left-5">
                    <motion.div
                      className={`px-5 py-2.5 bg-white rounded-full shadow-xl border-3 ${item.categoryText} font-black text-xs uppercase tracking-wider`}
                      whileHover={{ scale: 1.05, rotate: -2 }}
                      style={{ borderWidth: '3px', borderColor: 'currentColor' }}
                    >
                      {item.category}
                    </motion.div>
                  </div>

                  {/* Featured Badge */}
                  {index < 3 && (
                    <motion.div
                      className="absolute top-5 right-5 px-4 py-2 bg-linear-to-r from-yellow-400 to-orange-400 text-gray-900 rounded-full text-xs font-black shadow-xl"
                      animate={{
                        scale: [1, 1.08, 1],
                        rotate: [0, 2, -2, 0]
                      }}
                      transition={{ duration: 3, repeat: Infinity }}
                    >
                      FEATURED
                    </motion.div>
                  )}

                  {/* Date Badge - Bottom Corner */}
                  <div className="absolute bottom-5 right-5">
                    <div className="px-4 py-2 bg-white/95 backdrop-blur-sm rounded-full shadow-lg">
                      <span className="text-xs font-bold text-gray-700">{item.date}</span>
                    </div>
                  </div>

                  {/* Diagonal Accent Strip */}
                  <div className={`absolute bottom-0 left-0 right-0 h-2 bg-linear-to-r ${item.color}`}></div>
                </div>

                {/* Content Area */}
                <div className="p-7 relative">

                  {/* Large Number Watermark */}
                  <div className={`absolute -top-8 right-4 text-[120px] font-black leading-none opacity-5 bg-linear-to-br ${item.color} bg-clip-text text-transparent select-none pointer-events-none`}>
                    {(index + 1).toString().padStart(2, '0')}
                  </div>

                  {/* Title - Bold & Impactful */}
                  <h3 className="text-2xl font-black text-gray-800 mb-4 leading-tight line-clamp-2 group-hover:text-red-600 transition-colors relative z-10">
                    {item.title}
                  </h3>

                  {/* Creative Multi-Line Accent */}
                  <div className="flex items-center gap-1 mb-4">
                    <div className={`h-2 bg-linear-to-r ${item.color} rounded-full w-20 group-hover:w-32 transition-all duration-500`}></div>
                    <div className={`h-1.5 bg-linear-to-r ${item.color} opacity-60 rounded-full w-12 group-hover:w-20 transition-all duration-700`}></div>
                    <div className={`h-1 bg-linear-to-r ${item.color} opacity-30 rounded-full w-6 group-hover:w-10 transition-all duration-900`}></div>
                  </div>

                  {/* Excerpt */}
                  <p className="text-gray-600 text-sm leading-relaxed mb-5 line-clamp-3">
                    {item.excerpt}
                  </p>

                  {/* Read Button - Premium Style */}
                  <motion.button
                    whileHover={{ x: 8, scale: 1.02 }}
                    whileTap={{ scale: 0.98 }}
                    className={`w-full flex items-center justify-between font-bold text-sm px-5 py-3 rounded-2xl transition-all ${item.categoryBg} ${item.categoryText} shadow-md hover:shadow-lg group/btn`}
                  >
                    <span>Read Full Article </span>
                  </motion.button>

                  {/* Bottom Decorative Bars */}
                  <div className="mt-5 flex items-center gap-1.5 justify-center">
                    <div className={`h-1.5 w-12 bg-linear-to-r ${item.color} rounded-full opacity-80 group-hover:w-16 transition-all`}></div>
                    <div className={`h-1.5 w-8 bg-linear-to-r ${item.color} rounded-full opacity-50 group-hover:w-12 transition-all`}></div>
                    <div className={`h-1.5 w-4 bg-linear-to-r ${item.color} rounded-full opacity-30 group-hover:w-8 transition-all`}></div>
                  </div>
                </div>

                {/* Bottom Thick Gradient Strip */}
                <div className={`h-2 bg-linear-to-r ${item.color}`}></div>

                {/* Corner Geometric Accent */}
                <div className={`absolute bottom-0 right-0 w-32 h-32 bg-linear-to-tl ${item.color} opacity-5 group-hover:opacity-10 transition-all`} style={{ clipPath: 'polygon(100% 0, 100% 100%, 0 100%)' }}></div>

                {/* Side Accent Line */}
                <div className={`absolute right-0 top-1/3 bottom-1/3 w-1.5 bg-linear-to-b ${item.color} group-hover:w-2.5 transition-all`}></div>
              </div>
            </motion.article>
          ))}
        </div>

        {/* View All News Button */}
        <motion.div
          initial={{ opacity: 0 }}
          whileInView={{ opacity: 1 }}
          viewport={{ once: true }}
          className="text-center mt-12"
        >
          <motion.button
            whileHover={{ scale: 1.05 }}
            whileTap={{ scale: 0.95 }}
            className="px-8 py-4 bg-red-600 text-white font-bold rounded-full hover:bg-red-700 transition-all shadow-lg"
          >
            View All News & Articles
          </motion.button>
        </motion.div>
      </div>
    </section>
  );
}
