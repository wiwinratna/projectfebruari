import { ImageWithFallback } from '../figma/ImageWithFallback';
import { Calendar, Users, LineChart, Shield, Zap, Globe } from 'lucide-react';
import { motion } from 'motion/react';

const features = [
  {
    icon: Calendar,
    title: 'Event Management',
    description: 'Streamline planning and execution of sporting events with powerful tools',
    gradient: 'from-blue-500 to-cyan-500',
  },
  {
    icon: Users,
    title: 'Worker Coordination',
    description: 'Efficiently manage and coordinate workforce across multiple events',
    gradient: 'from-purple-500 to-pink-500',
  },
  {
    icon: LineChart,
    title: 'Advanced Analytics',
    description: 'Get insights with real-time data and comprehensive reporting',
    gradient: 'from-green-500 to-emerald-500',
  },
  {
    icon: Shield,
    title: 'Secure Platform',
    description: 'Enterprise-grade security to protect your sensitive information',
    gradient: 'from-red-500 to-orange-500',
  },
  {
    icon: Zap,
    title: 'Real-time Updates',
    description: 'Stay informed with instant notifications and live updates',
    gradient: 'from-yellow-500 to-orange-500',
  },
  {
    icon: Globe,
    title: 'Global Standards',
    description: 'Built following international best practices and standards',
    gradient: 'from-indigo-500 to-purple-500',
  },
];

export function Features() {
  return (
    <section id="features" className="relative py-24 bg-linear-to-br from-gray-50 to-white">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {/* Header */}
        <motion.div
          initial={{ opacity: 0, y: 30 }}
          whileInView={{ opacity: 1, y: 0 }}
          viewport={{ once: true }}
          className="text-center mb-16"
        >
          <div className="inline-flex items-center gap-2 px-4 py-2 bg-purple-100 rounded-full mb-6">
            <Zap className="text-purple-600" size={20} />
            <span className="text-purple-700 font-semibold">Powerful Features</span>
          </div>

          <h2 className="text-4xl md:text-5xl font-bold text-gray-900 mb-6">
            Everything You Need to<br />
            <span className="bg-linear-to-r from-red-600 to-purple-600 bg-clip-text text-transparent">
              Manage Sports Events
            </span>
          </h2>
          <p className="text-xl text-gray-600 max-w-2xl mx-auto">
            Comprehensive tools designed for modern sports event management
          </p>
        </motion.div>

        {/* Features Grid */}
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-16">
          {features.map((feature, index) => (
            <motion.div
              key={feature.title}
              initial={{ opacity: 0, y: 30 }}
              whileInView={{ opacity: 1, y: 0 }}
              viewport={{ once: true }}
              transition={{ delay: index * 0.1 }}
              whileHover={{ y: -10, scale: 1.02 }}
              className="group"
            >
              <div className="relative bg-white border border-gray-200 rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all overflow-hidden">
                {/* Background Gradient on Hover */}
                <div className={`absolute inset-0 bg-linear-to-br ${feature.gradient} opacity-0 group-hover:opacity-5 transition-opacity`}></div>

                {/* Icon */}
                <div className="inline-flex p-4 rounded-xl bg-gray-100 mb-4 shadow-lg group-hover:scale-110 transition-transform">
                  <feature.icon className="text-gray-700" size={28} />
                </div>

                {/* Content */}
                <h3 className="text-xl font-bold text-gray-900 mb-3">{feature.title}</h3>
                <p className="text-gray-600 leading-relaxed">{feature.description}</p>

                {/* Decorative Element */}
                <motion.div
                  className={`absolute -bottom-6 -right-6 w-20 h-20 bg-linear-to-br ${feature.gradient} rounded-full opacity-10 blur-xl`}
                  animate={{ scale: [1, 1.2, 1] }}
                  transition={{ duration: 3, repeat: Infinity }}
                ></motion.div>
              </div>
            </motion.div>
          ))}
        </div>

        {/* Feature Showcase */}
        <motion.div
          initial={{ opacity: 0, y: 30 }}
          whileInView={{ opacity: 1, y: 0 }}
          viewport={{ once: true }}
          className="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center bg-linear-to-br from-red-50 to-pink-50 rounded-3xl p-8 lg:p-12"
        >
          {/* Image */}
          <motion.div
            whileHover={{ scale: 1.02 }}
            className="relative"
          >
            <div className="relative rounded-2xl overflow-hidden shadow-2xl">
              <ImageWithFallback
                src="https://images.unsplash.com/photo-1760611656007-f767a8082758?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHx0ZWFtJTIwY29sbGFib3JhdGlvbiUyMG9mZmljZSUyMG1vZGVybnxlbnwxfHx8fDE3Njk3OTAwNzF8MA&ixlib=rb-4.1.0&q=80&w=1080&utm_source=figma&utm_medium=referral"
                alt="Team Collaboration"
                className="w-full h-[400px] object-cover"
              />
              <div className="absolute inset-0 bg-linear-to-t from-red-600/20 to-transparent"></div>
            </div>

            {/* Floating Badge */}
            <motion.div
              animate={{ y: [0, -10, 0] }}
              transition={{ duration: 3, repeat: Infinity }}
              className="absolute -top-4 -right-4 bg-white p-4 rounded-2xl shadow-2xl"
            >
              <div className="text-center">
                <div className="text-3xl font-bold bg-linear-to-r from-red-600 to-pink-600 bg-clip-text text-transparent">
                  95%
                </div>
                <div className="text-sm text-gray-600">Success Rate</div>
              </div>
            </motion.div>
          </motion.div>

          {/* Content */}
          <div>
            <h3 className="text-3xl font-bold text-gray-900 mb-6">
              Trusted by Sports Organizations Nationwide
            </h3>
            <p className="text-lg text-gray-600 mb-6 leading-relaxed">
              Our platform has successfully managed over 10 major sporting events,
              coordinating hundreds of professionals and delivering exceptional results
              for the National Olympic Academy of Indonesia.
            </p>

            <div className="space-y-4">
              {[
                'Streamlined event coordination',
                'Improved workforce efficiency',
                'Real-time performance tracking',
                'Enhanced communication tools',
              ].map((item, index) => (
                <motion.div
                  key={item}
                  initial={{ opacity: 0, x: -20 }}
                  whileInView={{ opacity: 1, x: 0 }}
                  viewport={{ once: true }}
                  transition={{ delay: index * 0.1 }}
                  className="flex items-center gap-3"
                >
                  <div className="shrink-0 w-6 h-6 bg-linear-to-br from-red-600 to-pink-600 rounded-full flex items-center justify-center">
                    <svg className="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 13l4 4L19 7" />
                    </svg>
                  </div>
                  <span className="text-gray-700 font-medium">{item}</span>
                </motion.div>
              ))}
            </div>
          </div>
        </motion.div>
      </div>
    </section>
  );
}
