import { ImageWithFallback } from './figma/ImageWithFallback';
import { Calendar, MapPin, Sparkles } from 'lucide-react';
import { motion } from 'motion/react';

export function Hero() {
  return (
    <section id="home" className="relative min-h-screen flex items-center overflow-hidden">
      {/* Animated Background */}
      <div className="absolute inset-0">
        <ImageWithFallback
          src="https://images.unsplash.com/photo-1721918297705-81ee0fdd0d8c?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxvbHltcGljJTIwc3RhZGl1bSUyMGF0aGxldGVzfGVufDF8fHx8MTc2OTg0NzgwOXww&ixlib=rb-4.1.0&q=80&w=1080&utm_source=figma&utm_medium=referral"
          alt="Olympic Stadium"
          className="w-full h-full object-cover"
        />
        <div className="absolute inset-0 bg-linear-to-br from-slate-950/95 via-blue-950/90 to-purple-950/95"></div>

        {/* Animated Particles */}
        {[...Array(20)].map((_, i) => (
          <motion.div
            key={i}
            className="absolute w-2 h-2 bg-white rounded-full"
            initial={{
              x: Math.random() * window.innerWidth,
              y: Math.random() * window.innerHeight,
              opacity: 0,
            }}
            animate={{
              y: [null, Math.random() * window.innerHeight],
              opacity: [0, 1, 0],
            }}
            transition={{
              duration: Math.random() * 5 + 5,
              repeat: Infinity,
              ease: "linear",
            }}
          />
        ))}
      </div>

      <div className="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-32">
        <div className="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
          {/* Left Content */}
          <div>
            <motion.div
              initial={{ opacity: 0, y: 30 }}
              animate={{ opacity: 1, y: 0 }}
              transition={{ duration: 0.8 }}
              className="inline-flex items-center gap-2 px-4 py-2 bg-linear-to-r from-blue-500/20 to-purple-500/20 rounded-full border border-blue-400/30 mb-6 backdrop-blur-sm"
            >
              <Sparkles className="text-yellow-400" size={20} />
              <span className="text-blue-300">Winter Olympics 2026</span>
            </motion.div>

            <motion.h1
              initial={{ opacity: 0, y: 30 }}
              animate={{ opacity: 1, y: 0 }}
              transition={{ duration: 0.8, delay: 0.2 }}
              className="text-6xl md:text-7xl lg:text-8xl mb-6 font-bold"
            >
              <span className="bg-linear-to-r from-blue-400 via-purple-400 to-pink-400 bg-clip-text text-transparent">
                Unite
              </span>
              <br />
              <span className="text-white">Through Sport</span>
            </motion.h1>

            <motion.p
              initial={{ opacity: 0, y: 30 }}
              animate={{ opacity: 1, y: 0 }}
              transition={{ duration: 0.8, delay: 0.4 }}
              className="text-xl md:text-2xl text-gray-300 mb-8 leading-relaxed"
            >
              Experience the greatest winter sports spectacle as athletes from around the globe compete for glory
            </motion.p>

            <motion.div
              initial={{ opacity: 0, y: 30 }}
              animate={{ opacity: 1, y: 0 }}
              transition={{ duration: 0.8, delay: 0.6 }}
              className="flex flex-col sm:flex-row gap-4 mb-12"
            >
              <div className="flex items-center gap-3 px-4 py-3 bg-white/10 backdrop-blur-lg rounded-xl border border-white/20">
                <Calendar className="text-blue-400" size={24} />
                <div>
                  <div className="text-xs text-gray-400">Date</div>
                  <div className="text-white font-semibold">Feb 6-22, 2026</div>
                </div>
              </div>
              <div className="flex items-center gap-3 px-4 py-3 bg-white/10 backdrop-blur-lg rounded-xl border border-white/20">
                <MapPin className="text-pink-400" size={24} />
                <div>
                  <div className="text-xs text-gray-400">Location</div>
                  <div className="text-white font-semibold">Milan-Cortina</div>
                </div>
              </div>
            </motion.div>

            <motion.div
              initial={{ opacity: 0, y: 30 }}
              animate={{ opacity: 1, y: 0 }}
              transition={{ duration: 0.8, delay: 0.8 }}
              className="flex flex-wrap gap-4"
            >
              <motion.button
                whileHover={{ scale: 1.05 }}
                whileTap={{ scale: 0.95 }}
                className="px-8 py-4 bg-linear-to-r from-blue-600 to-purple-600 rounded-full text-white font-semibold text-lg shadow-2xl shadow-blue-500/50 hover:shadow-blue-500/70 transition-all"
              >
                Explore Events
              </motion.button>
              <motion.button
                whileHover={{ scale: 1.05 }}
                whileTap={{ scale: 0.95 }}
                className="px-8 py-4 bg-white/10 backdrop-blur-lg border-2 border-white/30 rounded-full text-white font-semibold text-lg hover:bg-white/20 transition-all"
              >
                Watch Trailer
              </motion.button>
            </motion.div>
          </div>

          {/* Right Content - Floating Cards */}
          <div className="relative hidden lg:block">
            <motion.div
              initial={{ opacity: 0, scale: 0.8 }}
              animate={{ opacity: 1, scale: 1 }}
              transition={{ duration: 1, delay: 0.5 }}
              className="relative"
            >
              {/* Main Card */}
              <motion.div
                animate={{ y: [0, -20, 0] }}
                transition={{ duration: 4, repeat: Infinity }}
                className="relative bg-linear-to-br from-blue-500/20 to-purple-500/20 backdrop-blur-xl rounded-3xl p-8 border border-white/20 shadow-2xl"
              >
                <ImageWithFallback
                  src="https://images.unsplash.com/photo-1600027331314-62eadadb209a?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxvbHltcGljJTIwdG9yY2glMjBmbGFtZSUyMG5pZ2h0fGVufDF8fHx8MTc2OTg0Nzk3M3ww&ixlib=rb-4.1.0&q=80&w=1080&utm_source=figma&utm_medium=referral"
                  alt="Olympic Torch"
                  className="w-full h-80 object-cover rounded-2xl mb-4"
                />
                <h3 className="text-2xl font-bold text-white mb-2">The Olympic Flame</h3>
                <p className="text-gray-300">Symbol of unity and excellence</p>
              </motion.div>

              {/* Floating Badge 1 */}
              <motion.div
                animate={{ y: [0, -15, 0], rotate: [0, 5, 0] }}
                transition={{ duration: 3, repeat: Infinity }}
                className="absolute -top-6 -right-6 bg-linear-to-br from-yellow-400 to-orange-500 rounded-2xl p-4 shadow-2xl"
              >
                <div className="text-3xl font-bold text-white">109</div>
                <div className="text-xs text-white/80">Events</div>
              </motion.div>

              {/* Floating Badge 2 */}
              <motion.div
                animate={{ y: [0, -10, 0], rotate: [0, -5, 0] }}
                transition={{ duration: 3.5, repeat: Infinity, delay: 0.5 }}
                className="absolute -bottom-6 -left-6 bg-linear-to-br from-pink-500 to-purple-600 rounded-2xl p-4 shadow-2xl"
              >
                <div className="text-3xl font-bold text-white">91</div>
                <div className="text-xs text-white/80">Countries</div>
              </motion.div>
            </motion.div>
          </div>
        </div>
      </div>

      {/* Bottom Gradient */}
      <div className="absolute bottom-0 left-0 right-0 h-32 bg-linear-to-t from-slate-950 to-transparent"></div>
    </section>
  );
}
