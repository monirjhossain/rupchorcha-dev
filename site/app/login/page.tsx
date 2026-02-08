"use client";

import React from "react";
import { AuthProvider } from "@/app/contexts/AuthContext";
import LoginModal from "@/app/components/login/LoginModal";


export default function LoginPage() {
  // Always show the login modal on this page
  return (
    <AuthProvider>
      <LoginModal isOpen={true} onClose={() => { window.location.href = "/"; }} />
    </AuthProvider>
  );
}
