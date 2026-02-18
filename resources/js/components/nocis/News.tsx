import { ImageWithFallback } from '../figma/ImageWithFallback';
import { motion } from 'motion/react';
import { ArrowRight, Calendar, Clock, Newspaper } from 'lucide-react';
import { useState, useEffect } from 'react';

interface NewsItem {
  id: number;
  title: string;
  excerpt: string;
  image: string;
  date: string;
  readTime?: string;
  category: string;
  gradient: string;
}

export function News() {
  const [newsItems, setNewsItems] = useState<NewsItem[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);
  const [isLoggedIn, setIsLoggedIn] = useState(false);

  useEffect(() => {
    fetchNews();
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

  const fetchNews = async () => {
    try {
      setLoading(true);
      const response = await fetch('/api/news');

      if (!response.ok) {
        throw new Error('Failed to fetch news');
      }

      const result = await response.json();

      if (result.success && result.data) {
        const gradients = [
          'from-blue-500 to-cyan-500',
          'from-purple-500 to-pink-500',
          'from-green-500 to-emerald-500',
        ];

        const formattedNews = result.data.slice(0, 3).map((item: any, index: number) => ({
          id: item.id,
          title: item.title,
          excerpt: item.excerpt,
          image: item.image,
          date: item.date,
          readTime: `${Math.ceil(item.excerpt.split(' ').length / 200)} min read`,
          category: item.category || 'News Update',
          gradient: gradients[index % gradients.length],
        }));

        setNewsItems(formattedNews);
      }
      setError(null);
    } catch (err) {
      console.error('Error fetching news:', err);
      setError(err instanceof Error ? err.message : 'Failed to load news');
      setNewsItems([]);
    } finally {
      setLoading(false);
    }
  };

  return (
    <section id="news" className="relative py-24 bg-white">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {/* Header */}
        <motion.div
          initial={{ opacity: 0, y: 30 }}
          whileInView={{ opacity: 1, y: 0 }}
          viewport={{ once: true }}
          className="text-center mb-16"
        >
          <div className="inline-flex items-center gap-2 px-4 py-2 bg-linear-to-r from-red-100 to-orange-100 rounded-full mb-6">
            <Newspaper className="text-red-600" size={20} />
            <span className="text-red-700 font-semibold">Latest News</span>
          </div>

          <h2 className="text-4xl md:text-5xl font-bold text-gray-900 mb-6">
            Stay Updated with
            <br />
            <span className="bg-linear-to-r from-red-600 to-orange-600 bg-clip-text text-transparent">
              ARISE News
            </span>
          </h2>
          <p className="text-xl text-gray-600 max-w-2xl mx-auto">
            Get the latest updates, announcements, and insights from the world of sports workforce management
          </p>
        </motion.div>

        {/* Loading State */}
        {loading && (
          <div className="text-center py-12">
            <div className="inline-block animate-spin rounded-full h-12 w-12 border-4 border-red-600 border-t-transparent"></div>
            <p className="mt-4 text-gray-600">Loading news...</p>
          </div>
        )}

        {/* Error State */}
        {error && (
          <div className="text-center py-12">
            <p className="text-red-600 font-semibold mb-4">{error}</p>
            <button
              onClick={fetchNews}
              className="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors"
            >
              Retry
            </button>
          </div>
        )}

        {/* Empty State */}
        {!loading && !error && newsItems.length === 0 && (
          <div className="text-center py-12">
            <p className="text-gray-600">No news available at the moment.</p>
          </div>
        )}

        {/* News Grid */}
        {!loading && !error && newsItems.length > 0 && (
          <>
            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
              {newsItems.map((news, index) => (
                <motion.a
                  key={news.id}
                  initial={{ opacity: 0, y: 30 }}
                  whileInView={{ opacity: 1, y: 0 }}
                  viewport={{ once: true }}
                  transition={{ delay: index * 0.1 }}
                  whileHover={{ y: -10 }}
                  href={isLoggedIn ? `/news/${news.id}` : `/login?redirect=/news/${news.id}`}
                  className="group cursor-pointer"
                >
                  <div className="relative bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all">
                    {/* Image */}
                    <div className="relative h-56 overflow-hidden">
                      <ImageWithFallback
                        src={news.image}
                        alt={news.title}
                        className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                      />
                      <div className={`absolute inset-0 bg-linear-to-t ${news.gradient} opacity-20 group-hover:opacity-10 transition-opacity`}></div>

                      {/* Category Badge */}
                      <div className="absolute top-4 left-4">
                        <div className={`px-3 py-1 bg-linear-to-r ${news.gradient} rounded-full`}>
                          <span className="text-white text-sm font-semibold">{news.category}</span>
                        </div>
                      </div>
                    </div>

                    {/* Content */}
                    <div className="p-6">
                      {/* Meta Info */}
                      <div className="flex items-center gap-4 mb-4 text-sm text-gray-500">
                        <div className="flex items-center gap-1">
                          <Calendar size={16} />
                          <span>{news.date}</span>
                        </div>
                        <div className="flex items-center gap-1">
                          <Clock size={16} />
                          <span>{news.readTime}</span>
                        </div>
                      </div>

                      {/* Title */}
                      <h3 className="text-xl font-bold text-gray-900 mb-3 group-hover:text-red-600 transition-colors line-clamp-2">
                        {news.title}
                      </h3>

                      {/* Excerpt */}
                      <p className="text-gray-600 mb-4 leading-relaxed line-clamp-3">
                        {news.excerpt}
                      </p>

                      {/* Read More Link */}
                      <motion.div
                        whileHover={{ x: 5 }}
                        className="inline-flex items-center gap-2 text-red-600 font-semibold group-hover:gap-3 transition-all"
                      >
                        Read More
                        <ArrowRight size={20} />
                      </motion.div>
                    </div>

                    {/* Decorative Gradient */}
                    <div className={`absolute inset-0 bg-linear-to-t ${news.gradient} opacity-0 group-hover:opacity-5 transition-opacity pointer-events-none`}></div>
                  </div>
                </motion.a>
              ))}
            </div>

            {/* View All Button */}
            <motion.div
              initial={{ opacity: 0, y: 30 }}
              whileInView={{ opacity: 1, y: 0 }}
              viewport={{ once: true }}
              className="text-center mt-12"
            >
              <motion.a
                href={isLoggedIn ? '/news' : '/login?redirect=/news'}
                whileHover={{ scale: 1.05 }}
                whileTap={{ scale: 0.95 }}
                className="px-10 py-4 bg-linear-to-r from-red-600 to-orange-600 text-white font-bold text-lg rounded-xl shadow-2xl shadow-red-600/30 hover:shadow-red-600/50 transition-all"
              >
                View All News
              </motion.a>
            </motion.div>
          </>
        )}
      </div>
    </section>
  );
}
