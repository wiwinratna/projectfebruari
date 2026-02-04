import { Search, ArrowRight, Briefcase } from 'lucide-react';
import { motion } from 'motion/react';

export function JobOpenings() {
  return (
    <section id="jobs" className="relative py-24 bg-white">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {/* Header */}
        <motion.div
          initial={{ opacity: 0, y: 30 }}
          whileInView={{ opacity: 1, y: 0 }}
          viewport={{ once: true }}
          className="text-center mb-16"
        >
          <div className="inline-flex items-center gap-2 px-4 py-2 bg-red-100 rounded-full mb-6">
            <Briefcase className="text-red-600" size={20} />
            <span className="text-red-700 font-semibold">Career Opportunities</span>
          </div>

          <h2 className="text-4xl md:text-5xl font-bold text-gray-900 mb-6">
            Join the <span className="bg-linear-to-r from-red-600 to-purple-600 bg-clip-text text-transparent">Revolution</span> in<br />
            Sports Management
          </h2>
          <p className="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
            Be part of the future. Discover roles that challenge the status quo and push
            the boundaries of what's possible in the Olympic movement.
          </p>
        </motion.div>

        {/* No Openings State */}
        <motion.div
          initial={{ opacity: 0, scale: 0.9 }}
          whileInView={{ opacity: 1, scale: 1 }}
          viewport={{ once: true }}
          className="relative max-w-2xl mx-auto"
        >
          <div className="bg-linear-to-br from-gray-50 to-white border-2 border-gray-200 rounded-3xl p-12 shadow-xl text-center">
            {/* Icon */}
            <motion.div
              animate={{ scale: [1, 1.1, 1] }}
              transition={{ duration: 2, repeat: Infinity }}
              className="inline-flex p-6 bg-linear-to-br from-red-100 to-pink-100 rounded-full mb-6"
            >
              <Search className="text-red-600" size={48} />
            </motion.div>

            {/* Content */}
            <h3 className="text-2xl font-bold text-gray-900 mb-4">
              No Openings Currently
            </h3>
            <p className="text-gray-600 mb-8 leading-relaxed">
              We're expanding our horizons. Check back soon for new opportunities
              to join our dynamic team.
            </p>

            {/* Button */}
            <motion.button
              whileHover={{ scale: 1.05 }}
              whileTap={{ scale: 0.95 }}
              className="px-8 py-3 bg-red-600 text-white font-semibold rounded-xl hover:bg-red-700 transition-colors shadow-lg shadow-red-600/30"
            >
              View Archive
            </motion.button>

            {/* Decorative Elements */}
            <div className="absolute top-4 right-4 w-20 h-20 bg-linear-to-br from-red-200 to-pink-200 rounded-full blur-2xl opacity-50"></div>
            <div className="absolute bottom-4 left-4 w-20 h-20 bg-linear-to-br from-blue-200 to-purple-200 rounded-full blur-2xl opacity-50"></div>
          </div>
        </motion.div>

        {/* View All Link */}
        <motion.div
          initial={{ opacity: 0, y: 30 }}
          whileInView={{ opacity: 1, y: 0 }}
          viewport={{ once: true }}
          className="text-center mt-12"
        >
          <motion.a
            href="#"
            whileHover={{ x: 5 }}
            className="inline-flex items-center gap-2 text-red-600 font-semibold hover:text-red-700 transition-colors"
          >
            View All Opportunities
            <ArrowRight size={20} />
          </motion.a>
        </motion.div>

        {/* Career Categories */}
        <div className="grid grid-cols-2 md:grid-cols-4 gap-4 mt-16">
          {[
            { name: 'Event Coordination', count: 5 },
            { name: 'Sports Management', count: 8 },
            { name: 'Technical Support', count: 3 },
            { name: 'Administration', count: 4 },
          ].map((category, index) => (
            <motion.div
              key={category.name}
              initial={{ opacity: 0, y: 20 }}
              whileInView={{ opacity: 1, y: 0 }}
              viewport={{ once: true }}
              transition={{ delay: index * 0.1 }}
              whileHover={{ y: -5 }}
              className="bg-linear-to-br from-gray-50 to-white border border-gray-200 rounded-xl p-6 text-center cursor-pointer hover:shadow-lg transition-all"
            >
              <div className="text-3xl font-bold text-gray-900 mb-2">{category.count}</div>
              <div className="text-sm text-gray-600">{category.name}</div>
            </motion.div>
          ))}
        </div>
      </div>
    </section>
  );
}
