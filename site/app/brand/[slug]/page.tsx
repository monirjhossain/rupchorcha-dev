import { redirect } from 'next/navigation';

export default function OldBrandPage({ params }) {
  // Redirect to the new persistent sidebar route
  redirect(`/brands/${params.slug}`);
  return null;
}
