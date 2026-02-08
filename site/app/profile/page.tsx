"use client";
import React, { useState, useEffect } from "react";
import { useRouter } from "next/navigation";
import { useAuth } from "@/app/contexts/AuthContext";
import styles from "./profile.module.css";
import { fetchMyOrders, Order } from "@/src/data/orders";
import WishlistPage from "../wishlist/page";

// Address type for address state
type Address = {
  id?: number;
  name: string;
  phone: string;
  address: string;
  city: string;
  postal: string;
  type: string;
  is_default?: boolean;
};

function ProfileOrdersTable() {
  const [orders, setOrders] = useState<Order[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);
  const [selectedOrder, setSelectedOrder] = useState<Order | null>(null);
  const [showModal, setShowModal] = useState(false);

    useEffect(() => {
      fetchMyOrders()
        .then((data) => {
          setOrders(data);
          setLoading(false);
        })
        .catch(() => {
          setError("Failed to load orders.");
        });
    }, []);

    // Status color helper
    function getStatusColor(status: string) {
      switch (status) {
        case 'pending': return '#ff9800';
        case 'processing': return '#2196f3';
        case 'completed': return '#4caf50';
        case 'cancelled': return '#f44336';
        default: return '#757575';
      }
    }

    // Modal content for order details
    const renderOrderModal = () => {
      if (!selectedOrder) return null;
      return (
        <div style={{
          position: 'fixed',
          top: 0, left: 0, right: 0, bottom: 0,
          background: 'rgba(0,0,0,0.18)',
          zIndex: 1000,
          display: 'flex', alignItems: 'center', justifyContent: 'center',
          padding: '0 12px',
        }}>
          <div style={{
            background: '#fff',
            borderRadius: 16,
            boxShadow: '0 8px 32px rgba(99,102,241,0.18)',
            padding: '2rem 1.5rem',
            minWidth: 320,
            maxWidth: 460,
            width: '100%',
            position: 'relative',
            maxHeight: '85vh',
            overflowY: 'auto',
          }}>
            <button onClick={() => setShowModal(false)} style={{
              position: 'absolute', top: 18, right: 18, background: 'none', border: 'none', fontSize: 22, color: '#a004b0', cursor: 'pointer', fontWeight: 700
            }}>&times;</button>
            <h2 style={{ color: '#a004b0', fontWeight: 800, marginBottom: 18 }}>Order Details</h2>
            <div style={{ marginBottom: 12 }}>
              <strong>Order #:</strong> <span style={{ color: '#6366f1', fontWeight: 700 }}>#{selectedOrder.order_number || selectedOrder.id}</span>
            </div>
            <div style={{ marginBottom: 12 }}>
              <strong>Status:</strong> <span style={{ color: getStatusColor(selectedOrder.status), fontWeight: 700 }}>{selectedOrder.status}</span>
            </div>
            <div style={{ marginBottom: 12 }}>
              <strong>Date:</strong> {new Date(selectedOrder.created_at).toLocaleString()}
            </div>
            <div style={{ marginBottom: 12 }}>
              <strong>Total:</strong> <span style={{ color: '#a004b0', fontWeight: 700 }}>{selectedOrder.total} ৳</span>
            </div>
            {/* Product list, shipping, payment, notes commented out due to type errors */}
            {false && Array.isArray(selectedOrder.items) && selectedOrder.items.length > 0 && (
              <div style={{ marginBottom: 12 }}>
                <strong>Products:</strong>
                <ul style={{ margin: '8px 0 0 0', padding: 0, listStyle: 'none' }}>
                  {selectedOrder.items.map((item: any) => (
                    <li key={item.id} style={{ marginBottom: 6, fontSize: '0.98em' }}>
                      <span style={{ fontWeight: 600 }}>{item.product_name || item.name}</span> &times; {item.quantity} <span style={{ color: '#a004b0', fontWeight: 700 }}>৳{item.price}</span>
                    </li>
                  ))}
                </ul>
              </div>
            )}
            {false && selectedOrder.shipping_address && (
              <div style={{ marginBottom: 12 }}>
                <strong>Shipping Address:</strong>
                <div style={{ color: '#555', marginTop: 2 }}>{selectedOrder.shipping_address}</div>
              </div>
            )}
            {false && selectedOrder.payment_method && (
              <div style={{ marginBottom: 12 }}>
                <strong>Payment:</strong> {selectedOrder.payment_method} ({selectedOrder.payment_status})
              </div>
            )}
            {false && selectedOrder.notes && (
              <div style={{ marginBottom: 12 }}>
                <strong>Notes:</strong> {selectedOrder.notes}
              </div>
            )}
          </div>
        </div>
      );
    };

    return (
      <div style={{ overflowX: 'auto', margin: '0 auto', maxWidth: 900 }}>
        <div style={{
          background: '#fff',
          borderRadius: 18,
          boxShadow: '0 8px 32px rgba(99,102,241,0.10)',
          padding: '2.5rem 2rem',
          margin: '0 auto',
          minWidth: 340,
          maxWidth: 900,
        }}>
          <table style={{ width: "100%", borderCollapse: "separate", borderSpacing: 0, minWidth: 600 }}>
            <thead>
              <tr>
                <th style={{ color: '#a004b0', fontWeight: 800, fontSize: '1.05rem', background: 'none', border: 'none', padding: '14px 8px', textAlign: 'left' }}>Order #</th>
                <th style={{ color: '#a004b0', fontWeight: 800, fontSize: '1.05rem', background: 'none', border: 'none', padding: '14px 8px', textAlign: 'left' }}>Status</th>
                <th style={{ color: '#a004b0', fontWeight: 800, fontSize: '1.05rem', background: 'none', border: 'none', padding: '14px 8px', textAlign: 'left' }}>Total</th>
                <th style={{ color: '#a004b0', fontWeight: 800, fontSize: '1.05rem', background: 'none', border: 'none', padding: '14px 8px', textAlign: 'left' }}>Date</th>
                <th style={{ background: 'none', border: 'none', padding: '14px 8px' }}></th>
              </tr>
            </thead>
            <tbody>
              {orders.map((order) => (
                <tr key={order.id} style={{ transition: 'background 0.2s', borderRadius: 12 }}>
                  <td style={{ borderBottom: "1px solid #f0f0f0", padding: "14px 8px", fontWeight: 800, fontSize: '1.08em', color: '#333' }}>
                    #{order.order_number || order.id}
                  </td>
                  <td style={{ borderBottom: "1px solid #f0f0f0", padding: "14px 8px" }}>
                    <span style={{
                      color: getStatusColor(order.status),
                      fontWeight: 700,
                      textTransform: 'capitalize',
                      background: '#f9f9f9',
                      borderRadius: 12,
                      padding: '4px 18px',
                      fontSize: '1em',
                      boxShadow: '0 2px 8px rgba(99,102,241,0.07)',
                      border: `1.5px solid ${getStatusColor(order.status)}`,
                      letterSpacing: '0.5px',
                    }}>{order.status}</span>
                  </td>
                  <td style={{ borderBottom: "1px solid #f0f0f0", padding: "14px 8px", fontWeight: 700, color: '#a004b0' }}>{order.total} ৳</td>
                  <td style={{ borderBottom: "1px solid #f0f0f0", padding: "14px 8px", color: '#666', fontWeight: 500 }}>{new Date(order.created_at).toLocaleString()}</td>
                  <td style={{ borderBottom: "1px solid #f0f0f0", padding: "14px 8px" }}>
                    <button style={{
                      background: 'linear-gradient(90deg, #a004b0 0%, #6366f1 100%)',
                      color: '#fff',
                      border: 'none',
                      borderRadius: 8,
                      padding: '8px 22px',
                      fontWeight: 700,
                      cursor: 'pointer',
                      fontSize: '1em',
                      boxShadow: '0 2px 8px rgba(99,102,241,0.10)',
                      transition: 'background 0.2s, box-shadow 0.2s',
                    }}
                    onClick={() => { setSelectedOrder(order); setShowModal(true); }}
                    >View Details</button>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
          {showModal && renderOrderModal()}
        </div>
      </div>
  );
}

// Address modal and list for My Address section
function ProfileAddressSection() {
  const [addresses, setAddresses] = useState<Address[]>([]);
  const [showModal, setShowModal] = useState(false);
  const [form, setForm] = useState<Address>({ name: '', phone: '', address: '', city: '', postal: '', type: 'home', is_default: false });
  const [editId, setEditId] = useState<number | null>(null);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState<string | null>(null);
  const API_BASE = process.env.NEXT_PUBLIC_API_URL || 'http://localhost:8000/api';

  // Fetch addresses from backend and map fields
  const fetchAddresses = async () => {
    setLoading(true);
    setError(null);
    try {
      const res = await fetch(`${API_BASE}/addresses`, {
        headers: { 'Authorization': `Bearer ${localStorage.getItem('token')}` }
      });
      if (!res.ok) throw new Error('Failed to load addresses');
      const data = await res.json();
      // Map backend fields to frontend Address type
      const mapped = data.map((addr: any) => ({
        id: addr.id,
        name: addr.name,
        phone: addr.phone || '',
        address: addr.line1 || '',
        city: addr.city || '',
        postal: addr.postal_code || '',
        type: addr.type || '',
        is_default: addr.is_default || false
      }));
      setAddresses(mapped);
    } catch (err: any) {
      setError(err.message || 'Failed to load addresses');
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => { fetchAddresses(); }, []);

  const handleAdd = () => {
    setForm({ name: '', phone: '', address: '', city: '', postal: '', type: 'home', is_default: false });
    setEditId(null);
    setShowModal(true);
  };
  const handleEdit = (addr: Address) => {
    setForm({ ...addr });
    setEditId(addr.id!);
    setShowModal(true);
  };
  const handleDelete = async (id: number) => {
    if (!window.confirm('Delete this address?')) return;
    setLoading(true);
    setError(null);
    try {
      const res = await fetch(`${API_BASE}/addresses/${id}`, {
        method: 'DELETE',
        headers: { 'Authorization': `Bearer ${localStorage.getItem('token')}` }
      });
      if (!res.ok) throw new Error('Failed to delete address');
      await fetchAddresses();
    } catch (err: any) {
      setError(err.message || 'Failed to delete address');
    } finally {
      setLoading(false);
    }
  };
  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setLoading(true);
    setError(null);
    try {
      // Map frontend fields to backend fields
      const payload = {
        name: form.name,
        phone: form.phone,
        type: form.type,
        line1: form.address,
        city: form.city,
        postal_code: form.postal,
        is_default: form.is_default,
      };
      const method = editId ? 'PUT' : 'POST';
      const url = editId ? `${API_BASE}/addresses/${editId}` : `${API_BASE}/addresses`;
      const res = await fetch(url, {
        method,
        headers: {
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${localStorage.getItem('token')}`
        },
        body: JSON.stringify(payload)
      });
      if (!res.ok) {
        let data;
        try { data = await res.json(); } catch { throw new Error('Server error'); }
        if (data && data.errors) {
          const firstField = Object.keys(data.errors)[0];
          throw new Error(data.errors[firstField][0]);
        }
        throw new Error(data && data.message ? data.message : 'Failed to save address');
      }
      await fetchAddresses();
      setShowModal(false);
    } catch (err: any) {
      setError(err.message || 'Failed to save address');
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className={styles.section}>
      <div className={styles.sectionHeader}>
        <h2>My Address</h2>
        <button className={styles.editBtn} onClick={handleAdd}>
          + Add Address
        </button>
      </div>
      {loading && <p>Loading...</p>}
      {error && <p style={{ color: 'red' }}>{error}</p>}
      {addresses.length === 0 && !loading ? (
        <p className={styles.emptyState}>No addresses added yet.</p>
      ) : (
        <ul style={{ margin: 0, padding: 0, listStyle: 'none' }}>
          {addresses.map((addr) => (
            <li key={addr.id} style={{ marginBottom: 18, background: '#f9f9f9', borderRadius: 12, padding: '1.2rem 1.5rem', boxShadow: '0 2px 8px #6366f122', position: 'relative' }}>
              <div style={{ fontWeight: 700, fontSize: '1.08em', color: '#a004b0' }}>{addr.name} <span style={{ fontWeight: 400, color: '#6366f1', fontSize: '0.98em' }}>({addr.type})</span> {addr.is_default && <span style={{ color: '#4caf50', fontWeight: 600, marginLeft: 8 }}>(Default)</span>}</div>
              <div style={{ color: '#555', marginTop: 2 }}>{addr.address}, {addr.city}, {addr.postal}</div>
              <div style={{ color: '#555', marginTop: 2 }}>Phone: {addr.phone}</div>
              <div style={{ position: 'absolute', top: 16, right: 18, display: 'flex', gap: 8 }}>
                <button style={{ background: 'none', border: 'none', color: '#6366f1', fontWeight: 700, cursor: 'pointer' }} onClick={() => handleEdit(addr)}>Edit</button>
                <button style={{ background: 'none', border: 'none', color: '#f44336', fontWeight: 700, cursor: 'pointer' }} onClick={() => handleDelete(addr.id!)}>Delete</button>
              </div>
            </li>
          ))}
        </ul>
      )}
      {showModal && (
        <div style={{ position: 'fixed', top: 0, left: 0, right: 0, bottom: 0, background: 'rgba(0,0,0,0.18)', zIndex: 1000, display: 'flex', alignItems: 'center', justifyContent: 'center' }}>
          <form onSubmit={handleSubmit} style={{ background: '#fff', borderRadius: 16, boxShadow: '0 8px 32px #6366f122', padding: '2.5rem 2rem', minWidth: 340, maxWidth: 420, width: '100%', position: 'relative' }}>
            <button type="button" onClick={() => setShowModal(false)} style={{ position: 'absolute', top: 18, right: 18, background: 'none', border: 'none', fontSize: 22, color: '#a004b0', cursor: 'pointer', fontWeight: 700 }}>&times;</button>
            <h2 style={{ color: '#a004b0', fontWeight: 800, marginBottom: 18 }}>{editId !== null ? 'Edit Address' : 'Add Address'}</h2>
            <div style={{ marginBottom: 12 }}>
              <label>Name</label>
              <input required value={form.name} onChange={e => setForm({ ...form, name: e.target.value })} style={{ width: '100%', padding: 10, borderRadius: 8, border: '1px solid #ccc' }} />
            </div>
            <div style={{ marginBottom: 12 }}>
              <label>Phone</label>
              <input required value={form.phone} onChange={e => setForm({ ...form, phone: e.target.value })} style={{ width: '100%', padding: 10, borderRadius: 8, border: '1px solid #ccc' }} />
            </div>
            <div style={{ marginBottom: 12 }}>
              <label>Address</label>
              <input required value={form.address} onChange={e => setForm({ ...form, address: e.target.value })} style={{ width: '100%', padding: 10, borderRadius: 8, border: '1px solid #ccc' }} />
            </div>
            <div style={{ marginBottom: 12 }}>
              <label>City</label>
              <input required value={form.city} onChange={e => setForm({ ...form, city: e.target.value })} style={{ width: '100%', padding: 10, borderRadius: 8, border: '1px solid #ccc' }} />
            </div>
            <div style={{ marginBottom: 12 }}>
              <label>Postal Code</label>
              <input required value={form.postal} onChange={e => setForm({ ...form, postal: e.target.value })} style={{ width: '100%', padding: 10, borderRadius: 8, border: '1px solid #ccc' }} />
            </div>
            <div style={{ marginBottom: 12 }}>
              <label>Type</label>
              <select value={form.type} onChange={e => setForm({ ...form, type: e.target.value })} style={{ width: '100%', padding: 10, borderRadius: 8, border: '1px solid #ccc' }}>
                <option value="home">Home</option>
                <option value="office">Office</option>
                <option value="other">Other</option>
              </select>
            </div>
            <div style={{ marginBottom: 12 }}>
              <label><input type="checkbox" checked={!!form.is_default} onChange={e => setForm({ ...form, is_default: e.target.checked })} /> Set as default</label>
            </div>
            <button type="submit" style={{ width: '100%', padding: '12px', background: 'linear-gradient(90deg, #a004b0 0%, #6366f1 100%)', color: '#fff', border: 'none', borderRadius: 8, fontWeight: 700, fontSize: '1.1em', cursor: 'pointer' }} disabled={loading}>
              {editId !== null ? (loading ? 'Saving...' : 'Update Address') : (loading ? 'Saving...' : 'Add Address')}
            </button>
          </form>
        </div>
      )}
    </div>
  );
}

export default function ProfilePage() {
  const [activeSection, setActiveSection] = useState("general");
  const [editMode, setEditMode] = useState(false);
  const [form, setForm] = useState({
    name: '',
    phone: '',
    address: ''
  });
  const [saving, setSaving] = useState(false);
  const [saveError, setSaveError] = useState<string|null>(null);
  const router = useRouter();
  const { user, isLoading, logout } = useAuth();

  // Keep form in sync with user when not editing
  useEffect(() => {
    if (!editMode && user) {
      setForm({
        name: user.name || "",
        phone: user.phone || "",
        address: user.address || ""
      });
    }
  }, [user, editMode]);

  // Listen for auth-state-changed event to reload page/user state
  useEffect(() => {
    const handleAuthChange = () => {
      router.refresh(); // Next.js 13+ instant reload
    };
    window.addEventListener("auth-state-changed", handleAuthChange);
    return () => {
      window.removeEventListener("auth-state-changed", handleAuthChange);
    };
  }, [router]);

  const handleLogout = () => {
      logout();
      router.push("/");
  };

  useEffect(() => {
    if (!user && !isLoading) {
      router.push("/");
    }
  }, [user, isLoading, router]);

  if (isLoading) {
    return (
      <div style={{ textAlign: "center", padding: "4rem" }}>
        <p>Loading...</p>
      </div>
    );
  }
  if (!user) {
    return null;
  }

  // Fallback: show email if name is 'User' or empty
  const displayName = !user.name || user.name.trim().toLowerCase() === 'user' ? user.email : user.name;

  return (
    <div className={styles.container}>
      <div className={styles.sidebar}>
        {/* User Info */}
        <div className={styles.userCard}>
          <div className={styles.avatar}>
            <svg width="50" height="50" viewBox="0 0 24 24" fill="none">
              <circle cx="12" cy="8" r="4" fill="#6366f1" />
              <path
                d="M4 20c0-3.31 3.13-6 8-6s8 2.69 8 6"
                fill="#6366f1"
              />
            </svg>
          </div>
          <h3 className={styles.userName}>{displayName}</h3>
          <p className={styles.userPhone}>{user.phone}</p>
        </div>

        {/* Navigation Menu */}
        <div className={styles.menu}>
          <div className={styles.menuSection}>
            <p className={styles.menuTitle}>PROFILE</p>
            <button
              className={`${styles.menuItem} ${
                activeSection === "general" ? styles.active : ""
              }`}
              onClick={() => setActiveSection("general")}
            >
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                <circle cx="12" cy="8" r="4" stroke="currentColor" strokeWidth="2" />
                <path
                  d="M4 20c0-3.31 3.13-6 8-6s8 2.69 8 6"
                  stroke="currentColor"
                  strokeWidth="2"
                />
              </svg>
              General Info
            </button>
            <button
              className={`${styles.menuItem} ${
                activeSection === "favourites" ? styles.active : ""
              }`}
              onClick={() => setActiveSection("favourites")}
            >
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                <path
                  d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"
                  stroke="currentColor"
                  strokeWidth="2"
                />
              </svg>
              My Wishlist
            </button>
          </div>

          <div className={styles.menuSection}>
            <p className={styles.menuTitle}>ORDERS</p>
            <button
              className={`${styles.menuItem} ${
                activeSection === "orders" ? styles.active : ""
              }`}
              onClick={() => setActiveSection("orders")}
            >
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                <rect
                  x="3"
                  y="3"
                  width="18"
                  height="18"
                  rx="2"
                  stroke="currentColor"
                  strokeWidth="2"
                />
                <path d="M9 9h6M9 13h6M9 17h4" stroke="currentColor" strokeWidth="2" />
              </svg>
              My Orders
            </button>
            <button
              className={`${styles.menuItem} ${
                activeSection === "address" ? styles.active : ""
              }`}
              onClick={() => setActiveSection("address")}
            >
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                <path
                  d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"
                  fill="currentColor"
                />
              </svg>
              My Address
            </button>
          </div>

          <div className={styles.menuSection}>
            <p className={styles.menuTitle}>OTHER</p>
            <button
              className={`${styles.menuItem} ${
                activeSection === "reviews" ? styles.active : ""
              }`}
              onClick={() => setActiveSection("reviews")}
            >
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                <path
                  d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"
                  fill="currentColor"
                />
              </svg>
              My Reviews
            </button>
          </div>
        </div>

        {/* Logout Button */}
        <button className={styles.logoutBtn} onClick={handleLogout}>
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
            <path
              d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4V5z"
              fill="currentColor"
            />
          </svg>
          Logout
        </button>
      </div>

      {/* Main Content */}
      <div className={styles.content}>
        {activeSection === "general" && (
          <div className={styles.section}>
            <div className={styles.sectionHeader}>
              <h2>General Info</h2>
              {!editMode ? (
                <button className={styles.editBtn} onClick={() => setEditMode(true)}>
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                    <path
                      d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"
                      fill="currentColor"
                    />
                  </svg>
                  Edit
                </button>
              ) : null}
            </div>

            <div className={styles.infoGrid}>
              {editMode ? (
                <>
                  <div className={styles.infoItem}>
                    <label>Name</label>
                    <input
                      type="text"
                      value={form.name}
                      onChange={e => setForm(f => ({ ...f, name: e.target.value }))}
                      style={{ width: '100%', padding: 8, borderRadius: 6, border: '1px solid #ccc' }}
                    />
                  </div>
                  <div className={styles.infoItem}>
                    <label>Email Address</label>
                    <input type="text" value={user.email} disabled style={{ width: '100%', padding: 8, borderRadius: 6, border: '1px solid #eee', background: '#f9f9f9' }} />
                  </div>
                  <div className={styles.infoItem}>
                    <label>Mobile Number</label>
                    <input
                      type="text"
                      value={form.phone}
                      onChange={e => setForm(f => ({ ...f, phone: e.target.value }))}
                      style={{ width: '100%', padding: 8, borderRadius: 6, border: '1px solid #ccc' }}
                    />
                    {/* Show phone error below field if present */}
                    {saveError && saveError.toLowerCase().includes('phone') && (
                      <span style={{ color: 'red', fontSize: '0.98em' }}>{saveError}</span>
                    )}
                  </div>
                  <div className={styles.infoItem}>
                    <label>Role</label>
                    <input type="text" value={user.role || 'Customer'} disabled style={{ width: '100%', padding: 8, borderRadius: 6, border: '1px solid #eee', background: '#f9f9f9' }} />
                  </div>
                  <div className={styles.infoItem}>
                    <label>Date of Birth</label>
                    <input type="text" value="Not Provided (not editable)" disabled style={{ width: '100%', padding: 8, borderRadius: 6, border: '1px solid #eee', background: '#f9f9f9' }} />
                  </div>
                  <div className={styles.infoItem}>
                    <label>Address</label>
                    <input
                      type="text"
                      value={form.address}
                      onChange={e => setForm(f => ({ ...f, address: e.target.value }))}
                      style={{ width: '100%', padding: 8, borderRadius: 6, border: '1px solid #ccc' }}
                    />
                  </div>
                  <div style={{ gridColumn: '1/-1', display: 'flex', gap: 12, marginTop: 18 }}>
                    <button
                      onClick={async () => {
                        setSaving(true);
                        setSaveError(null);
                        try {
                          const res = await fetch(`${process.env.NEXT_PUBLIC_API_URL || 'http://localhost:8000/api'}/profile`, {
                            method: 'PUT',
                            headers: {
                              'Content-Type': 'application/json',
                              'Authorization': `Bearer ${localStorage.getItem('token')}`
                            },
                            body: JSON.stringify(form)
                          });
                          let data;
                          try {
                            data = await res.json();
                          } catch (jsonErr) {
                            throw new Error('Server error: Non-JSON response. Please check your login or try again.');
                          }
                          if (!res.ok || !data.success) {
                            // Show validation error if present
                            if (data.errors) {
                              // Show first error message found
                              const firstField = Object.keys(data.errors)[0];
                              throw new Error(data.errors[firstField][0]);
                            }
                            throw new Error(data.message || 'Failed to update profile');
                          }
                          window.dispatchEvent(new Event('auth-state-changed'));
                          setEditMode(false);
                        } catch (err: any) {
                          setSaveError(err.message || 'Failed to update profile');
                        } finally {
                          setSaving(false);
                        }
                      }}
                      style={{ padding: '10px 28px', background: 'linear-gradient(90deg, #a004b0 0%, #6366f1 100%)', color: '#fff', border: 'none', borderRadius: 8, fontWeight: 700, fontSize: '1.1em', cursor: 'pointer' }}
                      disabled={saving}
                    >{saving ? 'Saving...' : 'Save'}</button>
                    <button
                      onClick={() => { setEditMode(false); setSaveError(null); setForm({ name: user.name || '', phone: user.phone || '', address: user.address || '' }); }}
                      style={{ padding: '10px 28px', background: '#eee', color: '#a004b0', border: 'none', borderRadius: 8, fontWeight: 700, fontSize: '1.1em', cursor: 'pointer' }}
                      type="button"
                      disabled={saving}
                    >Cancel</button>
                    {/* Show other errors (not phone) below buttons */}
                    {saveError && !saveError.toLowerCase().includes('phone') && (
                      <span style={{ color: 'red', marginLeft: 12 }}>{saveError}</span>
                    )}
                  </div>
                </>
              ) : (
                <>
                  <div className={styles.infoItem}>
                    <label>Name</label>
                    <p>{displayName}</p>
                  </div>
                  <div className={styles.infoItem}>
                    <label>Email Address</label>
                    <p>{user.email || "Not Provided"}</p>
                  </div>
                  <div className={styles.infoItem}>
                    <label>Mobile Number</label>
                    <p>{user.phone}</p>
                  </div>
                  <div className={styles.infoItem}>
                    <label>Role</label>
                    <p>{user.role || "Customer"}</p>
                  </div>
                  <div className={styles.infoItem}>
                    <label>Date of Birth</label>
                    <p>Not Provided</p>
                  </div>
                  <div className={styles.infoItem}>
                    <label>Address</label>
                    <p>{user.address || 'Not Provided'}</p>
                  </div>
                </>
              )}
            </div>
          </div>
        )}

        {activeSection === "favourites" && (
          <div className={styles.section}>
            <WishlistPage />
          </div>
        )}

        {activeSection === "orders" && (
          <div className={styles.section}>
            <h2>My Orders</h2>
            <ProfileOrdersTable />
          </div>
        )}

        {activeSection === "reviews" && (
          <div className={styles.section}>
            <h2>My Reviews</h2>
            <p className={styles.emptyState}>No reviews yet</p>
          </div>
        )}

        {activeSection === "address" && <ProfileAddressSection />}
      </div>
    </div>
  );
}
