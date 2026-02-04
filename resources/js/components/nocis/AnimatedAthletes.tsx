import React, { useEffect, useState } from 'react';
import { motion } from 'motion/react';

export function AnimatedAthletes() {
  const [activeIndex, setActiveIndex] = useState(0);

  const athletes = [
    {
      name: "Sarah Johnson",
      sport: "Track & Field",
      country: "🇺🇸 USA",
      image: "https://images.unsplash.com/photo-1658702041515-18275b138fda?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxvbHltcGljJTIwYXRobGV0ZSUyMHJ1bm5pbmclMjBwb3J0cmFpdHxlbnwxfHx8fDE3Njk4NTcwNzd8MA&ixlib=rb-4.1.0&q=80&w=1080",
      medals: "3x Gold",
      direction: "left" // datang dari kiri
    },
    {
      name: "Maria Silva",
      sport: "Swimming",
      country: "🇧🇷 Brazil",
      image: "https://images.unsplash.com/photo-1710350427868-1c5cd7d79fb4?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxmZW1hbGUlMjBzd2ltbWVyJTIwYXRobGV0ZSUyMHBvcnRyYWl0fGVufDF8fHx8MTc2OTg1NzA3N3ww&ixlib=rb-4.1.0&q=80&w=1080",
      medals: "2x Silver",
      direction: "right" // datang dari kanan
    },
    {
      name: "James Chen",
      sport: "Basketball",
      country: "🇨🇳 China",
      image: "https://images.unsplash.com/photo-1763893312677-f408d814a244?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxiYXNrZXRiYWxsJTIwcGxheWVyJTIwanVtcGluZyUyMGFjdGlvbnxlbnwxfHx8fDE3Njk3ODY4MDh8MA&ixlib=rb-4.1.0&q=80&w=1080",
      medals: "1x Gold",
      direction: "top" // datang dari atas
    },
    {
      name: "Emma Williams",
      sport: "Gymnastics",
      country: "🇬🇧 Great Britain",
      image: "https://images.unsplash.com/photo-1669627960958-b4a809aa76ef?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxneW1uYXN0JTIwYXRobGV0ZSUyMHBvcnRyYWl0fGVufDF8fHx8MTc2OTg1NzA3OHww&ixlib=rb-4.1.0&q=80&w=1080",
      medals: "4x Gold",
      direction: "bottom" // datang dari bawah
    },
    {
      name: "Lucas Martin",
      sport: "Cycling",
      country: "🇫🇷 France",
      image: "https://images.unsplash.com/photo-1759659479017-f428353584eb?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxjeWNsaXN0JTIwYXRobGV0ZSUyMHJhY2luZyUyMGFjdGlvbnxlbnwxfHx8fDE3Njk4NTcwNzl8MA&ixlib=rb-4.1.0&q=80&w=1080",
      medals: "2x Bronze",
      direction: "left"
    },
    {
      name: "Ahmed Hassan",
      sport: "Weightlifting",
      country: "🇪🇬 Egypt",
      image: "https://images.unsplash.com/photo-1765728617086-5d16a7bac916?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHx3ZWlnaHRsaWZ0ZXIlMjBhdGhsZXRlJTIwdHJhaW5pbmd8ZW58MXx8fHwxNzY5ODU3MDc5fDA&ixlib=rb-4.1.0&q=80&w=1080",
      medals: "1x Silver",
      direction: "right"
    },
    {
      name: "Yuki Tanaka",
      sport: "Sprint",
      country: "🇯🇵 Japan",
      image: "https://images.unsplash.com/photo-1763639700615-225fe7fdffff?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHx0cmFjayUyMGF0aGxldGUlMjBzcHJpbnRpbmd8ZW58MXx8fHwxNzY5ODU3MDc5fDA&ixlib=rb-4.1.0&q=80&w=1080",
      medals: "2x Gold",
      direction: "top"
    },
    {
      name: "Sofia Rodriguez",
      sport: "Volleyball",
      country: "🇦🇷 Argentina",
      image: "https://images.unsplash.com/photo-1762341582293-1563fb50b330?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHx2b2xsZXliYWxsJTIwcGxheWVyJTIwYWN0aW9uJTIwc3BvcnR8ZW58MXx8fHwxNzY5ODU3MDgwfDA&ixlib=rb-4.1.0&q=80&w=1080",
      medals: "1x Bronze",
      direction: "bottom"
    }
  ];

  // Auto rotate athletes every 4 seconds
  useEffect(() => {
    const interval = setInterval(() => {
      setActiveIndex((prev) => (prev + 1) % athletes.length);
    }, 4000);
    return () => clearInterval(interval);
  }, [athletes.length]);

  // Get initial position based on direction
  const getInitialPosition = (direction: string) => {
    switch (direction) {
      case 'left':
        return { x: -1000, y: 0 };
      case 'right':
        return { x: 1000, y: 0 };
      case 'top':
        return { x: 0, y: -1000 };
      case 'bottom':
        return { x: 0, y: 1000 };
      default:
        return { x: 0, y: 0 };
    }
  };

  return (
    <section className="relative py-24 bg-linear-to-b from-white via-red-50 to-white overflow-hidden">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {/* Section Title */}
        <motion.div
          initial={{ opacity: 0, y: 20 }}
          whileInView={{ opacity: 1, y: 0 }}
          viewport={{ once: true }}
          className="text-center mb-16"
        >
          <h2 className="text-5xl md:text-7xl font-black text-red-600 mb-4">
            OUR Activity
          </h2>
          <p className="text-xl text-gray-600">
            Meet the champions who inspire millions
          </p>
        </motion.div>

        {/* Main Athlete Spotlight */}
        <div className="relative h-[600px] mb-16">
          {athletes.map((athlete, index) => {
            const initialPos = getInitialPosition(athlete.direction);
            return (
              <motion.div
                key={index}
                initial={{
                  opacity: 0,
                  x: initialPos.x,
                  y: initialPos.y,
                  scale: 0.5,
                  rotate: athlete.direction === 'left' ? -45 : athlete.direction === 'right' ? 45 : 0
                }}
                animate={{
                  opacity: activeIndex === index ? 1 : 0,
                  x: activeIndex === index ? 0 : initialPos.x,
                  y: activeIndex === index ? 0 : initialPos.y,
                  scale: activeIndex === index ? 1 : 0.5,
                  rotate: activeIndex === index ? 0 : (athlete.direction === 'left' ? -45 : athlete.direction === 'right' ? 45 : 0),
                  zIndex: activeIndex === index ? 10 : 0
                }}
                transition={{
                  duration: 1,
                  ease: [0.43, 0.13, 0.23, 0.96] // Custom easing untuk dramatic entrance
                }}
                className="absolute inset-0"
              >
                <div className="relative h-full rounded-3xl overflow-hidden shadow-2xl">
                  {/* Background Image */}
                  <div
                    className="absolute inset-0 bg-cover bg-center"
                    style={{ backgroundImage: `url('${athlete.image}')` }}
                  >
                    <div className="absolute inset-0 bg-linear-to-r from-red-900/80 via-red-900/50 to-transparent"></div>
                  </div>

                  {/* Content */}
                  <div className="relative h-full flex items-end p-12">
                    <div className="space-y-4">
                      <motion.div
                        initial={{ x: -50, opacity: 0 }}
                        animate={{
                          x: activeIndex === index ? 0 : -50,
                          opacity: activeIndex === index ? 1 : 0
                        }}
                        transition={{ delay: 0.5, duration: 0.5 }}
                        className="inline-block px-4 py-2 bg-[#C5D82F] text-black font-bold rounded-full"
                      >
                        {athlete.medals}
                      </motion.div>
                      <motion.h3
                        initial={{ x: -50, opacity: 0 }}
                        animate={{
                          x: activeIndex === index ? 0 : -50,
                          opacity: activeIndex === index ? 1 : 0
                        }}
                        transition={{ delay: 0.6, duration: 0.5 }}
                        className="text-6xl font-black text-white"
                      >
                        {athlete.name}
                      </motion.h3>
                      <motion.div
                        initial={{ x: -50, opacity: 0 }}
                        animate={{
                          x: activeIndex === index ? 0 : -50,
                          opacity: activeIndex === index ? 1 : 0
                        }}
                        transition={{ delay: 0.7, duration: 0.5 }}
                        className="flex items-center gap-4 text-white text-xl"
                      >
                        <span className="font-bold">{athlete.sport}</span>
                        <span>•</span>
                        <span>{athlete.country}</span>
                      </motion.div>
                    </div>
                  </div>

                  {/* Direction Indicator */}
                  {activeIndex === index && (
                    <motion.div
                      initial={{ opacity: 0 }}
                      animate={{ opacity: 1 }}
                      transition={{ delay: 0.8 }}
                      className="absolute top-4 left-4 px-3 py-1 bg-white/20 backdrop-blur-sm text-white text-xs font-bold rounded-full"
                    >
                      FROM {athlete.direction.toUpperCase()}
                    </motion.div>
                  )}
                </div>
              </motion.div>
            );
          })}
        </div>

        {/* Athlete Thumbnails */}
        <div className="flex justify-center gap-4 flex-wrap">
          {athletes.map((athlete, index) => (
            <motion.button
              key={index}
              onClick={() => setActiveIndex(index)}
              whileHover={{ scale: 1.1, y: -5 }}
              className={`relative w-20 h-20 rounded-full overflow-hidden border-4 transition-all ${
                activeIndex === index
                  ? 'border-red-600 shadow-xl shadow-red-600/50'
                  : 'border-white/50'
              }`}
            >
              <div
                className="absolute inset-0 bg-cover bg-center"
                style={{ backgroundImage: `url('${athlete.image}')` }}
              ></div>
              {activeIndex === index && (
                <motion.div
                  layoutId="activeIndicator"
                  className="absolute inset-0 bg-red-600/20"
                />
              )}
            </motion.button>
          ))}
        </div>
      </div>

      {/* Animated Olympic Rings Background */}
      <div className="absolute top-1/4 right-0 opacity-5 pointer-events-none">
        <motion.div
          animate={{
            rotate: 360,
            scale: [1, 1.1, 1]
          }}
          transition={{
            duration: 20,
            repeat: Infinity,
            ease: "linear"
          }}
          className="flex gap-4"
        >
          <div className="w-32 h-32 rounded-full border-8 border-blue-500"></div>
          <div className="w-32 h-32 rounded-full border-8 border-yellow-400"></div>
          <div className="w-32 h-32 rounded-full border-8 border-black"></div>
          <div className="w-32 h-32 rounded-full border-8 border-green-500"></div>
          <div className="w-32 h-32 rounded-full border-8 border-red-600"></div>
        </motion.div>
      </div>
    </section>
  );
}
