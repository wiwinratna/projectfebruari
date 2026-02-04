import { ImageWithFallback } from './figma/ImageWithFallback';
import { Play, Eye, Clock } from 'lucide-react';
import { motion } from 'motion/react';

const highlights = [
  {
    id: 1,
    title: 'Record Breaking 100m Sprint',
    duration: '2:34',
    views: '12.5M',
    image: 'https://images.unsplash.com/photo-1619018078044-41db8cd03eff?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxhdGhsZXRlJTIwcnVubmluZyUyMHRyYWNrJTIwc3VucmlzZXxlbnwxfHx8fDE3Njk4NDc5NzN8MA&ixlib=rb-4.1.0&q=80&w=1080&utm_source=figma&utm_medium=referral',
    category: 'Track & Field',
  },
  {
    id: 2,
    title: 'Perfect 10 in Gymnastics',
    duration: '3:12',
    views: '18.2M',
    image: 'https://images.unsplash.com/photo-1505619730259-b1288d154955?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxneW1uYXN0aWNzJTIwY29tcGV0aXRpb258ZW58MXx8fHwxNzY5ODQ3ODExfDA&ixlib=rb-4.1.0&q=80&w=1080&utm_source=figma&utm_medium=referral',
    category: 'Gymnastics',
  },
  {
    id: 3,
    title: 'Gold Medal Swimming Finale',
    duration: '4:45',
    views: '15.8M',
    image: 'https://images.unsplash.com/photo-1695326288959-89be49070059?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxkaXZpbmclMjBvbHltcGljJTIwcG9vbCUyMGFlcmlhbHxlbnwxfHx8fDE3Njk4NDc5NzN8MA&ixlib=rb-4.1.0&q=80&w=1080&utm_source=figma&utm_medium=referral',
    category: 'Swimming',
  },
];

export function Highlights() {
  return (
    <section id="highlights" className="relative py-32 bg-linear-to-br from-slate-900 via-slate-950 to-slate-900">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <motion.div
          initial={{ opacity: 0, y: 30 }}
          whileInView={{ opacity: 1, y: 0 }}
          viewport={{ once: true }}
          className="text-center mb-16"
        >
          <motion.div
            initial={{ scale: 0 }}
            whileInView={{ scale: 1 }}
            viewport={{ once: true }}
            className="inline-flex items-center gap-2 px-4 py-2 bg-linear-to-r from-red-500/20 to-orange-500/20 rounded-full border border-red-400/30 mb-6 backdrop-blur-sm"
          >
            <Play className="text-red-400" size={20} />
            <span className="text-red-300">Video Highlights</span>
          </motion.div>

          <h2 className="text-5xl md:text-6xl font-bold mb-6">
            <span className="bg-linear-to-r from-red-400 via-orange-400 to-yellow-400 bg-clip-text text-transparent">
              Unforgettable
            </span>
            <br />
            <span className="text-white">Moments</span>
          </h2>
          <p className="text-xl text-gray-400 max-w-2xl mx-auto">
            Relive the most spectacular performances and historic achievements
          </p>
        </motion.div>

        <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
          {highlights.map((highlight, index) => (
            <motion.div
              key={highlight.id}
              initial={{ opacity: 0, y: 30 }}
              whileInView={{ opacity: 1, y: 0 }}
              viewport={{ once: true }}
              transition={{ delay: index * 0.1 }}
              whileHover={{ y: -10 }}
              className="group relative cursor-pointer"
            >
              <div className="relative bg-linear-to-br from-white/5 to-white/10 backdrop-blur-xl rounded-3xl overflow-hidden border border-white/10 shadow-2xl">
                {/* Thumbnail */}
                <div className="relative h-64 overflow-hidden">
                  <ImageWithFallback
                    src={highlight.image}
                    alt={highlight.title}
                    className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                  />

                  {/* Dark Overlay */}
                  <div className="absolute inset-0 bg-linear-to-t from-black via-black/50 to-transparent"></div>

                  {/* Play Button */}
                  <motion.div
                    whileHover={{ scale: 1.2 }}
                    className="absolute inset-0 flex items-center justify-center"
                  >
                    <div className="w-16 h-16 rounded-full bg-white/20 backdrop-blur-lg border-2 border-white flex items-center justify-center group-hover:bg-red-500 transition-all shadow-2xl">
                      <Play className="text-white fill-white ml-1" size={28} />
                    </div>
                  </motion.div>

                  {/* Category Badge */}
                  <div className="absolute top-4 left-4 px-3 py-1 bg-black/60 backdrop-blur-lg rounded-full border border-white/20">
                    <span className="text-white text-sm font-semibold">{highlight.category}</span>
                  </div>
                </div>

                {/* Info */}
                <div className="p-6">
                  <h3 className="text-xl font-bold text-white mb-4 group-hover:text-red-400 transition-colors">
                    {highlight.title}
                  </h3>

                  <div className="flex items-center justify-between text-gray-400">
                    <div className="flex items-center gap-2">
                      <Clock size={16} />
                      <span className="text-sm">{highlight.duration}</span>
                    </div>
                    <div className="flex items-center gap-2">
                      <Eye size={16} />
                      <span className="text-sm">{highlight.views}</span>
                    </div>
                  </div>
                </div>

                {/* Hover Glow */}
                <div className="absolute inset-0 bg-linear-to-t from-red-500/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"></div>
              </div>
            </motion.div>
          ))}
        </div>

        {/* Call to Action */}
        <motion.div
          initial={{ opacity: 0, y: 30 }}
          whileInView={{ opacity: 1, y: 0 }}
          viewport={{ once: true }}
          className="mt-16 text-center"
        >
          <motion.button
            whileHover={{ scale: 1.05 }}
            whileTap={{ scale: 0.95 }}
            className="px-10 py-4 bg-linear-to-r from-red-600 to-orange-600 rounded-full text-white font-bold text-lg shadow-2xl shadow-red-500/50 hover:shadow-red-500/70 transition-all"
          >
            Watch All Highlights
          </motion.button>
        </motion.div>
      </div>
    </section>
  );
}
