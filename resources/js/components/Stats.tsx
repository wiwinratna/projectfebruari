import { Trophy, Users, Globe, Zap } from 'lucide-react';
import { motion } from 'motion/react';
import { useEffect, useState } from 'react';

const stats = [
  { icon: Trophy, label: 'Events', value: 109, suffix: '', color: 'from-yellow-400 to-orange-500' },
  { icon: Users, label: 'Athletes', value: 2900, suffix: '+', color: 'from-blue-400 to-cyan-500' },
  { icon: Globe, label: 'Countries', value: 91, suffix: '', color: 'from-pink-400 to-purple-500' },
  { icon: Zap, label: 'Records', value: 350, suffix: '+', color: 'from-green-400 to-emerald-500' },
];

function Counter({ end, duration = 2000 }: { end: number; duration?: number }) {
  const [count, setCount] = useState(0);

  useEffect(() => {
    let startTime: number;
    let animationFrame: number;

    const animate = (currentTime: number) => {
      if (!startTime) startTime = currentTime;
      const progress = Math.min((currentTime - startTime) / duration, 1);

      setCount(Math.floor(progress * end));

      if (progress < 1) {
        animationFrame = requestAnimationFrame(animate);
      }
    };

    animationFrame = requestAnimationFrame(animate);
    return () => cancelAnimationFrame(animationFrame);
  }, [end, duration]);

  return <span>{count}</span>;
}

export function Stats() {
  return (
    <section className="relative py-20 bg-slate-950">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="grid grid-cols-2 lg:grid-cols-4 gap-6">
          {stats.map((stat, index) => (
            <motion.div
              key={stat.label}
              initial={{ opacity: 0, y: 30 }}
              whileInView={{ opacity: 1, y: 0 }}
              viewport={{ once: true }}
              transition={{ delay: index * 0.1 }}
              whileHover={{ scale: 1.05, y: -5 }}
              className="relative group"
            >
              <div className="relative bg-linear-to-br from-white/5 to-white/10 backdrop-blur-xl rounded-2xl p-6 border border-white/10 overflow-hidden">
                {/* Gradient Overlay on Hover */}
                <div className={`absolute inset-0 bg-linear-to-br ${stat.color} opacity-0 group-hover:opacity-10 transition-opacity duration-300`}></div>

                {/* Icon */}
                <div className={`inline-flex p-3 rounded-xl bg-linear-to-br ${stat.color} mb-4 shadow-lg`}>
                  <stat.icon className="text-white" size={28} />
                </div>

                {/* Number */}
                <div className="text-4xl font-bold text-white mb-2">
                  <Counter end={stat.value} />
                  {stat.suffix}
                </div>

                {/* Label */}
                <div className="text-gray-400">{stat.label}</div>

                {/* Decorative Element */}
                <motion.div
                  className={`absolute -bottom-12 -right-12 w-32 h-32 bg-linear-to-br ${stat.color} rounded-full opacity-10 blur-2xl`}
                  animate={{ scale: [1, 1.2, 1] }}
                  transition={{ duration: 3, repeat: Infinity }}
                ></motion.div>
              </div>
            </motion.div>
          ))}
        </div>
      </div>
    </section>
  );
}
