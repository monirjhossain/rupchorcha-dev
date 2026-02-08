
import React, { useState } from "react";
import { useAuth } from "../../contexts/AuthContext";

interface LoginEmailProps {
  onSuccess: () => void;
  onCreateAccount?: () => void;
  onForgotPassword?: () => void;
}

const LoginEmail: React.FC<LoginEmailProps> = ({ onSuccess, onCreateAccount, onForgotPassword }) => {
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [localError, setLocalError] = useState("");
  const { loginWithEmail, isLoading, error, clearError } = useAuth();

  const handleLogin = async (e: React.FormEvent) => {
    e.preventDefault();
    setLocalError("");
    if (!email) {
      setLocalError("Enter valid email");
      return;
    }
    if (!password) {
      setLocalError("Enter your password");
      return;
    }
    try {
      await loginWithEmail(email, password);
      setEmail("");
      setPassword("");
      onSuccess();
    } catch (err) {
      // error is handled by context
    }
  };

  return (
    <form onSubmit={handleLogin} style={{marginBottom:0}}>
      <div style={{display:'flex',alignItems:'center',border:'1.5px solid #bbb',borderRadius:7,padding:'0 10px',marginBottom:14,height:44,background:'#fafafa'}}>
        <span style={{fontSize:20,marginRight:8,color:'#444'}}>&#9993;</span>
        <input
          type="email"
          placeholder="example@mail.com"
          value={email}
          onChange={e => { setEmail(e.target.value); if (error) clearError(); setLocalError(""); }}
          style={{flex:1,border:'none',outline:'none',background:'transparent',fontSize:17}}
          autoFocus
          disabled={isLoading}
        />
      </div>
      <div style={{display:'flex',alignItems:'center',border:'1.5px solid #bbb',borderRadius:7,padding:'0 10px',marginBottom:18,height:44,background:'#fafafa'}}>
        <span style={{fontSize:20,marginRight:8,color:'#444'}}>&#128274;</span>
        <input
          type="password"
          placeholder="Password"
          value={password}
          onChange={e => { setPassword(e.target.value); if (error) clearError(); setLocalError(""); }}
          style={{flex:1,border:'none',outline:'none',background:'transparent',fontSize:17}}
          disabled={isLoading}
        />
      </div>
      {(localError || error) && <div style={{color:'#b00020',marginBottom:10,fontWeight:600,fontSize:15}}>{localError || error}</div>}
      <button type="submit" style={{width:'100%',padding:13,borderRadius:7,background:'#888',color:'#fff',fontWeight:700,border:'none',fontSize:18,cursor:'pointer',marginBottom:10,display:'flex',alignItems:'center',justifyContent:'center',gap:8,opacity:isLoading?0.7:1}} disabled={isLoading}>
        Continue <span style={{fontSize:18,marginLeft:4}}>&#8594;</span>
      </button>
      {/* Only show navigation buttons if not on register mode */}
      {onCreateAccount && onForgotPassword && (
        <div style={{display:'flex',justifyContent:'space-between',alignItems:'center',marginBottom:18}}>
          <button type="button" style={{background:'none',border:'none',color:'#7c32ff',fontWeight:700,fontSize:15,cursor:'pointer',padding:0}} onClick={onCreateAccount}>Create Account</button>
          <button type="button" style={{background:'none',border:'none',color:'#a004b0',fontWeight:700,fontSize:15,cursor:'pointer',padding:0}} onClick={onForgotPassword}>Forgot password?</button>
        </div>
      )}
    </form>
  );
};

export default LoginEmail;
