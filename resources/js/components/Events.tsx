import { ImageWithFallback } from './figma/ImageWithFallback';
import { Calendar, MapPin, ChevronRight } from 'lucide-react';
import { motion } from 'motion/react';

const events = [
  {
    id: 1,
    name: 'Swimming',
    date: 'Feb 8-15',
    venue: 'Aquatic Center',
    participants: '842 Athletes',
    image: 'https://images.unsplash.com/photo-1695326288959-89be49070059?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxkaXZpbmclMjBvbHltcGljJTIwcG9vbCUyMGFlcmlhbHxlbnwxfHx8fDE3Njk4NDc5NzN8MA&ixlib=rb-4.1.0&q=80&w=1080&utm_source=figma&utm_medium=referral',
    gradient: 'from-blue-500 to-cyan-500',
  },
  {
    id: 2,
    name: 'Track & Field',
    date: 'Feb 6-20',
    venue: 'Olympic Stadium',
    participants: '2090 Athletes',
    image: 'https://images.unsplash.com/photo-1619018078044-41db8cd03eff?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxhdGhsZXRlJTIwcnVubmluZyUyMHRyYWNrJTIwc3VucmlzZXxlbnwxfHx8fDE3Njk4NDc5NzN8MA&ixlib=rb-4.1.0&q=80&w=1080&utm_source=figma&utm_medium=referral',
    gradient: 'from-orange-500 to-red-500',
  },
  {
    id: 3,
    name: 'Gymnastics',
    date: 'Feb 10-18',
    venue: 'Gymnastics Arena',
    participants: '324 Athletes',
    image: 'https://images.unsplash.com/photo-1505619730259-b1288d154955?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxneW1uYXN0aWNzJTIwY29tcGV0aXRpb258ZW58MXx8fHwxNzY5ODQ3ODExfDA&ixlib=rb-4.1.0&q=80&w=1080&utm_source=figma&utm_medium=referral',
    gradient: 'from-purple-500 to-pink-500',
  },
  {
    id: 4,
    name: 'Tennis',
    date: 'Feb 7-21',
    venue: 'Sports Complex',
    participants: '172 Athletes',
    image: 'https://images.unsplash.com/photo-1644199288616-100cc0536263?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHx0ZW5uaXMlMjBwbGF5ZXIlMjBhY3Rpb24lMjBvbHltcGljfGVufDF8fHx8MTc2OTg0Nzk3NHww&ixlib=rb-4.1.0&q=80&w=1080&utm_source=figma&utm_medium=referral',
    gradient: 'from-green-500 to-emerald-500',
  },
  {
    id: 5,
    name: 'Basketball',
    date: 'Feb 9-20',
    venue: 'Arena Hall',
    participants: '288 Athletes',
    image: 'https://images.unsplash.com/photo-1760784321805-ee1a59c1ea7f?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxiYXNrZXRiYWxsJTIwb2x5bXBpYyUyMGdhbWV8ZW58MXx8fHwxNzY5ODQ3ODExfDA&ixlib=rb-4.1.0&q=80&w=1080&utm_source=figma&utm_medium=referral',
    gradient: 'from-yellow-500 to-orange-500',
  },
  {
    id: 6,
    name: 'Volleyball',
    date: 'Feb 11-22',
    venue: 'Sports Arena',
    participants: '384 Athletes',
    image: 'https://images.unsplash.com/photo-1728532483490-708f6562b738?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHx0cmFjayUyMGFuZCUyMGZpZWxkJTIwYXRobGV0aWNzfGVufDF8fHx8MTc2OTg0NzgxMHww&ixlib=rb-4.1.0&q=80&w=1080&utm_source=figma&utm_medium=referral',
    gradient: 'from-indigo-500 to-blue-500',
  },
];

