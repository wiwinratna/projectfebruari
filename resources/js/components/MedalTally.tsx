import { Trophy, TrendingUp, Award } from 'lucide-react';
import { motion } from 'motion/react';

const medalData = [
  { rank: 1, country: 'United States', gold: 39, silver: 41, bronze: 33, total: 113, flag: '🇺🇸', trend: 'up' },
  { rank: 2, country: 'China', gold: 38, silver: 32, bronze: 18, total: 88, flag: '🇨🇳', trend: 'up' },
  { rank: 3, country: 'Japan', gold: 27, silver: 14, bronze: 17, total: 58, flag: '🇯🇵', trend: 'same' },
  { rank: 4, country: 'Great Britain', gold: 22, silver: 21, bronze: 22, total: 65, flag: '🇬🇧', trend: 'up' },
  { rank: 5, country: 'ROC', gold: 20, silver: 28, bronze: 23, total: 71, flag: '🇷🇺', trend: 'down' },
  { rank: 6, country: 'Australia', gold: 17, silver: 7, bronze: 22, total: 46, flag: '🇦🇺', trend: 'up' },
  { rank: 7, country: 'Netherlands', gold: 10, silver: 12, bronze: 14, total: 36, flag: '🇳🇱', trend: 'up' },
  { rank: 8, country: 'France', gold: 10, silver: 12, bronze: 11, total: 33, flag: '🇫🇷', trend: 'same' },
];

