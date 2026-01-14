
"use client";
import React, { useState, useEffect } from "react";
import Image from "next/image";
import styles from "./HeroSlider.module.css";

const slides = [
	{
		id: 1,
		image: "/slider/newyear.webp",
		ctaLink: "/category/makeup"
	},
	{
		id: 2,
		image: "/slider/christmas.png",
		ctaLink: "/deals"
	},
	{
		id: 3,
		image: "/slider/Freedom.webp",
		ctaLink: "/deals"
	},
	{
		id: 4,
		image: "/slider/Freedom1.webp",
		ctaLink: "/deals"
	},
	{
		id: 5,
		image: "/slider/Freedom2.webp",
		// title: "GIGA SALE",
		// subtitle: "Up to 50% OFF",
		// description: "On selected products",
		// cta: "Explore Deals",
		ctaLink: "/deals"
	},
	// Add more slides as needed
];

export default function HeroSlider() {
	const [current, setCurrent] = useState(0);

	useEffect(() => {
		const timer = setInterval(() => {
			setCurrent((prev) => (prev + 1) % slides.length);
		}, 5000);
		return () => clearInterval(timer);
	}, []);

	const goTo = (idx: number) => setCurrent(idx);
	const prev = () => setCurrent((prev) => (prev - 1 + slides.length) % slides.length);
	const next = () => setCurrent((prev) => (prev + 1) % slides.length);

	return (
		<section className={styles.heroSlider}>
			<div className={styles.sliderWrapper}>
				{slides.map((slide, idx) => (
					<div
						key={slide.id}
						className={
							styles.slide + (idx === current ? " " + styles.active : "")
						}
					>
						<Image
							src={slide.image}
							alt={slide.title || `Slider image ${idx + 1}`}
							fill
							className={styles.slideImg}
							priority={idx === current}
							sizes="(max-width: 1920px) 100vw, 1200px"
							quality={100}
						/>
						{/* <div className={styles.slideContent}>
							<h2 className={styles.title}>{slide.title}</h2>
							<h3 className={styles.subtitle}>{slide.subtitle}</h3>
							<p className={styles.desc}>{slide.description}</p>
							<a href={slide.ctaLink} className={styles.ctaBtn}>{slide.cta}</a>
						</div> */}
					</div>
				))}
				<button className={styles.arrow + " " + styles.left} onClick={prev}>
					&#10094;
				</button>
				<button className={styles.arrow + " " + styles.right} onClick={next}>
					&#10095;
				</button>
				<div className={styles.dots}>
					{slides.map((_, idx) => (
						<span
							key={idx}
							className={styles.dot + (idx === current ? " " + styles.activeDot : "")}
							onClick={() => goTo(idx)}
						/>
					))}
				</div>
			</div>
		</section>
	);
}
