"use client";
import React, { useState } from "react";
import { useRouter } from "next/navigation";

export default function LoginPage() {
  const router = useRouter();
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [error, setError] = useState("");
  const [loading, setLoading] = useState(false);

  const handleLogin = async (e: React.FormEvent) => {
    e.preventDefault();
    setLoading(true);
    setError("");
    try {
      // Replace with your actual login API endpoint
      const res = await fetch("http://localhost:8000/api/login", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "Accept": "application/json",
        },
        body: JSON.stringify({ email, password }),
      });
      const data = await res.json();
      if (res.ok && data.token) {
        localStorage.setItem("token", data.token);
        router.push("/profile");
      } else {
        setError(data.message || "Login failed");
      }
    } catch (err) {
      setError("Network error");
    } finally {
      setLoading(false);
    }
  };

  return (
    <div style={{ maxWidth: 400, margin: "4rem auto", padding: "2rem", background: "#fff", borderRadius: 16, boxShadow: "0 8px 32px rgba(99,102,241,0.10)" }}>
      <h2 style={{ color: "#a004b0", fontWeight: 800, marginBottom: 24 }}>Login</h2>
      <form onSubmit={handleLogin}>
        <div style={{ marginBottom: 18 }}>
          <label>Email</label>
          <input type="email" value={email} onChange={e => setEmail(e.target.value)} required style={{ width: "100%", padding: "10px", borderRadius: 8, border: "1px solid #ccc" }} />
        </div>
        <div style={{ marginBottom: 18 }}>
          <label>Password</label>
          <input type="password" value={password} onChange={e => setPassword(e.target.value)} required style={{ width: "100%", padding: "10px", borderRadius: 8, border: "1px solid #ccc" }} />
        </div>
        {error && <div style={{ color: "#f44336", marginBottom: 12 }}>{error}</div>}
        <button type="submit" disabled={loading} style={{ width: "100%", padding: "12px", background: "linear-gradient(90deg, #a004b0 0%, #6366f1 100%)", color: "#fff", border: "none", borderRadius: 8, fontWeight: 700, fontSize: "1.1em", cursor: "pointer" }}>
          {loading ? "Logging in..." : "Login"}
        </button>
      </form>
    </div>
  );
}
