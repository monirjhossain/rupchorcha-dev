"use client";

import React from "react";

type PaginationControlsProps = {
  currentPage: number;
  totalPages: number;
  isLoading?: boolean;
  onPageChange: (page: number) => void;
  /**
   * numbers   -> classic Prev/Next + page numbers (default)
   * load-more -> single "Load more" button that goes to next page
   * both      -> numbers + an extra "Load more" convenience button
   */
  variant?: "numbers" | "load-more" | "both";
};

export function PaginationControls({
  currentPage,
  totalPages,
  isLoading = false,
  onPageChange,
  variant = "numbers",
}: PaginationControlsProps) {
  if (totalPages <= 1) return null;

  const getPageNumbers = () => {
    const pages: (number | string)[] = [];
    const showPages = 3;
    const start = Math.max(1, currentPage - showPages);
    const end = Math.min(totalPages, currentPage + showPages);

    if (start > 1) {
      pages.push(1);
      if (start > 2) pages.push("...");
    }

    for (let i = start; i <= end; i++) {
      pages.push(i);
    }

    if (end < totalPages) {
      if (end < totalPages - 1) pages.push("...");
      pages.push(totalPages);
    }

    return pages;
  };

  const handleSafeChange = (page: number) => {
    if (page < 1 || page > totalPages || isLoading) return;
    onPageChange(page);
  };

  const pageNumbers = getPageNumbers();

  // Pure "Load more" variant: only show a single button advancing to next page
  if (variant === "load-more") {
    const hasMore = currentPage < totalPages;
    if (!hasMore) return null;

    return (
      <div className="pagination" style={{ justifyContent: "center" }}>
        <button
          className="pagination-btn"
          onClick={() => handleSafeChange(currentPage + 1)}
          disabled={!hasMore || isLoading}
        >
          {isLoading ? "⏳" : "Load more"}
        </button>
      </div>
    );
  }

  return (
    <div className="pagination">
      <button
        className="pagination-btn"
        onClick={() => handleSafeChange(currentPage - 1)}
        disabled={currentPage === 1 || isLoading}
      >
        {isLoading ? "⏳" : "← Previous"}
      </button>

      <div className="pagination-numbers">
        {pageNumbers.map((page, idx) =>
          typeof page === "string" ? (
            <span
              key={`dots-${idx}`}
              style={{ padding: "0.5rem 0.25rem", color: "#999" }}
            >
              {page}
            </span>
          ) : (
            <button
              key={page}
              className={`pagination-number ${
                currentPage === page ? "active" : ""
              }`}
              onClick={() => handleSafeChange(page as number)}
              disabled={isLoading}
            >
              {page}
            </button>
          )
        )}
      </div>

      <button
        className="pagination-btn"
        onClick={() => handleSafeChange(currentPage + 1)}
        disabled={currentPage === totalPages || isLoading}
      >
        {isLoading ? "⏳" : "Next →"}
      </button>

      {variant === "both" && currentPage < totalPages && (
        <button
          className="pagination-btn"
          onClick={() => handleSafeChange(currentPage + 1)}
          disabled={isLoading}
          style={{ marginLeft: "1rem" }}
        >
          {isLoading ? "⏳" : "Load more"}
        </button>
      )}
    </div>
  );
}

export default PaginationControls;
