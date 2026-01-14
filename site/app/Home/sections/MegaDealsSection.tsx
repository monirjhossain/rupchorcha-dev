
"use client";
import Image from 'next/image';

const megaDeals = [
  {
    id: 1,
    image: '/best-deals/2.jpg',
    title: 'Mega Deal 1',
    gradient: 'linear-gradient(135deg, #f6d365 0%, #fda085 100%)'
  },
  {
    id: 2,
    image: '/best-deals/6.jpg',
    title: 'Mega Deal 2',
    gradient: 'linear-gradient(135deg, #a1c4fd 0%, #c2e9fb 100%)'
  },
  {
    id: 3,
    image: '/best-deals/7.jpg',
    title: 'Mega Deal 3',
    gradient: 'linear-gradient(135deg, #fbc2eb 0%, #a6c1ee 100%)'
  },
  {
    id: 4,
    image: '/best-deals/8.jpg',
    title: 'Mega Deal 4',
    gradient: 'linear-gradient(135deg, #fdcbf1 0%, #e6dee9 100%)'
  }
];

const MegaDealsSection = () => {
  return (
    <section>
      <h2>Mega Deals</h2>
      <div style={{display:'flex',gap:'1rem',flexWrap:'wrap',justifyContent:'center'}}>
        {megaDeals.map(deal => (
          <div key={deal.id} style={{width:180,height:180,display:'flex',alignItems:'center',justifyContent:'center',background:deal.gradient,borderRadius:16,boxShadow:'0 1px 6px #0002'}}>
            <Image src={deal.image} alt={deal.title} width={120} height={120} style={{objectFit:'cover',borderRadius:12}} />
          </div>
        ))}
      </div>
    </section>
  );
};

export default MegaDealsSection;
