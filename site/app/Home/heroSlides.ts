import type { Slide } from "./components/HeroSlider";

// Single source of truth for hero slides (desktop + optional mobile images)
export const heroSlides: Slide[] = [
  {
    id: 1,
    image: "/slider/newyear.webp",
    mobileImage: "/slider/Axis-y-mobile.webp",
    ctaLink: "/category/makeup",
  },
  {
    id: 2,
    image: "/slider/christmas.png",
    mobileImage: "/slider/freedom-mobile.webp",
    ctaLink: "/deals",
  },
  {
    id: 3,
    image: "/slider/Freedom.webp",
    mobileImage: "/slider/myglamm-mobile.webp",
    ctaLink: "/deals",
  },
  {
    id: 4,
    image: "/slider/Freedom1.webp",
    // mobileImage: "/slider/Freedom1-mobile.webp",
    ctaLink: "/deals",
  },
  {
    id: 5,
    image: "/slider/Freedom2.webp",
    // mobileImage: "/slider/Freedom2-mobile.webp",
    ctaLink: "/deals",
  },
];
