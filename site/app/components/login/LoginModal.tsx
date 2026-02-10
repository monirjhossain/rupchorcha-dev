"use client";


import React from "react";
import Image from "next/image";
import LoginPhone from "./LoginPhone";
import LoginEmail from "./LoginEmail";
import GoogleLoginButton from "./GoogleLoginButton";
import { useAuth } from "../../contexts/AuthContext";


interface LoginModalProps {
  isOpen: boolean;
  onClose: () => void;
}

const LoginModal: React.FC<LoginModalProps> = ({ isOpen, onClose }) => {
  // Modes: 'phone', 'email', 'register', 'forgot'
  const [mode, setMode] = React.useState<'phone' | 'email' | 'register' | 'forgot'>("phone");
  // Register state
  const [name, setName] = React.useState("");
  const [email, setEmail] = React.useState("");
  const [phone, setPhone] = React.useState("");
  const [password, setPassword] = React.useState("");
  const [registerError, setRegisterError] = React.useState("");
  const { register, isLoading, error, clearError } = useAuth();
  // Forgot state
  const [forgotEmail, setForgotEmail] = React.useState("");
  const [forgotError, setForgotError] = React.useState("");
  const [forgotSent, setForgotSent] = React.useState(false);
  const [forgotLoading, setForgotLoading] = React.useState(false);

  React.useEffect(() => {
    if (mode !== 'forgot') setForgotSent(false);
    if (mode !== 'register') {
      setName(""); setEmail(""); setPhone(""); setPassword(""); setRegisterError("");
    }
    if (mode !== 'forgot') {
      setForgotEmail(""); setForgotError("");
    }
  }, [mode]);

  const handleRegister = async (e: React.FormEvent) => {
    e.preventDefault();
    setRegisterError("");
    if (!name || !email || !phone || !password) {
      setRegisterError("All fields required");
      return;
    }
    try {
      await register({
        name,
        email,
        phone,
        password,
        password_confirmation: password,
      });
      setName(""); setEmail(""); setPhone(""); setPassword("");
      onClose(); // Close modal on success
    } catch {
      // error handled by context
    }
  };

  const handleForgot = (e: React.FormEvent) => {
    e.preventDefault();
    setForgotError("");
    setForgotLoading(true);
    setTimeout(() => {
      setForgotLoading(false);
      if (!forgotEmail) setForgotError("Enter your email");
      else { setForgotSent(true); }
    }, 900);
  };

  // Always reset to 'phone' when modal opens
  React.useEffect(() => {
    if (isOpen) setMode('phone');
  }, [isOpen]);

  if (!isOpen) return null;


  // Render mobile OTP login when mode is 'phone'

  return (
    <div style={{
      position: 'fixed',
      top: 0,
      left: 0,
      width: '100vw',
      height: '100vh',
      background: 'rgba(44, 19, 80, 0.18)',
      backdropFilter: 'blur(6px)',
      WebkitBackdropFilter: 'blur(6px)',
      display: 'flex',
      alignItems: 'center',
      justifyContent: 'center',
      zIndex: 9999,
      fontFamily: 'Inter, Segoe UI, Arial, sans-serif',
    }}>
      <div style={{
        background: '#fff',
        borderRadius: 16,
        padding: '20px 18px 18px 18px',
        maxWidth: 340,
        width: '92vw',
        position: 'relative',
        boxShadow: '0 4px 32px #0001',
        border: 'none',
        margin: '0 6px',
        maxHeight: '90vh',
        overflowY: 'auto',
        WebkitOverflowScrolling: 'touch',
      }}>
        {/* Back button only for email login mode */}
        {mode === 'email' && (
          <div style={{display:'flex',justifyContent:'center',marginTop:24}}>
            <button
              onClick={() => setMode('phone')}
              style={{
                display: 'flex',
                alignItems: 'center',
                justifyContent: 'center',
                background: 'none',
                border: 'none',
                fontSize: 28,
                color: '#7c32ff',
                cursor: 'pointer',
                borderRadius: '50%',
                width: 44,
                height: 44,
                boxShadow: '0 1px 4px #7c32ff11',
                transition: 'background 0.15s',
              }}
              aria-label="Back"
            >
              <span style={{fontSize: 28, lineHeight: 1}}>&#8592;</span>
            </button>
          </div>
        )}
        <button onClick={onClose} style={{
          position: 'absolute',
          top: 18,
          right: 18,
          background: 'none',
          border: 'none',
          fontSize: 24,
          color: '#222',
          cursor: 'pointer',
          fontWeight: 700,
          borderRadius: 100,
          opacity: 0.7,
        }} aria-label="Close">×</button>
        <div style={{display:'flex',flexDirection:'column',alignItems:'center',marginBottom:16}}>
          <Image
            src="/rupchorcha-logo.png"
            alt="Logo"
            width={150}
            height={50}
            style={{
              marginBottom: 10,
              borderRadius: 12,
              background: '#fff',
              objectFit: 'contain',
              height: 50,
            }}
          />
          <h2 style={{margin:0, marginBottom: 2, textAlign:'center',fontWeight:700,letterSpacing:'-0.5px',fontSize: '1.15rem',color:'#222'}}>Let&apos;s Login</h2>
        </div>
        {/* Show mobile OTP login as default, now below logo/title */}
        {mode === 'phone' && (
          <LoginPhone onSuccess={onClose} onCreateAccount={()=>setMode('register')} onForgotPassword={()=>setMode('forgot')} />
        )}
        {mode === 'email' && (
          <LoginEmail onSuccess={onClose} onCreateAccount={()=>setMode('register')} onForgotPassword={()=>setMode('forgot')} />
        )}
        {/* Tab navigation for register/forgot */}
        {(mode === 'register' || mode === 'forgot') && (
          <div style={{display:'flex',justifyContent:'center',gap:18,marginBottom:18}}>
            <button onClick={()=>setMode('register')} style={{fontWeight:700,fontSize:16,padding:'6px 18px',border:'none',borderBottom:mode==='register'?'2.5px solid #7c32ff':'2.5px solid #eee',background:'none',color:mode==='register'?'#7c32ff':'#888',cursor:'pointer',transition:'color 0.15s'}}>
              Create Account
            </button>
            <button onClick={()=>setMode('forgot')} style={{fontWeight:700,fontSize:16,padding:'6px 18px',border:'none',borderBottom:mode==='forgot'?'2.5px solid #a004b0':'2.5px solid #eee',background:'none',color:mode==='forgot'?'#a004b0':'#888',cursor:'pointer',transition:'color 0.15s'}}>
              Forgot Password
            </button>
          </div>
        )}
        {/* Register form */}
        {mode === 'register' && (
          <form onSubmit={handleRegister}>
            <input type="text" placeholder="Full Name" value={name} onChange={e=>{setName(e.target.value); if (error) clearError(); setRegisterError("");}} style={{width:'100%',marginBottom:10,padding:10,borderRadius:7,border:'1.5px solid #bbb',fontSize:16}} autoFocus disabled={isLoading} />
            <input type="email" placeholder="Email" value={email} onChange={e=>{setEmail(e.target.value); if (error) clearError(); setRegisterError("");}} style={{width:'100%',marginBottom:10,padding:10,borderRadius:7,border:'1.5px solid #bbb',fontSize:16}} disabled={isLoading} />
            <input type="tel" placeholder="Phone" value={phone} onChange={e=>{setPhone(e.target.value.replace(/\D/g, '')); if (error) clearError(); setRegisterError("");}} style={{width:'100%',marginBottom:10,padding:10,borderRadius:7,border:'1.5px solid #bbb',fontSize:16}} disabled={isLoading} />
            <input type="password" placeholder="Password" value={password} onChange={e=>{setPassword(e.target.value); if (error) clearError(); setRegisterError("");}} style={{width:'100%',marginBottom:14,padding:10,borderRadius:7,border:'1.5px solid #bbb',fontSize:16}} disabled={isLoading} />
            {(registerError || error) && <div style={{color:'#b00020',marginBottom:10,fontWeight:600,fontSize:15}}>{registerError || error}</div>}
            <button type="submit" style={{width:'100%',padding:12,borderRadius:7,background:'#7c32ff',color:'#fff',fontWeight:700,border:'none',fontSize:17,cursor:'pointer',marginBottom:0,opacity:isLoading?0.7:1}} disabled={isLoading}>
              {isLoading ? 'Registering...' : 'Register'}
            </button>
          </form>
        )}
        {/* Forgot password form */}
        {mode === 'forgot' && (
          !forgotSent ? (
            <form onSubmit={handleForgot}>
              <input type="email" placeholder="Enter your email" value={forgotEmail} onChange={e=>setForgotEmail(e.target.value)} style={{width:'100%',marginBottom:14,padding:10,borderRadius:7,border:'1.5px solid #bbb',fontSize:16}} autoFocus disabled={forgotLoading} />
              {forgotError && <div style={{color:'#b00020',marginBottom:10,fontWeight:600,fontSize:15}}>{forgotError}</div>}
              <button type="submit" style={{width:'100%',padding:12,borderRadius:7,background:'#a004b0',color:'#fff',fontWeight:700,border:'none',fontSize:17,cursor:'pointer',marginBottom:0,opacity:forgotLoading?0.7:1}} disabled={forgotLoading}>
                {forgotLoading ? 'Sending...' : 'Send Reset Link'}
              </button>
            </form>
          ) : (
            <div style={{textAlign:'center',color:'#222',fontWeight:600,fontSize:16}}>
              If this email is registered, a reset link has been sent.
            </div>
          )
        )}
        <div style={{display:'flex',alignItems:'center',margin:'18px 0 0 0'}}>
          <div style={{flex:1,height:1,background:'#eee'}}></div>
          <span style={{margin:'0 12px',color:'#aaa',fontWeight:600,fontSize:15}}>or</span>
          <div style={{flex:1,height:1,background:'#eee'}}></div>
        </div>
        {mode === 'phone' && (
          <div style={{marginTop:18, display:'flex', flexDirection:'column', gap:14}}>
            <button
              onClick={()=>setMode('email')}
              style={{
                border:'1.5px solid #7c32ff',
                background:'#fff',
                borderRadius:8,
                width:'100%',
                height:48,
                display:'flex',
                alignItems:'center',
                justifyContent:'center',
                fontSize:17,
                fontWeight:700,
                color:'#7c32ff',
                cursor:'pointer',
                gap:10,
                boxShadow:'0 2px 8px #7c32ff11',
                transition:'border 0.15s, box-shadow 0.15s',
              }}
              aria-label="Continue with Email"
            >
              <span style={{fontSize:22,marginRight:4}}>&#9993;</span>
              Continue with Email
            </button>
            <GoogleLoginButton onSuccess={onClose} fullWidth />
          </div>
        )}
        {/* Back button at the very bottom for non-phone modes */}
        {(mode === 'email' || mode === 'register' || mode === 'forgot') && (
          <div style={{display:'flex',justifyContent:'center',marginTop:24}}>
            <button
              onClick={() => setMode('phone')}
              style={{
                display: 'flex',
                alignItems: 'center',
                justifyContent: 'center',
                background: 'none',
                border: 'none',
                fontSize: 28,
                color: '#7c32ff',
                cursor: 'pointer',
                borderRadius: '50%',
                width: 44,
                height: 44,
                boxShadow: '0 1px 4px #7c32ff11',
                transition: 'background 0.15s',
              }}
              aria-label="Back"
            >
              <span style={{fontSize: 28, lineHeight: 1}}>&#8592;</span>
            </button>
          </div>
        )}
      </div>
    </div>
  );
};

export default LoginModal;