export function MedalTally() {
  return (
    <section id="medals" className="relative py-32 bg-linear-to-br from-slate-900 via-slate-950 to-slate-900">
      {/* Decorative Elements */}
      <div className="absolute inset-0 overflow-hidden">
        <div className="absolute top-0 left-1/3 w-96 h-96 bg-yellow-500/5 rounded-full blur-3xl"></div>
        <div className="absolute bottom-0 right-1/3 w-96 h-96 bg-blue-500/5 rounded-full blur-3xl"></div>
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
            className="inline-flex items-center gap-3 mb-6"
          >
            <Trophy className="text-yellow-400" size={48} />
            <Award className="text-gray-400" size={48} />
            <Trophy className="text-orange-400" size={48} />
          </motion.div>

          <h2 className="text-5xl md:text-6xl font-bold mb-6">
            <span className="bg-linear-to-r from-yellow-400 via-orange-400 to-red-400 bg-clip-text text-transparent">
              Medal Standings
            </span>
          </h2>
          <p className="text-xl text-gray-400 max-w-2xl mx-auto">
            Track the top-performing nations in real-time
          </p>
        </motion.div>

        <motion.div
          initial={{ opacity: 0, scale: 0.95 }}
          whileInView={{ opacity: 1, scale: 1 }}
          viewport={{ once: true }}
          className="relative bg-linear-to-br from-white/5 to-white/10 backdrop-blur-2xl rounded-3xl overflow-hidden border border-white/10 shadow-2xl"
        >
          {/* Header */}
          <div className="bg-linear-to-r from-yellow-500/20 via-orange-500/20 to-red-500/20 backdrop-blur-xl border-b border-white/10">
            <div className="px-6 py-6">
              <div className="grid grid-cols-12 gap-4 items-center font-semibold text-gray-300">
                <div className="col-span-1 text-center">Rank</div>
                <div className="col-span-5 md:col-span-4">Nation</div>
                <div className="col-span-2 text-center">
                  <div className="flex flex-col items-center gap-1">
                    <div className="w-8 h-8 rounded-full bg-linear-to-br from-yellow-400 to-yellow-600 shadow-lg"></div>
                    <span className="text-xs hidden md:block">Gold</span>
                  </div>
                </div>
                <div className="col-span-2 text-center">
                  <div className="flex flex-col items-center gap-1">
                    <div className="w-8 h-8 rounded-full bg-linear-to-br from-gray-300 to-gray-500 shadow-lg"></div>
                    <span className="text-xs hidden md:block">Silver</span>
                  </div>
                </div>
                <div className="col-span-2 text-center">
                  <div className="flex flex-col items-center gap-1">
                    <div className="w-8 h-8 rounded-full bg-linear-to-br from-orange-400 to-orange-600 shadow-lg"></div>
                    <span className="text-xs hidden md:block">Bronze</span>
                  </div>
                </div>
                <div className="hidden md:block md:col-span-1 text-center">Total</div>
              </div>
            </div>
          </div>

          {/* Rows */}
          <div>
            {medalData.map((country, index) => (
              <motion.div
                key={country.rank}
                initial={{ opacity: 0, x: -30 }}
                whileInView={{ opacity: 1, x: 0 }}
                viewport={{ once: true }}
                transition={{ delay: index * 0.05 }}
                whileHover={{ scale: 1.02, backgroundColor: 'rgba(255, 255, 255, 0.05)' }}
                className={`px-6 py-5 border-b border-white/5 transition-all ${
                  index < 3 ? 'bg-linear-to-r from-yellow-500/10 to-transparent' : ''
                }`}
              >
                <div className="grid grid-cols-12 gap-4 items-center">
                  {/* Rank */}
                  <div className="col-span-1 text-center">
                    {country.rank <= 3 ? (
                      <motion.div
                        whileHover={{ rotate: 360 }}
                        transition={{ duration: 0.5 }}
                        className="inline-flex items-center justify-center w-10 h-10 rounded-full bg-linear-to-br from-yellow-400 to-orange-500 shadow-lg"
                      >
                        <span className="text-white font-bold">{country.rank}</span>
                      </motion.div>
                    ) : (
                      <span className="text-gray-400 font-semibold">{country.rank}</span>
                    )}
                  </div>

                  {/* Country */}
                  <div className="col-span-5 md:col-span-4 flex items-center gap-3">
                    <span className="text-4xl">{country.flag}</span>
                    <div>
                      <div className="text-white font-semibold">{country.country}</div>
                      <div className="flex items-center gap-1 text-xs text-gray-500">
                        {country.trend === 'up' && (
                          <>
                            <TrendingUp size={12} className="text-green-400" />
                            <span className="text-green-400">Rising</span>
                          </>
                        )}
                        {country.trend === 'same' && <span>Stable</span>}
                      </div>
                    </div>
                  </div>

                  {/* Gold */}
                  <motion.div
                    whileHover={{ scale: 1.2 }}
                    className="col-span-2 text-center"
                  >
                    <div className="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-linear-to-br from-yellow-400/20 to-yellow-600/20 border border-yellow-400/30">
                      <span className="text-white font-bold text-lg">{country.gold}</span>
                    </div>
                  </motion.div>

                  {/* Silver */}
                  <motion.div
                    whileHover={{ scale: 1.2 }}
                    className="col-span-2 text-center"
                  >
                    <div className="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-linear-to-br from-gray-300/20 to-gray-500/20 border border-gray-400/30">
                      <span className="text-white font-bold text-lg">{country.silver}</span>
                    </div>
                  </motion.div>

                  {/* Bronze */}
                  <motion.div
                    whileHover={{ scale: 1.2 }}
                    className="col-span-2 text-center"
                  >
                    <div className="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-linear-to-br from-orange-400/20 to-orange-600/20 border border-orange-400/30">
                      <span className="text-white font-bold text-lg">{country.bronze}</span>
                    </div>
                  </motion.div>

                  {/* Total */}
                  <div className="hidden md:block md:col-span-1 text-center">
                    <div className="text-white font-bold text-xl">{country.total}</div>
                  </div>
                </div>
              </motion.div>
            ))}
          </div>

          {/* Footer */}
          <div className="px-6 py-4 bg-white/5 backdrop-blur-xl text-center">
            <motion.button
              whileHover={{ scale: 1.05 }}
              whileTap={{ scale: 0.95 }}
              className="text-blue-400 hover:text-blue-300 font-semibold transition-colors"
            >
              View Complete Standings →
            </motion.button>
          </div>
        </motion.div>
      </div>
    </section>
  );
}
