import React, { useState } from "react";
import SimpleModal from "./SimpleModal";

interface CombinedModalProps {
  isOpen: boolean;
  onClose: () => void;
}

const CombinedModal: React.FC<CombinedModalProps> = ({ isOpen, onClose }) => {
  const [tab, setTab] = useState<'register'|'forgot'>('register');

  // Register state
  const [name, setName] = useState("");
  const [email, setEmail] = useState("");
  const [phone, setPhone] = useState("");
  const [password, setPassword] = useState("");
  const [registerError, setRegisterError] = useState("");
  const [registerLoading, setRegisterLoading] = useState(false);

  // Forgot state
  const [forgotEmail, setForgotEmail] = useState("");
  const [forgotError, setForgotError] = useState("");
  const [forgotSent, setForgotSent] = useState(false);
  const [forgotLoading, setForgotLoading] = useState(false);

  const handleRegister = (e: React.FormEvent) => {
    e.preventDefault();
    setRegisterError("");
    setRegisterLoading(true);
    setTimeout(() => {
      setRegisterLoading(false);
      if (!name || !email || !phone || !password) setRegisterError("All fields required");
      else {
        setName(""); setEmail(""); setPhone(""); setPassword("");
        onClose();
      }
    }, 900);
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

  React.useEffect(() => {
    if (!isOpen) {
      setTab('register');
      setForgotSent(false);
      setForgotEmail("");
      setForgotError("");
      setRegisterError("");
    }
  }, [isOpen]);

  return (
    <SimpleModal isOpen={isOpen} onClose={onClose} title={tab === 'register' ? 'Create Account' : 'Forgot Password'}>
      <div style={{display:'flex',justifyContent:'center',gap:18,marginBottom:18}}>
        <button onClick={()=>setTab('register')} style={{fontWeight:700,fontSize:16,padding:'6px 18px',border:'none',borderBottom:tab==='register'?'2.5px solid #7c32ff':'2.5px solid #eee',background:'none',color:tab==='register'?'#7c32ff':'#888',cursor:'pointer',transition:'color 0.15s'}}>
          Create Account
        </button>
        <button onClick={()=>setTab('forgot')} style={{fontWeight:700,fontSize:16,padding:'6px 18px',border:'none',borderBottom:tab==='forgot'?'2.5px solid #a004b0':'2.5px solid #eee',background:'none',color:tab==='forgot'?'#a004b0':'#888',cursor:'pointer',transition:'color 0.15s'}}>
          Forgot Password
        </button>
      </div>
      {tab === 'register' && (
        <form onSubmit={handleRegister}>
          <input type="text" placeholder="Full Name" value={name} onChange={e=>setName(e.target.value)} style={{width:'100%',marginBottom:10,padding:10,borderRadius:7,border:'1.5px solid #bbb',fontSize:16}} autoFocus disabled={registerLoading} />
          <input type="email" placeholder="Email" value={email} onChange={e=>setEmail(e.target.value)} style={{width:'100%',marginBottom:10,padding:10,borderRadius:7,border:'1.5px solid #bbb',fontSize:16}} disabled={registerLoading} />
          <input type="tel" placeholder="Phone" value={phone} onChange={e=>setPhone(e.target.value.replace(/\D/g, ''))} style={{width:'100%',marginBottom:10,padding:10,borderRadius:7,border:'1.5px solid #bbb',fontSize:16}} disabled={registerLoading} />
          <input type="password" placeholder="Password" value={password} onChange={e=>setPassword(e.target.value)} style={{width:'100%',marginBottom:14,padding:10,borderRadius:7,border:'1.5px solid #bbb',fontSize:16}} disabled={registerLoading} />
          {registerError && <div style={{color:'#b00020',marginBottom:10,fontWeight:600,fontSize:15}}>{registerError}</div>}
          <button type="submit" style={{width:'100%',padding:12,borderRadius:7,background:'#7c32ff',color:'#fff',fontWeight:700,border:'none',fontSize:17,cursor:'pointer',marginBottom:0,opacity:registerLoading?0.7:1}} disabled={registerLoading}>
            {registerLoading ? 'Registering...' : 'Register'}
          </button>
        </form>
      )}
      {tab === 'forgot' && (
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
    </SimpleModal>
  );
};

export default CombinedModal;
