import React, { useState } from 'react';
import { motion, useScroll, useTransform } from 'motion/react';

export function RoyalHeader() {
  const [isMenuOpen, setIsMenuOpen] = useState(false);
  const { scrollY } = useScroll();
  const headerOpacity = useTransform(scrollY, [0, 100], [0.95, 1]);
  const headerShadow = useTransform(
    scrollY,
    [0, 100],
    ['0 0 0 rgba(0,0,0,0)', '0 10px 40px rgba(0, 133, 199, 0.2)']
  );

  return (
    <motion.header
      initial={{ y: -100 }}
      animate={{ y: 0 }}
      transition={{ duration: 0.6, type: 'spring', stiffness: 100 }}
      style={{
        opacity: headerOpacity,
        boxShadow: headerShadow
      }}
      className="fixed top-0 left-0 right-0 z-50"
    >
      <div className="px-4 sm:px-6 lg:px-8 pt-3">
        <div className="max-w-7xl mx-auto bg-white/10 backdrop-blur-2xl border border-white/20 rounded-xl shadow-2xl">
          <div className="px-4 py-2.5">
            <div className="flex items-center justify-between">
              {/* Logo with Olympic Rings Style */}
              <motion.div
                whileHover={{ scale: 1.05 }}
                className="flex items-center gap-2 cursor-pointer"
              >
                <motion.div
                  className="relative w-10 h-10"
                  whileHover={{ rotate: 360 }}
                  transition={{ duration: 0.8 }}
                >
                  {/* Olympic Rings Inspired Logo */}
                  <div className="absolute inset-0 flex items-center justify-center">
                    <div className="w-9 h-9 rounded-full bg-linear-to-br from-blue-600 to-blue-700 flex items-center justify-center shadow-olympic olympic-pulse">
                      <span className="text-white font-black text-base">A</span>
                    </div>
                  </div>
                  {/* Mini Olympic Rings */}
                  <div className="absolute -top-0.5 -right-0.5 flex gap-0.5">
                    <div className="w-1.5 h-1.5 rounded-full bg-yellow-400 border border-yellow-500"></div>
                    <div className="w-1.5 h-1.5 rounded-full bg-red-600 border border-red-700"></div>
                  </div>
                  <div className="absolute -bottom-0.5 -right-0.5 flex gap-0.5">
                    <div className="w-1.5 h-1.5 rounded-full bg-green-600 border border-green-700"></div>
                  </div>
                </motion.div>
                <div>
                  <h1 className="text-xl font-black text-white drop-shadow-lg">
                    ARISE
                  </h1>
                </div>
              </motion.div>

              {/* Desktop Navigation */}
              <nav className="hidden md:flex items-center gap-6 text-sm relative">
                <a className="text-white/90 hover:text-white font-semibold transition-all relative py-2 group" href="#jobs">
                  Jobs
                  <span className="absolute bottom-0 left-0 w-0 h-0.5 bg-linear-to-r from-blue-500 to-yellow-400 group-hover:w-full transition-all duration-300"></span>
                </a>
                <a className="text-white/90 hover:text-white font-semibold transition-all relative py-2 group" href="#about">
                  About
                  <span className="absolute bottom-0 left-0 w-0 h-0.5 bg-linear-to-r from-yellow-400 to-green-500 group-hover:w-full transition-all duration-300"></span>
                </a>
                <a className="text-white/90 hover:text-white font-semibold transition-all relative py-2 group" href="#flow">
                  Flow
                  <span className="absolute bottom-0 left-0 w-0 h-0.5 bg-linear-to-r from-green-500 to-red-500 group-hover:w-full transition-all duration-300"></span>
                </a>
                <a className="text-white/90 hover:text-white font-semibold transition-all relative py-2 group" href="#features">
                  Features
                  <span className="absolute bottom-0 left-0 w-0 h-0.5 bg-linear-to-r from-red-500 to-blue-500 group-hover:w-full transition-all duration-300"></span>
                </a>
                <a className="text-white/90 hover:text-white font-semibold transition-all relative py-2 group" href="#news">
                  News
                  <span className="absolute bottom-0 left-0 w-0 h-0.5 bg-linear-to-r from-blue-500 to-yellow-400 group-hover:w-full transition-all duration-300"></span>
                </a>
              </nav>

              {/* Right Actions */}
              <div className="flex items-center gap-2.5">
                <motion.button
                  whileHover={{ scale: 1.05 }}
                  whileTap={{ scale: 0.95 }}
                  onClick={() => window.location.href = '/login'}
                  className="hidden md:flex items-center gap-2 px-5 py-2 bg-linear-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white font-bold rounded-full relative overflow-hidden shadow-lg text-sm cursor-pointer"
                >
                  <span className="relative z-10">Login</span>
                </motion.button>
                <motion.button
                  whileHover={{ scale: 1.05 }}
                  whileTap={{ scale: 0.95 }}
                  onClick={() => window.location.href = '/register'}
                  className="hidden md:flex items-center gap-2 px-5 py-2 btn-olympic text-white font-bold rounded-full relative overflow-hidden shadow-lg text-sm cursor-pointer"
                >
                  <span className="relative z-10">Sign Up</span>
                </motion.button>
                {/* Mobile Menu Toggle */}
                <button
                  onClick={() => setIsMenuOpen(!isMenuOpen)}
                  className="md:hidden px-3 py-1.5 bg-white/20 hover:bg-white/30 rounded-full transition-colors font-bold text-white text-sm"
                >
                  Menu
                </button>
              </div>
            </div>

            {/* Mobile Navigation */}
            {isMenuOpen && (
              <motion.div
                initial={{ opacity: 0, height: 0 }}
                animate={{ opacity: 1, height: 'auto' }}
                exit={{ opacity: 0, height: 0 }}
                className="md:hidden pt-4 mt-4 border-t border-white/20"
              >
                <nav className="flex flex-col gap-4">
                  <a className="text-white/90 hover:text-white font-semibold transition-colors py-2" href="#jobs">
                    Jobs
                  </a>
                  <a className="text-white/90 hover:text-white font-semibold transition-colors py-2" href="#about">
                    About
                  </a>
                  <a className="text-white/90 hover:text-white font-semibold transition-colors py-2" href="#flow">
                    Flow
                  </a>
                  <a className="text-white/90 hover:text-white font-semibold transition-colors py-2" href="#features">
                    Features
                  </a>
                  <a className="text-white/90 hover:text-white font-semibold transition-colors py-2" href="#news">
                    News
                  </a>
                  <button
                    onClick={() => window.location.href = '/login'}
                    className="w-full px-6 py-2.5 bg-linear-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white font-bold rounded-full"
                  >
                    Login
                  </button>
                  <button
                    onClick={() => window.location.href = 'resources\views\auth\register.blade.php  '}
                    className="w-full px-6 py-2.5 btn-olympic text-white font-bold rounded-full flex items-center justify-center gap-2"
                    >
                    Apply Now
                  </button>
                </nav>
              </motion.div>
            )}
          </div>
        </div>
      </div>
    </motion.header>
  );
}
