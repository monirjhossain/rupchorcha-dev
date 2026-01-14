import React from "react";
import styles from "./Footer.module.css";

const Footer: React.FC = () => {
  return (
    <footer className={styles.footer}>
      <div className={styles.footerInner}>
        <p>&copy; {new Date().getFullYear()} Rupchorcha. All rights reserved.</p>
      </div>
    </footer>
  );
};

export default Footer;
