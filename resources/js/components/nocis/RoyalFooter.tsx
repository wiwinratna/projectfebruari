import { Facebook, Twitter, Instagram, Linkedin, Mail, Phone, MapPin } from 'lucide-react';
import React from 'react';
import { motion } from 'motion/react';

export function RoyalFooter() {
  const currentYear = new Date().getFullYear();

  return (
    <footer className="relative bg-linear-to-br from-gray-900 via-gray-800 to-gray-900 text-white">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-12">
          {/* Logo & Description */}
          <motion.div
            initial={{ opacity: 0, y: 30 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
          >
            <div className="flex items-center gap-2 mb-4">
              <span className="text-3xl font-bold text-blue-400">A</span>
              <span className="text-3xl font-bold text-yellow-400">R</span>
              <span className="text-3xl font-bold text-black">I</span>
              <span className="text-3xl font-bold text-green-400">S</span>
              <span className="text-3xl font-bold text-red-500">E</span>
            </div>
            <p className="text-gray-400 mb-6 leading-relaxed">
              Revolutionizing sports workforce management through innovative technology and dedicated service.
            </p>
            <div className="flex gap-3">
              {[
                { icon: Facebook, color: 'hover:text-blue-400' },
                { icon: Twitter, color: 'hover:text-sky-400' },
                { icon: Instagram, color: 'hover:text-pink-400' },
                { icon: Linkedin, color: 'hover:text-blue-500' },
              ].map((social, index) => (
                <motion.a
                  key={index}
                  href="#"
                  whileHover={{ scale: 1.2 }}
                  whileTap={{ scale: 0.9 }}
                  className={`p-2 bg-white/10 rounded-lg ${social.color} transition-colors`}
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
            <h3 className="text-lg font-bold mb-4">Quick Links</h3>
            <ul className="space-y-3">
              {['Job Openings', 'About ARISE', 'Our Partners', 'Events', 'Contact Us'].map((item) => (
                <li key={item}>
                  <motion.a
                    href="#"
                    whileHover={{ x: 5 }}
                    className="text-gray-400 hover:text-white transition-all flex items-center gap-2"
                  >
                    <span className="w-1 h-1 rounded-full bg-red-500"></span>
                    {item}
                  </motion.a>
                </li>
              ))}
            </ul>
          </motion.div>

          {/* Resources */}
          <motion.div
            initial={{ opacity: 0, y: 30 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            transition={{ delay: 0.2 }}
          >
            <h3 className="text-lg font-bold mb-4">Resources</h3>
            <ul className="space-y-3">
              {['FAQ & Help', 'Documentation', 'Privacy Policy', 'Terms of Service', 'Career Archive'].map((item) => (
                <li key={item}>
                  <motion.a
                    href="#"
                    whileHover={{ x: 5 }}
                    className="text-gray-400 hover:text-white transition-all flex items-center gap-2"
                  >
                    <span className="w-1 h-1 rounded-full bg-blue-500"></span>
                    {item}
                  </motion.a>
                </li>
              ))}
            </ul>
          </motion.div>

          {/* Connect With Us */}
          <motion.div
            initial={{ opacity: 0, y: 30 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            transition={{ delay: 0.3 }}
          >
            <h3 className="text-lg font-bold mb-4">Connect With Us</h3>
            <ul className="space-y-4">
              <li className="flex items-start gap-3 text-gray-400">
                <MapPin size={20} className="text-red-500 mt-1 shrink-0" />
                <span>Jakarta, Indonesia</span>
              </li>
              <li className="flex items-center gap-3 text-gray-400">
                <Phone size={20} className="text-green-500 shrink-0" />
                <span>+62 21 1234 5678</span>
              </li>
              <li className="flex items-center gap-3 text-gray-400">
                <Mail size={20} className="text-blue-500 shrink-0" />
                <span>info@arise.id</span>
              </li>
            </ul>
          </motion.div>
        </div>

        {/* Bottom Bar */}
        <motion.div
          initial={{ opacity: 0 }}
          whileInView={{ opacity: 1 }}
          viewport={{ once: true }}
          className="pt-8 border-t border-gray-700"
        >
          <div className="flex flex-col md:flex-row justify-between items-center gap-4">
            <p className="text-gray-400 text-sm">
              © {currentYear} ARISE - National Olympic Academy of Indonesia System. All rights reserved.
            </p>
            <div className="flex gap-6 text-sm">
              <motion.a
                href="#"
                whileHover={{ scale: 1.05 }}
                className="text-gray-400 hover:text-white transition-colors"
              >
                Privacy
              </motion.a>
              <motion.a
                href="#"
                whileHover={{ scale: 1.05 }}
                className="text-gray-400 hover:text-white transition-colors"
              >
                Terms
              </motion.a>
              <motion.a
                href="#"
                whileHover={{ scale: 1.05 }}
                className="text-gray-400 hover:text-white transition-colors"
              >
                Cookies
              </motion.a>
            </div>
          </div>
        </motion.div>
      </div>
    </footer>
  );
}
