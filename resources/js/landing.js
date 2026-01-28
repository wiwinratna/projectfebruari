// resources/js/landing.js
import gsap from "gsap";
import ScrollTrigger from "gsap/ScrollTrigger";
import Lenis from "@studio-freight/lenis";

gsap.registerPlugin(ScrollTrigger);

document.addEventListener("DOMContentLoaded", () => {
  const root = document.querySelector(".landing-scope");
  if (!root) return;

  const isTouch = "ontouchstart" in window || navigator.maxTouchPoints > 0;

  // ===============================
  // LENIS (desktop only)
  // ===============================
  let lenis = null;

  if (!isTouch) {
    lenis = new Lenis({
      lerp: 0.22, // responsif tapi tetap smooth
      smoothWheel: true,
      smoothTouch: false,
      wheelMultiplier: 1.15,
      touchMultiplier: 1.0,
    });

    // ONE raf source: GSAP ticker
    gsap.ticker.add((t) => lenis.raf(t * 1000));
    gsap.ticker.lagSmoothing(0);

    // Lenis -> ScrollTrigger
    lenis.on("scroll", ScrollTrigger.update);

    // ScrollTrigger -> Lenis (biar sinkron kalau refresh)
    ScrollTrigger.addEventListener("refresh", () => lenis?.resize?.());
  }

  // refresh after first paint (lebih stabil)
  requestAnimationFrame(() => ScrollTrigger.refresh());

  // ===============================
  // HERO: WOW OPENING (TIMELINE)
  // ===============================
  const heroTL = gsap.timeline({ defaults: { ease: "power3.out" } });

  // Background glow fade-in (kalau ada)
  heroTL.to(".landing-scope .js-fade", { opacity: 1, duration: 0.9 }, 0);

  // Atlet kiri/kanan: masuk dari samping (opening)
  heroTL.to(
    ".landing-scope .js-left",
    {
      opacity: 1,
      x: 0,
      y: 0,
      rotate: 0,
      scale: 1,
      duration: 1.05,
      ease: "back.out(1.35)",
    },
    0.10
  );

  heroTL.to(
    ".landing-scope .js-right",
    {
      opacity: 1,
      x: 0,
      y: 0,
      rotate: 0,
      scale: 1,
      duration: 1.05,
      ease: "back.out(1.35)",
    },
    0.18
  );

  // Judul: blur -> clear + scale
  heroTL.fromTo(
    ".landing-scope .hero-title",
    {
      opacity: 0,
      y: 34,
      scale: 0.965,
      filter: "blur(8px)",
    },
    {
      opacity: 1,
      y: 0,
      scale: 1,
      filter: "blur(0px)",
      duration: 1.0,
    },
    0.18
  );

  // Item hero lain (badge, typewriter, desc, divider, buttons) stagger
  heroTL.fromTo(
    ".landing-scope .js-hero:not(.hero-title)",
    { opacity: 0, y: 18 },
    { opacity: 1, y: 0, duration: 0.7, stagger: 0.12 },
    0.52
  );

  // Cards kecil di bawah hero (kalau ada js-stagger + js-scale)
  heroTL.fromTo(
    ".landing-scope .landing-hero .js-scale",
    { opacity: 0, scale: 0.96 },
    { opacity: 1, scale: 1, duration: 0.7, stagger: 0.12 },
    0.95
  );

  // ===============================
  // HERO: PARALLAX ON SCROLL (halus)
  // ===============================
  if (document.querySelector(".landing-scope .landing-hero")) {
    gsap.to(".landing-scope .js-left", {
      y: 44,
      rotate: -2,
      ease: "none",
      scrollTrigger: {
        trigger: ".landing-hero",
        start: "top top",
        end: "bottom top",
        scrub: 0.6,
      },
    });

    gsap.to(".landing-scope .js-right", {
      y: 30,
      rotate: 2,
      ease: "none",
      scrollTrigger: {
        trigger: ".landing-hero",
        start: "top top",
        end: "bottom top",
        scrub: 0.6,
      },
    });
  }

  // ===============================
  // REVEAL SECTIONS (bottom-up)
  // ===============================
  gsap.utils.toArray(".landing-scope .js-reveal").forEach((el) => {
    gsap.to(el, {
      opacity: 1,
      y: 0,
      duration: 0.9,
      ease: "power3.out",
      scrollTrigger: {
        trigger: el,
        start: "top 85%",
        once: true, // biar gak “bolak-balik” patah
      },
    });
  });

  // ===============================
  // SLIDE IN (left/right) - kalau kamu pakai class ini di section lain
  // ===============================
  gsap.utils.toArray(".landing-scope .js-from-left").forEach((el) => {
    gsap.to(el, {
      opacity: 1,
      x: 0,
      duration: 0.9,
      ease: "power3.out",
      scrollTrigger: {
        trigger: el,
        start: "top 85%",
        once: true,
      },
    });
  });

  gsap.utils.toArray(".landing-scope .js-from-right").forEach((el) => {
    gsap.to(el, {
      opacity: 1,
      x: 0,
      duration: 0.9,
      ease: "power3.out",
      scrollTrigger: {
        trigger: el,
        start: "top 85%",
        once: true,
      },
    });
  });

  // ===============================
  // STAGGER CARDS (fitur dsb)
  // ===============================
  gsap.utils.toArray(".landing-scope .js-stagger").forEach((wrap) => {
    const items = wrap.querySelectorAll(".js-scale");
    if (!items.length) return;

    gsap.to(items, {
      opacity: 1,
      scale: 1,
      duration: 0.75,
      ease: "power3.out",
      stagger: 0.10,
      scrollTrigger: {
        trigger: wrap,
        start: "top 82%",
        once: true,
      },
    });
  });

  // ===============================
  // GENERIC PARALLAX (elements with data-parallax)
  // ===============================
  gsap.utils.toArray(".landing-scope [data-parallax]").forEach((el) => {
    const amt = Number(el.getAttribute("data-parallax")) || 24;
    gsap.to(el, {
      y: amt,
      ease: "none",
      scrollTrigger: {
        trigger: el.closest("section") || el,
        start: "top bottom",
        end: "bottom top",
        scrub: 0.35,
      },
    });
  });

  // ===============================
  // NAV: indicator + smooth scroll on click + shrink on scroll
  // ===============================
  const navSurface = document.querySelector(".landing-scope .js-nav-surface");
  const links = gsap.utils.toArray(".landing-scope .js-navlink");
  const indicator = document.querySelector(".landing-scope .js-nav-indicator");

  function moveIndicatorTo(el) {
    if (!indicator || !el) return;
    const rect = el.getBoundingClientRect();
    const parent = el.parentElement.getBoundingClientRect();
    indicator.style.width = Math.max(20, rect.width - 10) + "px";
    indicator.style.transform = `translateX(${rect.left - parent.left + 5}px)`;
  }

  if (links.length && indicator) {
    // initial indicator
    moveIndicatorTo(links[0]);

    links.forEach((a) => {
      a.addEventListener("mouseenter", () => moveIndicatorTo(a));

      a.addEventListener("click", (e) => {
        const id = a.dataset.target;
        const target = document.getElementById(id);
        if (!target) return;

        e.preventDefault();

        // offset biar ga ketutup nav fixed
        const offset = -96;

        if (lenis) {
          lenis.scrollTo(target, { offset, duration: 1.0, immediate: false });
        } else {
          // native smooth: scroll ke posisi target - offset
          const top = target.getBoundingClientRect().top + window.scrollY + offset;
          window.scrollTo({ top, behavior: "smooth" });
        }
      });
    });

    // auto-active by section visibility
    const sections = links
      .map((a) => document.getElementById(a.dataset.target))
      .filter(Boolean);

    const obs = new IntersectionObserver(
      (entries) => {
        const visible = entries
          .filter((e) => e.isIntersecting)
          .sort((a, b) => b.intersectionRatio - a.intersectionRatio)[0];
        if (!visible) return;

        const activeLink = links.find((l) => l.dataset.target === visible.target.id);
        if (activeLink) moveIndicatorTo(activeLink);
      },
      { threshold: [0.25, 0.35, 0.5, 0.65] }
    );

    sections.forEach((s) => obs.observe(s));
  }

  if (navSurface) {
    ScrollTrigger.create({
      start: 0,
      end: 99999,
      onUpdate: (self) => {
        const y = self.scroll();

        gsap.to(navSurface, {
          scale: y > 40 ? 0.985 : 1,
          y: y > 40 ? -2 : 0,
          duration: 0.18,
          ease: "power2.out",
          overwrite: "auto",
        });
      },
    });
  }
});
