"use client";
import React from "react";
import HeroSlider from "./components/HeroSlider";
import styles from "./MobileHome.module.css";
import TopCategories from "./sections/TopCategories/TopCategories";
import TrendingBrand from "./sections/TrendingBrand/TrendingBrand";
import ShopBySkinType from "./sections/ShopBySkinType/ShopBySkinType";
import TopBrands from "./sections/TopBrands/TopBrands";
import FreeDeliveryOffer from "./sections/FreeDeliveryOffer/FreeDeliveryOffer";
import ExclusiveAccessories from "./sections/ExclusiveAccessories/ExclusiveAccessories";
import ComboOffers from "./sections/ComboOffers/ComboOffers";
import BuyOneGetOne from "./sections/BuyOneGetOne/BuyOneGetOne";

const MobileHome: React.FC = () => {
  return (
    <div className={styles.mobileHome}>
      <HeroSlider />
      <TopCategories />
      <TrendingBrand />
      <ShopBySkinType />
      <TopBrands />
      <FreeDeliveryOffer />
      <ExclusiveAccessories />
      <ComboOffers />
      <BuyOneGetOne />
    </div>
  );
};

export default MobileHome;
