import { ImageWithFallback } from './figma/ImageWithFallback';
import { Award, Medal, Star, Trophy } from 'lucide-react';
import { motion } from 'motion/react';

const athletes = [
  {
    id: 1,
    name: 'Simone Biles',
    country: 'USA',
    sport: 'Gymnastics',
    medals: { gold: 4, silver: 2, bronze: 1 },
    total: 7,
    image: 'https://images.unsplash.com/photo-1505619730259-b1288d154955?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxneW1uYXN0aWNzJTIwY29tcGV0aXRpb258ZW58MXx8fHwxNzY5ODQ3ODExfDA&ixlib=rb-4.1.0&q=80&w=1080&utm_source=figma&utm_medium=referral',
    flag: '🇺🇸',
    gradient: 'from-purple-500 to-pink-500',
  },
  {
    id: 2,
    name: 'Caeleb Dressel',
    country: 'USA',
    sport: 'Swimming',
    medals: { gold: 5, silver: 0, bronze: 0 },
    total: 5,
    image: 'https://images.unsplash.com/photo-1695326288959-89be49070059?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxkaXZpbmclMjBvbHltcGljJTIwcG9vbCUyMGFlcmlhbHxlbnwxfHx8fDE3Njk4NDc5NzN8MA&ixlib=rb-4.1.0&q=80&w=1080&utm_source=figma&utm_medium=referral',
    flag: '🇺🇸',
    gradient: 'from-blue-500 to-cyan-500',
  },
  {
    id: 3,
    name: 'Elaine Thompson',
    country: 'Jamaica',
    sport: 'Track & Field',
    medals: { gold: 5, silver: 0, bronze: 0 },
    total: 5,
    image: 'https://images.unsplash.com/photo-1619018078044-41db8cd03eff?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxhdGhsZXRlJTIwcnVubmluZyUyMHRyYWNrJTIwc3VucmlzZXxlbnwxfHx8fDE3Njk4NDc5NzN8MA&ixlib=rb-4.1.0&q=80&w=1080&utm_source=figma&utm_medium=referral',
    flag: '🇯🇲',
    gradient: 'from-green-500 to-yellow-500',
  },
  {
    id: 4,
    name: 'Naomi Osaka',
    country: 'Japan',
    sport: 'Tennis',
    medals: { gold: 1, silver: 1, bronze: 1 },
    total: 3,
    image: 'https://images.unsplash.com/photo-1644199288616-100cc0536263?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHx0ZW5uaXMlMjBwbGF5ZXIlMjBhY3Rpb24lMjBvbHltcGljfGVufDF8fHx8MTc2OTg0Nzk3NHww&ixlib=rb-4.1.0&q=80&w=1080&utm_source=figma&utm_medium=referral',
    flag: '🇯🇵',
    gradient: 'from-red-500 to-orange-500',
  },
];

