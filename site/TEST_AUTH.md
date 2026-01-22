# Auth Testing Guide

## ব্রাউজারে Test করুন

### Step 1: Browser Console এ Run করুন (F12 → Console)

```javascript
// Test 1: Check API connection
fetch('http://localhost:8000/api/health')
  .then(r => r.json())
  .then(d => console.log('✅ Backend Health:', d))
  .catch(e => console.error('❌ Backend Error:', e));

// Test 2: Test Register Endpoint
const testData = {
  name: "Test User " + Math.random().toString(36).substring(7),
  email: "test" + Math.random().toString(36).substring(7) + "@test.com",
  phone: "01912345678",
  password: "password123",
  password_confirmation: "password123"
};

fetch('http://localhost:8000/api/register', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json'
  },
  body: JSON.stringify(testData)
})
  .then(r => {
    console.log('Status:', r.status);
    return r.json();
  })
  .then(d => {
    console.log('✅ Register Response:', d);
    if (d.token) {
      console.log('✅ Token received:', d.token.substring(0, 20) + '...');
      localStorage.setItem('token', d.token);
      console.log('✅ Token saved to localStorage');
      
      // Test profile endpoint
      return fetch('http://localhost:8000/api/profile', {
        headers: {
          'Authorization': 'Bearer ' + d.token,
          'Accept': 'application/json'
        }
      });
    }
  })
  .then(r => r.json())
  .then(d => console.log('✅ Profile Response:', d))
  .catch(e => console.error('❌ Error:', e));
```

### Step 2: Network Tab Check করুন

1. F12 → Network tab open করুন
2. "Sign Up" button click করুন modal থেকে
3. যে errors দেখাবে screenshot নিন:
   - Request URL
   - Status Code
   - Response Headers (особенно CORS headers)
   - Response Body

### Step 3: Console Errors

Console tab এ কোন red errors আছে কিনা দেখুন এবং copy করুন।

## Expected Results

যদি সব ঠিক থাকে:
- ✅ Health check: `{"status":"ok"}`
- ✅ Register status: 200
- ✅ Token present in response
- ✅ Profile returns user data

## Common Issues

### Issue 1: CORS Error
```
Access-Control-Allow-Origin header missing
```
**Fix**: Backend CORS config ইতিমধ্যে fixed করা হয়েছে

### Issue 2: 401 Unauthorized
```
Unauthorized
```
**Fix**: Token localStorage এ save হচ্ছে না

### Issue 3: 422 Validation Error
```
{"message": "The email has already been taken"}
```
**Fix**: অন্য email ব্যবহার করুন

### Issue 4: Network Timeout
```
Failed to fetch
```
**Fix**: Backend server চালু আছে কিনা check করুন
