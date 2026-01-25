"use client";

import { useState, useEffect } from "react";
import { useRouter, useSearchParams } from "next/navigation";

const VALID_SORTS = [
  "default",
  "price_low",
  "price_high",
  "name_asc",
  "name_desc",
  "newest",
];

interface UsePaginationSortOptions {
  defaultSort?: string;
}

export function usePaginationSort(options: UsePaginationSortOptions = {}) {
  const { defaultSort = "default" } = options;
  const router = useRouter();
  const searchParams = useSearchParams();

  // Read from URL on initial mount
  const pageFromUrl = parseInt(searchParams.get("page") || "1", 10);
  const sortFromUrl = searchParams.get("sort") || defaultSort;

  const [currentPage, setCurrentPage] = useState(pageFromUrl);
  const [sortBy, setSortBy] = useState(sortFromUrl);

  // Sync with URL changes
  useEffect(() => {
    const urlPage = parseInt(searchParams.get("page") || "1", 10);
    const urlSort = searchParams.get("sort") || defaultSort;

    if (urlPage !== currentPage) {
      setCurrentPage(urlPage);
    }
    if (urlSort !== sortBy) {
      setSortBy(urlSort);
    }
  }, [searchParams, currentPage, sortBy, defaultSort]);

  /**
   * Handle page change with sort parameter persistence
   * @param newPage - New page number
   * @param basePath - Base path for the route (e.g., "/shop", "/brands/samsung")
   */
  const handlePageChange = (newPage: number, basePath: string) => {
    const sortParam = sortBy !== defaultSort ? `&sort=${sortBy}` : "";
    router.push(`${basePath}?page=${newPage}${sortParam}`);
    window.scrollTo({ top: 0, behavior: "smooth" });
  };

  /**
   * Handle sort change with page reset
   * @param value - Sort value
   * @param basePath - Base path for the route
   */
  const handleSortChange = (value: string, basePath: string) => {
    // Validate sort value
    if (!VALID_SORTS.includes(value)) {
      console.warn(`Invalid sort value: ${value}`);
      return;
    }

    const sortParam = value !== defaultSort ? `&sort=${value}` : "";
    router.push(`${basePath}?page=1${sortParam}`);
    window.scrollTo({ top: 0, behavior: "smooth" });
  };

  return {
    currentPage,
    sortBy,
    handlePageChange,
    handleSortChange,
  };
}
