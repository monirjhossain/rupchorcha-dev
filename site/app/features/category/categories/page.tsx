"use client";
import React from "react";
import Image from "next/image";

// Remove all mock category/subcategory data. Only use backend data.

const CategoryPage = () => {
  return (
    <main style={{ maxWidth: 1200, margin: "2rem auto", padding: "1rem" }}>
      <nav style={{ fontSize: "0.95rem", color: "#888", marginBottom: "1.5rem" }}>
        Home &gt; <span style={{ color: "#222" }}>Category</span>
      </nav>
      {categories.map((cat) => (
        <section key={cat.name} style={{ marginBottom: "2.5rem" }}>
          <h2 style={{ fontSize: "2rem", fontWeight: "bold", margin: "1.5rem 0 1rem 0", color: "#111" }}>{cat.name}</h2>
          <div style={{ display: "flex", flexWrap: "wrap", gap: "2.5rem 2.5rem", alignItems: "flex-start" }}>
            {cat.subcategories.map((sub) => (
              <div key={sub.name} style={{ display: "flex", flexDirection: "column", alignItems: "center", width: 140 }}>
                <div style={{ width: 110, height: 110, borderRadius: "50%", background: "#fff", boxShadow: "0 1px 4px #0001", border: "4px solid #f7c6e0", display: "flex", alignItems: "center", justifyContent: "center", overflow: "hidden", marginBottom: "0.5rem" }}>
                  <Image src={sub.image} alt={sub.name} width={100} height={100} style={{ objectFit: "cover", borderRadius: "50%" }} />
                </div>
                <span style={{ fontWeight: 500, color: "#222", fontSize: "1.08rem", textAlign: "center", letterSpacing: "0.01em", marginBottom: "0.5rem" }}>{sub.name}</span>
                <button style={{ padding: "0.25rem 0.75rem", background: "#fff", color: "#111", border: "1px solid #eee", borderRadius: 6, fontSize: "0.95rem", cursor: "pointer", boxShadow: "0 1px 4px #0001" }}>Products</button>
              </div>
            ))}
          </div>
        </section>
      ))}
    </main>
  );
};

export default CategoryPage;
