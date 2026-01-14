import { notFound } from 'next/navigation';
import { getBrandProducts } from '@/app/services/brandService';
import ProductList from '@/app/components/ProductList';

export default async function BrandPage(props: any) {
  const params = await props.params;
  const products = await getBrandProducts(params.slug);

  if (!products) {
    notFound();
  }

  return (
    <>
      <h1 style={{ fontSize: '2rem', fontWeight: 'bold', marginBottom: '1.5rem' }}>
        Products by Brand: {params.slug}
      </h1>
      <ProductList products={products} />
    </>
  );
}
