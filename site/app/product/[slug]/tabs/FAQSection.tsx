"use client";
import React, { useState } from "react";
import type { Product } from "../types";
import styles from "./FAQSection.module.css";

interface FAQSectionProps {
  product: Product;
}

interface FAQItem {
  question: string;
  answer: string;
}

export default function FAQSection({ product }: FAQSectionProps) {
  const [openIndex, setOpenIndex] = useState<number | null>(0);

  // Get FAQs from product or use default
  const productFaqs = (product as Record<string, unknown>).faqs as FAQItem[] || [];
  
  const defaultFaqs: FAQItem[] = [
    {
      question: "How do I place an order?",
      answer: "Simply add items to your cart, proceed to checkout, fill in your delivery details, and choose your preferred payment method. You'll receive a confirmation email once your order is placed.",
    },
    {
      question: "What payment methods do you accept?",
      answer: "We accept Credit/Debit Cards, bKash, Nagad, Bank Transfer, and Cash on Delivery (COD). All online payments are secured with SSL encryption.",
    },
    {
      question: "How long does delivery take?",
      answer: "Inside Dhaka: 2-3 business days. Outside Dhaka: 3-5 business days. You'll receive a tracking number once your order is shipped.",
    },
    {
      question: "Can I return or exchange this product?",
      answer: "Yes! We offer a 7-day return and exchange policy. The product must be unused and in original packaging. Contact our support team to initiate a return.",
    },
    {
      question: "Is this product authentic?",
      answer: "Absolutely! We guarantee 100% authentic products sourced directly from authorized suppliers and brands.",
    },
    {
      question: "Do you provide warranty?",
      answer: "Yes, all products come with manufacturer warranty. The warranty period varies by product - check the product details or contact us for specific warranty information.",
    },
    {
      question: "How can I track my order?",
      answer: "Once your order is shipped, you'll receive a tracking number via email and SMS. You can use this to track your delivery status.",
    },
    {
      question: "What if I receive a damaged product?",
      answer: "If you receive a damaged or defective product, contact us immediately with photos. We'll arrange a free replacement or full refund.",
    },
  ];

  const faqs = productFaqs.length > 0 ? productFaqs : defaultFaqs;

  const toggleFAQ = (index: number) => {
    setOpenIndex(openIndex === index ? null : index);
  };

  return (
    <div className={styles.container}>
      <h2 className={styles.heading}>Frequently Asked Questions</h2>
      <p className={styles.intro}>
        Find answers to common questions about this product and our services.
      </p>

      <div className={styles.faqList}>
        {faqs.map((faq: FAQItem, index: number) => (
          <div key={index} className={styles.faqItem}>
            <button
              className={`${styles.faqQuestion} ${openIndex === index ? styles.active : ""}`}
              onClick={() => toggleFAQ(index)}
              aria-expanded={openIndex === index}
            >
              <span className={styles.questionText}>{faq.question}</span>
              <span className={styles.toggleIcon}>
                {openIndex === index ? "−" : "+"}
              </span>
            </button>
            {openIndex === index && (
              <div className={styles.faqAnswer}>
                <p>{faq.answer}</p>
              </div>
            )}
          </div>
        ))}
      </div>

      {/* Still have questions? */}
      <div className={styles.contactPrompt}>
        <h3 className={styles.promptTitle}>Still have questions?</h3>
        <p className={styles.promptText}>
          Our customer support team is here to help! Contact us anytime.
        </p>
        <div className={styles.contactButtons}>
          <a href="tel:+8801234567890" className={styles.contactButton}>
            📞 Call Us
          </a>
          <a href="mailto:support@rupchorcha.com" className={styles.contactButton}>
            📧 Email Us
          </a>
        </div>
      </div>
    </div>
  );
}
