import React from 'react';
import styles from './CheckoutSteps.module.css';
import { FaShoppingCart, FaTruck, FaCheck } from 'react-icons/fa';

interface CheckoutStepsProps {
  currentStep: number; // 1: Cart, 2: Checkout, 3: Confirmation
}

export default function CheckoutSteps({ currentStep }: CheckoutStepsProps) {
  const steps = [
    { number: 1, label: 'Shopping Cart', icon: FaShoppingCart },
    { number: 2, label: 'Checkout', icon: FaTruck },
    { number: 3, label: 'Confirmation', icon: FaCheck },
  ];

  return (
    <div className={styles.steps}>
      {steps.map((step, index) => {
        const Icon = step.icon;
        const isActive = currentStep === step.number;
        const isCompleted = currentStep > step.number;
        
        return (
          <React.Fragment key={step.number}>
            <div className={`${styles.step} ${isActive ? styles.active : ''} ${isCompleted ? styles.completed : ''}`}>
              <div className={styles.stepIcon}>
                {isCompleted ? <FaCheck /> : <Icon />}
              </div>
              <span className={styles.stepLabel}>{step.label}</span>
            </div>
            {index < steps.length - 1 && (
              <div className={`${styles.stepLine} ${isCompleted ? styles.completed : ''}`} />
            )}
          </React.Fragment>
        );
      })}
    </div>
  );
}
