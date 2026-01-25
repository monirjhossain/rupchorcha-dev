"use client";
import React from "react";
import type { Product } from "../types";
import styles from "./ProductDescription.module.css";

interface ProductDescriptionProps {
  product: Product;
}

export default function ProductDescription({ product }: ProductDescriptionProps) {
  // Extract description from product or use default
  const shortDescription = (product as Record<string, unknown>).description as string || product.name;
  const longDescription = (product as Record<string, unknown>).long_description as string || "";
  const features = (product as Record<string, unknown>).features as string[] || [];
  const specifications = (product as Record<string, unknown>).specifications as Record<string, unknown> || {};

  return (
    <div className={styles.container}>
      <h2 className={styles.heading}>Product Description</h2>
      
      {/* Long Description - Primary content */}
      {longDescription && (
        <div className={styles.longDescription}>
          <div dangerouslySetInnerHTML={{ __html: longDescription }} />
        </div>
      )}

      {/* Short Description - Fallback */}
      {!longDescription && (
        <div className={styles.description}>
          <p>{shortDescription}</p>
        </div>
      )}

      {/* Key Features */}
      {features.length > 0 && (
        <div className={styles.section}>
          <h3 className={styles.subheading}>Key Features</h3>
          <ul className={styles.featureList}>
            {features.map((feature: string, index: number) => (
              <li key={index} className={styles.featureItem}>
                <span className={styles.featureIcon}>✓</span>
                {feature}
              </li>
            ))}
          </ul>
        </div>
      )}

      {/* Specifications */}
      {Object.keys(specifications).length > 0 && (
        <div className={styles.section}>
          <h3 className={styles.subheading}>Specifications</h3>
          <table className={styles.specTable}>
            <tbody>
              {Object.entries(specifications).map(([key, value]) => (
                <tr key={key}>
                  <td className={styles.specLabel}>{key}</td>
                  <td className={styles.specValue}>{String(value)}</td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      )}

      {/* Default specs if none from backend */}
      {Object.keys(specifications).length === 0 && (
        <div className={styles.section}>
          <h3 className={styles.subheading}>Product Details</h3>
          <table className={styles.specTable}>
            <tbody>
              {product.brand?.name && (
                <tr>
                  <td className={styles.specLabel}>Brand</td>
                  <td className={styles.specValue}>{product.brand.name}</td>
                </tr>
              )}
              {product.category?.name && (
                <tr>
                  <td className={styles.specLabel}>Category</td>
                  <td className={styles.specValue}>{product.category.name}</td>
                </tr>
              )}
              {product.sku && (
                <tr>
                  <td className={styles.specLabel}>SKU</td>
                  <td className={styles.specValue}>{product.sku}</td>
                </tr>
              )}
              <tr>
                <td className={styles.specLabel}>Condition</td>
                <td className={styles.specValue}>Brand New</td>
              </tr>
            </tbody>
          </table>
        </div>
      )}
    </div>
  );
}
