import { Building2, Users, Briefcase, TrendingUp } from 'lucide-react';
import { motion } from 'motion/react';
import { useEffect, useState } from 'react';

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

const stats = [
  {
    icon: Building2,
    value: 10,
    suffix: '+',
    label: 'Managed Events',
    sublabel: 'International & National',
    gradient: 'from-red-500 to-pink-500',
  },
  {
    icon: Users,
    value: 500,
    suffix: '+',
    label: 'Registered Workers',
    sublabel: 'Active Professionals',
    gradient: 'from-blue-500 to-cyan-500',
  },
  {
    icon: Briefcase,
    value: 20,
    suffix: '+',
    label: 'Job Categories',
    sublabel: 'Specialized Roles',
    gradient: 'from-purple-500 to-pink-500',
  },
  {
    icon: TrendingUp,
    value: 95,
    suffix: '%',
    label: 'Satisfaction Rate',
    sublabel: 'Client Feedback',
    gradient: 'from-green-500 to-emerald-500',
  },
];

export function About() {
  return (
    <section id="about" className="relative py-24 bg-white">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {/* Header */}
        <motion.div
          initial={{ opacity: 0, y: 30 }}
          whileInView={{ opacity: 1, y: 0 }}
          viewport={{ once: true }}
          className="text-center mb-16"
        >
          <div className="inline-flex items-center gap-2 px-4 py-2 bg-blue-100 rounded-full mb-6">
            <div className="w-2 h-2 bg-blue-600 rounded-full"></div>
            <span className="text-blue-700 font-semibold">About ARISE</span>
          </div>

          <h2 className="text-4xl md:text-5xl font-bold text-gray-900 mb-6">
            Sport Event Management
          </h2>
          <p className="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
            The Worksport is a comprehensive digital platform designed to streamline and
            enhance the management of Organizing Committee. Our system integrates event management,
            worker coordination, job categorization, and advanced analytics to provide a unified
            solution for national Olympic Academy.
          </p>
        </motion.div>

        {/* Logo */}
        <motion.div
          initial={{ opacity: 0, scale: 0.8 }}
          whileInView={{ opacity: 1, scale: 1 }}
          viewport={{ once: true }}
          className="flex justify-center mb-16"
        >
          <div className="text-center">
            <div className="flex items-center justify-center gap-2 mb-4">
              <span className="text-6xl font-bold text-blue-600">N</span>
              <span className="text-6xl font-bold text-yellow-500">O</span>
              <span className="text-6xl font-bold text-red-600">C</span>
              <span className="text-6xl font-bold text-green-600">I</span>
              <span className="text-6xl font-bold text-purple-600">S</span>
            </div>
            <div className="text-gray-700 font-semibold text-lg">
              National Olympic Academy of<br />Indonesia System
            </div>
          </div>
        </motion.div>

        {/* Stats Grid */}
        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
          {stats.map((stat, index) => (
            <motion.div
              key={stat.label}
              initial={{ opacity: 0, y: 30 }}
              whileInView={{ opacity: 1, y: 0 }}
              viewport={{ once: true }}
              transition={{ delay: index * 0.1 }}
              whileHover={{ y: -10, scale: 1.02 }}
              className="relative group"
            >
              <div className="relative bg-linear-to-br from-gray-50 to-white border border-gray-200 rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all overflow-hidden">
                {/* Background Gradient */}
                <div className={`absolute inset-0 bg-linear-to-br ${stat.gradient} opacity-0 group-hover:opacity-5 transition-opacity`}></div>

                {/* Icon */}
                <div className="inline-flex p-4 rounded-xl bg-blue-50 mb-4 shadow-lg">
                  <stat.icon className="text-blue-600" size={32} />
                </div>

                {/* Number */}
                <div className="text-5xl font-bold text-gray-900 mb-2">
                  <Counter end={stat.value} />
                  {stat.suffix}
                </div>

                {/* Label */}
                <div className="text-lg font-semibold text-gray-900 mb-1">{stat.label}</div>
                <div className="text-sm text-gray-600">{stat.sublabel}</div>

                {/* Decorative Circle */}
                <motion.div
                  className={`absolute -bottom-8 -right-8 w-24 h-24 bg-linear-to-br ${stat.gradient} rounded-full opacity-10 blur-2xl`}
                  animate={{ scale: [1, 1.2, 1] }}
                  transition={{ duration: 3, repeat: Infinity }}
                ></motion.div>
              </div>
            </motion.div>
          ))}
        </div>

        {/* CTA Button */}
        <motion.div
          initial={{ opacity: 0, y: 30 }}
          whileInView={{ opacity: 1, y: 0 }}
          viewport={{ once: true }}
          className="text-center mt-16"
        >
          <motion.button
            whileHover={{ scale: 1.05 }}
            whileTap={{ scale: 0.95 }}
            className="px-10 py-4 bg-linear-to-r from-red-600 to-pink-600 text-white font-bold text-lg rounded-xl shadow-2xl shadow-red-600/30 hover:shadow-red-600/50 transition-all"
          >
            Experience ARISE Now
          </motion.button>
        </motion.div>
      </div>
    </section>
  );
}