export function Events() {
  return (
    <section id="events" className="relative py-32 bg-slate-950">
      {/* Background Effects */}
      <div className="absolute inset-0 overflow-hidden">
        <div className="absolute top-1/4 left-1/4 w-96 h-96 bg-blue-500/10 rounded-full blur-3xl"></div>
        <div className="absolute bottom-1/4 right-1/4 w-96 h-96 bg-purple-500/10 rounded-full blur-3xl"></div>
      </div>

      <div className="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <motion.div
          initial={{ opacity: 0, y: 30 }}
          whileInView={{ opacity: 1, y: 0 }}
          viewport={{ once: true }}
          className="text-center mb-16"
        >
          <motion.div
            initial={{ opacity: 0, scale: 0.8 }}
            whileInView={{ opacity: 1, scale: 1 }}
            viewport={{ once: true }}
            className="inline-flex items-center gap-2 px-4 py-2 bg-linear-to-r from-blue-500/20 to-purple-500/20 rounded-full border border-blue-400/30 mb-6 backdrop-blur-sm"
          >
            <span className="text-blue-300">Featured Events</span>
          </motion.div>

          <h2 className="text-5xl md:text-6xl font-bold mb-6">
            <span className="bg-linear-to-r from-blue-400 via-purple-400 to-pink-400 bg-clip-text text-transparent">
              Experience
            </span>
            <br />
            <span className="text-white">World-Class Competition</span>
          </h2>
          <p className="text-xl text-gray-400 max-w-2xl mx-auto">
            Witness history as the world's finest athletes compete across multiple disciplines
          </p>
        </motion.div>

        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          {events.map((event, index) => (
            <motion.div
              key={event.id}
              initial={{ opacity: 0, y: 30 }}
              whileInView={{ opacity: 1, y: 0 }}
              viewport={{ once: true }}
              transition={{ delay: index * 0.1 }}
              whileHover={{ y: -10 }}
              className="group relative"
            >
              <div className="relative bg-linear-to-br from-white/5 to-white/10 backdrop-blur-xl rounded-3xl overflow-hidden border border-white/10 shadow-2xl">
                {/* Image */}
                <div className="relative h-64 overflow-hidden">
                  <ImageWithFallback
                    src={event.image}
                    alt={event.name}
                    className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                  />
                  <div className={`absolute inset-0 bg-linear-to-t ${event.gradient} opacity-60 group-hover:opacity-40 transition-opacity`}></div>

                  {/* Overlay Content */}
                  <div className="absolute inset-0 bg-linear-to-t from-black/80 via-black/20 to-transparent"></div>

                  {/* Floating Badge */}
                  <motion.div
                    initial={{ scale: 0 }}
                    whileInView={{ scale: 1 }}
                    transition={{ delay: 0.3 }}
                    className="absolute top-4 right-4 px-3 py-1 bg-white/20 backdrop-blur-lg rounded-full border border-white/30"
                  >
                    <span className="text-white text-sm font-semibold">{event.participants}</span>
                  </motion.div>
                </div>

                {/* Content */}
                <div className="p-6">
                  <h3 className="text-2xl font-bold text-white mb-3">{event.name}</h3>

                  <div className="space-y-2 mb-4">
                    <div className="flex items-center gap-2 text-gray-400">
                      <Calendar size={16} />
                      <span className="text-sm">{event.date}</span>
                    </div>
                    <div className="flex items-center gap-2 text-gray-400">
                      <MapPin size={16} />
                      <span className="text-sm">{event.venue}</span>
                    </div>
                  </div>

                  <motion.button
                    whileHover={{ scale: 1.02 }}
                    whileTap={{ scale: 0.98 }}
                    className={`w-full py-3 rounded-xl bg-linear-to-r ${event.gradient} text-white font-semibold flex items-center justify-center gap-2 shadow-lg group-hover:shadow-2xl transition-all`}
                  >
                    View Schedule
                    <ChevronRight size={20} className="group-hover:translate-x-1 transition-transform" />
                  </motion.button>
                </div>

                {/* Glow Effect */}
                <div className={`absolute inset-0 bg-linear-to-r ${event.gradient} opacity-0 group-hover:opacity-20 transition-opacity duration-300 pointer-events-none`}></div>
              </div>
            </motion.div>
          ))}
        </div>
      </div>
    </section>
  );
}
