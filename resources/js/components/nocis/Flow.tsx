import { UserPlus, FileSearch, CheckCircle, Rocket } from 'lucide-react';
import { motion } from 'motion/react';

const steps = [
  {
    number: '01',
    icon: UserPlus,
    title: 'Create Account',
    description: 'Sign up and create your professional profile in minutes',
    gradient: 'from-blue-500 to-cyan-500',
  },
  {
    number: '02',
    icon: FileSearch,
    title: 'Browse Opportunities',
    description: 'Explore available positions matching your skills and interests',
    gradient: 'from-purple-500 to-pink-500',
  },
  {
    number: '03',
    icon: CheckCircle,
    title: 'Apply & Get Hired',
    description: 'Submit your application and connect with event organizers',
    gradient: 'from-green-500 to-emerald-500',
  },
  {
    number: '04',
    icon: Rocket,
    title: 'Start Working',
    description: 'Begin your journey in the exciting world of sports events',
    gradient: 'from-red-500 to-orange-500',
  },
];

export function Flow() {
  return (
    <section id="flow" className="relative py-24 bg-white overflow-hidden">
      {/* Background Decoration */}
      <div className="absolute inset-0 overflow-hidden">
        <div className="absolute top-1/2 left-1/4 w-96 h-96 bg-blue-100 rounded-full blur-3xl opacity-30"></div>
        <div className="absolute bottom-1/4 right-1/4 w-96 h-96 bg-purple-100 rounded-full blur-3xl opacity-30"></div>
      </div>

      <div className="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {/* Header */}
        <motion.div
          initial={{ opacity: 0, y: 30 }}
          whileInView={{ opacity: 1, y: 0 }}
          viewport={{ once: true }}
          className="text-center mb-20"
        >
          <div className="inline-flex items-center gap-2 px-4 py-2 bg-linear-to-r from-blue-100 to-purple-100 rounded-full mb-6">
            <span className="text-transparent bg-linear-to-r from-blue-600 to-purple-600 bg-clip-text font-semibold">
              How It Works
            </span>
          </div>

          <h2 className="text-4xl md:text-5xl font-bold text-gray-900 mb-6">
            Get Started in <span className="bg-linear-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">4 Easy Steps</span>
          </h2>
          <p className="text-xl text-gray-600 max-w-2xl mx-auto">
            Your journey to becoming part of Indonesia's premier sports workforce starts here
          </p>
        </motion.div>

        {/* Steps */}
        <div className="relative">
          {/* Connection Line - Desktop */}
          <div className="hidden lg:block absolute top-1/2 left-0 right-0 h-1 bg-linear-to-r from-blue-200 via-purple-200 to-red-200 -translate-y-1/2 z-0"></div>

          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 relative z-10">
            {steps.map((step, index) => (
              <motion.div
                key={step.number}
                initial={{ opacity: 0, y: 50 }}
                whileInView={{ opacity: 1, y: 0 }}
                viewport={{ once: true }}
                transition={{ delay: index * 0.2 }}
                className="relative"
              >
                <motion.div
                  whileHover={{ y: -10, scale: 1.02 }}
                  className="relative bg-white border-2 border-gray-100 rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all"
                >
                  {/* Step Number */}
                  <div className="absolute -top-4 left-8 px-4 py-1 bg-linear-to-r from-gray-900 to-gray-700 rounded-full">
                    <span className="text-white font-bold">{step.number}</span>
                  </div>

                  {/* Icon */}
                  <div className="inline-flex p-4 rounded-2xl bg-red-50 mb-6 shadow-lg">
                    <step.icon className="text-red-600" size={32} />
                  </div>

                  {/* Content */}
                  <h3 className="text-xl font-bold text-gray-900 mb-3">{step.title}</h3>
                  <p className="text-gray-600 leading-relaxed">{step.description}</p>

                  {/* Decorative Element */}
                  <motion.div
                    className={`absolute -bottom-6 -right-6 w-20 h-20 bg-linear-to-br ${step.gradient} rounded-full opacity-10 blur-2xl`}
                    animate={{ scale: [1, 1.2, 1] }}
                    transition={{ duration: 3, repeat: Infinity, delay: index * 0.5 }}
                  ></motion.div>

                  {/* Arrow - Desktop Only */}
                  {index < steps.length - 1 && (
                    <div className="hidden lg:block absolute -right-4 top-1/2 -translate-y-1/2 z-20">
                      <motion.div
                        animate={{ x: [0, 5, 0] }}
                        transition={{ duration: 1.5, repeat: Infinity }}
                      >
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                          <path d="M5 12H19M19 12L12 5M19 12L12 19" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" className={`text-gradient-to-r ${step.gradient}`}/>
                        </svg>
                      </motion.div>
                    </div>
                  )}
                </motion.div>
              </motion.div>
            ))}
          </div>
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
            className="px-10 py-4 bg-linear-to-r from-blue-600 to-purple-600 text-white font-bold text-lg rounded-xl shadow-2xl shadow-blue-600/30 hover:shadow-blue-600/50 transition-all"
          >
            Get Started Now
          </motion.button>
        </motion.div>
      </div>
    </section>
  );
}
