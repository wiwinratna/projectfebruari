import { motion } from 'motion/react';
import { Building2 } from 'lucide-react';

const partners = [
  { name: 'Olympic Committee', initial: 'OC' },
  { name: 'Sports Ministry', initial: 'SM' },
  { name: 'National Sports Federation', initial: 'NSF' },
  { name: 'Asian Games Committee', initial: 'AGC' },
  { name: 'Youth Sports Foundation', initial: 'YSF' },
  { name: 'Elite Training Center', initial: 'ETC' },
];

export function Partners() {
  return (
    <section className="relative py-24 bg-linear-to-br from-gray-50 to-white">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {/* Header */}
        <motion.div
          initial={{ opacity: 0, y: 30 }}
          whileInView={{ opacity: 1, y: 0 }}
          viewport={{ once: true }}
          className="text-center mb-16"
        >
          <div className="inline-flex items-center gap-2 px-4 py-2 bg-blue-100 rounded-full mb-6">
            <Building2 className="text-blue-600" size={20} />
            <span className="text-blue-700 font-semibold">Trusted Partners</span>
          </div>

          <h2 className="text-4xl md:text-5xl font-bold text-gray-900 mb-6">
            OUR PARTNERS
          </h2>
          <p className="text-xl text-gray-600 max-w-2xl mx-auto">
            Collaborating with leading organizations to advance sports excellence in Indonesia
          </p>
        </motion.div>

        {/* Partners Grid */}
        <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6">
          {partners.map((partner, index) => (
            <motion.div
              key={partner.name}
              initial={{ opacity: 0, scale: 0.8 }}
              whileInView={{ opacity: 1, scale: 1 }}
              viewport={{ once: true }}
              transition={{ delay: index * 0.1 }}
              whileHover={{ scale: 1.05, y: -5 }}
              className="group"
            >
              <div className="relative bg-white border-2 border-gray-200 rounded-2xl p-8 hover:border-red-600 transition-all shadow-lg hover:shadow-2xl aspect-square flex items-center justify-center overflow-hidden">
                {/* Background Gradient on Hover */}
                <div className="absolute inset-0 bg-linear-to-br from-red-50 to-pink-50 opacity-0 group-hover:opacity-100 transition-opacity"></div>

                {/* Initial */}
                <div className="relative text-center">
                  <div className="text-4xl font-bold bg-linear-to-br from-red-600 to-purple-600 bg-clip-text text-transparent mb-2">
                    {partner.initial}
                  </div>
                  <div className="text-xs text-gray-600 group-hover:text-gray-900 transition-colors">
                    {partner.name}
                  </div>
                </div>

                {/* Decorative Circle */}
                <motion.div
                  className="absolute -bottom-8 -right-8 w-24 h-24 bg-linear-to-br from-red-600 to-pink-600 rounded-full opacity-5 blur-xl group-hover:opacity-20 transition-opacity"
                  animate={{ scale: [1, 1.2, 1] }}
                  transition={{ duration: 3, repeat: Infinity }}
                ></motion.div>
              </div>
            </motion.div>
          ))}
        </div>

        {/* CTA */}
        <motion.div
          initial={{ opacity: 0, y: 30 }}
          whileInView={{ opacity: 1, y: 0 }}
          viewport={{ once: true }}
          className="text-center mt-16"
        >
          <p className="text-gray-600 mb-6">Interested in partnering with us?</p>
          <motion.button
            whileHover={{ scale: 1.05 }}
            whileTap={{ scale: 0.95 }}
            className="px-8 py-3 bg-linear-to-r from-red-600 to-pink-600 text-white font-semibold rounded-xl hover:shadow-2xl shadow-lg shadow-red-600/30 transition-all"
          >
            Become a Partner
          </motion.button>
        </motion.div>
      </div>
    </section>
  );
}
