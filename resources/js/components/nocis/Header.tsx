import { useState } from 'react';
import { Menu, X } from 'lucide-react';
import { motion, AnimatePresence } from 'motion/react';

export function Header() {
  const [isMenuOpen, setIsMenuOpen] = useState(false);

  return (
    <motion.header
      initial={{ y: -100 }}
      animate={{ y: 0 }}
      className="fixed top-0 left-0 right-0 z-50 bg-linear-to-r from-emerald-600 via-blue-700 to-purple-700 shadow-xl"
    >
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="flex items-center justify-between h-20">
          {/* Logo */}
          <motion.div
            whileHover={{ scale: 1.05 }}
            className="flex items-center gap-2"
          >
            <div className="flex gap-1">
              <div className="w-2 h-2 rounded-full bg-blue-400"></div>
              <div className="w-2 h-2 rounded-full bg-yellow-400"></div>
              <div className="w-2 h-2 rounded-full bg-red-400"></div>
              <div className="w-2 h-2 rounded-full bg-green-400"></div>
            </div>
            <span className="text-2xl font-bold text-white">ARISE</span>
          </motion.div>

          {/* Desktop Navigation */}
          <nav className="hidden md:flex items-center gap-8">
            <motion.a
              href="#jobs"
              whileHover={{ scale: 1.05 }}
              className="text-white/90 hover:text-white transition-colors font-medium"
            >
              Jobs
            </motion.a>
            <motion.a
              href="#about"
              whileHover={{ scale: 1.05 }}
              className="text-white/90 hover:text-white transition-colors font-medium"
            >
              About
            </motion.a>
            <motion.a
              href="#flow"
              whileHover={{ scale: 1.05 }}
              className="text-white/90 hover:text-white transition-colors font-medium"
            >
              Flow
            </motion.a>
            <motion.a
              href="#features"
              whileHover={{ scale: 1.05 }}
              className="text-white/90 hover:text-white transition-colors font-medium"
            >
              Features
            </motion.a>
            <motion.a
              href="#news"
              whileHover={{ scale: 1.05 }}
              className="text-white/90 hover:text-white transition-colors font-medium"
            >
              News
            </motion.a>
          </nav>

          {/* Desktop Auth Buttons */}
          <div className="hidden md:flex items-center gap-4">
            <motion.button
              whileHover={{ scale: 1.05 }}
              whileTap={{ scale: 0.95 }}
              className="px-6 py-2 text-white font-semibold transition-colors hover:text-white/80"
            >
              Log In
            </motion.button>
            <motion.button
              whileHover={{ scale: 1.05 }}
              whileTap={{ scale: 0.95 }}
              className="px-6 py-2 bg-red-600 text-white font-semibold rounded-lg shadow-lg hover:bg-red-700 transition-colors"
            >
              Sign Up
            </motion.button>
          </div>

          {/* Mobile Menu Button */}
          <button
            onClick={() => setIsMenuOpen(!isMenuOpen)}
            className="md:hidden p-2 text-gray-700"
          >
            {isMenuOpen ? <X size={24} /> : <Menu size={24} />}
          </button>
        </div>

        {/* Mobile Menu */}
        <AnimatePresence>
          {isMenuOpen && (
            <motion.div
              initial={{ opacity: 0, height: 0 }}
              animate={{ opacity: 1, height: 'auto' }}
              exit={{ opacity: 0, height: 0 }}
              className="md:hidden overflow-hidden bg-linear-to-br from-emerald-700 via-blue-800 to-purple-800"
            >
              <nav className="py-4 space-y-3">
                <a href="#jobs" className="block py-2 text-white/90 hover:text-white transition-colors">
                  Jobs
                </a>
                <a href="#about" className="block py-2 text-white/90 hover:text-white transition-colors">
                  About
                </a>
                <a href="#flow" className="block py-2 text-white/90 hover:text-white transition-colors">
                  Flow
                </a>
                <a href="#features" className="block py-2 text-white/90 hover:text-white transition-colors">
                  Features
                </a>
                <a href="#news" className="block py-2 text-white/90 hover:text-white transition-colors">
                  News
                </a>
                <div className="flex flex-col gap-2 pt-4">
                  <button className="py-2 text-white font-semibold">Log In</button>
                  <button className="py-2 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition-colors">
                    Sign Up
                  </button>
                </div>
              </nav>
            </motion.div>
          )}
        </AnimatePresence>
      </div>
    </motion.header>
  );
}
