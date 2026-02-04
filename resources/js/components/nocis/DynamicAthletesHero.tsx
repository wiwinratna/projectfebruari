import React, { useEffect } from 'react';
import { motion } from 'motion/react';

export function DynamicAthletesHero() {
  return (
    <section className="relative min-h-screen overflow-hidden bg-gray-100">
      {/* Geometric Background Shapes */}
      <motion.div
        initial={{ x: -500, rotate: 0 }}
        animate={{ x: 0, rotate: 20 }}
        transition={{ duration: 1.2, ease: "easeOut" }}
        className="absolute top-0 left-0 w-96 h-96 bg-blue-600 opacity-80"
        style={{ clipPath: 'polygon(0 0, 100% 0, 80% 100%, 0 80%)' }}
      ></motion.div>

      <motion.div
        initial={{ x: 500, rotate: 0 }}
        animate={{ x: 0, rotate: -15 }}
        transition={{ duration: 1.2, ease: "easeOut", delay: 0.2 }}
        className="absolute top-0 right-0 w-[500px] h-[500px] bg-green-500 opacity-80"
        style={{ clipPath: 'polygon(20% 0, 100% 0, 100% 100%, 0 80%)' }}
      ></motion.div>

      <motion.div
        initial={{ y: 500, rotate: 0 }}
        animate={{ y: 0, rotate: 25 }}
        transition={{ duration: 1.2, ease: "easeOut", delay: 0.3 }}
        className="absolute bottom-0 right-1/4 w-[400px] h-[400px] bg-pink-600 opacity-80"
        style={{ clipPath: 'polygon(0 20%, 100% 0, 100% 100%, 20% 100%)' }}
      ></motion.div>

      <motion.div
        initial={{ y: -500, rotate: 0 }}
        animate={{ y: 0, rotate: -20 }}
        transition={{ duration: 1.2, ease: "easeOut", delay: 0.4 }}
        className="absolute top-1/4 right-1/3 w-80 h-80 bg-purple-600 opacity-70"
        style={{ clipPath: 'polygon(50% 0, 100% 40%, 80% 100%, 20% 100%, 0 40%)' }}
      ></motion.div>

      <motion.div
        initial={{ x: -500, y: 500 }}
        animate={{ x: 0, y: 0 }}
        transition={{ duration: 1.2, ease: "easeOut", delay: 0.5 }}
        className="absolute bottom-0 left-0 w-72 h-72 bg-red-600 opacity-80"
        style={{ clipPath: 'polygon(0 0, 80% 20%, 100% 100%, 0 100%)' }}
      ></motion.div>

      {/* Athlete 1 - Yellow/Gold Overlay - Left Side */}
      <motion.div
        initial={{ x: -800, y: 100, opacity: 0, scale: 0.5 }}
        animate={{ x: 0, y: 0, opacity: 1, scale: 1 }}
        transition={{ duration: 1.5, ease: [0.43, 0.13, 0.23, 0.96], delay: 0.6 }}
        className="absolute bottom-0 left-12 w-[400px] h-[600px] z-20"
      >
        <div className="relative w-full h-full">
          <div
            className="absolute inset-0 bg-cover bg-center"
            style={{
              backgroundImage: `url('https://images.unsplash.com/photo-1658702041515-18275b138fda?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxvbHltcGljJTIwYXRobGV0ZSUyMHJ1bm5pbmclMjBwb3J0cmFpdHxlbnwxfHx8fDE3Njk4NTcwNzd8MA&ixlib=rb-4.1.0&q=80&w=1080')`,
              mixBlendMode: 'multiply'
            }}
          ></div>
          {/* Yellow Overlay */}
          <div className="absolute inset-0 bg-yellow-400 opacity-60 mix-blend-color"></div>
          {/* Gradient Shadow */}
          <div className="absolute inset-0 bg-linear-to-t from-black/50 via-transparent to-transparent"></div>
        </div>
      </motion.div>

      {/* Athlete 2 - Blue/Purple Overlay - Right Side */}
      <motion.div
        initial={{ x: 800, y: -100, opacity: 0, scale: 0.5, rotate: 15 }}
        animate={{ x: 0, y: 0, opacity: 1, scale: 1, rotate: 0 }}
        transition={{ duration: 1.5, ease: [0.43, 0.13, 0.23, 0.96], delay: 0.8 }}
        className="absolute top-32 right-16 w-[350px] h-[500px] z-20"
      >
        <div className="relative w-full h-full">
          <div
            className="absolute inset-0 bg-cover bg-center"
            style={{
              backgroundImage: `url('https://images.unsplash.com/photo-1763639700615-225fe7fdffff?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHx0cmFjayUyMGF0aGxldGUlMjBzcHJpbnRpbmd8ZW58MXx8fHwxNzY5ODU3MDc5fDA&ixlib=rb-4.1.0&q=80&w=1080')`,
              mixBlendMode: 'multiply'
            }}
          ></div>
          {/* Blue/Purple Overlay */}
          <div className="absolute inset-0 bg-linear-to-br from-blue-500 to-purple-600 opacity-70 mix-blend-color"></div>
          <div className="absolute inset-0 bg-linear-to-t from-black/50 via-transparent to-transparent"></div>
        </div>
      </motion.div>

      {/* Athlete 3 - Pink Overlay - Center Bottom */}
      <motion.div
        initial={{ y: 800, opacity: 0, scale: 0.8 }}
        animate={{ y: 0, opacity: 1, scale: 1 }}
        transition={{ duration: 1.5, ease: [0.43, 0.13, 0.23, 0.96], delay: 1 }}
        className="absolute bottom-0 left-1/2 -translate-x-1/2 w-[300px] h-[450px] z-10"
      >
        <div className="relative w-full h-full">
          <div
            className="absolute inset-0 bg-cover bg-center"
            style={{
              backgroundImage: `url('https://images.unsplash.com/photo-1710350427868-1c5cd7d79fb4?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxmZW1hbGUlMjBzd2ltbWVyJTIwYXRobGV0ZSUyMHBvcnRyYWl0fGVufDF8fHx8MTc2OTg1NzA3N3ww&ixlib=rb-4.1.0&q=80&w=1080')`,
              mixBlendMode: 'multiply'
            }}
          ></div>
          {/* Pink Overlay */}
          <div className="absolute inset-0 bg-pink-500 opacity-60 mix-blend-color"></div>
          <div className="absolute inset-0 bg-linear-to-t from-black/50 via-transparent to-transparent"></div>
        </div>
      </motion.div>

      {/* Athlete 4 - Green Overlay - Top Left */}
      <motion.div
        initial={{ x: -600, y: -600, opacity: 0, rotate: -20 }}
        animate={{ x: 0, y: 0, opacity: 1, rotate: 0 }}
        transition={{ duration: 1.5, ease: [0.43, 0.13, 0.23, 0.96], delay: 1.2 }}
        className="absolute top-20 left-1/4 w-[280px] h-[400px] z-15"
      >
        <div className="relative w-full h-full">
          <div
            className="absolute inset-0 bg-cover bg-center"
            style={{
              backgroundImage: `url('https://images.unsplash.com/photo-1763893312677-f408d814a244?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxiYXNrZXRiYWxsJTIwcGxheWVyJTIwanVtcGxpbmcUyMGFjdGlvbnxlbnwxfHx8fDE3Njk3ODY4MDh8MA&ixlib=rb-4.1.0&q=80&w=1080')`,
              mixBlendMode: 'multiply'
            }}
          ></div>
          {/* Green Overlay */}
          <div className="absolute inset-0 bg-green-400 opacity-60 mix-blend-color"></div>
          <div className="absolute inset-0 bg-linear-to-t from-black/50 via-transparent to-transparent"></div>
        </div>
      </motion.div>

      {/* Central Content */}
      <div className="relative z-30 flex items-center justify-center min-h-screen px-4 sm:px-6 lg:px-8">
        <div className="text-center max-w-4xl">
          <motion.div
            initial={{ opacity: 0, y: 30 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ delay: 1.5, duration: 0.8 }}
            className="mb-6"
          >
            <div className="inline-flex gap-3 mb-4 flex-wrap justify-center">
              <span className="px-4 py-2 bg-white/95 backdrop-blur-sm rounded-full text-sm font-bold text-gray-800 shadow-2xl border-2 border-white">
                Be a Liaison Officer
              </span>
              <span className="px-4 py-2 bg-white/95 backdrop-blur-sm rounded-full text-sm font-bold text-gray-800 shadow-2xl border-2 border-white">
                International Experience
              </span>
              <span className="px-4 py-2 bg-white/95 backdrop-blur-sm rounded-full text-sm font-bold text-gray-800 shadow-2xl border-2 border-white">
                Sports Events
              </span>
            </div>
          </motion.div>

          <motion.h1
            initial={{ opacity: 0, scale: 0.9 }}
            animate={{ opacity: 1, scale: 1 }}
            transition={{ delay: 1.7, duration: 0.8 }}
            className="mb-6"
            style={{ textShadow: '4px 4px 8px rgba(0,0,0,0.3)' }}
          >
            <span className="block text-5xl md:text-7xl lg:text-8xl font-black text-olympic-gradient mb-2">
              ARISE
            </span>
            <span className="block text-3xl md:text-5xl lg:text-6xl font-black text-white">
              LIAISON OFFICER
            </span>
            <span className="block text-3xl md:text-5xl lg:text-6xl font-black bg-linear-to-r from-blue-600 to-cyan-500 bg-clip-text text-transparent">
              Recruitment Platform
            </span>
          </motion.h1>

          <motion.p
            initial={{ opacity: 0 }}
            animate={{ opacity: 1 }}
            transition={{ delay: 1.9, duration: 0.8 }}
            className="text-base md:text-lg text-white mb-8 max-w-3xl mx-auto px-4 font-bold"
            style={{ textShadow: '2px 2px 6px rgba(0,0,0,0.5)' }}
          >
            Bergabunglah sebagai Liaison Officer dan jadilah jembatan komunikasi antara atlet internasional
            dengan penyelenggara event. Dapatkan pengalaman internasional yang tak terlupakan!
          </motion.p>

          {/* Olympic Colored Lines */}
          <motion.div
            initial={{ scaleX: 0 }}
            animate={{ scaleX: 1 }}
            transition={{ delay: 2.1, duration: 0.6 }}
            className="flex gap-2 justify-center mb-8"
          >
            <div className="h-1.5 w-20 md:w-32 bg-blue-600 rounded-full olympic-ring"></div>
            <div className="h-1.5 w-20 md:w-32 bg-yellow-400 rounded-full olympic-ring"></div>
            <div className="h-1.5 w-20 md:w-32 bg-black rounded-full olympic-ring"></div>
            <div className="h-1.5 w-20 md:w-32 bg-green-600 rounded-full olympic-ring"></div>
            <div className="h-1.5 w-20 md:w-32 bg-red-600 rounded-full olympic-ring"></div>
          </motion.div>

          <motion.div
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ delay: 2.3, duration: 0.6 }}
            className="flex gap-4 justify-center flex-wrap"
          >
            <button className="px-6 md:px-8 py-3 md:py-4 btn-olympic text-white font-bold rounded-full transition-all shadow-olympic hover:shadow-olympic-hover">
              Apply as Liaison Officer
            </button>
            <button className="px-6 md:px-8 py-3 md:py-4 bg-white text-blue-600 font-bold rounded-full hover:bg-blue-50 transition-all shadow-olympic border-2 border-blue-600">
              Learn More
            </button>
          </motion.div>
        </div>
      </div>

      {/* Floating Animation for Athletes */}
      <motion.div
        animate={{ y: [0, -20, 0] }}
        transition={{ duration: 3, repeat: Infinity, ease: "easeInOut" }}
        className="absolute bottom-0 left-12 w-[400px] h-[600px] z-19 pointer-events-none"
      ></motion.div>
    </section>
  );
}
