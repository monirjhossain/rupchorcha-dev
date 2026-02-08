import React from "react";
import { GoogleLogin } from "@react-oauth/google";
import { useAuth } from "@/app/contexts/AuthContext";
import { useRouter } from "next/navigation";

interface GoogleLoginButtonProps {
  onSuccess: () => void;
  fullWidth?: boolean;
}

const GoogleLoginButton: React.FC<GoogleLoginButtonProps> = ({ onSuccess, fullWidth }) => {
  const [loading, setLoading] = React.useState(false);
  const [error, setError] = React.useState("");
  const { loginWithGoogle } = useAuth();
  const router = useRouter();

  return (
    <div style={{
      display: 'flex',
      flexDirection: 'column',
      alignItems: 'center',
      width: fullWidth ? '100%' : 44,
      minWidth: fullWidth ? 0 : 44,
      margin: 0,
    }}>
      <GoogleLogin
        theme="filled_blue"
        text={fullWidth ? 'continue_with' : ''}
        shape={fullWidth ? 'pill' : 'circle'}
        logo_alignment="center"
        onSuccess={async (credentialResponse) => {
          setLoading(true);
          setError("");
          try {
            if (credentialResponse.credential) {
              await loginWithGoogle(credentialResponse.credential);
              onSuccess(); // modal close only, no redirect
            } else {
              setError("Google login failed. No credential received.");
            }
          } catch (err: any) {
            setError(err?.message || "Google login failed");
          } finally {
            setLoading(false);
          }
        }}
        onError={() => setError("Google login failed. Please try again.")}
        useOneTap={false}
      />
      {error && <div style={{color:'#b00020',fontWeight:600,fontSize:13,marginTop:8,textAlign:'center',maxWidth:fullWidth?300:80}}>{error}</div>}
    </div>
  );
};

export default GoogleLoginButton;
