"use client";

import React, { useState } from "react";
import Link from "next/link";
import styles from "../auth.module.css";

const API_BASE = process.env.NEXT_PUBLIC_API_URL || "http://localhost:8000/api";

export default function ForgotPasswordPage() {
  const [email, setEmail] = useState("");
  const [isLoading, setIsLoading] = useState(false);
  const [error, setError] = useState("");
  const [success, setSuccess] = useState(false);

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setError("");
    setSuccess(false);
    setIsLoading(true);

    if (!email.trim()) {
      setError("Please enter your email address");
      setIsLoading(false);
      return;
    }

    try {
      const res = await fetch(`${API_BASE}/forgot-password`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ email }),
      });

      if (!res.ok) {
        const data = await res.json();
        throw new Error(data.message || "Failed to send reset link");
      }

      setSuccess(true);
      setEmail("");
    } catch (err: any) {
      setError(err.message || "Failed to send reset link. Please try again.");
    } finally {
      setIsLoading(false);
    }
  };

  return (
    <div className={styles.authContainer}>
      <div className={styles.authCard}>
        <h1 className={styles.title}>Forgot Password?</h1>
        <p className={styles.subtitle}>Enter your email and we'll send you a link to reset your password</p>

        {error && (
          <div className={styles.error}>
            {error}
          </div>
        )}

        {success && (
          <div className={styles.successMessage}>
            ✓ Password reset link sent to {email}! Please check your email inbox and spam folder.
          </div>
        )}

        {!success ? (
          <form onSubmit={handleSubmit} className={styles.form}>
            <div className={styles.formGroup}>
              <label className={styles.label}>Email Address</label>
              <input
                type="email"
                className={styles.input}
                placeholder="Enter your registered email"
                value={email}
                onChange={(e) => setEmail(e.target.value)}
                disabled={isLoading}
                required
              />
            </div>

            <button
              type="submit"
              className={styles.submitBtn}
              disabled={isLoading}
            >
              {isLoading ? "Sending..." : "Send Reset Link"}
            </button>

            <div className={styles.footer}>
              <button
                type="button"
                onClick={() => {
                  openLoginModal();
                  router.push('/');
                }}
                className={styles.link}
                style={{ background: 'none', border: 'none', cursor: 'pointer', color: 'inherit' }}
              >
                ← Back to login
              </button>
            </div>
          </form>
        ) : (
          <div className={styles.footer}>
            <p>Didn't receive the email? <br />Check your spam folder or <button onClick={() => setSuccess(false)} className={styles.link}>try again</button></p>
            <button
              type="button"
              onClick={() => {
                openLoginModal();
                router.push('/');
              }}
              className={styles.link}
              style={{ background: 'none', border: 'none', cursor: 'pointer', color: 'inherit' }}
            >
              ← Back to login
            </button>
          </div>
        )}
      </div>
    </div>
  );
}
