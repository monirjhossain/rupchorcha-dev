
import React, { useState } from "react";
import { useAuth } from "../../contexts/AuthContext";

interface LoginPhoneProps {
  onSuccess: () => void;
  onCreateAccount?: () => void;
  onForgotPassword?: () => void;
}

const LoginPhone: React.FC<LoginPhoneProps> = ({ onSuccess, onCreateAccount, onForgotPassword }) => {
  const [phone, setPhone] = useState("");
  const [otp, setOtp] = useState("");
  const [otpSent, setOtpSent] = useState(false);
  const [localError, setLocalError] = useState("");
  const { sendOtp, verifyOtp, isLoading, error, clearError } = useAuth();

  const handleSendOtp = async (e: React.FormEvent) => {
    e.preventDefault();
    setLocalError("");
    if (!phone || phone.length < 10) {
      setLocalError("Enter valid phone");
      return;
    }
    try {
      await sendOtp(phone);
      setOtpSent(true);
    } catch {
      // error handled by context
    }
  };

  const handleOtpLogin = async (e: React.FormEvent) => {
    e.preventDefault();
    setLocalError("");
    if (!otp || otp.length < 4) {
      setLocalError("Enter valid OTP");
      return;
    }
    try {
      await verifyOtp(phone, otp);
      setOtpSent(false);
      setPhone("");
      setOtp("");
      onSuccess();
    } catch {
      // error handled by context
    }
  };

  return (
    <form onSubmit={otpSent ? handleOtpLogin : handleSendOtp} style={{marginBottom:0, width:'100%'}}>
      <div style={{display:'flex',flexDirection:'column',gap:12,marginBottom:10}}>
        <div style={{display:'flex',alignItems:'center',border:'1.5px solid #bbb',borderRadius:8,padding:'0 12px',height:48,background:'#fafafa'}}>
          <span style={{fontSize:20,marginRight:8,color:'#7c32ff'}}>&#128222;</span>
          <input
            type="tel"
            placeholder="+88  XXXXXXXXXX"
            value={phone}
            onChange={e => { setPhone(e.target.value.replace(/\D/g, '')); if (error) clearError(); setLocalError(""); }}
            style={{flex:1,border:'none',outline:'none',background:'transparent',fontSize:17}}
            autoFocus
            disabled={isLoading || otpSent}
          />
        </div>
        {otpSent && (
          <div style={{display:'flex',alignItems:'center',border:'1.5px solid #bbb',borderRadius:8,padding:'0 12px',height:48,background:'#fafafa'}}>
            <span style={{fontSize:20,marginRight:8,color:'#a004b0'}}>&#128273;</span>
            <input
              type="text"
              placeholder="Enter OTP"
              value={otp}
              onChange={e => { setOtp(e.target.value.replace(/\D/g, '')); if (error) clearError(); setLocalError(""); }}
              style={{flex:1,border:'none',outline:'none',background:'transparent',fontSize:17}}
              autoFocus
              disabled={isLoading}
            />
          </div>
        )}
      </div>
      {(localError || error) && <div style={{color:'#b00020',marginBottom:10,fontWeight:600,fontSize:15,textAlign:'center'}}>{localError || error}</div>}
      <button type="submit" style={{width:'100%',padding:14,borderRadius:8,background:'#7c32ff',color:'#fff',fontWeight:800,border:'none',fontSize:18,cursor:'pointer',marginBottom:12,display:'flex',alignItems:'center',justifyContent:'center',gap:8,opacity:isLoading?0.7:1,boxShadow:'0 2px 8px #7c32ff11',transition:'background 0.15s'}} disabled={isLoading}>
        Continue <span style={{fontSize:18,marginLeft:4}}>&#8594;</span>
      </button>
      <div style={{display:'flex',justifyContent:'space-between',alignItems:'center',marginBottom:8}}>
        <button type="button" style={{background:'none',border:'none',color:'#7c32ff',fontWeight:700,fontSize:15,cursor:'pointer',padding:0}} onClick={onCreateAccount}>Create Account</button>
        <button type="button" style={{background:'none',border:'none',color:'#a004b0',fontWeight:700,fontSize:15,cursor:'pointer',padding:0}} onClick={onForgotPassword}>Forgot password?</button>
      </div>
    </form>
  );
};

export default LoginPhone;
