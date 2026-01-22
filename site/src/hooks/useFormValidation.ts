import { useState, useCallback } from "react";

export interface ValidationError {
  [key: string]: string[];
}

export interface FormValidationState {
  errors: ValidationError;
  touched: { [key: string]: boolean };
  isValid: boolean;
  hasErrors: boolean;
  getFieldError: (fieldName: string) => string | null;
  setFieldError: (fieldName: string, error: string | string[]) => void;
  clearFieldError: (fieldName: string) => void;
  clearAllErrors: () => void;
  setTouched: (fieldName: string, value: boolean) => void;
  setAllTouched: () => void;
}

export const useFormValidation = (): FormValidationState => {
  const [errors, setErrors] = useState<ValidationError>({});
  const [touched, setTouched] = useState<{ [key: string]: boolean }>({});

  const getFieldError = useCallback((fieldName: string): string | null => {
    const fieldErrors = errors[fieldName];
    return fieldErrors && fieldErrors.length > 0 ? fieldErrors[0] : null;
  }, [errors]);

  const setFieldError = useCallback((fieldName: string, error: string | string[]) => {
    const errorArray = Array.isArray(error) ? error : [error];
    setErrors((prev) => ({
      ...prev,
      [fieldName]: errorArray,
    }));
  }, []);

  const clearFieldError = useCallback((fieldName: string) => {
    setErrors((prev) => ({
      ...prev,
      [fieldName]: [],
    }));
  }, []);

  const clearAllErrors = useCallback(() => {
    setErrors({});
  }, []);

  const setFieldTouched = useCallback((fieldName: string, value: boolean) => {
    setTouched((prev) => ({
      ...prev,
      [fieldName]: value,
    }));
  }, []);

  const setAllTouched = useCallback(() => {
    setTouched((prev) => {
      const newTouched = { ...prev };
      Object.keys(errors).forEach((key) => {
        newTouched[key] = true;
      });
      return newTouched;
    });
  }, [errors]);

  const isValid = Object.values(errors).every((err) => !err || err.length === 0);
  const hasErrors = !isValid;

  return {
    errors,
    touched,
    isValid,
    hasErrors,
    getFieldError,
    setFieldError,
    clearFieldError,
    clearAllErrors,
    setTouched: setFieldTouched,
    setAllTouched,
  };
};

/**
 * Email validation helper
 */
export const validateEmail = (email: string): string | null => {
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (!email) return "Email is required";
  if (!emailRegex.test(email)) return "Please enter a valid email";
  return null;
};

/**
 * Password validation helper
 */
export const validatePassword = (password: string): string | null => {
  if (!password) return "Password is required";
  if (password.length < 6) return "Password must be at least 6 characters";
  // Optional: Add more complex password rules
  // if (!/[A-Z]/.test(password)) return "Password must contain uppercase letter";
  // if (!/[0-9]/.test(password)) return "Password must contain number";
  return null;
};

/**
 * Phone validation helper
 */
export const validatePhone = (phone: string): string | null => {
  if (!phone) return "Phone number is required";
  const phoneRegex = /^[\d\s\-\+\(\)]{10,}$/;
  if (!phoneRegex.test(phone)) return "Please enter a valid phone number";
  return null;
};

/**
 * Name validation helper
 */
export const validateName = (name: string, minLength = 2): string | null => {
  if (!name) return "Name is required";
  if (name.trim().length < minLength) return `Name must be at least ${minLength} characters`;
  return null;
};

/**
 * Confirm password validation
 */
export const validatePasswordMatch = (password: string, confirmPassword: string): string | null => {
  if (password !== confirmPassword) return "Passwords do not match";
  return null;
};
