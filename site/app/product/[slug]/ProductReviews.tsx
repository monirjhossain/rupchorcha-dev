"use client";

import React, { useEffect, useState } from "react";
import styles from "./ProductReviews.module.css";
import { useAuth } from "@/app/contexts/AuthContext";
import { openLoginModal } from "@/src/utils/loginModal";

const API_BASE = process.env.NEXT_PUBLIC_API_URL || "http://localhost:8000/api";

interface ReviewUser {
  id: number;
  name: string;
}

interface ReviewItem {
  id: number;
  user_id: number;
  product_id: number;
  rating: number;
  comment: string | null;
  created_at: string;
  user?: ReviewUser;
  images?: string[];
}

interface RatingSummary {
  average_rating: number;
  total_reviews: number;
}

interface ProductReviewsProps {
  productId: number;
  initialAverage?: number;
  initialCount?: number;
}

const ProductReviews: React.FC<ProductReviewsProps> = ({
  productId,
  initialAverage,
  initialCount,
}) => {
  const { isAuthenticated, token, user } = useAuth();

  const [ratingSummary, setRatingSummary] = useState<RatingSummary>({
    average_rating: initialAverage ?? 0,
    total_reviews: initialCount ?? 0,
  });
  const [reviews, setReviews] = useState<ReviewItem[]>([]);
  const [loadingList, setLoadingList] = useState<boolean>(true);
  const [loadingSubmit, setLoadingSubmit] = useState<boolean>(false);
  const [error, setError] = useState<string | null>(null);
  const [success, setSuccess] = useState<string | null>(null);

  const [newRating, setNewRating] = useState<number>(5);
  const [newComment, setNewComment] = useState<string>("");
  const [files, setFiles] = useState<File[]>([]);
  const [filePreviews, setFilePreviews] = useState<string[]>([]);
  const [currentPage, setCurrentPage] = useState<number>(1);

  const hasUserReview = user
    ? reviews.some((r) => r.user_id === user.id)
    : false;

  const loadData = async () => {
    setLoadingList(true);
    setError(null);
    try {
      const [ratingRes, listRes] = await Promise.all([
        fetch(`${API_BASE}/products/${productId}/rating`, { cache: "no-store" }),
        fetch(`${API_BASE}/reviews/${productId}`, { cache: "no-store" }),
      ]);

      if (ratingRes.ok) {
        const ratingData = await ratingRes.json();
        if (ratingData && ratingData.success) {
          setRatingSummary({
            average_rating: ratingData.average_rating ?? 0,
            total_reviews: ratingData.total_reviews ?? 0,
          });
        }
      }

      if (listRes.ok) {
        const listData = await listRes.json();
        if (listData && listData.success && Array.isArray(listData.data)) {
          setReviews(listData.data);
          setCurrentPage(1);
        }
      }
    } catch (err) {
      console.error("Failed to load reviews", err);
      setError("Could not load reviews right now. Please try again later.");
    } finally {
      setLoadingList(false);
    }
  };

  useEffect(() => {
    loadData();
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [productId]);

  const handleFileChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    const list = e.target.files;
    if (!list || list.length === 0) {
      setFiles([]);
      setFilePreviews((prev) => {
        prev.forEach((url) => URL.revokeObjectURL(url));
        return [];
      });
      return;
    }

    const fileArray = Array.from(list);
    setFiles(fileArray);
    setFilePreviews((prev) => {
      prev.forEach((url) => URL.revokeObjectURL(url));
      return fileArray.map((file) => URL.createObjectURL(file));
    });
  };

  const handleRemoveFile = (index: number) => {
    setFiles((prevFiles) => prevFiles.filter((_, i) => i !== index));
    setFilePreviews((prev) => {
      if (index >= 0 && index < prev.length) {
        URL.revokeObjectURL(prev[index]);
      }
      return prev.filter((_, i) => i !== index);
    });
  };

  const handleStartReview = () => {
    setError(null);
    setSuccess(null);

    if (!isAuthenticated) {
      openLoginModal();
      return;
    }
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setError(null);
    setSuccess(null);

    if (!isAuthenticated || !token) {
      openLoginModal();
      return;
    }

    if (hasUserReview) {
      setError("You have already reviewed this product.");
      return;
    }

    if (newRating < 1 || newRating > 5) {
      setError("Rating must be between 1 and 5 stars.");
      return;
    }

    setLoadingSubmit(true);
    try {
      const formData = new FormData();
      formData.append("product_id", String(productId));
      formData.append("rating", String(newRating));
      if (newComment) {
        formData.append("comment", newComment);
      }
      files.forEach((file) => {
        formData.append("images[]", file);
      });

      const res = await fetch(`${API_BASE}/reviews`, {
        method: "POST",
        headers: {
          Authorization: `Bearer ${token}`,
        },
        body: formData,
      });

      const data = await res.json();
      if (!res.ok || !data.success) {
        const message = data?.message || "Could not submit review.";
        throw new Error(message);
      }

      setNewRating(5);
      setNewComment("");
      setFiles([]);
      setFilePreviews((prev) => {
        prev.forEach((url) => URL.revokeObjectURL(url));
        return [];
      });
      setSuccess("Thank you! Your review has been submitted.");
      await loadData();
    } catch (err: any) {
      console.error("Submit review failed", err);
      setError(err.message || "Could not submit review.");
    } finally {
      setLoadingSubmit(false);
    }
  };

  const formatDate = (iso: string) => {
    try {
      return new Date(iso).toLocaleDateString(undefined, {
        year: "numeric",
        month: "short",
        day: "numeric",
      });
    } catch {
      return "";
    }
  };

  const REVIEWS_PER_PAGE = 5;
  const totalPages = Math.ceil(reviews.length / REVIEWS_PER_PAGE) || 1;
  const paginatedReviews = reviews.slice(
    (currentPage - 1) * REVIEWS_PER_PAGE,
    currentPage * REVIEWS_PER_PAGE
  );

  return (
    <section className={styles.reviewsSection} aria-label="Reviews and ratings">
      <div className={styles.headerRow}>
        <h2 className={styles.heading}>Reviews &amp; Ratings</h2>
        <div className={styles.summaryBox}>
          <div className={styles.summaryMain}>
            <div className={styles.average}>{
              ratingSummary.average_rating
                ? ratingSummary.average_rating.toFixed(1)
                : "0.0"
            }</div>
            <div className={styles.stars} aria-hidden="true">
              {"★★★★★".split("").map((star, i) => (
                <span
                  key={i}
                  className={
                    i < Math.round(ratingSummary.average_rating || 0)
                      ? styles.starFilled
                      : styles.starEmpty
                  }
                >
                  ★
                </span>
              ))}
            </div>
          </div>
          <div className={styles.summaryMeta}>
            <div className={styles.countText}>
              {ratingSummary.total_reviews} review
              {ratingSummary.total_reviews === 1 ? "" : "s"}
            </div>
            <button
              type="button"
              className={styles.writeButton}
              onClick={handleStartReview}
            >
              Write a review
            </button>
          </div>
        </div>
      </div>

      {/* Review form */}
      {isAuthenticated && !hasUserReview && (
        <form className={styles.form} onSubmit={handleSubmit}>
          <div className={styles.formRow}>
            <label className={styles.label}>Your rating</label>
            <div className={styles.ratingInput}>
              {[1, 2, 3, 4, 5].map((value) => (
                <button
                  key={value}
                  type="button"
                  className={
                    value <= newRating
                      ? styles.ratingStarActive
                      : styles.ratingStar
                  }
                  onClick={() => setNewRating(value)}
                  aria-label={`${value} star${value > 1 ? "s" : ""}`}
                >
                  ★
                </button>
              ))}
              <span className={styles.ratingValue}>{newRating}/5</span>
            </div>
          </div>
          <div className={styles.formRow}>
            <label className={styles.label}>Your review (optional)</label>
            <textarea
              className={styles.textarea}
              rows={3}
              placeholder="Share your experience with this product"
              value={newComment}
              onChange={(e) => setNewComment(e.target.value)}
            />
          </div>
          <div className={styles.formRow}>
            <label className={styles.label}>Upload images (optional)</label>
            <input
              type="file"
              multiple
              accept="image/*"
              onChange={handleFileChange}
            />
            {filePreviews.length > 0 && (
              <div className={styles.previewImages}>
                {filePreviews.map((src, idx) => (
                  <div key={idx} className={styles.previewItem}>
                    <img
                      src={src}
                      alt="Selected review image"
                      className={styles.previewImage}
                    />
                    <button
                      type="button"
                      className={styles.previewRemove}
                      onClick={() => handleRemoveFile(idx)}
                      aria-label="Remove image"
                    >
                      ×
                    </button>
                  </div>
                ))}
              </div>
            )}
          </div>
          {error && <div className={styles.error}>{error}</div>}
          {success && <div className={styles.success}>{success}</div>}
          <button
            type="submit"
            className={styles.submitButton}
            disabled={loadingSubmit}
          >
            {loadingSubmit ? "Submitting..." : "Submit review"}
          </button>
        </form>
      )}

      {!isAuthenticated && (
        <p className={styles.loginHint}>
          Please log in to write a review.
        </p>
      )}

      {isAuthenticated && hasUserReview && (
        <p className={styles.loginHint}>
          You have already submitted a review for this product.
        </p>
      )}

      {/* Reviews list */}
      <div className={styles.listWrapper}>
        {loadingList ? (
          <div className={styles.loading}>Loading reviews...</div>
        ) : reviews.length === 0 ? (
          <div className={styles.empty}>No reviews yet. Be the first!</div>
        ) : (
          <ul className={styles.list}>
            {paginatedReviews.map((review) => (
              <li key={review.id} className={styles.reviewItem}>
                <div className={styles.reviewHeader}>
                  <div className={styles.reviewerName}>
                    {review.user?.name || "Customer"}
                  </div>
                  <div className={styles.reviewMeta}>
                    <span className={styles.reviewStars}>
                      {Array.from({ length: 5 }).map((_, idx) => (
                        <span
                          key={idx}
                          className={
                            idx < review.rating
                              ? styles.starFilled
                              : styles.starEmpty
                          }
                        >
                          ★
                        </span>
                      ))}
                    </span>
                    <span className={styles.reviewDate}>
                      {formatDate(review.created_at)}
                    </span>
                  </div>
                </div>
                {review.comment && (
                  <p className={styles.reviewText}>{review.comment}</p>
                )}
                {review.images && review.images.length > 0 && (
                  <div className={styles.reviewImages}>
                    {review.images.map((img, idx) => (
                      <img
                        key={idx}
                        src={img.startsWith("http")
                          ? img
                          : `${
                              process.env.NEXT_PUBLIC_IMAGE_BASE_URL ||
                              (process.env.NEXT_PUBLIC_API_URL
                                ? process.env.NEXT_PUBLIC_API_URL.replace(/\/api$/, "")
                                : "http://localhost:8000")
                            }/storage/${img}`}
                        alt="Review image"
                        className={styles.reviewImage}
                        loading="lazy"
                      />
                    ))}
                  </div>
                )}
              </li>
            ))}
          </ul>
        )}
        {reviews.length > REVIEWS_PER_PAGE && (
          <div className={styles.pagination}>
            <button
              type="button"
              className={styles.pageBtn}
              disabled={currentPage === 1}
              onClick={() => setCurrentPage((p) => Math.max(1, p - 1))}
            >
              Previous
            </button>
            <span className={styles.pageInfo}>
              Page {currentPage} of {totalPages}
            </span>
            <button
              type="button"
              className={styles.pageBtn}
              disabled={currentPage === totalPages}
              onClick={() =>
                setCurrentPage((p) => Math.min(totalPages, p + 1))
              }
            >
              Next
            </button>
          </div>
        )}
      </div>
    </section>
  );
};

export default ProductReviews;
