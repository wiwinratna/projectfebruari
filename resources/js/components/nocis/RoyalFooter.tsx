import { Facebook, Twitter, Instagram, Linkedin, Mail, Phone, MapPin } from 'lucide-react';
import React from 'react';
import { motion } from 'motion/react';

interface FooterLink {
  label: string;
  href: string;
}

interface FooterConfigResponse {
  brand_description?: string | null;
  quick_links_title?: string | null;
  connect_title?: string | null;
  quick_links?: FooterLink[] | null;
  legal_links?: FooterLink[] | null;
  facebook_url?: string | null;
  twitter_url?: string | null;
  instagram_url?: string | null;
  linkedin_url?: string | null;
  address_text?: string | null;
  address_url?: string | null;
  phone_text?: string | null;
  phone_url?: string | null;
  email_text?: string | null;
  email_url?: string | null;
  copyright_text?: string | null;
}

export function RoyalFooter() {
  const currentYear = new Date().getFullYear();
  const [socialLinks, setSocialLinks] = React.useState([
    { icon: Facebook, color: 'hover:text-blue-400', href: 'https://facebook.com' },
    { icon: Twitter, color: 'hover:text-sky-400', href: 'https://x.com' },
    { icon: Instagram, color: 'hover:text-pink-400', href: 'https://www.instagram.com/arisegames' },
    { icon: Linkedin, color: 'hover:text-blue-500', href: 'https://linkedin.com' },
  ]);

  const [quickLinks, setQuickLinks] = React.useState<FooterLink[]>([
    { label: 'Job Openings', href: '/jobs' },
    { label: 'About ARISE', href: '#about' },
    { label: 'Our Partners', href: '#our-partners' },
    { label: 'Events', href: '#news' },
    { label: 'Contact Us', href: '#contact' },
  ]);

  const [legalLinks, setLegalLinks] = React.useState<FooterLink[]>([
    { label: 'Privacy', href: '/register' },
    { label: 'Terms', href: '/register' },
    { label: 'Cookies', href: '/register' },
  ]);

  const [copy, setCopy] = React.useState({
    brandDescription: 'Revolutionizing sports workforce management through innovative technology and dedicated service.',
    quickLinksTitle: 'Quick Links',
    connectTitle: 'Connect With Us',
    addressText: 'Jakarta, Indonesia',
    addressUrl: 'https://maps.google.com/?q=Jakarta,Indonesia',
    phoneText: '+62 21 1234 5678',
    phoneUrl: 'tel:+622112345678',
    emailText: 'info@arise.id',
    emailUrl: 'mailto:info@arise.id',
    copyrightText: 'ARISE - National Olympic Academy of Indonesia System. All rights reserved.',
  });

  React.useEffect(() => {
    fetch('/api/landing-footer')
      .then((r) => r.json())
      .then((data: FooterConfigResponse) => {
        setCopy((prev) => ({
          ...prev,
          brandDescription: data.brand_description || prev.brandDescription,
          quickLinksTitle: data.quick_links_title || prev.quickLinksTitle,
          connectTitle: data.connect_title || prev.connectTitle,
          addressText: data.address_text || prev.addressText,
          addressUrl: data.address_url || prev.addressUrl,
          phoneText: data.phone_text || prev.phoneText,
          phoneUrl: data.phone_url || prev.phoneUrl,
          emailText: data.email_text || prev.emailText,
          emailUrl: data.email_url || prev.emailUrl,
          copyrightText: data.copyright_text || prev.copyrightText,
        }));

        if (Array.isArray(data.quick_links) && data.quick_links.length > 0) {
          setQuickLinks(
            data.quick_links.map((item) =>
              item.label === 'Our Partners'
                ? { ...item, href: '#our-partners' }
                : item
            )
          );
        }

        if (Array.isArray(data.legal_links) && data.legal_links.length > 0) {
          setLegalLinks(data.legal_links);
        }

        setSocialLinks((prev) => [
          { ...prev[0], href: data.facebook_url || prev[0].href },
          { ...prev[1], href: data.twitter_url || prev[1].href },
          { ...prev[2], href: data.instagram_url || prev[2].href },
          { ...prev[3], href: data.linkedin_url || prev[3].href },
        ]);
      })
      .catch(() => {
        // Keep default footer values when API is unavailable.
      });
  }, []);

  return (
    <footer id="contact" className="relative bg-linear-to-br from-gray-900 via-gray-800 to-gray-900 text-white">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12 mb-12">
          {/* Logo & Description */}
          <motion.div
            initial={{ opacity: 0, y: 30 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
          >
            <div className="flex items-center gap-2 mb-4">
              <span className="text-3xl font-bold text-blue-400">A</span>
              <span className="text-3xl font-bold text-yellow-400">R</span>
              <span className="text-3xl font-bold text-black">I</span>
              <span className="text-3xl font-bold text-green-400">S</span>
              <span className="text-3xl font-bold text-red-500">E</span>
            </div>
            <p className="text-gray-400 mb-6 leading-relaxed">
              {copy.brandDescription}
            </p>
            <div className="flex gap-3">
              {socialLinks.map((social, index) => (
                <motion.a
                  key={index}
                  href={social.href}
                  target    ="_blank"
                  rel="noopener noreferrer"
                  whileHover={{ scale: 1.2 }}
                  whileTap={{ scale: 0.9 }}
                  className={`p-2 bg-white/10 rounded-lg ${social.color} transition-colors`}
                >
                  <social.icon size={20} />
                </motion.a>
              ))}
            </div>
          </motion.div>

          {/* Quick Links */}
          <motion.div
            initial={{ opacity: 0, y: 30 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            transition={{ delay: 0.1 }}
          >
            <h3 className="text-lg font-bold mb-4">{copy.quickLinksTitle}</h3>
            <ul className="space-y-3">
              {quickLinks.map((item) => (
                <li key={item.label}>
                  <motion.a
                    href={item.href}
                    whileHover={{ x: 5 }}
                    className="text-gray-400 hover:text-white transition-all flex items-center gap-2"
                  >
                    <span className="w-1 h-1 rounded-full bg-red-500"></span>
                    {item.label}
                  </motion.a>
                </li>
              ))}
            </ul>
          </motion.div>

          {/* Connect With Us */}
          <motion.div
            initial={{ opacity: 0, y: 30 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            transition={{ delay: 0.2 }}
          >
            <h3 className="text-lg font-bold mb-4">{copy.connectTitle}</h3>
            <ul className="space-y-4">
              <li className="flex items-start gap-3 text-gray-400">
                <MapPin size={20} className="text-red-500 mt-1 shrink-0" />
                <a
                  href={copy.addressUrl}
                  target="_blank"
                  rel="noopener noreferrer"
                  className="hover:text-white transition-colors"
                >
                  {copy.addressText}
                </a>
              </li>
              <li className="flex items-center gap-3 text-gray-400">
                <Phone size={20} className="text-green-500 shrink-0" />
                <a href={copy.phoneUrl} className="hover:text-white transition-colors">{copy.phoneText}</a>
              </li>
              <li className="flex items-center gap-3 text-gray-400">
                <Mail size={20} className="text-blue-500 shrink-0" />
                <a href={copy.emailUrl} className="hover:text-white transition-colors">{copy.emailText}</a>
              </li>
            </ul>
          </motion.div>
        </div>

        {/* Bottom Bar */}
        <motion.div
          initial={{ opacity: 0 }}
          whileInView={{ opacity: 1 }}
          viewport={{ once: true }}
          className="pt-8 border-t border-gray-700"
        >
          <div className="flex flex-col md:flex-row justify-between items-center gap-4">
            <p className="text-gray-400 text-sm">
              © {currentYear} {copy.copyrightText}
            </p>
            <div className="flex gap-6 text-sm">
              {legalLinks.map((item) => (
                <motion.a
                  key={item.label}
                  href={item.href}
                  whileHover={{ scale: 1.05 }}
                  className="text-gray-400 hover:text-white transition-colors"
                >
                  {item.label}
                </motion.a>
              ))}
            </div>
          </div>
        </motion.div>
      </div>
    </footer>
  );
}
