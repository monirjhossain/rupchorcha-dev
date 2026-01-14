"use client";

import React, { useState, useRef, useEffect } from "react";
import { useRouter } from "next/navigation";
import styles from "./SearchBox.module.css";

interface Product {
	id: number;
	name: string;
	price: number;
	sale_price?: number;
	main_image?: string;
	subtitle?: string;
	unit?: string;
	slug?: string;
	brand?: string;
}

interface Category {
	id: number;
	name: string;
	slug?: string;
	products_count?: number;
}

type Brand = string;

// Example API functions (replace with your real API calls)
async function searchProducts(query: string) {
	try {
		const res = await fetch(`http://localhost:8000/api/products?name=${encodeURIComponent(query)}`);
		if (!res.ok) {
			console.error('API error:', res.status, res.statusText);
			return { success: false, products: [] };
		}
		const contentType = res.headers.get('content-type');
		if (contentType && contentType.includes('application/json')) {
			return await res.json();
		} else {
			const text = await res.text();
			console.error('Non-JSON response:', text);
			return { success: false, products: [] };
		}
	} catch (err) {
		console.error('Fetch error:', err);
		return { success: false, products: [] };
	}
}
async function fetchCategories() {
	// Use full backend URL to avoid 404 and CORS issues
	const res = await fetch("http://localhost:8000/api/categories");
	return res.json();
}

const SearchBox = () => {
	const [searchQuery, setSearchQuery] = useState("");
	const [searchResults, setSearchResults] = useState<{
		products: Product[];
		categories: Category[];
		brands: Brand[];
	}>({
		products: [],
		categories: [],
		brands: [],
	});
	const [isDropdownOpen, setIsDropdownOpen] = useState(false);
	const [loading, setLoading] = useState(false);
	const searchRef = useRef<HTMLDivElement>(null);
	const router = useRouter();

	// Close dropdown when clicking outside
	useEffect(() => {
		const handleClickOutside = (event: MouseEvent) => {
			if (searchRef.current && !searchRef.current.contains(event.target as Node)) {
				setIsDropdownOpen(false);
			}
		};
		document.addEventListener("mousedown", handleClickOutside);
		return () => document.removeEventListener("mousedown", handleClickOutside);
	}, []);

	// Real-time search
	useEffect(() => {
		const delaySearch = setTimeout(() => {
			if (searchQuery.trim().length >= 2) {
				performSearch(searchQuery);
			} else {
				setSearchResults({ products: [], categories: [], brands: [] });
				setIsDropdownOpen(false);
			}
		}, 100); // Reduced debounce delay for faster search
		return () => clearTimeout(delaySearch);
	}, [searchQuery]);

	const performSearch = async (query: string) => {
		setLoading(true);
		setIsDropdownOpen(true);
		try {
			// Search products
			const productResponse = await searchProducts(query);
			let products = [];
			if (Array.isArray(productResponse)) {
				products = productResponse;
			} else if (productResponse.products && Array.isArray(productResponse.products.data)) {
				products = productResponse.products.data;
			} else if (Array.isArray(productResponse.products)) {
				products = productResponse.products;
			} else {
				products = [];
			}
			// Search categories
			const categoryResponse = await fetchCategories();
			const allCategories = categoryResponse.data?.data || categoryResponse.data || [];
			const filteredCategories = allCategories.filter((cat: any) =>
				cat.name?.toLowerCase().includes(query.toLowerCase())
			);
			// Extract unique brands from products
			const uniqueBrands = [
				...new Set(products.map((p: any) => p.brand).filter(Boolean)),
			] as string[];
			setSearchResults({
				products: products, // Show all products
				categories: filteredCategories.slice(0, 3),
				brands: uniqueBrands.slice(0, 3),
			});
		} catch (error) {
			// eslint-disable-next-line no-console
			console.error("Search error:", error);
		} finally {
			setLoading(false);
		}
	};

	const handleProductClick = (product: any) => {
		router.push(`/product/${product.slug}`);
		setIsDropdownOpen(false);
		setSearchQuery("");
	};
	const handleCategoryClick = (category: any) => {
		router.push(`/category/${category.slug || category.id}`);
		setIsDropdownOpen(false);
		setSearchQuery("");
	};
	const handleSearchSubmit = (e: React.FormEvent) => {
		e.preventDefault();
		if (searchQuery.trim()) {
			router.push(`/search?q=${encodeURIComponent(searchQuery)}`);
			setIsDropdownOpen(false);
		}
	};

	return (
		<div className={styles.searchBoxContainer} ref={searchRef}>
			<form onSubmit={handleSearchSubmit} className={styles.searchBox}>
				<div className={styles.searchIcon}>
					<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#ff2d7a" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
						<circle cx="11" cy="11" r="8"></circle>
						<path d="m21 21-4.35-4.35"></path>
					</svg>
				</div>
				<input
					type="text"
					className={styles.searchboxInput}
					placeholder="Search for Products, Brands..."
					value={searchQuery}
					onChange={(e) => {
						setSearchQuery(e.target.value);
						if (e.target.value.length >= 2) setIsDropdownOpen(true);
						else setIsDropdownOpen(false);
					}}
					onFocus={() => {
						if (searchQuery.length >= 2) setIsDropdownOpen(true);
					}}
				/>
				{searchQuery && (
					<button
						type="button"
						className={styles.clearBtn}
						onClick={() => {
							setSearchQuery("");
							setIsDropdownOpen(false);
						}}
					>
						✕
					</button>
				)}
			</form>
			{isDropdownOpen && (
				<div className={styles.searchDropdown} style={{maxHeight: '350px', overflowY: 'auto'}}>
					<div className={styles.searchSectionHeader} style={{padding: '12px 16px', fontWeight: 'bold', fontSize: '13px', color: '#666', borderBottom: '1px solid #eee'}}>PRODUCTS</div>
					{loading ? (
						<div className={styles.searchLoading}>
							<div className={styles.spinner}></div>
							<span>Searching...</span>
						</div>
					) : (
						<div className={styles.searchItems}>
							{searchResults.products.length === 0 && (
								<div style={{padding: '18px 20px', color: '#888', fontSize: '14px'}}>No products found</div>
							)}
							{searchResults.products.map((product: Product) => (
								<div key={product.id} className={styles.searchProductItem} onClick={() => handleProductClick(product)}>
									<div className={styles.searchSuggestionThumb}>
										{product.main_image ? (
											<img
												src={product.main_image.startsWith('http') ? product.main_image : `http://127.0.0.1:8000/storage/${product.main_image}`}
												alt={product.name}
												style={{width: '100%', height: '100%', objectFit: 'cover', borderRadius: '8px'}}
											/>
										) : (
											<div className={styles.noThumb}>📦</div>
										)}
									</div>
									<div className={styles.searchSuggestionMain}>
										<div className={styles.searchSuggestionTitle}>{product.name}</div>
										{product.subtitle && (
											<div className={styles.searchSuggestionSubtitle}>{product.subtitle}</div>
										)}
										<div className={styles.searchSuggestionPrice}>
											{product.sale_price ? (
												<>
													<span className={styles.specialPrice}>৳{product.sale_price}</span>
													<span className={styles.originalPrice}>৳{product.price}</span>
												</>
											) : (
												<span className={styles.price}>৳{product.price}</span>
											)}
											{product.unit && (
												<span style={{color: '#888', fontSize: '12px', marginLeft: '8px'}}>{product.unit}</span>
											)}
										</div>
									</div>
								</div>
							))}
						</div>
					)}
				</div>
			)}
		</div>
	);
};

export default SearchBox;
