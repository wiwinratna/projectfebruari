import { Facebook, Twitter, Instagram, Youtube, Mail, MapPin, Phone } from 'lucide-react';
import { motion } from 'motion/react';

export function Footer() {
  const currentYear = new Date().getFullYear();

  return (
    <footer className="relative bg-linear-to-br from-slate-950 via-slate-900 to-slate-950 border-t border-white/10">
      {/* Background Decoration */}
      <div className="absolute inset-0 overflow-hidden opacity-30">
        <div className="absolute top-0 left-1/4 w-96 h-96 bg-blue-500/10 rounded-full blur-3xl"></div>
        <div className="absolute bottom-0 right-1/4 w-96 h-96 bg-purple-500/10 rounded-full blur-3xl"></div>
      </div>

      <div className="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-12">
          {/* Logo and Description */}
          <motion.div
            initial={{ opacity: 0, y: 30 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            className="lg:col-span-2"
          >
            <div className="flex items-center gap-3 mb-6">
              <div className="flex gap-1">
                <div className="w-10 h-10 rounded-full border-4 border-blue-500 shadow-lg shadow-blue-500/50"></div>
                <div className="w-10 h-10 rounded-full border-4 border-yellow-400 -ml-4 shadow-lg shadow-yellow-400/50"></div>
                <div className="w-10 h-10 rounded-full border-4 border-red-500 -ml-4 shadow-lg shadow-red-500/50"></div>
              </div>
              <span className="text-2xl font-bold bg-linear-to-r from-blue-400 via-purple-400 to-pink-400 bg-clip-text text-transparent">
                Olympics 2026
              </span>
            </div>

            <p className="text-gray-400 mb-6 leading-relaxed max-w-md">
              Experience the world's greatest sporting event. Celebrating excellence, unity, and the
              indomitable human spirit through winter sports competition.
            </p>

            {/* Social Media */}
            <div className="flex gap-4">
              {[
                { icon: Facebook, color: 'hover:text-blue-400' },
                { icon: Twitter, color: 'hover:text-sky-400' },
                { icon: Instagram, color: 'hover:text-pink-400' },
                { icon: Youtube, color: 'hover:text-red-500' },
              ].map((social, index) => (
                <motion.a
                  key={index}
                  href="#"
                  whileHover={{ scale: 1.2, rotate: 5 }}
                  whileTap={{ scale: 0.9 }}
                  className={`p-3 bg-white/5 backdrop-blur-lg rounded-xl border border-white/10 text-gray-400 ${social.color} transition-all hover:border-white/30`}
                >
                  <social.icon size={20} />
                </motion.a>
              ))}
            </div>
          </motion.div>

          {/* Quick Links */}
          <motion.div
            initial={{ opacity: 0, y: 30 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            transition={{ delay: 0.1 }}
          >
            <h3 className="text-lg font-bold text-white mb-6">Quick Links</h3>
            <ul className="space-y-3">
              {['About Olympics', 'Events Schedule', 'Athletes Directory', 'Medal Standings', 'News & Updates', 'Venues'].map((item) => (
                <li key={item}>
                  <motion.a
                    href="#"
                    whileHover={{ x: 5 }}
                    className="text-gray-400 hover:text-white transition-all flex items-center gap-2"
                  >
                    <span className="w-1 h-1 rounded-full bg-blue-400"></span>
                    {item}
                  </motion.a>
                </li>
              ))}
            </ul>
          </motion.div>

          {/* Contact Info */}
          <motion.div
            initial={{ opacity: 0, y: 30 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            transition={{ delay: 0.2 }}
          >
            <h3 className="text-lg font-bold text-white mb-6">Contact</h3>
            <ul className="space-y-4">
              <li className="flex items-start gap-3 text-gray-400">
                <MapPin size={20} className="text-blue-400 mt-1 shrink-0" />
                <span>Milan-Cortina, Italy</span>
              </li>
              <li className="flex items-center gap-3 text-gray-400">
                <Phone size={20} className="text-purple-400 shrink-0" />
                <span>+39 02 1234 5678</span>
              </li>
              <li className="flex items-center gap-3 text-gray-400">
                <Mail size={20} className="text-pink-400 shrink-0" />
                <span>info@olympics2026.com</span>
              </li>
            </ul>

            {/* Newsletter */}
            <div className="mt-6">
              <div className="flex gap-2">
                <input
                  type="email"
                  placeholder="Newsletter"
                  className="flex-1 px-4 py-2 bg-white/5 backdrop-blur-lg border border-white/10 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:border-blue-400 transition-all"
                />
                <motion.button
                  whileHover={{ scale: 1.05 }}
                  whileTap={{ scale: 0.95 }}
                  className="px-4 py-2 bg-linear-to-r from-blue-600 to-purple-600 rounded-lg text-white font-semibold shadow-lg"
                >
                  <Mail size={20} />
                </motion.button>
              </div>
            </div>
          </motion.div>
        </div>

        {/* Bottom Bar */}
        <motion.div
          initial={{ opacity: 0 }}
          whileInView={{ opacity: 1 }}
          viewport={{ once: true }}
          className="pt-8 border-t border-white/10"
        >
          <div className="flex flex-col md:flex-row justify-between items-center gap-4">
            <p className="text-gray-500 text-sm">
              © {currentYear} Olympics 2026. All rights reserved.
            </p>
            <div className="flex gap-6 text-sm">
              <motion.a
                href="#"
                whileHover={{ scale: 1.05 }}
                className="text-gray-500 hover:text-white transition-colors"
              >
                Privacy Policy
              </motion.a>
              <motion.a
                href="#"
                whileHover={{ scale: 1.05 }}
                className="text-gray-500 hover:text-white transition-colors"
              >
                Terms of Service
              </motion.a>
              <motion.a
                href="#"
                whileHover={{ scale: 1.05 }}
                className="text-gray-500 hover:text-white transition-colors"
              >
                Cookie Policy
              </motion.a>
            </div>
          </div>
        </motion.div>
      </div>
    </footer>
  );
}
