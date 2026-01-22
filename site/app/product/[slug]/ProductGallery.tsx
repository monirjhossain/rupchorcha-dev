"use client";
// ProductGallery: Shows main image, thumbnails, and carousel for product images
import React, { useState } from "react";
import styles from "./ProductGallery.module.css";


export default function ProductGallery({ images, name }: { images: string[]; name: string }) {
  const [current, setCurrent] = useState(0);
  const [modalOpen, setModalOpen] = useState(false);

  // Fallback: if images is not array or empty, show placeholder
  if (!Array.isArray(images) || images.length === 0) {
    return (
      <div className={styles.placeholder}>
        No Image
      </div>
    );
  }

  const prev = () => setCurrent((c) => (c === 0 ? images.length - 1 : c - 1));
  const next = () => setCurrent((c) => (c === images.length - 1 ? 0 : c + 1));

  return (
    <div className={styles.gallery}>
      <div className={styles.mainImageWrapper}>
        <img
          src={images[current]}
          alt={name}
          className={styles.mainImage}
          style={{ cursor: 'zoom-in' }}
          onClick={() => setModalOpen(true)}
        />
        {modalOpen && (
          <div className={styles.modalOverlay} onClick={() => setModalOpen(false)}>
            <div className={styles.modalContent} onClick={e => e.stopPropagation()}>
              <img
                src={images[current]}
                alt={name + ' large'}
                className={styles.modalImage}
              />
              <button className={styles.modalClose} onClick={() => setModalOpen(false)} aria-label="Close large image">×</button>
            </div>
          </div>
        )}
        {images.length > 1 && (
          <>
            <button
              onClick={prev}
              className={styles.arrowBtn + ' ' + styles.arrowLeft}
              aria-label="Previous image"
            >
              <svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="12" cy="12" r="12" fill="none" />
                <path d="M15.5 19L9.5 12L15.5 5" stroke="#fff" strokeWidth="2.8" strokeLinecap="round" strokeLinejoin="round"/>
              </svg>
            </button>
            <button
              onClick={next}
              className={styles.arrowBtn + ' ' + styles.arrowRight}
              aria-label="Next image"
            >
              <svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="12" cy="12" r="12" fill="none" />
                <path d="M8.5 5L14.5 12L8.5 19" stroke="#fff" strokeWidth="2.8" strokeLinecap="round" strokeLinejoin="round"/>
              </svg>
            </button>
          </>
        )}
      </div>
      <div className={styles.thumbs}>
        {images.map((img, i) => (
          <img
            key={i}
            src={img}
            alt={name + " thumb"}
            className={
              styles.thumb + (current === i ? ' ' + styles.thumbActive : '')
            }
            onClick={() => setCurrent(i)}
          />
        ))}
      </div>
    </div>
  );
}
