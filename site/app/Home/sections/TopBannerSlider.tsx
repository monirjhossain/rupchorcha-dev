"use client";
import Image from 'next/image';

const topBanners = [
  { id: 1, image: '/banners/banner1.jpg', alt: 'Giga Sale Banner' },
  { id: 2, image: '/banners/banner2.jpg', alt: 'Ponds Offer' },
  // Add more as needed
];

export default function TopBannerSlider() {
  return (
    <section style={{width:'100%',background:'#fff',marginBottom:'1.5rem',boxSizing:'border-box'}}>
      <div style={{maxWidth:1440,margin:'0 auto',position:'relative',boxSizing:'border-box',padding:'0 0'}}>
        <div style={{width:'100%',display:'flex',justifyContent:'center',alignItems:'center'}}>
          <div style={{width:'100%',maxWidth:1200,boxSizing:'border-box',borderRadius:24,overflow:'hidden',background:'#fff',boxShadow:'0 2px 16px #0001'}}>
            <Image 
              src={topBanners[0].image} 
              alt={topBanners[0].alt} 
              width={1200} 
              height={400} 
              style={{width:'100%',height:'auto',objectFit:'cover',boxSizing:'border-box',display:'block'}} 
              priority
            />
          </div>
        </div>
      </div>
    </section>
  );
}
