import React, { useEffect, useState } from 'react';
import { motion } from 'motion/react';

interface Item {
  id: number;
  name: string;
  logo_url: string | null;
  website: string | null;
  initial: string;
}

function MarqueeCard({ item, badgeColor, badgeTextColor }: { item: Item; badgeColor: string; badgeTextColor: string }) {
  const inner = (
    <motion.div
      whileHover={{ scale: 1.1, zIndex: 10 }}
      className="relative flex-shrink-0 w-80 h-96 rounded-2xl overflow-hidden group cursor-pointer"
    >
      {/* Background */}
      {item.logo_url ? (
        <div
          className="absolute inset-0 bg-cover bg-center transition-transform duration-700 group-hover:scale-110"
          style={{ backgroundImage: `url('${item.logo_url}')` }}
        >
          <div className="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent" />
        </div>
      ) : (
        <div className={`absolute inset-0 flex items-center justify-center ${badgeColor}`}>
          <span className={`text-7xl font-black ${badgeTextColor}`}>{item.initial}</span>
          <div className="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent" />
        </div>
      )}

      {/* Badge */}
      <div className="absolute top-4 right-4">
        <motion.div
          animate={{ rotate: [0, 5, -5, 0] }}
          transition={{ duration: 2, repeat: Infinity }}
          className={`px-4 py-2 ${badgeColor} ${badgeTextColor} font-bold rounded-full text-sm`}
        >
          {item.name}
        </motion.div>
      </div>

      {/* Hover Overlay */}
      <div className="absolute inset-0 bg-red-600/0 group-hover:bg-red-600/20 transition-colors duration-300" />
    </motion.div>
  );

  if (item.website) {
    return (
      <a href={item.website} target="_blank" rel="noopener noreferrer" className="flex-shrink-0">
        {inner}
      </a>
    );
  }
  return <div className="flex-shrink-0">{inner}</div>;
}

export function FloatingAthletesMarquee() {
  const [partners, setPartners] = useState<Item[]>([]);
  const [clients, setClients] = useState<Item[]>([]);

  useEffect(() => {
    fetch('/api/partners')
      .then((r) => r.json())
      .then((data) => setPartners(data))
      .catch(() => {});

    fetch('/api/clients')
      .then((r) => r.json())
      .then((data) => setClients(data))
      .catch(() => {});
  }, []);

  // Duplicate items enough times to fill the marquee seamlessly
  const fill = <T,>(arr: T[]) => {
    if (arr.length === 0) return [];
    const repeat = Math.max(3, Math.ceil(12 / arr.length));
    return Array.from({ length: repeat }, () => arr).flat();
  };

  const duplicatedPartners = fill(partners);
  const duplicatedClients  = fill(clients);

  return (
    <section id="our-partners" className="relative py-24 bg-linear-to-b from-red-950 to-black overflow-hidden">

      {/* --- Our Partner Row --- */}
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-10">
        <motion.h2
          initial={{ opacity: 0, y: 20 }}
          whileInView={{ opacity: 1, y: 0 }}
          viewport={{ once: true }}
          className="text-5xl md:text-7xl font-black text-white text-center mb-4"
        >
          Our Partner
        </motion.h2>
        <p className="text-xl text-gray-400 text-center">
          Trusted organizations powering Indonesian sports
        </p>
      </div>

      <div className="relative mb-16 overflow-hidden min-h-[100px] flex items-center justify-center">
        {duplicatedPartners.length > 0 ? (
          <motion.div
            animate={{ x: [0, -(duplicatedPartners.length / 2) * (320 + 24)] }}
            transition={{ duration: duplicatedPartners.length * 3, repeat: Infinity, ease: 'linear' }}
            className="flex gap-6"
          >
            {duplicatedPartners.map((item, index) => (
              <MarqueeCard key={`p-${item.id}-${index}`} item={item} badgeColor="bg-[#C5D82F]" badgeTextColor="text-black" />
            ))}
          </motion.div>
        ) : (
          <p className="text-gray-500 text-lg italic">Partner belum ditambahkan</p>
        )}
      </div>

      {/* --- Our Client Row --- */}
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-10">
        <motion.h2
          initial={{ opacity: 0, y: 20 }}
          whileInView={{ opacity: 1, y: 0 }}
          viewport={{ once: true }}
          className="text-5xl md:text-7xl font-black text-white text-center mb-4"
        >
          Our Client
        </motion.h2>
        <p className="text-xl text-gray-400 text-center">
          Organizations we proudly serve
        </p>
      </div>

      <div className="relative overflow-hidden min-h-[100px] flex items-center justify-center">
        {duplicatedClients.length > 0 ? (
          <motion.div
            animate={{ x: [-(duplicatedClients.length / 2) * (320 + 24), 0] }}
            transition={{ duration: duplicatedClients.length * 3, repeat: Infinity, ease: 'linear' }}
            className="flex gap-6"
          >
            {duplicatedClients.map((item, index) => (
              <MarqueeCard key={`c-${item.id}-${index}`} item={item} badgeColor="bg-white" badgeTextColor="text-black" />
            ))}
          </motion.div>
        ) : (
          <p className="text-gray-500 text-lg italic">Client belum ditambahkan</p>
        )}
      </div>

      {/* Decorative Gradient Overlays */}
      <div className="absolute top-0 left-0 w-32 h-full bg-linear-to-r from-red-950 to-transparent pointer-events-none z-10"></div>
      <div className="absolute top-0 right-0 w-32 h-full bg-linear-to-l from-black to-transparent pointer-events-none z-10"></div>
    </section>
  );
}
