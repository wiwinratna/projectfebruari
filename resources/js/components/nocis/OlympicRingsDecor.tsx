import { motion } from 'motion/react';

export function OlympicRingsDecor() {
  const rings = [
    { color: 'border-blue-600', delay: 0, x: 0, y: 0 },
    { color: 'border-yellow-400', delay: 0.2, x: 40, y: 0 },
    { color: 'border-black', delay: 0.4, x: 80, y: 0 },
    { color: 'border-green-600', delay: 0.6, x: 20, y: 20 },
    { color: 'border-red-600', delay: 0.8, x: 60, y: 20 }
  ];

  return (
    <div className="relative w-32 h-24">
      {rings.map((ring, index) => (
        <motion.div
          key={index}
          initial={{ opacity: 0, scale: 0 }}
          animate={{ opacity: 1, scale: 1 }}
          transition={{ 
            delay: ring.delay,
            type: 'spring',
            stiffness: 200,
            damping: 10
          }}
          className={`absolute w-8 h-8 rounded-full border-4 ${ring.color} olympic-ring`}
          style={{ 
            left: `${ring.x}px`,
            top: `${ring.y}px`
          }}
        />
      ))}
    </div>
  );
}
