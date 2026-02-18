import React from 'react';
import { RoyalHeader } from './components/nocis/RoyalHeader';
import { RoyalHeroVideo } from './components/nocis/RoyalHeroVideo';
import { DynamicAthletesHero } from './components/nocis/DynamicAthletesHero';
import { JobsSection } from './components/nocis/JobsSection';
import { AboutSection } from './components/nocis/AboutSection';
import { FlowSection } from './components/nocis/FlowSection';
import { FeatureCards } from './components/nocis/FeatureCards';
import { NewsSection } from './components/nocis/NewsSection';
import { AnimatedAthletes } from './components/nocis/AnimatedAthletes';
import { FloatingAthletesMarquee } from './components/nocis/FloatingAthletesMarquee';
import { RoyalFooter } from './components/nocis/RoyalFooter';

export default function App() {
  return (
    <div className="min-h-screen bg-white">
      <RoyalHeader />
      <RoyalHeroVideo />
      <DynamicAthletesHero />
      <JobsSection />
      <AboutSection />
      <FlowSection />
      <FeatureCards />
      <NewsSection />
      <AnimatedAthletes />
      <FloatingAthletesMarquee />
      <RoyalFooter />
    </div>
  );
}
