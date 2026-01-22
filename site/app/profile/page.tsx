"use client";

import React, { useState, useEffect } from "react";
import { useRouter } from "next/navigation";
import styles from "./profile.module.css";

export default function ProfilePage() {
  const [activeSection, setActiveSection] = useState("general");
  const [user, setUser] = useState<any>(null);
  const [isLoading, setIsLoading] = useState(true);
  const router = useRouter();

  // Load user from localStorage
  useEffect(() => {
    const token = localStorage.getItem("token");
    if (!token) {
      router.push("/");
      return;
    }

    // Fetch user profile
    fetch(`http://localhost:8000/api/profile`, {
      method: 'GET',
      headers: { 
        'Authorization': `Bearer ${token}`,
        'Accept': 'application/json',
        'Content-Type': 'application/json'
      },
    })
      .then((res) => {
        if (res.status === 401) {
          localStorage.removeItem('token');
          setUser(null);
          router.replace("/");
          return null;
        }
        if (!res.ok) {
          throw new Error(`HTTP error! status: ${res.status}`);
        }
        return res.json();
      })
      .then((data) => {
        if (!data) return;
        if (data?.user) {
          setUser(data.user);
        } else {
          console.error('No user data in response');
          router.replace("/");
        }
      })
      .catch((error) => {
        console.error('Failed to fetch profile:', error);
        router.push("/");
      })
      .finally(() => {
        setIsLoading(false);
      });
  }, [router]);

  const handleLogout = () => {
    localStorage.removeItem("token");
    router.push("/");
  };

  if (isLoading) {
    return (
      <div style={{ textAlign: "center", padding: "4rem" }}>
        <p>Loading...</p>
      </div>
    );
  }

  if (!user) {
    return (
      <div style={{ textAlign: "center", padding: "4rem" }}>
        <h2>Session expired</h2>
        <p>Please log in again to view your profile.</p>
        <button
          style={{
            marginTop: "1rem",
            padding: "0.85rem 1.4rem",
            background: "#6366f1",
            color: "white",
            border: "none",
            borderRadius: "10px",
            cursor: "pointer",
            fontWeight: 700,
          }}
          onClick={() => router.push("/")}
        >
          Go to Home
        </button>
      </div>
    );
  }

  return (
    <div className={styles.container}>
      <div className={styles.sidebar}>
        {/* User Info */}
        <div className={styles.userCard}>
          <div className={styles.avatar}>
            <svg width="50" height="50" viewBox="0 0 24 24" fill="none">
              <circle cx="12" cy="8" r="4" fill="#6366f1" />
              <path
                d="M4 20c0-3.31 3.13-6 8-6s8 2.69 8 6"
                fill="#6366f1"
              />
            </svg>
          </div>
          <h3 className={styles.userName}>{user.name}</h3>
          <p className={styles.userPhone}>{user.phone}</p>
        </div>

        {/* Navigation Menu */}
        <div className={styles.menu}>
          <div className={styles.menuSection}>
            <p className={styles.menuTitle}>PROFILE</p>
            <button
              className={`${styles.menuItem} ${
                activeSection === "general" ? styles.active : ""
              }`}
              onClick={() => setActiveSection("general")}
            >
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                <circle cx="12" cy="8" r="4" stroke="currentColor" strokeWidth="2" />
                <path
                  d="M4 20c0-3.31 3.13-6 8-6s8 2.69 8 6"
                  stroke="currentColor"
                  strokeWidth="2"
                />
              </svg>
              General Info
            </button>
            <button
              className={`${styles.menuItem} ${
                activeSection === "favourites" ? styles.active : ""
              }`}
              onClick={() => setActiveSection("favourites")}
            >
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                <path
                  d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"
                  stroke="currentColor"
                  strokeWidth="2"
                />
              </svg>
              My Wishlist
            </button>
          </div>

          <div className={styles.menuSection}>
            <p className={styles.menuTitle}>ORDERS</p>
            <button
              className={`${styles.menuItem} ${
                activeSection === "orders" ? styles.active : ""
              }`}
              onClick={() => setActiveSection("orders")}
            >
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                <rect
                  x="3"
                  y="3"
                  width="18"
                  height="18"
                  rx="2"
                  stroke="currentColor"
                  strokeWidth="2"
                />
                <path d="M9 9h6M9 13h6M9 17h4" stroke="currentColor" strokeWidth="2" />
              </svg>
              My Orders
            </button>
            <button
              className={`${styles.menuItem} ${
                activeSection === "address" ? styles.active : ""
              }`}
              onClick={() => setActiveSection("address")}
            >
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                <path
                  d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"
                  fill="currentColor"
                />
              </svg>
              My Address
            </button>
          </div>

          <div className={styles.menuSection}>
            <p className={styles.menuTitle}>OTHER</p>
            <button
              className={`${styles.menuItem} ${
                activeSection === "reviews" ? styles.active : ""
              }`}
              onClick={() => setActiveSection("reviews")}
            >
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                <path
                  d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"
                  fill="currentColor"
                />
              </svg>
              My Reviews
            </button>
          </div>
        </div>

        {/* Logout Button */}
        <button className={styles.logoutBtn} onClick={handleLogout}>
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
            <path
              d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4V5z"
              fill="currentColor"
            />
          </svg>
          Logout
        </button>
      </div>

      {/* Main Content */}
      <div className={styles.content}>
        {activeSection === "general" && (
          <div className={styles.section}>
            <div className={styles.sectionHeader}>
              <h2>General Info</h2>
              <button className={styles.editBtn}>
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                  <path
                    d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"
                    fill="currentColor"
                  />
                </svg>
                Edit
              </button>
            </div>

            <div className={styles.infoGrid}>
              <div className={styles.infoItem}>
                <label>Name</label>
                <p>{user.name}</p>
              </div>

              <div className={styles.infoItem}>
                <label>Email Address</label>
                <p>{user.email || "Not Provided"}</p>
              </div>

              <div className={styles.infoItem}>
                <label>Mobile Number</label>
                <p>{user.phone}</p>
              </div>

              <div className={styles.infoItem}>
                <label>Role</label>
                <p>{user.role || "Customer"}</p>
              </div>

              <div className={styles.infoItem}>
                <label>Date of Birth</label>
                <p>Not Provided</p>
              </div>

              <div className={styles.infoItem}>
                <label>Address</label>
                <p>Not Provided</p>
              </div>
            </div>
          </div>
        )}

        {activeSection === "favourites" && (
          <div className={styles.section}>
            <h2>My Wishlist</h2>
            <p className={styles.emptyState}>Your wishlist is empty</p>
          </div>
        )}

        {activeSection === "orders" && (
          <div className={styles.section}>
            <h2>My Orders</h2>
            <p className={styles.emptyState}>No orders yet</p>
          </div>
        )}

        {activeSection === "address" && (
          <div className={styles.section}>
            <h2>My Address</h2>
            <p className={styles.emptyState}>No saved addresses</p>
          </div>
        )}

        {activeSection === "reviews" && (
          <div className={styles.section}>
            <h2>My Reviews</h2>
            <p className={styles.emptyState}>No reviews yet</p>
          </div>
        )}
      </div>
    </div>
  );
}
