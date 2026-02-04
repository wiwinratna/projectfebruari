import { ImageWithFallback } from '../figma/ImageWithFallback';
import { motion } from 'motion/react';
import { ArrowRight, Calendar, Clock, Newspaper } from 'lucide-react';

const newsItems = [
  {
    id: 1,
    title: 'ARISE Launches New Worker Management System',
    excerpt: 'Revolutionary platform set to transform how we manage sports events workforce across Indonesia.',
    image: 'https://images.unsplash.com/photo-1585909695789-d998198243b7?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxuZXdzJTIwc3BvcnRzJTIwYW5ub3VuY2VtZW50fGVufDF8fHx8MTc2OTg0ODg3MXww&ixlib=rb-4.1.0&q=80&w=1080&utm_source=figma&utm_medium=referral',
    date: 'Jan 28, 2026',
    readTime: '5 min read',
    category: 'Platform Update',
    gradient: 'from-blue-500 to-cyan-500',
  },
  {
    id: 2,
    title: 'National Olympic Academy Partners with ARISE',
    excerpt: 'Strategic partnership aims to enhance professional development and career opportunities in sports.',
    image: 'https://images.unsplash.com/photo-1758691737584-a8f17fb34475?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxidXNpbmVzcyUyMHRlYW0lMjBzdWNjZXNzJTIwY2VsZWJyYXRpb258ZW58MXx8fHwxNzY5NzgwMDI2fDA&ixlib=rb-4.1.0&q=80&w=1080&utm_source=figma&utm_medium=referral',
    date: 'Jan 25, 2026',
    readTime: '4 min read',
    category: 'Partnership',
    gradient: 'from-purple-500 to-pink-500',
  },
  {
    id: 3,
    title: '500+ Professionals Join ARISE Community',
    excerpt: 'Milestone achievement as platform reaches half-thousand active sports workforce professionals.',
    image: 'https://images.unsplash.com/photo-1765302741884-e846c7a178df?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxhdGhsZXRlJTIwdHJhaW5pbmclMjBtb3RpdmF0aW9ufGVufDF8fHx8MTc2OTg0ODg3MXww&ixlib=rb-4.1.0&q=80&w=1080&utm_source=figma&utm_medium=referral',
    date: 'Jan 20, 2026',
    readTime: '3 min read',
    category: 'Milestone',
    gradient: 'from-green-500 to-emerald-500',
  },
];

export function News() {
  return (
    <section id="news" className="relative py-24 bg-white">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {/* Header */}
        <motion.div
          initial={{ opacity: 0, y: 30 }}
          whileInView={{ opacity: 1, y: 0 }}
          viewport={{ once: true }}
          className="text-center mb-16"
        >
          <div className="inline-flex items-center gap-2 px-4 py-2 bg-linear-to-r from-red-100 to-orange-100 rounded-full mb-6">
            <Newspaper className="text-red-600" size={20} />
            <span className="text-red-700 font-semibold">Latest News</span>
          </div>

          <h2 className="text-4xl md:text-5xl font-bold text-gray-900 mb-6">
            Stay Updated with
            <br />
            <span className="bg-linear-to-r from-red-600 to-orange-600 bg-clip-text text-transparent">
              ARISE News
            </span>
          </h2>
          <p className="text-xl text-gray-600 max-w-2xl mx-auto">
            Get the latest updates, announcements, and insights from the world of sports workforce management
          </p>
        </motion.div>

        {/* News Grid */}
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          {newsItems.map((news, index) => (
            <motion.article
              key={news.id}
              initial={{ opacity: 0, y: 30 }}
              whileInView={{ opacity: 1, y: 0 }}
              viewport={{ once: true }}
              transition={{ delay: index * 0.1 }}
              whileHover={{ y: -10 }}
              className="group cursor-pointer"
            >
              <div className="relative bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all">
                {/* Image */}
                <div className="relative h-56 overflow-hidden">
                  <ImageWithFallback
                    src={news.image}
                    alt={news.title}
                    className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                  />
                  <div className={`absolute inset-0 bg-linear-to-t ${news.gradient} opacity-20 group-hover:opacity-10 transition-opacity`}></div>

                  {/* Category Badge */}
                  <div className="absolute top-4 left-4">
                    <div className={`px-3 py-1 bg-linear-to-r ${news.gradient} rounded-full`}>
                      <span className="text-white text-sm font-semibold">{news.category}</span>
                    </div>
                  </div>
                </div>

                {/* Content */}
                <div className="p-6">
                  {/* Meta Info */}
                  <div className="flex items-center gap-4 mb-4 text-sm text-gray-500">
                    <div className="flex items-center gap-1">
                      <Calendar size={16} />
                      <span>{news.date}</span>
                    </div>
                    <div className="flex items-center gap-1">
                      <Clock size={16} />
                      <span>{news.readTime}</span>
                    </div>
                  </div>

                  {/* Title */}
                  <h3 className="text-xl font-bold text-gray-900 mb-3 group-hover:text-red-600 transition-colors line-clamp-2">
                    {news.title}
                  </h3>

                  {/* Excerpt */}
                  <p className="text-gray-600 mb-4 leading-relaxed line-clamp-3">
                    {news.excerpt}
                  </p>

                  {/* Read More Link */}
                  <motion.div
                    whileHover={{ x: 5 }}
                    className="inline-flex items-center gap-2 text-red-600 font-semibold group-hover:gap-3 transition-all"
                  >
                    Read More
                    <ArrowRight size={20} />
                  </motion.div>
                </div>

                {/* Decorative Gradient */}
                <div className={`absolute inset-0 bg-linear-to-t ${news.gradient} opacity-0 group-hover:opacity-5 transition-opacity pointer-events-none`}></div>
              </div>
            </motion.article>
          ))}
        </div>

        {/* View All Button */}
        <motion.div
          initial={{ opacity: 0, y: 30 }}
          whileInView={{ opacity: 1, y: 0 }}
          viewport={{ once: true }}
          className="text-center mt-12"
        >
          <motion.button
            whileHover={{ scale: 1.05 }}
            whileTap={{ scale: 0.95 }}
            className="px-10 py-4 bg-linear-to-r from-red-600 to-orange-600 text-white font-bold text-lg rounded-xl shadow-2xl shadow-red-600/30 hover:shadow-red-600/50 transition-all"
          >
            View All News
          </motion.button>
        </motion.div>
      </div>
    </section>
  );
}