export function Athletes() {
  return (
    <section id="athletes" className="relative py-32 bg-slate-950">
      {/* Background Decoration */}
      <div className="absolute inset-0 overflow-hidden">
        <div className="absolute top-1/3 right-0 w-96 h-96 bg-purple-500/10 rounded-full blur-3xl"></div>
        <div className="absolute bottom-1/3 left-0 w-96 h-96 bg-pink-500/10 rounded-full blur-3xl"></div>
      </div>

      <div className="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
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
            className="inline-flex items-center gap-2 px-4 py-2 bg-linear-to-r from-purple-500/20 to-pink-500/20 rounded-full border border-purple-400/30 mb-6 backdrop-blur-sm"
          >
            <Star className="text-yellow-400" size={20} />
            <span className="text-purple-300">Hall of Champions</span>
          </motion.div>

          <h2 className="text-5xl md:text-6xl font-bold mb-6">
            <span className="bg-linear-to-r from-purple-400 via-pink-400 to-red-400 bg-clip-text text-transparent">
              Elite Athletes
            </span>
          </h2>
          <p className="text-xl text-gray-400 max-w-2xl mx-auto">
            Meet the extraordinary individuals pushing the boundaries of human achievement
          </p>
        </motion.div>

        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
          {athletes.map((athlete, index) => (
            <motion.div
              key={athlete.id}
              initial={{ opacity: 0, y: 30 }}
              whileInView={{ opacity: 1, y: 0 }}
              viewport={{ once: true }}
              transition={{ delay: index * 0.1 }}
              whileHover={{ y: -15, scale: 1.02 }}
              className="group relative"
            >
              <div className="relative bg-linear-to-br from-white/5 to-white/10 backdrop-blur-xl rounded-3xl overflow-hidden border border-white/10 shadow-2xl">
                {/* Image Container */}
                <div className="relative h-96 overflow-hidden">
                  <ImageWithFallback
                    src={athlete.image}
                    alt={athlete.name}
                    className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                  />

                  {/* Gradient Overlay */}
                  <div className={`absolute inset-0 bg-linear-to-t ${athlete.gradient} opacity-40 group-hover:opacity-20 transition-opacity`}></div>
                  <div className="absolute inset-0 bg-linear-to-t from-black via-black/50 to-transparent"></div>

                  {/* Top Badge - Country */}
                  <motion.div
                    initial={{ y: -50, opacity: 0 }}
                    whileInView={{ y: 0, opacity: 1 }}
                    transition={{ delay: 0.2 }}
                    className="absolute top-4 left-4 flex items-center gap-2 px-3 py-2 bg-black/50 backdrop-blur-lg rounded-full border border-white/20"
                  >
                    <span className="text-2xl">{athlete.flag}</span>
                    <span className="text-white text-sm font-semibold">{athlete.country}</span>
                  </motion.div>

                  {/* Star Badge */}
                  <motion.div
                    initial={{ scale: 0 }}
                    whileInView={{ scale: 1 }}
                    whileHover={{ rotate: 360 }}
                    transition={{ delay: 0.3 }}
                    className="absolute top-4 right-4 w-12 h-12 bg-yellow-400 rounded-full flex items-center justify-center shadow-lg shadow-yellow-400/50"
                  >
                    <Star className="text-white fill-white" size={24} />
                  </motion.div>

                  {/* Bottom Content */}
                  <div className="absolute bottom-0 left-0 right-0 p-6">
                    <h3 className="text-2xl font-bold text-white mb-1">{athlete.name}</h3>
                    <p className="text-gray-300 mb-4">{athlete.sport}</p>

                    {/* Medals Display */}
                    <div className="flex items-center gap-2 mb-3">
                      {athlete.medals.gold > 0 && (
                        <div className="flex items-center gap-1 px-2 py-1 bg-linear-to-r from-yellow-400 to-yellow-600 rounded-lg">
                          <Award size={16} className="text-white" />
                          <span className="text-white font-bold text-sm">{athlete.medals.gold}</span>
                        </div>
                      )}
                      {athlete.medals.silver > 0 && (
                        <div className="flex items-center gap-1 px-2 py-1 bg-linear-to-r from-gray-300 to-gray-500 rounded-lg">
                          <Medal size={16} className="text-white" />
                          <span className="text-white font-bold text-sm">{athlete.medals.silver}</span>
                        </div>
                      )}
                      {athlete.medals.bronze > 0 && (
                        <div className="flex items-center gap-1 px-2 py-1 bg-linear-to-r from-orange-400 to-orange-600 rounded-lg">
                          <Medal size={16} className="text-white" />
                          <span className="text-white font-bold text-sm">{athlete.medals.bronze}</span>
                        </div>
                      )}
                    </div>

                    {/* Total Medals */}
                    <motion.div
                      whileHover={{ scale: 1.05 }}
                      className={`inline-flex items-center gap-2 px-4 py-2 bg-linear-to-r ${athlete.gradient} rounded-full shadow-lg`}
                    >
                      <Trophy className="text-white" size={20} />
                      <span className="text-white font-bold">{athlete.total} Total Medals</span>
                    </motion.div>
                  </div>
                </div>

                {/* Glow Effect */}
                <div className={`absolute inset-0 bg-linear-to-t ${athlete.gradient} opacity-0 group-hover:opacity-30 transition-opacity duration-500 pointer-events-none`}></div>
              </div>
            </motion.div>
          ))}
        </div>

        {/* View All Button */}
        <motion.div
          initial={{ opacity: 0, y: 30 }}
          whileInView={{ opacity: 1, y: 0 }}
          viewport={{ once: true }}
          className="mt-16 text-center"
        >
          <motion.button
            whileHover={{ scale: 1.05 }}
            whileTap={{ scale: 0.95 }}
            className="px-10 py-4 bg-linear-to-r from-purple-600 to-pink-600 rounded-full text-white font-bold text-lg shadow-2xl shadow-purple-500/50 hover:shadow-purple-500/70 transition-all"
          >
            Discover All Athletes
          </motion.button>
        </motion.div>
      </div>
    </section>
  );
}
