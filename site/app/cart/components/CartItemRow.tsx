import React from 'react';
import styles from './CartItemRow.module.css';

interface CartItemRowProps {
  item: {
    product_id: number;
    quantity: number;
    product?: {
      name?: string;
      price?: number;
      sale_price?: number;
      brand?: { name: string } | string;
      main_image?: string;
      image?: string;
      images?: { url?: string; path?: string }[] | string[];
    };
  };
  isSelected: boolean;
  onToggleSelect: () => void;
  onUpdateQuantity: (quantity: number) => void;
  onRemove: () => void;
}

const CartItemRow: React.FC<CartItemRowProps> = ({
  item,
  isSelected,
  onToggleSelect,
  onUpdateQuantity,
  onRemove,
}) => {
  const product = item.product || {};
  const brandName = typeof product.brand === 'object' ? product.brand?.name : (product.brand || 'BRAND');
  const productName = product.name || 'Product Name';
  const originalPrice = Number(product.price || 0);
  const salePrice = Number(product.sale_price || product.price || 0);
  const hasDiscount = salePrice < originalPrice && salePrice > 0;

  const getImageUrl = (): string => {
    const backendBase = process.env.NEXT_PUBLIC_API_URL?.replace('/api', '') || 'http://127.0.0.1:8000';
    
    if (product?.images?.length) {
      const firstImage = product.images[0];
      const img = typeof firstImage === 'string' ? firstImage : (firstImage.url || firstImage.path || '');
      if (img) {
        return img.startsWith('http') ? img : `${backendBase}/storage/${img.replace(/^storage[\\/]/, '')}`;
      }
    }
    
    if (typeof product?.main_image === 'string' && product.main_image) {
      return product.main_image.startsWith('http')
        ? product.main_image
        : `${backendBase}/storage/${product.main_image.replace(/^storage[\\/]/, '')}`;
    }
    
    if (typeof product?.image === 'string' && product.image) {
      return product.image.startsWith('http')
        ? product.image
        : `${backendBase}/storage/${product.image.replace(/^storage[\\/]/, '')}`;
    }
    
    return 'https://via.placeholder.com/100x100?text=No+Image';
  };

  const handleQuantityChange = (delta: number) => {
    const newQuantity = Math.max(1, item.quantity + delta);
    onUpdateQuantity(newQuantity);
  };

  return (
    <div className={styles.cartItemRow}>
      {/* Checkbox */}
      <div className={styles.checkbox}>
        <input
          type="checkbox"
          checked={isSelected}
          onChange={onToggleSelect}
          className={styles.checkboxInput}
        />
      </div>

      {/* Product Image */}
      <div className={styles.productImage}>
        {/* eslint-disable-next-line @next/next/no-img-element */}
        <img src={getImageUrl()} alt={productName} />
      </div>

      {/* Product Details */}
      <div className={styles.productDetails}>
        <div className={styles.brandName}>{brandName}</div>
        <div className={styles.productName}>{productName}</div>
      </div>

      {/* Price */}
      <div className={styles.priceSection}>
        {hasDiscount && (
          <div className={styles.originalPrice}>৳ {originalPrice.toFixed(0)}</div>
        )}
        <div className={styles.salePrice}>৳ {salePrice.toFixed(0)}</div>
      </div>

      {/* Quantity Controls */}
      <div className={styles.quantityControls}>
        <button
          className={styles.quantityBtn}
          onClick={() => handleQuantityChange(-1)}
          disabled={item.quantity <= 1}
        >
          −
        </button>
        <span className={styles.quantityValue}>{item.quantity}</span>
        <button
          className={styles.quantityBtn}
          onClick={() => handleQuantityChange(1)}
        >
          +
        </button>
      </div>
    </div>
  );
};

export default CartItemRow;
