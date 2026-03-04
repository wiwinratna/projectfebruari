import { motion } from 'motion/react';
import { Building2 } from 'lucide-react';
import { useEffect, useState } from 'react';

interface ClientItem {
  id: number;
  name: string;
  logo_url: string | null;
  website: string | null;
  description: string | null;
  initial: string;
}

export function Clients() {
  const [clients, setClients] = useState<ClientItem[]>([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    fetch('/api/clients')
      .then((r) => r.json())
      .then((data) => {
        setClients(data);
        setLoading(false);
      })
      .catch(() => setLoading(false));
  }, []);

  if (loading) return null;
  if (clients.length === 0) return null;

  return (
    <section className="relative py-24 bg-white">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {/* Header */}
        <motion.div
          initial={{ opacity: 0, y: 30 }}
          whileInView={{ opacity: 1, y: 0 }}
          viewport={{ once: true }}
          className="text-center mb-16"
        >
          <div className="inline-flex items-center gap-2 px-4 py-2 bg-blue-100 rounded-full mb-6">
            <Building2 className="text-blue-600" size={20} />
            <span className="text-blue-700 font-semibold">Happy Clients</span>
          </div>

          <h2 className="text-4xl md:text-5xl font-bold text-gray-900 mb-6">
            OUR CLIENTS
          </h2>
          <p className="text-xl text-gray-600 max-w-2xl mx-auto">
            Trusted by leading organizations across Indonesia and beyond
          </p>
        </motion.div>

        {/* Clients Grid */}
        <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-6">
          {clients.map((client, index) => {
            const card = (
              <div className="relative bg-white border-2 border-gray-200 rounded-2xl p-6 hover:border-blue-500 transition-all shadow-lg hover:shadow-2xl aspect-square flex items-center justify-center overflow-hidden">
                {/* Background Gradient on Hover */}
                <div className="absolute inset-0 bg-gradient-to-br from-blue-50 to-indigo-50 opacity-0 group-hover:opacity-100 transition-opacity"></div>

                {/* Logo or Initial */}
                <div className="relative text-center">
                  {client.logo_url ? (
                    <img
                      src={client.logo_url}
                      alt={client.name}
                      className="w-16 h-16 object-contain mx-auto mb-2"
                    />
                  ) : (
                    <div className="text-4xl font-bold bg-gradient-to-br from-blue-600 to-indigo-600 bg-clip-text text-transparent mb-2">
                      {client.initial}
                    </div>
                  )}
                  <div className="text-xs text-gray-600 group-hover:text-gray-900 transition-colors leading-tight">
                    {client.name}
                  </div>
                </div>

                {/* Decorative Circle */}
                <motion.div
                  className="absolute -bottom-8 -right-8 w-24 h-24 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-full opacity-5 blur-xl group-hover:opacity-20 transition-opacity"
                  animate={{ scale: [1, 1.2, 1] }}
                  transition={{ duration: 3, repeat: Infinity }}
                />
              </div>
            );

            return (
              <motion.div
                key={client.id}
                initial={{ opacity: 0, scale: 0.8 }}
                whileInView={{ opacity: 1, scale: 1 }}
                viewport={{ once: true }}
                transition={{ delay: index * 0.08 }}
                whileHover={{ scale: 1.05, y: -5 }}
                className="group"
              >
                {client.website ? (
                  <a href={client.website} target="_blank" rel="noopener noreferrer">
                    {card}
                  </a>
                ) : card}
              </motion.div>
            );
          })}
        </div>
      </div>
    </section>
  );
}
