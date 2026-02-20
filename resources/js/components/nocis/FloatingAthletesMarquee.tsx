import React from 'react';
import { motion } from 'motion/react';

export function FloatingAthletesMarquee() {
  const athletesRow1 = [
    {
      image: "https://images.unsplash.com/photo-1658702041515-18275b138fda?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxvbHltcGljJTIwYXRobGV0ZSUyMHJ1bm5pbmclMjBwb3J0cmFpdHxlbnwxfHx8fDE3Njk4NTcwNzd8MA&ixlib=rb-4.1.0&q=80&w=1080",
      sport: "Track"
    },
    {
      image: "https://images.unsplash.com/photo-1710350427868-1c5cd7d79fb4?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxmZW1hbGUlMjBzd2ltbWVyJTIwYXRobGV0ZSUyMHBvcnRyYWl0fGVufDF8fHx8MTc2OTg1NzA3N3ww&ixlib=rb-4.1.0&q=80&w=1080",
      sport: "Swimming"
    },
    {
      image: "https://images.unsplash.com/photo-1763893312677-f408d814a244?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxiYXNrZXRiYWxsJTIwcGxheWVyJTIwanVtcGluZyUyMGFjdGlvbnxlbnwxfHx8fDE3Njk3ODY4MDh8MA&ixlib=rb-4.1.0&q=80&w=1080",
      sport: "Basketball"
    },
    {
      image: "https://images.unsplash.com/photo-1669627960958-b4a809aa76ef?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxneW1uYXN0JTIwYXRobGV0ZSUyMHBvcnRyYWl0fGVufDF8fHx8MTc2OTg1NzA3OHww&ixlib=rb-4.1.0&q=80&w=1080",
      sport: "Gymnastics"
    }
  ];

  const athletesRow2 = [
    {
      image: "https://images.unsplash.com/photo-1759659479017-f428353584eb?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxjeWNsaXN0JTIwYXRobGV0ZSUyMHJhY2luZyUyMGFjdGlvbnxlbnwxfHx8fDE3Njk4NTcwNzl8MA&ixlib=rb-4.1.0&q=80&w=1080",
      sport: "Cycling"
    },
    {
      image: "https://images.unsplash.com/photo-1765728617086-5d16a7bac916?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHx3ZWlnaHRsaWZ0ZXIlMjBhdGhsZXRlJTIwdHJhaW5pbmd8ZW58MXx8fHwxNzY5ODU3MDc5fDA&ixlib=rb-4.1.0&q=80&w=1080",
      sport: "Weightlifting"
    },
    {
      image: "https://images.unsplash.com/photo-1763639700615-225fe7fdffff?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHx0cmFjayUyMGF0aGxldGUlMjBzcHJpbnRpbmd8ZW58MXx8fHwxNzY5ODU3MDc5fDA&ixlib=rb-4.1.0&q=80&w=1080",
      sport: "Sprint"
    },
    {
      image: "https://images.unsplash.com/photo-1762341582293-1563fb50b330?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHx2b2xsZXliYWxsJTIwcGxheWVyJTIwYWN0aW9uJTIwc3BvcnR8ZW58MXx8fHwxNzY5ODU3MDgwfDA&ixlib=rb-4.1.0&q=80&w=1080",
      sport: "Volleyball"
    }
  ];

  // Duplicate arrays for seamless loop
  const duplicatedRow1 = [...athletesRow1, ...athletesRow1, ...athletesRow1];
  const duplicatedRow2 = [...athletesRow2, ...athletesRow2, ...athletesRow2];

  return (
    <section className="relative py-24 bg-linear-to-b from-red-950 to-black overflow-hidden">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-12">
        <motion.h2
          initial={{ opacity: 0, y: 20 }}
          whileInView={{ opacity: 1, y: 0 }}
          viewport={{ once: true }}
          className="text-5xl md:text-7xl font-black text-white text-center mb-4"
        >
          Our Partner
        </motion.h2>
        <p className="text-xl text-gray-400 text-center">
          Athletes in motion, excellence in action
        </p>
      </div>

      {/* Row 1 - Moving Right */}
      <div className="relative mb-8">
        <motion.div
          animate={{ x: [0, -1400] }}
          transition={{
            duration: 30,
            repeat: Infinity,
            ease: "linear"
          }}
          className="flex gap-6"
        >
          {duplicatedRow1.map((athlete, index) => (
            <motion.div
              key={index}
              whileHover={{ scale: 1.1, zIndex: 10 }}
              className="relative shrink-0 w-80 h-96 rounded-2xl overflow-hidden group cursor-pointer"
            >
              {/* Image */}
              <div
                className="absolute inset-0 bg-cover bg-center transition-transform duration-700 group-hover:scale-110"
                style={{ backgroundImage: `url('${athlete.image}')` }}
              >
                <div className="absolute inset-0 bg-linear-to-t from-black/80 via-black/20 to-transparent"></div>
              </div>

              {/* Badge */}
              <div className="absolute top-4 right-4">
                <motion.div
                  animate={{ rotate: [0, 5, -5, 0] }}
                  transition={{ duration: 2, repeat: Infinity }}
                  className="px-4 py-2 bg-[#C5D82F] text-black font-bold rounded-full text-sm"
                >
                  {athlete.sport}
                </motion.div>
              </div>

              {/* Hover Overlay */}
              <div className="absolute inset-0 bg-red-600/0 group-hover:bg-red-600/20 transition-colors duration-300"></div>
            </motion.div>
          ))}
        </motion.div>
      </div>

      {/* Row 2 - Moving Left */}
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-12">
        <motion.h2
          initial={{ opacity: 0, y: 20 }}
          whileInView={{ opacity: 1, y: 0 }}
          viewport={{ once: true }}
          className="text-5xl md:text-7xl font-black text-white text-center mb-4"
        >
          Our Client
        </motion.h2>
        <p className="text-xl text-gray-400 text-center">
          Athletes in motion, excellence in action
        </p>
      </div>
      <div className="relative">
        <motion.div
          animate={{ x: [-1400, 0] }}
          transition={{
            duration: 30,
            repeat: Infinity,
            ease: "linear"
          }}
          className="flex gap-6"
        >
          {duplicatedRow2.map((athlete, index) => (
            <motion.div
              key={index}
              whileHover={{ scale: 1.1, zIndex: 10 }}
              className="relative shrink-0 w-80 h-96 rounded-2xl overflow-hidden group cursor-pointer"
            >
              {/* Image */}
              <div
                className="absolute inset-0 bg-cover bg-center transition-transform duration-700 group-hover:scale-110"
                style={{ backgroundImage: `url('${athlete.image}')` }}
              >
                <div className="absolute inset-0 bg-linear-to-t from-black/80 via-black/20 to-transparent"></div>
              </div>

              {/* Badge */}
              <div className="absolute top-4 right-4">
                <motion.div
                  animate={{ rotate: [0, -5, 5, 0] }}
                  transition={{ duration: 2, repeat: Infinity }}
                  className="px-4 py-2 bg-white text-black font-bold rounded-full text-sm"
                >
                  {athlete.sport}
                </motion.div>
              </div>

              {/* Hover Overlay */}
              <div className="absolute inset-0 bg-red-600/0 group-hover:bg-red-600/20 transition-colors duration-300"></div>
            </motion.div>
          ))}
        </motion.div>
      </div>

      {/* Decorative Gradient Overlays */}
      <div className="absolute top-0 left-0 w-32 h-full bg-linear-to-r from-red-950 to-transparent pointer-events-none z-10"></div>
      <div className="absolute top-0 right-0 w-32 h-full bg-linear-to-l from-black to-transparent pointer-events-none z-10"></div>
    </section>
  );
}
