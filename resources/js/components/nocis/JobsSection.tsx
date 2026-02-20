import React, { useState, useEffect } from 'react';
import { motion, useMotionValue, useTransform } from 'motion/react';

interface JobItem {
  id: number;
  title: string;
  category: string;
  location: string;
  type: string;
  applicants: number;
  deadline: string;
  slotsTotal: number;
  slotsFilled: number;
  color: string;
  ringColor: string;
}

export function JobsSection() {
  const [jobs, setJobs] = useState<JobItem[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);
  const [isLoggedIn, setIsLoggedIn] = useState(false);

  const colorSchemes = [
    { color: "from-red-500 to-red-600", ringColor: "bg-red-600" },
    { color: "from-red-500 to-red-600", ringColor: "bg-red-600" },
    { color: "from-red-500 to-red-600", ringColor: "bg-red-600" },
    { color: "from-red-500 to-red-600", ringColor: "bg-red-600" },
    { color: "from-red-500 to-red-600", ringColor: "bg-red-600" },
    { color: "from-red-500 to-red-600", ringColor: "bg-red-600" },

  ];

  useEffect(() => {
    fetchJobs();
  }, []);

  useEffect(() => {
    const metaTag = document.querySelector('meta[name="auth-user"]');
    const content = metaTag?.getAttribute('content');

    if (!content || content === 'null') {
      setIsLoggedIn(false);
      return;
    }

    try {
      const parsed = JSON.parse(content);
      setIsLoggedIn(Boolean(parsed?.id));
    } catch {
      setIsLoggedIn(false);
    }
  }, []);

  const fetchJobs = async () => {
    try {
      setLoading(true);
      const response = await fetch('/api/jobs');
      if (!response.ok) throw new Error('Failed to fetch jobs');
      const result = await response.json();

      if (result.success && result.data) {
        const jobsWithColors = result.data.map((item: any, index: number) => ({
          id: item.id,
          title: item.title,
          category: item.job_category ?? 'Uncategorized',
          location: item.city ?? 'Indonesia',
          type: item.event ?? 'Event',
          applicants: item.applicant_count ?? 0,
          deadline: item.application_deadline ?? 'TBD',
          slotsTotal: item.slots_total ?? 0,
          slotsFilled: item.slots_filled ?? 0,
          ...colorSchemes[index % colorSchemes.length]
        }));
        setJobs(jobsWithColors);
      }
      setError(null);
    } catch (err) {
      console.error('Error fetching jobs:', err);
      setJobs([]);
      setError(err instanceof Error ? err.message : 'Failed to load jobs');
    } finally {
      setLoading(false);
    }
  };

  const CardWithParallax = ({ job, index }: { job: JobItem; index: number }) => {
    const x = useMotionValue(0);
    const y = useMotionValue(0);
    const rotateX = useTransform(y, [-100, 100], [10, -10]);
    const rotateY = useTransform(x, [-100, 100], [-10, 10]);

    return (
      <motion.div
        initial={{ opacity: 0, y: 50 }}
        whileInView={{ opacity: 1, y: 0 }}
        viewport={{ once: true }}
        transition={{ delay: index * 0.1, type: 'spring', stiffness: 100 }}
        onMouseMove={(e) => {
          const rect = e.currentTarget.getBoundingClientRect();
          const centerX = rect.left + rect.width / 2;
          const centerY = rect.top + rect.height / 2;
          x.set(e.clientX - centerX);
          y.set(e.clientY - centerY);
        }}
        onMouseLeave={() => {
          x.set(0);
          y.set(0);
        }}
        style={{ rotateX, rotateY, transformStyle: 'preserve-3d' }}
        className="group relative bg-white rounded-3xl shadow-premium hover:shadow-luxury transition-all duration-500 overflow-hidden"
      >
        {/* Gradient Header with Animation */}
        <div className={`h-2 bg-linear-to-r ${job.color} gradient-animate`}></div>

        <div className="p-6 relative z-10">
          {/* Job Type Badge */ }
          <div className="flex items-center justify-between mb-4">
            <motion.span
              className={`px-3 py-1.5 bg-linear-to-r ${job.color} text-white rounded-full text-xs font-bold shadow-lg`}
              whileHover={{ scale: 1.1 }}
            >
              {job.type}
            </motion.span>
            <span className="text-gray-400 text-xs font-bold">
              {job.category}
            </span>
          </div>

          {/* Job Title */}
          <h3 className="text-2xl font-black text-gray-800 mb-3 group-hover:text-red-600 transition-colors">
            {job.title}
          </h3>

          {/* Location */}
          <div className="flex items-center gap-2 text-gray-600 mb-4">
            <span className="text-sm font-bold"></span>
            <span className="text-sm font-medium">{job.location}</span>
          </div>

          {/* Stats with Gold Accents */}
          <div className="space-y-2 mb-4 pb-4 border-b-2 border-gray-100">
            <div className="flex items-center gap-2 text-gray-600">
              <span className="font-bold text-red-600">👥</span>
              <span className="text-sm font-bold">{job.applicants} applicants</span>
            </div>
            <div className="flex items-center gap-2 text-gray-600">
              <span className="font-bold text-red-600">📅</span>
              <span className="text-sm font-medium">{job.deadline}</span>
            </div>
            <div className="flex items-center gap-2 text-gray-600">
              <span className="font-bold text-red-600">👤</span>
              <span className="text-sm font-medium">{job.slotsFilled}/{job.slotsTotal} slots</span>
            </div>
          </div>

          {/* Apply Button with Premium Effect */}
          <motion.a
            href={isLoggedIn ? `/jobs/${job.id}` : '/login'}
            whileHover={{ scale: 1.05 }}
            whileTap={{ scale: 0.95 }}
            className={`w-full py-3.5 bg-linear-to-r ${job.color} text-white font-bold rounded-xl shadow-lg hover:shadow-2xl transition-all flex items-center justify-center gap-2 group/btn relative overflow-hidden`}
          >
            <motion.div
              className="absolute inset-0 bg-white/20"
              initial={{ x: '-100%' }}
              whileHover={{ x: '100%' }}
              transition={{ duration: 0.6 }}
            />
            <span className="relative z-10">Apply Now →</span>
          </motion.a>
        </div>

        {/* Floating Sparkle Effect */}
        <motion.div
          className="absolute top-4 right-4 w-2 h-2 bg-yellow-400 rounded-full"
          animate={{
            scale: [1, 1.5, 1],
            opacity: [0.5, 1, 0.5]
          }}
          transition={{ duration: 2, repeat: Infinity }}
        />

        {/* Hover Glow Effect */}
        <div className={`absolute inset-0 bg-linear-to-br ${job.color} opacity-0 group-hover:opacity-5 transition-opacity pointer-events-none`}></div>
      </motion.div>
    );
  };

  return (
    <section id="jobs" className="py-24 bg-linear-to-b from-[#6B1625] via-[#8B2332] to-[#4A0F1C] relative overflow-hidden">
      {/* Olympic Rings Pattern - Background */}
      <div className="absolute inset-0 opacity-5">
        <svg className="absolute top-20 left-20 w-64 h-40" viewBox="0 0 300 200">
          <circle cx="50" cy="50" r="40" fill="none" stroke="#0085C7" strokeWidth="8" />
          <circle cx="130" cy="50" r="40" fill="none" stroke="#000000" strokeWidth="8" />
          <circle cx="210" cy="50" r="40" fill="none" stroke="#EE334E" strokeWidth="8" />
          <circle cx="90" cy="90" r="40" fill="none" stroke="#FCB131" strokeWidth="8" />
          <circle cx="170" cy="90" r="40" fill="none" stroke="#00A651" strokeWidth="8" />
        </svg>
        <svg className="absolute bottom-32 right-32 w-64 h-40 rotate-12" viewBox="0 0 300 200">
          <circle cx="50" cy="50" r="40" fill="none" stroke="#0085C7" strokeWidth="8" />
          <circle cx="130" cy="50" r="40" fill="none" stroke="#000000" strokeWidth="8" />
          <circle cx="210" cy="50" r="40" fill="none" stroke="#EE334E" strokeWidth="8" />
          <circle cx="90" cy="90" r="40" fill="none" stroke="#FCB131" strokeWidth="8" />
          <circle cx="170" cy="90" r="40" fill="none" stroke="#00A651" strokeWidth="8" />
        </svg>
      </div>

      {/* Flying Garuda Silhouettes */}
      <motion.div
        className="absolute top-40 left-1/4 w-32 h-32 opacity-10"
        animate={{
          x: [0, 200, 400, 600],
          y: [0, -30, -20, -50],
          rotate: [0, 5, -5, 0]
        }}
        transition={{ duration: 25, repeat: Infinity, ease: "linear" }}
      >
        <svg viewBox="0 0 100 100" fill="white">
          <path d="M50,20 Q30,30 20,50 Q30,55 50,60 Q70,55 80,50 Q70,30 50,20 M35,45 L25,55 L35,50 M65,45 L75,55 L65,50" />
        </svg>
      </motion.div>

      <motion.div
        className="absolute top-60 right-1/3 w-24 h-24 opacity-8"
        animate={{
          x: [0, -150, -300, -450],
          y: [0, 40, 20, 60],
          rotate: [0, -8, 5, -3]
        }}
        transition={{ duration: 20, repeat: Infinity, ease: "linear", delay: 3 }}
      >
        <svg viewBox="0 0 100 100" fill="white">
          <path d="M50,20 Q30,30 20,50 Q30,55 50,60 Q70,55 80,50 Q70,30 50,20 M35,45 L25,55 L35,50 M65,45 L75,55 L65,50" />
        </svg>
      </motion.div>

      <motion.div
        className="absolute bottom-40 left-1/2 w-28 h-28 opacity-10"
        animate={{
          x: [0, 250, 500],
          y: [0, -40, -80],
          rotate: [0, 10, -5]
        }}
        transition={{ duration: 30, repeat: Infinity, ease: "linear", delay: 8 }}
      >
        <svg viewBox="0 0 100 100" fill="#D4FF00">
          <path d="M50,20 Q30,30 20,50 Q30,55 50,60 Q70,55 80,50 Q70,30 50,20 M35,45 L25,55 L35,50 M65,45 L75,55 L65,50" />
        </svg>
      </motion.div>

      {/* Athletic Silhouettes */}
      <motion.div
        className="absolute top-1/4 right-20 opacity-5"
        animate={{
          y: [0, -20, 0],
          rotate: [0, 5, 0]
        }}
        transition={{ duration: 4, repeat: Infinity }}
      >
        <svg width="120" height="150" viewBox="0 0 100 150" fill="white">
          {/* Runner silhouette */}
          <ellipse cx="50" cy="15" rx="12" ry="15" />
          <path d="M50,30 L50,70 M50,45 L30,60 M50,45 L70,55 M50,70 L35,110 M50,70 L65,105" strokeWidth="6" stroke="white" fill="none" />
        </svg>
      </motion.div>

      <motion.div
        className="absolute bottom-1/3 left-16 opacity-5"
        animate={{
          x: [0, 15, 0],
          rotate: [0, -10, 0]
        }}
        transition={{ duration: 5, repeat: Infinity }}
      >
        <svg width="100" height="140" viewBox="0 0 100 150" fill="#D4FF00">
          {/* Jumping athlete */}
          <ellipse cx="50" cy="20" rx="12" ry="15" />
          <path d="M50,35 L50,65 M50,45 L25,50 M50,45 L75,50 M50,65 L40,95 M50,65 L60,95" strokeWidth="6" stroke="#D4FF00" fill="none" />
        </svg>
      </motion.div>

      {/* Geometric Sport Patterns */}
      <div className="absolute top-0 left-0 w-full h-full opacity-5">
        <svg className="absolute top-1/3 left-10" width="80" height="80">
          <polygon points="40,10 70,30 70,70 40,90 10,70 10,30" fill="none" stroke="white" strokeWidth="3" />
        </svg>
        <svg className="absolute bottom-1/4 right-1/4 rotate-45" width="60" height="60">
          <polygon points="30,5 55,15 55,45 30,55 5,45 5,15" fill="none" stroke="#D4FF00" strokeWidth="2" />
        </svg>
      </div>

      {/* Floating Background Elements */}
      <motion.div
        className="absolute top-20 left-10 w-72 h-72 bg-[#D4FF00]/10 rounded-full blur-3xl"
        animate={{
          y: [0, 50, 0],
          scale: [1, 1.1, 1]
        }}
        transition={{ duration: 8, repeat: Infinity }}
      />
      <motion.div
        className="absolute bottom-20 right-10 w-96 h-96 bg-yellow-400/10 rounded-full blur-3xl"
        animate={{
          y: [0, -50, 0],
          scale: [1, 1.2, 1]
        }}
        transition={{ duration: 10, repeat: Infinity }}
      />

      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        {/* Header */}
        <motion.div
          initial={{ opacity: 0, y: 20 }}
          whileInView={{ opacity: 1, y: 0 }}
          viewport={{ once: true }}
          transition={{ type: 'spring', stiffness: 100 }}
          className="text-center mb-16"
        >
          <motion.div
            className="inline-flex items-center gap-2 px-5 py-2.5 bg-[#D4FF00] rounded-full mb-6 shadow-lg"
            whileHover={{ scale: 1.05 }}
          >
            <span className="text-[#4A0F1C] font-bold text-sm"> CAREER OPPORTUNITIES</span>
          </motion.div>
          <motion.h2
            className="text-5xl md:text-7xl font-black mb-4"
            initial={{ opacity: 0, scale: 0.9 }}
            whileInView={{ opacity: 1, scale: 1 }}
            viewport={{ once: true }}
          >
            <span className="text-white">Join the </span>
            <span className="text-[#D4FF00]">Revolution</span>
            <br />
            <span className="text-white">
              in Sports Management
            </span>
          </motion.h2>
          <p className="text-xl text-gray-300 max-w-2xl mx-auto">
            6 posisi Liaison Officer tersedia untuk event olahraga internasional — Daftar sekarang sebelum kuota penuh!
          </p>
        </motion.div>

        {/* Jobs Grid */}
        {loading && (
          <div className="text-center py-12">
            <div className="inline-block animate-spin rounded-full h-12 w-12 border-4 border-yellow-400 border-t-transparent"></div>
            <p className="mt-4 text-gray-300">Loading jobs...</p>
          </div>
        )}

        {error && (
          <div className="text-center py-12">
            <p className="text-red-400 font-semibold mb-4">{error}</p>
            <button
              onClick={fetchJobs}
              className="px-6 py-2 bg-yellow-400 text-gray-900 rounded-lg hover:bg-yellow-300 transition-colors font-semibold"
            >
              Retry
            </button>
          </div>
        )}

        {!loading && !error && jobs.length === 0 && (
          <div className="text-center py-12">
            <p className="text-gray-400">No jobs available at the moment.</p>
          </div>
        )}

        {!loading && !error && jobs.length > 0 && (
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            {jobs.map((job, index) => (
              <CardWithParallax key={job.id} job={job} index={index} />
            ))}
          </div>
        )}

        {/* View All Jobs Button */}
        <motion.div
          initial={{ opacity: 0 }}
          whileInView={{ opacity: 1 }}
          viewport={{ once: true }}
          className="text-center mt-16"
        >
          <motion.button
            whileHover={{ scale: 1.05 }}
            whileTap={{ scale: 0.95 }}
            onClick={() => window.location.href = '/jobs'}
            className="px-10 py-4 bg-[#D4FF00] text-[#4A0F1C] font-bold rounded-full hover:bg-[#E5FF33] transition-all shadow-lg hover:shadow-2xl relative overflow-hidden group"
          >
            <motion.div
              className="absolute inset-0 bg-white/20"
              initial={{ x: '-100%' }}
              whileHover={{ x: '100%' }}
              transition={{ duration: 0.4 }}
            />
            <span className="relative z-10">View All Job Openings</span>
          </motion.button>
        </motion.div>
      </div>
    </section>
  );
}
