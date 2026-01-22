"use client";

import React, { useState, useEffect } from "react";
import styles from "./LoginModal.module.css";
import { useAuth } from "@/src/hooks/useAuth";
import { useRouter } from "next/navigation";
import { GoogleLogin, CredentialResponse } from "@react-oauth/google";

interface LoginModalProps {
  isOpen: boolean;
  onClose: () => void;
}

type LoginStep = "mobile" | "email" | "emailPassword" | "otp";
type ProfileCompletionStep = "completeProfile";
type AllSteps = LoginStep | ProfileCompletionStep;

const LoginModal: React.FC<LoginModalProps> = ({ isOpen, onClose }) => {
  const [authMode, setAuthMode] = useState<"login" | "signup">("login");
  const [step, setStep] = useState<AllSteps>("mobile");
  const [phone, setPhone] = useState("");
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [otp, setOtp] = useState("");
  const [name, setName] = useState("");
  const [passwordConfirm, setPasswordConfirm] = useState("");
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState("");
  const { sendOtp, verifyOtp, loginWithEmail, loginWithGoogle, completeProfile, register } = useAuth();
  const router = useRouter();

  // Handle Google Login response (credential = ID token JWT)
  const handleGoogleLoginSuccess = async (credentialResponse: CredentialResponse) => {
    if (!credentialResponse?.credential) {
      setError("Google login failed. Please try again.");
      return;
    }
    setLoading(true);
    setError("");
    try {
      await loginWithGoogle(credentialResponse.credential);
      setTimeout(() => {
        window.dispatchEvent(new Event('auth-state-changed'));
        onClose();
        router.push('/');
      }, 200);
    } catch (err: any) {
      setError(err.message || "Google login failed. Please try again.");
    } finally {
      setLoading(false);
    }
  };


  useEffect(() => {
    if (!isOpen) {
      setAuthMode("login");
      setPhone("");
      setEmail("");
      setPassword("");
      setOtp("");
      setName("");
      setPasswordConfirm("");
      setStep("mobile");
      setError("");
    }
  }, [isOpen]);

  const handleAuthModeSwitch = (mode: "login" | "signup") => {
    setAuthMode(mode);
    setError("");
    setLoading(false);
    if (mode === "login") {
      setStep("mobile");
    }
  };

  const handlePhoneContinue = async () => {
    if (!phone || phone.length < 10) {
      setError("Please enter a valid phone number");
      return;
    }
    setLoading(true);
    setError("");
    try {
      await sendOtp(phone);
      setStep("otp");
    } catch (err: any) {
      setError(err.message || "Failed to send OTP");
    } finally {
      setLoading(false);
    }
  };

  const handleOtpContinue = async () => {
    if (!otp || otp.length < 4) {
      setError("Please enter a valid OTP");
      return;
    }
    setLoading(true);
    setError("");
    try {
      const result = await verifyOtp(phone, otp);
      // If profile completion is required, show the form instead of redirecting
      if (result?.requires_profile_completion) {
        setStep("completeProfile");
        setLoading(false);
        return;
      }
      // Give time for auth state to update
      setTimeout(() => {
        window.dispatchEvent(new Event('auth-state-changed'));
        onClose();
        router.push('/');
      }, 300);
    } catch (err: any) {
      setError(err.message || "Invalid OTP");
    } finally {
      setLoading(false);
    }
  };

  const handleCompleteProfile = async () => {
    if (!name || name.length < 2) {
      setError("Please enter your name");
      return;
    }
    if (!password || password.length < 6) {
      setError("Password must be at least 6 characters");
      return;
    }
    if (password !== passwordConfirm) {
      setError("Passwords do not match");
      return;
    }
    setLoading(true);
    setError("");
    try {
      await completeProfile({ name, password, password_confirmation: passwordConfirm });
      setTimeout(() => {
        window.dispatchEvent(new Event('auth-state-changed'));
        onClose();
        router.push('/');
      }, 200);
    } catch (err: any) {
      setError(err.message || "Profile completion failed");
    } finally {
      setLoading(false);
    }
  };

  const handleEmailContinue = async () => {
    if (!email || !email.includes("@")) {
      setError("Please enter a valid email");
      return;
    }
    setStep("emailPassword");
    setError("");
  };

  const handleEmailPasswordContinue = async () => {
    if (!password || password.length < 6) {
      setError("Password must be at least 6 characters");
      return;
    }
    setLoading(true);
    setError("");
    try {
      await loginWithEmail(email, password);
      // Give time for auth state to update before closing
      setTimeout(() => {
        window.dispatchEvent(new Event('auth-state-changed'));
        onClose();
        router.push('/');
      }, 200);
    } catch (err: any) {
      setError(err.message || "Invalid email or password");
    } finally {
      setLoading(false);
    }
  };

  const handleRegister = async () => {
    console.log("🔵 Register button clicked");
    console.log("📝 Form data:", { name, email, phone, password: "***", passwordConfirm: "***" });
    
    if (!name || name.length < 2) {
      setError("Please enter your name");
      console.log("❌ Validation: Name too short");
      return;
    }
    if (!email || !email.includes("@")) {
      setError("Please enter a valid email");
      console.log("❌ Validation: Invalid email");
      return;
    }
    if (!phone || phone.length < 10) {
      setError("Please enter a valid phone number");
      console.log("❌ Validation: Invalid phone");
      return;
    }
    if (!password || password.length < 6) {
      setError("Password must be at least 6 characters");
      console.log("❌ Validation: Password too short");
      return;
    }
    if (password !== passwordConfirm) {
      setError("Passwords do not match");
      console.log("❌ Validation: Passwords don't match");
      return;
    }
    
    console.log("✅ Validation passed, calling register API...");
    setLoading(true);
    setError("");
    try {
      const result = await register({ name, email, phone, password, password_confirmation: passwordConfirm });
      console.log("✅ Register API response:", result);
      setTimeout(() => {
        window.dispatchEvent(new Event('auth-state-changed'));
        onClose();
        router.push('/');
      }, 200);
    } catch (err: any) {
      console.error("❌ Register error:", err);
      setError(err.message || "Registration failed. Please try again.");
    } finally {
      setLoading(false);
    }
  };

  if (!isOpen) return null;

  return (
    <div className={styles.overlay} onClick={onClose}>
      <div className={styles.modal} onClick={(e) => e.stopPropagation()}>
        {/* Close Button */}
        <button className={styles.closeBtn} onClick={onClose} aria-label="Close">
          ✕
        </button>

        {/* Logo */}
        <div className={styles.logo}>
          <svg width="40" height="40" viewBox="0 0 24 24" fill="none">
            <path
              d="M12 2L2 7v5c0 5.55 3.84 10.74 9 12c5.16-1.26 9-6.45 9-12V7l-10-5z"
              fill="#7c32ff"
            />
            <circle cx="12" cy="11" r="2.5" fill="#e91e63" />
          </svg>
        </div>

        {/* Title */}
        <h2 className={styles.title}>Continue to Rupchorcha</h2>

        {/* Mode Switch */}
        <div className={styles.modeSwitch}>
          <button
            className={`${styles.modeBtn} ${authMode === "login" ? styles.activeMode : ""}`}
            onClick={() => handleAuthModeSwitch("login")}
          >
            Log In
          </button>
          <button
            className={`${styles.modeBtn} ${authMode === "signup" ? styles.activeMode : ""}`}
            onClick={() => handleAuthModeSwitch("signup")}
          >
            Create Account
          </button>
        </div>

        {/* Error Message */}
        {error && <div className={styles.error}>{error}</div>}

        {/* Sign Up */}
        {authMode === "signup" && (
          <div className={styles.stepContent}>
            <input
              type="text"
              placeholder="Your Name"
              value={name}
              onChange={(e) => setName(e.target.value)}
              className={styles.input}
              disabled={loading}
            />
            <input
              type="email"
              placeholder="example@mail.com"
              value={email}
              onChange={(e) => setEmail(e.target.value)}
              className={styles.input}
              disabled={loading}
            />
            <input
              type="tel"
              placeholder="Phone number"
              value={phone}
              onChange={(e) => setPhone(e.target.value.replace(/\D/g, ""))}
              className={styles.input}
              maxLength="11"
              disabled={loading}
            />
            <input
              type="password"
              placeholder="Password"
              value={password}
              onChange={(e) => setPassword(e.target.value)}
              className={styles.input}
              disabled={loading}
            />
            <input
              type="password"
              placeholder="Confirm Password"
              value={passwordConfirm}
              onChange={(e) => setPasswordConfirm(e.target.value)}
              className={styles.input}
              disabled={loading}
            />
            <button
              className={styles.continueBtn}
              onClick={handleRegister}
              disabled={loading}
            >
              {loading ? "Creating..." : "Create Account"} →
            </button>
            <button
              className={styles.backBtn}
              onClick={() => handleAuthModeSwitch("login")}
              disabled={loading}
            >
              ← Back to Login
            </button>
          </div>
        )}

        {/* Step 1: Mobile OTP */}
        {authMode === "login" && step === "mobile" && (
          <div className={styles.stepContent}>
            <input
              type="tel"
              placeholder="+88 xxxxxxxx"
              value={phone}
              onChange={(e) => setPhone(e.target.value.replace(/\D/g, ""))}
              className={styles.input}
              maxLength="11"
              disabled={loading}
            />
            <button
              className={styles.continueBtn}
              onClick={handlePhoneContinue}
              disabled={loading}
            >
              {loading ? "Sending..." : "Continue"} →
            </button>
          </div>
        )}

        {/* Step 2: OTP Verification */}
        {authMode === "login" && step === "otp" && (
          <div className={styles.stepContent}>
            <p className={styles.stepText}>Enter OTP sent to {phone}</p>
            <input
              type="text"
              placeholder="Enter OTP"
              value={otp}
              onChange={(e) => setOtp(e.target.value.replace(/\D/g, ""))}
              className={styles.input}
              maxLength="6"
              disabled={loading}
            />
            <button
              className={styles.continueBtn}
              onClick={handleOtpContinue}
              disabled={loading}
            >
              {loading ? "Verifying..." : "Continue"} →
            </button>
            <button
              className={styles.backBtn}
              onClick={() => {
                setStep("mobile");
                setOtp("");
                setError("");
              }}
            >
              ← Back
            </button>
          </div>
        )}

        {/* Step 3: Profile Completion */}
        {authMode === "login" && step === "completeProfile" && (
          <div className={styles.stepContent}>
            <p className={styles.stepText}>Complete your profile</p>
            <input
              type="text"
              placeholder="Your Name"
              value={name}
              onChange={(e) => setName(e.target.value)}
              className={styles.input}
              disabled={loading}
            />
            <input
              type="password"
              placeholder="Password"
              value={password}
              onChange={(e) => setPassword(e.target.value)}
              className={styles.input}
              disabled={loading}
            />
            <input
              type="password"
              placeholder="Confirm Password"
              value={passwordConfirm}
              onChange={(e) => setPasswordConfirm(e.target.value)}
              className={styles.input}
              disabled={loading}
            />
            <button
              className={styles.continueBtn}
              onClick={handleCompleteProfile}
              disabled={loading}
            >
              {loading ? "Saving..." : "Save & Continue"} →
            </button>
          </div>
        )}

        {/* Divider */}
        {authMode === "login" && step === "mobile" && (
          <>
            <div className={styles.divider}>
              <span>or</span>
            </div>

            {/* Alternative Login Options */}
            <div className={styles.altLogins}>
              <button
                className={styles.iconBtn}
                onClick={() => {
                  setStep("email");
                  setError("");
                }}
                title="Email"
              >
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
                  <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                  <polyline points="22,6 12,13 2,6" />
                </svg>
              </button>
              <div style={{ position: 'relative', display: 'inline-block' }}>
                <GoogleLogin
                  onSuccess={handleGoogleLoginSuccess}
                  onError={() => setError("Google login failed. Please try again.")}
                  useOneTap={false}
                  theme="outline"
                  size="medium"
                />
              </div>
            </div>
          </>
        )}

        {/* Email Step */}
        {authMode === "login" && step === "email" && (
          <div className={styles.emailStep}>
            <input
              type="email"
              placeholder="example@mail.com"
              value={email}
              onChange={(e) => setEmail(e.target.value)}
              className={styles.input}
              disabled={loading}
            />
            <button
              className={styles.continueBtn}
              onClick={handleEmailContinue}
              disabled={loading}
            >
              Continue →
            </button>
            <button
              className={styles.backBtn}
              onClick={() => {
                setStep("mobile");
                setEmail("");
                setError("");
              }}
            >
              ← Back
            </button>
          </div>
        )}

        {/* Email Password Step */}
        {authMode === "login" && step === "emailPassword" && (
          <div className={styles.emailStep}>
            <p className={styles.stepText}>Enter password for {email}</p>
            <input
              type="password"
              placeholder="Enter password"
              value={password}
              onChange={(e) => setPassword(e.target.value)}
              className={styles.input}
              disabled={loading}
            />
            <button
              className={styles.continueBtn}
              onClick={handleEmailPasswordContinue}
              disabled={loading}
            >
              {loading ? "Logging in..." : "Continue"} →
            </button>
            <button
              className={styles.backBtn}
              onClick={() => {
                setStep("email");
                setPassword("");
                setError("");
              }}
            >
              ← Back
            </button>
          </div>
        )}
      </div>
    </div>
  );
};

export default LoginModal;
