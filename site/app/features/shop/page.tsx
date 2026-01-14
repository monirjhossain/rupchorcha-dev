"use client";
import React, { useState, useEffect, useCallback } from "react";
import Header from "../components/Header";
import Sidebar from "../components/Sidebar";
import Image from "next/image";

const shopBanner = "/shop/shop-banner.png";
const shopInfo = {
  name: "Beauty Booth Official Shop",
  description: "Your trusted source for authentic beauty products. Shop top brands, exclusive deals, and enjoy fast delivery!",
  rating: 4.8,
  reviews: 1200,
  followers: 35000,
};
// Remove all mock product data. Only use products from backend API.

  useEffect(() => {
    // Load categories and brands with caching
    const loadFilters = async () => {
      try {
        const cachedCategories = typeof window !== "undefined" ? localStorage.getItem("categories_cache") : null;
        const cachedBrands = typeof window !== "undefined" ? localStorage.getItem("brands_cache") : null;
        const cacheTime = typeof window !== "undefined" ? localStorage.getItem("categories_cache_time") : null;
        const now = Date.now();
        if (cachedCategories && cacheTime && now - parseInt(cacheTime) < 300000) {
          setCategories(JSON.parse(cachedCategories));
          if (cachedBrands) {
            setBrands(JSON.parse(cachedBrands));
          }
          return;
        }
        const [categoriesResponse, brandsResponse] = await Promise.all([
          categoriesAPI.getAll(),
          brandsAPI.getAll(),
        ]);
        const categoriesData = categoriesResponse.data?.data || categoriesResponse.data || categoriesResponse;
        if (Array.isArray(categoriesData)) {
          setCategories(categoriesData);
          if (typeof window !== "undefined") {
            localStorage.setItem("categories_cache", JSON.stringify(categoriesData));
            localStorage.setItem("categories_cache_time", now.toString());
          }
        }
        const brandsData = brandsResponse.data?.data || brandsResponse.data || brandsResponse;
        if (Array.isArray(brandsData)) {
          setBrands(brandsData);
          if (typeof window !== "undefined") {
            localStorage.setItem("brands_cache", JSON.stringify(brandsData));
          }
        }
      } catch (error) {
        // handle error
      }
    };
    loadFilters();
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, []);

  const fetchProducts = useCallback(async (page: number) => {
    if (page > totalPages) return;
    try {
      setLoadingMore(true);
      const params: any = {
        page: page,
        per_page: perPage,
        min_price: priceRange[0],
        max_price: priceRange[1],
      };
      if (selectedCategories.length > 0) {
        params.categories = selectedCategories.join(",");
      }
      if (selectedBrands.length > 0) {
        params.brands = selectedBrands.join(",");
      }
      if (sortBy !== "default") {
        params.sort = sortBy;
      }
      if (searchQuery) {
        params.search = searchQuery;
      }
      const response = await productsAPI.getAll(currentPage, perPage, params);
      const productsData = response.data?.data || response.data || response;
      if (Array.isArray(productsData)) {
        setProducts((prev) => [...prev, ...productsData]);
        setTotalPages(response.data?.last_page || 1);
      } else if (productsData.data && Array.isArray(productsData.data)) {
        setProducts(productsData.data);
        setTotalPages(productsData.last_page || 1);
      } else {
        setProducts([]);
      }
    } catch (error) {
      setProducts([]);
    } finally {
      setLoading(false);
      setLoadingMore(false);
    }
  }, [totalPages, priceRange, selectedCategories, selectedBrands, sortBy, searchQuery, perPage, currentPage]);

  useEffect(() => {
    fetchProducts(currentPage);
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [currentPage, priceRange, selectedCategories, selectedBrands, sortBy, searchQuery, fetchProducts]);

  const handleCategoryToggle = (categoryId: number) => {
    setSelectedCategories((prev) =>
      prev.includes(categoryId) ? prev.filter((id) => id !== categoryId) : [...prev, categoryId]
    );
    setCurrentPage(1);
  };

  const handleBrandToggle = (brandId: number) => {
    setSelectedBrands((prev) =>
      prev.includes(brandId) ? prev.filter((id) => id !== brandId) : [...prev, brandId]
    );
    setCurrentPage(1);
  };

  const handlePriceChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    const value = parseInt(e.target.value);
    setPriceRange([0, value]);
    setCurrentPage(1);
  };

  const handleSearch = (e: React.FormEvent) => {
    e.preventDefault();
    setCurrentPage(1);
    fetchProducts(currentPage);
  };

  const handleAddToCart = async (productId: number, e?: any) => {
    if (e) {
      e.preventDefault();
      e.stopPropagation();
    }
    setAddingToCart((prev) => ({ ...prev, [productId]: true }));
    try {
      const product = products.find((p) => p.id === productId);
      if (!product) throw new Error("Product not found");
      cartStorage.addItem(product, 1);
      if (updateCartCount) {
        const cart = cartStorage.getCart();
        updateCartCount(cart.count);
      }
      setToastMessage("✓ Product added to cart!");
      setShowToast(true);
      setTimeout(() => setShowToast(false), 3000);
    } catch (error) {
      setToastMessage("✗ Failed to add to cart");
      setShowToast(true);
      setTimeout(() => setShowToast(false), 3000);
    } finally {
      setAddingToCart((prev) => ({ ...prev, [productId]: false }));
    }
  };

  const handleScroll = useCallback(() => {
    if (loadingMore || currentPage >= totalPages) return;
    const { scrollTop, scrollHeight, clientHeight } = document.documentElement;
    if (scrollHeight - scrollTop <= clientHeight + 100) {
      setCurrentPage((prev) => prev + 1);
    }
  }, [loadingMore, currentPage, totalPages]);

  useEffect(() => {
    window.addEventListener("scroll", handleScroll);
    return function cleanup() {
      window.removeEventListener("scroll", handleScroll);
    };
  }, [handleScroll]);

  return (
    <>
      <Header />
      <div style={{display:'flex',gap:'2rem',alignItems:'flex-start',maxWidth:1400,margin:'2rem auto',padding:'0 1rem'}}>
        <Sidebar />
        <div style={{flex:1}}>
          {/* Shop Banner & Info */}
          <div style={{background:'#fff',borderRadius:14,boxShadow:'0 2px 8px #0001',padding:'1.5rem',marginBottom:'2rem',display:'flex',alignItems:'center',gap:'2rem'}}>
            <Image src={shopBanner} alt="Shop Banner" width={120} height={120} style={{borderRadius:12,objectFit:'cover',boxShadow:'0 2px 8px #0001'}} />
            <div>
              <div style={{fontSize:'1.7rem',fontWeight:'bold',color:'#222',marginBottom:'0.5rem'}}>{shopInfo.name}</div>
              <div style={{fontSize:'1.08rem',color:'#444',marginBottom:'0.7rem'}}>{shopInfo.description}</div>
              <div style={{display:'flex',alignItems:'center',gap:'1.2rem'}}>
                <span style={{fontWeight:700,color:'#e91e63',fontSize:'1.08rem'}}>★ {shopInfo.rating} ({shopInfo.reviews} reviews)</span>
                <span style={{fontWeight:700,color:'#7c32ff',fontSize:'1.08rem'}}>👥 {shopInfo.followers} followers</span>
              </div>
            </div>
          </div>
          {/* Product Grid */}
          <div style={{display:'grid',gridTemplateColumns:'repeat(auto-fit,minmax(180px,1fr))',gap:'1.5rem',marginTop:'2rem'}}>
            {products.map(product => (
              <div key={product.id} style={{background:'#fff',borderRadius:12,boxShadow:'0 2px 8px #0001',padding:'1rem',position:'relative',display:'flex',flexDirection:'column',alignItems:'center',transition:'box-shadow 0.2s'}}>
                {product.sale && (
                  <span style={{position:'absolute',top:12,left:12,background:'#e91e63',color:'#fff',padding:'0.18rem 0.65rem',borderRadius:6,fontWeight:'bold',fontSize:'0.92rem',zIndex:2}}>{product.tag}</span>
                )}
                <Image src={product.image} alt={product.name} width={110} height={110} style={{objectFit:'contain',marginBottom:'0.7rem',borderRadius:8}} />
                <div style={{fontWeight:'bold',fontSize:'1.01rem',color:'#222',textAlign:'center',marginBottom:'0.4rem',minHeight:38}}>{product.name}</div>
                <div style={{display:'flex',alignItems:'center',gap:'0.5rem',marginBottom:'0.4rem'}}>
                  <span style={{fontWeight:'bold',color:'#e91e63',fontSize:'1.05rem'}}>৳{product.price}</span>
                  {product.oldPrice && <span style={{textDecoration:'line-through',color:'#aaa',fontSize:'0.97rem'}}>৳{product.oldPrice}</span>}
                </div>
                <div style={{fontSize:'0.93rem',color:'#888',marginBottom:'0.4rem'}}>{product.weight}</div>
                <button style={{width:'100%',padding:'0.5rem 0',background:'#7c32ff',color:'#fff',border:'none',borderRadius:8,fontWeight:'bold',fontSize:'0.98rem',cursor:'pointer',marginTop:'auto'}}>ADD TO CART</button>
              </div>
            ))}
          </div>
        </div>
      </div>
    </>
  );
};

export default ShopPage;
