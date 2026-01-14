"use client";
import dynamic from "next/dynamic";
const CategoryProductsPage = dynamic(() => import("../features/category/categoryProducts/page"), { ssr: false });
export default CategoryProductsPage;
