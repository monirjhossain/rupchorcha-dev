This is a [Next.js](https://nextjs.org) project bootstrapped with [`create-next-app`](https://nextjs.org/docs/app/api-reference/cli/create-next-app).

## Getting Started

First, run the development server:

```bash
npm run dev
# or
yarn dev
# or
pnpm dev
# or
bun dev
```

Open [http://localhost:3000](http://localhost:3000) with your browser to see the result.

You can start editing the page by modifying `app/page.tsx`. The page auto-updates as you edit the file.

This project uses [`next/font`](https://nextjs.org/docs/app/building-your-application/optimizing/fonts) to automatically optimize and load [Geist](https://vercel.com/font), a new font family for Vercel.

## Project-specific notes

### Product pagination & "Load more"

- All listing pages (shop, category, brand, tag) use backend-side pagination via the common hook `usePaginationSort` and a shared UI component [app/components/PaginationControls.tsx](app/components/PaginationControls.tsx).
- URLs always include a stable `?page=` query parameter (for example `/shop?page=2`, `/category/[slug]?page=3`), which keeps SEO, sharing, and back/forward navigation predictable.
- `PaginationControls` supports three variants via the `variant` prop:
	- `"numbers"` – classic Previous/Next + page numbers (default, SEO-friendly primary behavior).
	- `"load-more"` – a single "Load more" button that advances to the next page while still updating `?page=`.
	- `"both"` – page numbers plus an additional "Load more" convenience button.
- Page components are responsible for calling `onPageChange(page)` with the correct base path so the router updates `?page=` while reusing the same backend pagination API.

This pattern lets us combine good UX (optional load-more) with clean, indexable paginated URLs.
