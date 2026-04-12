import React, { useState, useEffect } from 'react';
import { motion, AnimatePresence } from 'motion/react';

interface HeroSlide {
  id: number;
  image: string;
  title: string;
  subtitle: string;
  description: string;
  gradient: string;
}

interface HeroSlideApiItem {
  id: number;
  image_url: string | null;
  title: string;
  subtitle: string | null;
  description: string | null;
}

const FALLBACK_SLIDES: HeroSlide[] = [
  {
    id: 1,
    image: 'https://images.unsplash.com/photo-1600408942605-e39b013aaaea?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&q=80&w=1920',
    title: 'ARISE',
    subtitle: '2026',
    description: 'Always like never before',
    gradient: 'from-blue-600/40 to-purple-600/40'
  },
  {
    id: 2,
    image: 'https://images.unsplash.com/photo-1461896836934-ffe607ba8211?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&q=80&w=1920',
    title: 'PARIS 2024',
    subtitle: 'SUMMER',
    description: 'The greatest show on earth',
    gradient: 'from-red-600/40 to-orange-600/40'
  },
  {
    id: 3,
    image: 'https://images.unsplash.com/photo-1532444458054-01a7dd3e9fca?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&q=80&w=1920',
    title: 'CHAMPIONS',
    subtitle: 'RISE',
    description: 'Witness history in the making',
    gradient: 'from-green-600/40 to-emerald-600/40'
  },
  {
    id: 4,
    image: 'https://images.unsplash.com/photo-1551958219-acbc608c6377?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&q=80&w=1920',
    title: 'GLORY AWAITS',
    subtitle: 'JOIN US',
    description: 'Be part of the Olympic legacy',
    gradient: 'from-yellow-600/40 to-amber-600/40'
  },
  {
    id: 5,
    image: 'https://images.unsplash.com/photo-1587280501635-68a0e82cd5ff?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&q=80&w=1920',
    title: 'EXCELLENCE',
    subtitle: 'UNITY',
    description: 'One world, one dream',
    gradient: 'from-indigo-600/40 to-blue-600/40'
  }
];

const GRADIENTS = [
  'from-blue-600/40 to-purple-600/40',
  'from-red-600/40 to-orange-600/40',
  'from-green-600/40 to-emerald-600/40',
  'from-yellow-600/40 to-amber-600/40',
  'from-indigo-600/40 to-blue-600/40',
];

