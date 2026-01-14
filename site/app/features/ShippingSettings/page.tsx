
"use client";
import React, { useState, useEffect } from "react";
import axios from "axios";
import "../ShippingSettings/ShippingSettings.css";

const ShippingSettingsPage = () => {
  const [methods, setMethods] = useState<any[]>([]);
  const [freeShippingThreshold, setFreeShippingThreshold] = useState(3000);
  const [loading, setLoading] = useState(true);
  const [editingMethod, setEditingMethod] = useState<any>(null);

  useEffect(() => {
    fetchSettings();
  }, []);

  const fetchSettings = async () => {
    try {
      setLoading(true);
      const response = await axios.get("http://127.0.0.1:8000/api/admin/shipping-settings");
      setMethods(response.data.data.shipping_methods);
      setFreeShippingThreshold(response.data.data.free_shipping_threshold);
    } catch (error) {
      alert("Failed to load settings");
    } finally {
      setLoading(false);
    }
  };

  const handleToggleActive = async (id: number) => {
    try {
      await axios.patch(`http://127.0.0.1:8000/api/admin/shipping-settings/methods/${id}/toggle`);
      fetchSettings();
    } catch (error) {
      alert("Failed to update status");
    }
  };

  const handleUpdateMethod = async (id: number, data: any) => {
    try {
      await axios.put(`http://127.0.0.1:8000/api/admin/shipping-settings/methods/${id}`, data);
      setEditingMethod(null);
      fetchSettings();
      alert("Shipping method updated successfully!");
    } catch (error) {
      alert("Failed to update method");
    }
  };

  const handleUpdateThreshold = async () => {
    try {
      await axios.put("http://127.0.0.1:8000/api/admin/shipping-settings/general", {
        free_shipping_threshold: freeShippingThreshold,
      });
      alert("Free shipping threshold updated!");
    } catch (error) {
      alert("Failed to update threshold");
    }
  };

  if (loading) {
    return <div className="shipping-settings-loading">Loading...</div>;
  }

  return (
    <div className="shipping-settings-page">
      <div className="container">
        <h1>Shipping Settings</h1>
        {/* Free Shipping Threshold */}
        <div className="settings-card">
          <h2>General Settings</h2>
          <div className="form-group">
            <label>Free Shipping Threshold (৳)</label>
            <div className="input-with-button">
              <input
                type="number"
                value={freeShippingThreshold}
                onChange={(e) => setFreeShippingThreshold(Number(e.target.value))}
                min="0"
                step="100"
              />
              <button onClick={handleUpdateThreshold} className="save-btn">Save</button>
            </div>
            <p className="help-text">Orders above this amount will get free shipping</p>
          </div>
        </div>
        {/* Shipping Methods */}
        <div className="settings-card">
          <h2>Shipping Methods</h2>
          <div className="methods-list">
            {methods.map((method) => (
              <div key={method.id} className="method-item">
                {editingMethod?.id === method.id ? (
                  <EditMethodForm
                    method={method}
                    onSave={(data: any) => handleUpdateMethod(method.id, data)}
                    onCancel={() => setEditingMethod(null)}
                  />
                ) : (
                  <>
                    <div className="method-header">
                      <div className="method-title">
                        <h3>{method.method_name}</h3>
                        <span className={`status-badge ${method.active ? "active" : "inactive"}`}>
                          {method.active ? "Active" : "Inactive"}
                        </span>
                      </div>
                      <div className="method-actions">
                        <button onClick={() => setEditingMethod(method)} className="edit-btn">Edit</button>
                        <button onClick={() => handleToggleActive(method.id)} className={`toggle-btn ${method.active ? "disable" : "enable"}`}>
                          {method.active ? "Disable" : "Enable"}
                        </button>
                      </div>
                    </div>
                    <div className="method-details">
                      <div className="detail-item">
                        <span className="label">Code:</span>
                        <span className="value">{method.method_code}</span>
                      </div>
                      <div className="detail-item">
                        <span className="label">Description:</span>
                        <span className="value">{method.description}</span>
                      </div>
                      <div className="detail-item">
                        <span className="label">Price:</span>
                        <span className="value">৳ {method.price}</span>
                      </div>
                      <div className="detail-item">
                        <span className="label">Delivery Time:</span>
                        <span className="value">{method.delivery_time_min}-{method.delivery_time_max} days</span>
                      </div>
                    </div>
                  </>
                )}
              </div>
            ))}
          </div>
        </div>
      </div>
    </div>
  );
};

const EditMethodForm = ({ method, onSave, onCancel }: { method: any; onSave: (data: any) => void; onCancel: () => void }) => {
  const [formData, setFormData] = useState({
    method_name: method.method_name,
    description: method.description,
    price: method.price,
    delivery_time_min: method.delivery_time_min,
    delivery_time_max: method.delivery_time_max,
    active: method.active,
    sort_order: method.sort_order,
  });

  const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    const value = e.target.type === "checkbox" ? e.target.checked : e.target.value;
    setFormData({ ...formData, [e.target.name]: value });
  };

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    onSave(formData);
  };

  return (
    <form onSubmit={handleSubmit} className="edit-method-form">
      <div className="form-row">
        <div className="form-group">
          <label>Method Name *</label>
          <input type="text" name="method_name" value={formData.method_name} onChange={handleChange} required />
        </div>
        <div className="form-group">
          <label>Price (৳) *</label>
          <input type="number" name="price" value={formData.price} onChange={handleChange} min="0" step="0.01" required />
        </div>
      </div>
      <div className="form-group">
        <label>Description</label>
        <input type="text" name="description" value={formData.description} onChange={handleChange} />
      </div>
      <div className="form-row">
        <div className="form-group">
          <label>Min Delivery Days *</label>
          <input type="number" name="delivery_time_min" value={formData.delivery_time_min} onChange={handleChange} min="1" required />
        </div>
        <div className="form-group">
          <label>Max Delivery Days *</label>
          <input type="number" name="delivery_time_max" value={formData.delivery_time_max} onChange={handleChange} min="1" required />
        </div>
      </div>
      <div className="form-actions">
        <button type="button" onClick={onCancel} className="cancel-btn">Cancel</button>
        <button type="submit" className="save-btn">Save Changes</button>
      </div>
    </form>
  );
};

export default ShippingSettingsPage;
