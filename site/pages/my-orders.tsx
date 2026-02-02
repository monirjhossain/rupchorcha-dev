
import React, { useEffect, useState } from "react";
import { fetchMyOrders, Order } from "@/src/data/orders";

const MyOrders = () => {
  const [orders, setOrders] = useState<Order[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);

  useEffect(() => {
    fetchMyOrders()
      .then((data) => {
        setOrders(data);
        setLoading(false);
      })
      .catch((err) => {
        setError("Failed to load orders.");
        setLoading(false);
      });
  }, []);

  return (
    <div style={{ padding: "2rem" }}>
      <h1>My Orders</h1>
      {loading && <p>Loading your orders...</p>}
      {error && <p style={{ color: "red" }}>{error}</p>}
      {!loading && !error && orders.length === 0 && <p>No orders found.</p>}
      {!loading && !error && orders.length > 0 && (
        <table style={{ width: "100%", borderCollapse: "collapse", marginTop: "1rem" }}>
          <thead>
            <tr>
              <th style={{ borderBottom: "1px solid #ccc", textAlign: "left", padding: "8px" }}>Order #</th>
              <th style={{ borderBottom: "1px solid #ccc", textAlign: "left", padding: "8px" }}>Status</th>
              <th style={{ borderBottom: "1px solid #ccc", textAlign: "left", padding: "8px" }}>Total</th>
              <th style={{ borderBottom: "1px solid #ccc", textAlign: "left", padding: "8px" }}>Date</th>
            </tr>
          </thead>
          <tbody>
            {orders.map((order) => (
              <tr key={order.id}>
                <td style={{ borderBottom: "1px solid #eee", padding: "8px" }}>{order.order_number}</td>
                <td style={{ borderBottom: "1px solid #eee", padding: "8px" }}>{order.status}</td>
                <td style={{ borderBottom: "1px solid #eee", padding: "8px" }}>{order.total} ৳</td>
                <td style={{ borderBottom: "1px solid #eee", padding: "8px" }}>{new Date(order.created_at).toLocaleString()}</td>
              </tr>
            ))}
          </tbody>
        </table>
      )}
    </div>
  );
};

export default MyOrders;
