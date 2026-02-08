import React from "react";
import Sidebar from "../components/Sidebar";

export default function WithSidebarLayout({ children }: { children: React.ReactNode }) {
  return (
    <div style={{ display: 'flex', gap: '2rem', alignItems: 'flex-start', maxWidth: 1400, margin: '2rem auto', padding: '0 1rem' }}>
      <div className="responsive-sidebar">
        <Sidebar />
      </div>
      <div style={{ flex: 1 }}>
        {children}
      </div>
    </div>
  );
}
