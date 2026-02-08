import React, { useState } from "react";

interface ModalProps {
  isOpen: boolean;
  onClose: () => void;
  title: string;
  children: React.ReactNode;
}

const SimpleModal: React.FC<ModalProps> = ({ isOpen, onClose, title, children }) => {
  if (!isOpen) return null;
  return (
    <div style={{
      position: 'fixed',
      top: 0,
      left: 0,
      width: '100vw',
      height: '100vh',
      background: 'rgba(44, 19, 80, 0.18)',
      display: 'flex',
      alignItems: 'center',
      justifyContent: 'center',
      zIndex: 10001,
      fontFamily: 'Inter, Segoe UI, Arial, sans-serif',
    }}>
      <div style={{
        background: '#fff',
        borderRadius: 16,
        padding: '28px 24px 24px 24px',
        maxWidth: 370,
        width: '97vw',
        position: 'relative',
        boxShadow: '0 4px 32px #0001',
        border: 'none',
        margin: '0 6px',
        overflow: 'hidden',
      }}>
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
        <h2 style={{margin:0, marginBottom: 18, textAlign:'center',fontWeight:700,letterSpacing:'-0.5px',fontSize: '1.25rem',color:'#222'}}>{title}</h2>
        {children}
      </div>
    </div>
  );
};

export default SimpleModal;
