import React from "react";
import Sidebar from "../components/Sidebar";

export default function WithSidebarLayout({ children }: { children: React.ReactNode }) {
  return (
    <div style={{ display: 'flex', gap: '2rem', alignItems: 'flex-start', maxWidth: 1400, margin: '0 auto', padding: '2rem 1rem' }}>
      <div className="responsive-sidebar" style={{ flex: '0 0 300px' }}>
        <Sidebar />
      </div>
      <div style={{ flex: 1, minWidth: 0 }}>
        {children}
      </div>
    </div>
  );
}