export function RoyalHeroVideo() {
  const [slides, setSlides] = useState<HeroSlide[]>(FALLBACK_SLIDES);
  const [currentSlide, setCurrentSlide] = useState(0);

  useEffect(() => {
    let mounted = true;

    fetch('/api/hero-slides')
      .then((r) => r.json())
      .then((data: HeroSlideApiItem[]) => {
        if (!mounted || !Array.isArray(data) || data.length === 0) {
          return;
        }

        const mappedSlides = data
          .filter((item) => !!item.image_url)
          .map((item, index) => ({
            id: item.id,
            image: item.image_url as string,
            title: item.title,
            subtitle: item.subtitle ?? '',
            description: item.description ?? '',
            gradient: GRADIENTS[index % GRADIENTS.length],
          }));

        if (mappedSlides.length > 0) {
          setSlides(mappedSlides);
          setCurrentSlide(0);
        }
      })
      .catch(() => {
        // Keep fallback slides when API is unavailable.
      });

    return () => {
      mounted = false;
    };
  }, []);

  useEffect(() => {
    const timer = setInterval(() => {
      setCurrentSlide((prev) => (prev + 1) % slides.length);
    }, 5000); // Change slide every 5 seconds

    return () => clearInterval(timer);
  }, [slides.length]);

  const nextSlide = () => {
    setCurrentSlide((prev) => (prev + 1) % slides.length);
  };

  const prevSlide = () => {
    setCurrentSlide((prev) => (prev - 1 + slides.length) % slides.length);
  };

  const goToSlide = (index: number) => {
    setCurrentSlide(index);
  };

  return (
    <section className="relative h-screen overflow-hidden bg-black">
      {/* Slideshow Background */}
      <AnimatePresence mode="wait">
        <motion.div
          key={currentSlide}
          initial={{ opacity: 0, scale: 1.1 }}
          animate={{ opacity: 1, scale: 1 }}
          exit={{ opacity: 0, scale: 0.95 }}
          transition={{ duration: 1 }}
          className="absolute inset-0"
        >
          {/* Background Image */}
          <div
            className="absolute inset-0 bg-cover bg-center"
            style={{ backgroundImage: `url('${slides[currentSlide].image}')` }}
          ></div>

          {/* Gradient Overlay */}
          <div className={`absolute inset-0 bg-linear-to-br ${slides[currentSlide].gradient}`}></div>
          <div className="absolute inset-0 bg-linear-to-t from-black/60 via-black/20 to-black/40"></div>
        </motion.div>
      </AnimatePresence>

      {/* Content Overlay */}
      <div className="relative h-full flex flex-col justify-center px-4 sm:px-6 lg:px-8 pt-16">
        <div className="max-w-7xl mx-auto w-full">
          <AnimatePresence mode="wait">
            <motion.div
              key={currentSlide}
              initial={{ opacity: 0, y: 50 }}
              animate={{ opacity: 1, y: 0 }}
              exit={{ opacity: 0, y: -50 }}
              transition={{ duration: 0.6 }}
              className="flex flex-col lg:flex-row items-start lg:items-center gap-6 lg:gap-8 mb-16 w-full"
            >
              {/* Olympic Rings Dots */}
              <motion.div
                className="flex gap-2"
                initial={{ x: -50, opacity: 0 }}
                animate={{ x: 0, opacity: 1 }}
                transition={{ delay: 0.3 }}
              >
                <div className="shrink-0 w-10 h-10 md:w-12 md:h-12 rounded-full bg-blue-500 opacity-80 shadow-lg"></div>
                <div className="shrink-0 w-10 h-10 md:w-12 md:h-12 rounded-full bg-yellow-400 opacity-80 shadow-lg"></div>
                <div className="shrink-0 w-10 h-10 md:w-12 md:h-12 rounded-full bg-black opacity-80 border-2 border-white shadow-lg"></div>
                <div className="shrink-0 w-10 h-10 md:w-12 md:h-12 rounded-full bg-green-500 opacity-80 shadow-lg"></div>
                <div className="shrink-0 w-10 h-10 md:w-12 md:h-12 rounded-full bg-red-600 opacity-80 shadow-lg"></div>
              </motion.div>

              <div className="w-full lg:w-auto">
                <motion.h1
                  className="text-4xl sm:text-5xl md:text-6xl lg:text-6xl xl:text-7xl 2xl:text-8xl font-black text-white leading-none tracking-tight drop-shadow-2xl break-words"
                  initial={{ opacity: 0, x: -30 }}
                  animate={{ opacity: 1, x: 0 }}
                  transition={{ delay: 0.4 }}
                >
                  {slides[currentSlide].title}
                </motion.h1>
              </div>

              <motion.div
                className="lg:border-l-2 border-white/50 lg:pl-8 lg:ml-8 mt-4 lg:mt-0 w-full lg:flex-1"
                initial={{ opacity: 0, x: 30 }}
                animate={{ opacity: 1, x: 0 }}
                transition={{ delay: 0.5 }}
              >
                <div className="text-3xl sm:text-4xl md:text-5xl lg:text-5xl xl:text-6xl 2xl:text-7xl font-black text-white drop-shadow-2xl leading-none break-words">
                  {slides[currentSlide].subtitle}
                </div>
                <div className="text-lg sm:text-xl md:text-2xl font-light text-white mt-2 break-words">
                  {slides[currentSlide].description}
                </div>
              </motion.div>
            </motion.div>
          </AnimatePresence>
        </div>
      </div>
    </section>
  );
}
