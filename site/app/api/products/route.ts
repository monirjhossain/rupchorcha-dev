import type { NextRequest } from 'next/server';
import { NextResponse } from 'next/server';

export async function GET(req: NextRequest) {
  const { searchParams } = new URL(req.url);
  const name = searchParams.get('name') || '';
  // You can add more query params as needed

  // Proxy to backend
  const backendUrl = `http://localhost:8000/api/products?name=${encodeURIComponent(name)}`;
  const res = await fetch(backendUrl, {
    headers: {
      'Accept': 'application/json',
    },
  });
  const data = await res.json();
  return NextResponse.json(data);
}
