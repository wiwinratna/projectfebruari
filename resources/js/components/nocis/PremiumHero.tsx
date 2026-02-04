import { motion } from 'motion/react';
import { Calendar, ChevronRight } from 'lucide-react';

export function PremiumHero() {
  const liveMatches = [
    {
      sport: "GENTLEMEN'S SINGLES",
      venue: "Centre Court",
      player1: { name: "J. Smith", country: "🇺🇸", flag: "USA" },
      player2: { name: "K. Johnson", country: "🇬🇧", flag: "GBR" },
      score1: [6, 4, 7],
      score2: [4, 6, 5],
      status: "LIVE NOW",
      time: "SET 3"
    },
    {
      sport: "WOMEN'S SINGLES",
      venue: "Centre Court",
      player1: { name: "M. Williams", country: "🇺🇸", flag: "USA" },
      player2: { name: "A. Chen", country: "🇨🇳", flag: "CHN" },
      score1: [6, 3],
      score2: [2, 1],
      status: "COMPLETED",
      time: "SET 2"
    }
  ];

  const newsCards = [
    {
      badge: "MAIN",
      date: "AUGUST 3",
      title: "Olympic champion claims historic gold medal with stunning performance",
      bgColor: "from-emerald-900/80 to-emerald-700/60"
    },
    {
      badge: "VIDEO",
      date: "AUGUST 3",
      title: "Paris 2024 - the Highlights",
      bgColor: "from-blue-900/80 to-blue-700/60"
    },
    {
      badge: "FINAL",
      date: "AUGUST 2",
      title: "Historic win with record-breaking specialty",
      bgColor: "from-purple-900/80 to-purple-700/60"
    }
  ];

  return (
    <div className="relative">
      {/* Hero Section 1 - Main Event */}
      <section className="relative h-screen overflow-hidden">
        {/* Background Image */}
        <div
          className="absolute inset-0 bg-cover bg-center"
          style={{
            backgroundImage: `url('https://images.unsplash.com/photo-1600408942605-e39b013aaaea?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxhdGhsZXRlJTIwaG9sZGluZyUyMHRyb3BoeSUyMGNlbGVicmF0aW9ufGVufDF8fHx8MTc2OTg1MTU2NXww&ixlib=rb-4.1.0&q=80&w=1080')`
          }}
        >
          <div className="absolute inset-0 from-black/60 via-black/40 to-black/80 bg-linear-to-b"></div>
        </div>

        {/* Content */}
        <div className="relative h-full flex flex-col justify-between px-4 sm:px-6 lg:px-8 pt-32 pb-16">
          {/* Main Title */}
          <div className="max-w-7xl mx-auto w-full">
            <motion.div
              initial={{ opacity: 0, y: 50 }}
              animate={{ opacity: 1, y: 0 }}
              transition={{ duration: 0.8 }}
              className="flex items-center gap-12"
            >
              <div>
                <h1 className="text-7xl md:text-9xl font-black text-white leading-none tracking-tight">
                  OLYMPIC
                  <br />
                  GAMES
                </h1>
              </div>
              <div className="border-l-2 border-white/30 pl-8">
                <div className="text-6xl md:text-8xl font-black text-white">2024</div>
                <div className="text-2xl md:text-3xl font-light text-white mt-2">
                  Always like
                  <br />
                  never before
                </div>
              </div>
            </motion.div>
          </div>

          {/* Bottom Section - Schedule & Cards */}
          <div className="max-w-7xl mx-auto w-full">
            {/* Schedule Bar */}
            <motion.div
              initial={{ opacity: 0, y: 30 }}
              animate={{ opacity: 1, y: 0 }}
              transition={{ delay: 0.3 }}
              className="backdrop-blur-xl bg-white/10 border border-white/20 rounded-2xl px-6 py-4 mb-6 flex items-center justify-between"
            >
              <div className="flex items-center gap-6">
                <Calendar className="text-white" size={20} />
                <span className="text-white font-semibold">LONDON, THU 12:00 PM</span>
                <span className="text-white/60">•</span>
                <span className="text-white font-semibold">COURT ONE, THU 1:15 PM</span>
              </div>
            </motion.div>

            {/* Action Buttons & Cards Grid */}
            <div className="flex flex-col lg:flex-row gap-6">
              {/* Left - News Cards */}
              <div className="flex-1 space-y-4">
                {newsCards.map((card, index) => (
                  <motion.div
                    key={index}
                    initial={{ opacity: 0, x: -30 }}
                    animate={{ opacity: 1, x: 0 }}
                    transition={{ delay: 0.4 + index * 0.1 }}
                    className={`backdrop-blur-xl ${card.bgColor} bg-linear-to-br border border-white/20 rounded-2xl p-6 hover:scale-[1.02] transition-transform cursor-pointer group`}
                  >
                    <div className="flex items-start justify-between mb-3">
                      <span className="px-3 py-1 bg-white/20 backdrop-blur-sm rounded-full text-white text-xs font-bold">
                        {card.badge}
                      </span>
                      <span className="text-white/60 text-sm">{card.date}</span>
                    </div>
                    <h3 className="text-white font-bold text-lg group-hover:text-yellow-300 transition-colors">
                      {card.title}
                    </h3>
                  </motion.div>
                ))}
              </div>

              {/* Right - Live Match Cards */}
              <div className="flex-1 space-y-4">
                {/* Buttons */}
                <div className="flex gap-3 mb-4">
                  <button className="px-6 py-2 bg-yellow-400 text-black font-bold rounded-full hover:bg-yellow-300 transition-colors">
                    RESULTS
                  </button>
                  <button className="px-6 py-2 bg-white/20 backdrop-blur-sm text-white font-bold rounded-full border border-white/30 hover:bg-white/30 transition-colors">
                    ORDER OF PLAY
                  </button>
                  <button className="px-6 py-2 bg-white/20 backdrop-blur-sm text-white font-bold rounded-full border border-white/30 hover:bg-white/30 transition-colors">
                    ALL RESULTS →
                  </button>
                </div>

                {/* Live Matches */}
                {liveMatches.map((match, index) => (
                  <motion.div
                    key={index}
                    initial={{ opacity: 0, x: 30 }}
                    animate={{ opacity: 1, x: 0 }}
                    transition={{ delay: 0.5 + index * 0.1 }}
                    className="backdrop-blur-xl bg-black/40 border border-white/20 rounded-2xl p-6 hover:bg-black/50 transition-colors"
                  >
                    <div className="flex items-center justify-between mb-4">
                      <div className="text-white/60 text-xs font-bold">{match.sport}</div>
                      <div className="flex items-center gap-2">
                        <span className="text-white/60 text-xs">{match.venue}</span>
                        {match.status === "LIVE NOW" && (
                          <span className="px-3 py-1 bg-red-600 text-white text-xs font-bold rounded-full animate-pulse">
                            LIVE NOW
                          </span>
                        )}
                      </div>
                    </div>

                    {/* Players & Scores */}
                    <div className="space-y-3">
                      <div className="flex items-center justify-between">
                        <div className="flex items-center gap-3">
                          <span className="text-2xl">{match.player1.country}</span>
                          <span className="text-white font-bold">{match.player1.name}</span>
                        </div>
                        <div className="flex gap-2">
                          {match.score1.map((score, idx) => (
                            <div key={idx} className="w-10 h-10 flex items-center justify-center bg-white/10 rounded-lg">
                              <span className="text-white font-bold">{score}</span>
                            </div>
                          ))}
                        </div>
                      </div>

                      <div className="flex items-center justify-between">
                        <div className="flex items-center gap-3">
                          <span className="text-2xl">{match.player2.country}</span>
                          <span className="text-white font-bold">{match.player2.name}</span>
                        </div>
                        <div className="flex gap-2">
                          {match.score2.map((score, idx) => (
                            <div key={idx} className="w-10 h-10 flex items-center justify-center bg-white/10 rounded-lg">
                              <span className="text-white font-bold">{score}</span>
                            </div>
                          ))}
                        </div>
                      </div>
                    </div>

                    <div className="mt-4 text-yellow-400 text-xs font-bold">{match.time}</div>
                  </motion.div>
                ))}
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Hero Section 2 - Secondary */}
      <section className="relative h-screen overflow-hidden">
        {/* Background Image */}
        <div
          className="absolute inset-0 bg-cover bg-center"
          style={{
            backgroundImage: `url('https://images.unsplash.com/photo-1630484174614-f9176ca48dd5?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxvbHltcGljJTIwcnVubmluZyUyMHRyYWNrJTIwc3RhZGl1bXxlbnwxfHx8fDE3Njk4NTE1NjV8MA&ixlib=rb-4.1.0&q=80&w=1080')`
          }}
        >
          <div className="absolute inset-0 from-black/50 via-transparent to-black/70 bg-linear-to-b"></div>
        </div>

        {/* Content */}
        <div className="relative h-full flex flex-col justify-between px-4 sm:px-6 lg:px-8 py-16">
          {/* Centered Title */}
          <div className="flex-1 flex items-center justify-center">
            <motion.h2
              initial={{ opacity: 0, scale: 0.9 }}
              whileInView={{ opacity: 1, scale: 1 }}
              viewport={{ once: true }}
              className="text-8xl md:text-9xl font-black text-white leading-none tracking-tight text-center"
            >
              OLYMPIC
              <br />
              GAMES
            </motion.h2>
          </div>

          {/* Bottom CTA */}
          <motion.div
            initial={{ opacity: 0, y: 30 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            className="max-w-4xl mx-auto w-full backdrop-blur-xl bg-yellow-400/90 rounded-3xl p-8 shadow-2xl"
          >
            <p className="text-black text-lg mb-6">
              Sign up to ARISE account for exclusive access to the Championships,
              including more ARISE Stories, updates about your favorite players
              and the chance to be the first to know about all of the Royal Court happenings.
            </p>
            <button className="px-8 py-3 bg-black text-white font-bold rounded-full hover:bg-gray-800 transition-colors flex items-center gap-2">
              JOIN NOW
              <ChevronRight size={20} />
            </button>
          </motion.div>
        </div>
      </section>
    </div>
  );
}
