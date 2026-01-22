"use client";

import React, { useEffect, useState } from "react";
import { useRouter } from "next/navigation";
import { useAuth } from "@/src/hooks/useAuth";
import styles from "../auth.module.css";

export default function CompleteProfilePage() {
  const router = useRouter();
  const { completeProfile, isLoading, error, clearError, token } = useAuth();
  const [formData, setFormData] = useState({
    name: "",
    password: "",
    password_confirmation: "",
  });
  const [localError, setLocalError] = useState("");
  const [needsCompletion, setNeedsCompletion] = useState(false);

  // Check if user needs profile completion
  useEffect(() => {
    const completion = localStorage.getItem("requires_profile_completion");
    if (!completion || !token) {
      router.push("/");
      return;
    }
    setNeedsCompletion(true);
  }, [token, router]);

  const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    const { name, value } = e.target;
    setFormData((prev) => ({ ...prev, [name]: value }));
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setLocalError("");
    clearError();

    // Validation
    if (!formData.name.trim()) {
      setLocalError("Name is required");
      return;
    }

    if (formData.name.length < 2) {
      setLocalError("Name must be at least 2 characters");
      return;
    }

    if (formData.password.length < 6) {
      setLocalError("Password must be at least 6 characters");
      return;
    }

    if (formData.password !== formData.password_confirmation) {
      setLocalError("Passwords do not match");
      return;
    }

    try {
      await completeProfile({
        name: formData.name,
        password: formData.password,
        password_confirmation: formData.password_confirmation,
      });
      // Success - redirect to home
      router.push("/");
    } catch {
      // Error is already set in useAuth
    }
  };

  if (!needsCompletion) {
    return null;
  }

  return (
    <div className={styles.authContainer}>
      <div className={styles.authCard}>
        <h1 className={styles.title}>Complete Your Profile</h1>
        <p className={styles.subtitle}>Set your name and password to finish registration</p>

        {error && (
          <div className={styles.error}>
            {error}
            <button onClick={clearError} className={styles.errorClose}>×</button>
          </div>
        )}

        {localError && (
          <div className={styles.error}>
            {localError}
          </div>
        )}

        <form onSubmit={handleSubmit} className={styles.form}>
          <div className={styles.formGroup}>
            <label className={styles.label}>Full Name</label>
            <input
              type="text"
              name="name"
              className={styles.input}
              placeholder="Enter your full name"
              value={formData.name}
              onChange={handleChange}
              disabled={isLoading}
              required
            />
            <small className={styles.hint}>Your profile will be visible to others with this name</small>
          </div>

          <div className={styles.formGroup}>
            <label className={styles.label}>Password</label>
            <input
              type="password"
              name="password"
              className={styles.input}
              placeholder="Create a strong password"
              value={formData.password}
              onChange={handleChange}
              disabled={isLoading}
              required
            />
            <small className={styles.hint}>Minimum 6 characters</small>
          </div>

          <div className={styles.formGroup}>
            <label className={styles.label}>Confirm Password</label>
            <input
              type="password"
              name="password_confirmation"
              className={styles.input}
              placeholder="Confirm your password"
              value={formData.password_confirmation}
              onChange={handleChange}
              disabled={isLoading}
              required
            />
          </div>

          <button
            type="submit"
            className={styles.submitBtn}
            disabled={isLoading}
          >
            {isLoading ? "Completing Profile..." : "Complete Profile"}
          </button>

          <div className={styles.footer}>
            <small>This information helps us personalize your experience</small>
          </div>
        </form>
      </div>
    </div>
  );
}
