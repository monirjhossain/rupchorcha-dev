import axios from 'axios';

export async function getBrandProducts(slug: string) {
  try {
    const response = await axios.get(`${process.env.NEXT_PUBLIC_API_URL}/brands/${slug}/products`);
    return response.data.products;
  } catch (error) {
    return null;
  }
}
