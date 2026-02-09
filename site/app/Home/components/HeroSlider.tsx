"use client";
import React, { useState, useEffect } from "react";
import Image from "next/image";
import styles from "./HeroSlider.module.css";
import { heroSlides } from "../heroSlides";

export type Slide = {
  id: number;
  image: string; // desktop / default image
  mobileImage?: string; // optional mobile-specific image
  title?: string;
  subtitle?: string;
  description?: string;
  cta?: string;
  ctaLink?: string;
};

const useIsMobile = () => {
  const [isMobile, setIsMobile] = useState(false);

  useEffect(() => {
    const check = () => {
      if (typeof window !== "undefined") {
        setIsMobile(window.innerWidth <= 768);
      }
    };
    check();
    window.addEventListener("resize", check);
    return () => window.removeEventListener("resize", check);
  }, []);

  return isMobile;
};

interface HeroSliderProps {
  slides?: Slide[];
}

const HeroSlider: React.FC<HeroSliderProps> = ({ slides }) => {
  const [current, setCurrent] = useState(0);
  const isMobile = useIsMobile();
  const effectiveSlides = slides && slides.length > 0 ? slides : heroSlides;

  useEffect(() => {
    // reset index when slide count changes
    setCurrent(0);
    if (effectiveSlides.length <= 1) return;

    const timer = setInterval(() => {
      setCurrent((prev) => (prev + 1) % effectiveSlides.length);
    }, 5000);

    return () => clearInterval(timer);
  }, [effectiveSlides.length]);

  const goTo = (idx: number) => setCurrent(idx);
  const prev = () => setCurrent((prev) => (prev - 1 + effectiveSlides.length) % effectiveSlides.length);
  const next = () => setCurrent((prev) => (prev + 1) % effectiveSlides.length);

  if (!effectiveSlides.length) return null;

  return (
    <section className={styles.heroSlider}>
      <div className={styles.sliderWrapper}>
        {effectiveSlides.map((slide, idx) => {
          const imageSrc = isMobile && slide.mobileImage ? slide.mobileImage : slide.image;
          const altText = slide.title || `Slider image ${idx + 1}`;

          return (
            <div
              key={slide.id}
              className={styles.slide + (idx === current ? " " + styles.active : "")}
            >
              {isMobile && slide.mobileImage ? (
                <Image
                  src={imageSrc}
                  alt={altText}
                  width={750}
                  height={400}
                  className={styles.mobileSlideImg}
                  priority={idx === current}
                  sizes="100vw"
                  quality={100}
                />
              ) : (
                <Image
                  src={imageSrc}
                  alt={altText}
                  fill
                  className={styles.slideImg}
                  priority={idx === current}
                  sizes="(max-width: 1920px) 100vw, 1200px"
                  quality={100}
                />
              )}
            </div>
          );
        })}
        {effectiveSlides.length > 1 && (
          <>
            <button className={styles.arrow + " " + styles.left} onClick={prev}>
              &#10094;
            </button>
            <button className={styles.arrow + " " + styles.right} onClick={next}>
              &#10095;
            </button>
            <div className={styles.dots}>
              {effectiveSlides.map((_, idx) => (
                <span
                  key={idx}
                  className={styles.dot + (idx === current ? " " + styles.activeDot : "")}
                  onClick={() => goTo(idx)}
                />
              ))}
            </div>
          </>
        )}
      </div>
    </section>
  );
};

export default HeroSlider;
