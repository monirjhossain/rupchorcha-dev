import type { Metadata } from "next";
import { Geist, Geist_Mono } from "next/font/google";
import Footer from "./components/Footer";
import "./globals.css";
import ClientLayout from "./ClientLayout";
import ToastProvider from "./ToastProvider";
import { WishlistProvider } from "./components/WishlistContext";
import { UserProvider } from "./common/UserContext";
import { GoogleOAuthProvider } from "@react-oauth/google";
import { AuthProvider } from "./contexts/AuthContext";

const geistSans = Geist({
  variable: "--font-geist-sans",
  subsets: ["latin"],
});

const geistMono = Geist_Mono({
  variable: "--font-geist-mono",
  subsets: ["latin"],
});

export const metadata: Metadata = {
  metadataBase: new URL(process.env.NEXT_PUBLIC_SITE_URL || "http://localhost:3000"),
  title: {
    default: "Rupchorcha - Your Trusted Online Shopping Destination in Bangladesh",
    template: "%s | Rupchorcha",
  },
  description: "Discover and shop your favorite products at Rupchorcha. Best prices, authentic brands, and fast delivery across Bangladesh. Shop electronics, fashion, beauty, and more.",
  keywords: ['online shopping Bangladesh', 'e-commerce', 'Rupchorcha', 'buy online', 'fashion', 'electronics', 'beauty products'],
  
  openGraph: {
    title: "Rupchorcha - Online Shopping in Bangladesh",
    description: "Discover and shop your favorite products at Rupchorcha. Best prices and fast delivery.",
    url: "/",
    siteName: "Rupchorcha",
    type: "website",
    locale: 'en_US',
    images: [
      {
        url: '/rupchorcha-logo.png',
        width: 1200,
        height: 630,
        alt: 'Rupchorcha Logo',
      },
    ],
  },
  
  twitter: {
    card: 'summary_large_image',
    title: 'Rupchorcha - Online Shopping in Bangladesh',
    description: 'Discover and shop your favorite products at Rupchorcha.',
    images: ['/rupchorcha-logo.png'],
  },
  
  robots: {
    index: true,
    follow: true,
    googleBot: {
      index: true,
      follow: true,
      'max-video-preview': -1,
      'max-image-preview': 'large',
      'max-snippet': -1,
    },
  },
  
  verification: {
    // google: 'add-your-verification-code-here',
  },
};

export default function RootLayout({ children }: Readonly<{ children: React.ReactNode }>) {
  return (
    <html lang="en" suppressHydrationWarning>
      <body className={`${geistSans.variable} ${geistMono.variable} antialiased`} suppressHydrationWarning>
        <GoogleOAuthProvider clientId={process.env.NEXT_PUBLIC_GOOGLE_CLIENT_ID || ""}>
          <AuthProvider>
            <ToastProvider />
            <UserProvider>
              <WishlistProvider>
                <ClientLayout>
                  <div
                    style={{
                      minHeight: '80vh',
                      maxWidth: 1400,
                      margin: '2rem auto',
                      padding: '0 1rem',
                    }}
                  >
                    {children}
                  </div>
                  <Footer />
                </ClientLayout>
              </WishlistProvider>
            </UserProvider>
          </AuthProvider>
        </GoogleOAuthProvider>
      </body>
    </html>
  );
}
