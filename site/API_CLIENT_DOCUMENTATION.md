# API Client Documentation

## Overview

The application uses a production-ready API client with advanced error handling, automatic retry logic, 
and network status monitoring.

## Architecture

```
┌─────────────────────────────────────────┐
│     React Components / Pages             │
└──────────────┬──────────────────────────┘
               │
               ▼
┌─────────────────────────────────────────┐
│  Custom Hooks (useAuth, useNetworkStatus)│
└──────────────┬──────────────────────────┘
               │
               ▼
┌─────────────────────────────────────────┐
│    apiClient.ts (Core API Layer)        │
│  - Retry Logic (3x with backoff)        │
│  - Error Detection (8 types)            │
│  - Health Checks                        │
│  - Token Management                     │
└──────────────┬──────────────────────────┘
               │
               ▼
┌─────────────────────────────────────────┐
│   Request Interceptor                   │
│  - Add Auth Headers                     │
│  - Validate Content-Type                │
│  - Log Requests (dev mode)              │
└──────────────┬──────────────────────────┘
               │
               ▼
┌─────────────────────────────────────────┐
│     Browser Fetch API                   │
└──────────────┬──────────────────────────┘
               │
               ▼
┌─────────────────────────────────────────┐
│   Response Interceptor                  │
│  - Handle 401 (Token Expiry)           │
│  - Log Errors (dev mode)               │
│  - Validate Response Structure         │
└──────────────┬──────────────────────────┘
               │
               ▼
┌─────────────────────────────────────────┐
│   Backend Laravel API (127.0.0.1:8000)  │
└─────────────────────────────────────────┘
```

## Error Handling

### Error Types Detected

1. **NETWORK_ERROR** - No internet connection or server unreachable
2. **TIMEOUT** - Request took too long (> 10s)
3. **CORS_ERROR** - Cross-origin request blocked
4. **UNAUTHORIZED** - 401 status (token expired/invalid)
5. **FORBIDDEN** - 403 status (permission denied)
6. **NOT_FOUND** - 404 status (endpoint doesn't exist)
7. **VALIDATION_ERROR** - 422 status (invalid input)
8. **SERVER_ERROR** - 5xx status (backend error)

### Error Messages

Each error type has a user-friendly message:

```typescript
const userFriendlyMessages = {
  NETWORK_ERROR: 'Network error. Please check your internet connection.',
  TIMEOUT: 'Request took too long. Please try again.',
  CORS_ERROR: 'CORS error. Please contact support.',
  UNAUTHORIZED: 'Your session has expired. Please log in again.',
  FORBIDDEN: 'You do not have permission to perform this action.',
  NOT_FOUND: 'The requested resource was not found.',
  VALIDATION_ERROR: 'Please check your input and try again.',
  SERVER_ERROR: 'Server error. Please try again later.',
};
```

## Usage Examples

### Basic GET Request

```typescript
import { api } from '@/src/services/apiClient';

// Simple fetch
const products = await api.get('/products');

// With parameters
const filtered = await api.get('/products', {
  params: { category: 'electronics', limit: 10 },
});
```

### POST Request (Create)

```typescript
import { api } from '@/src/services/apiClient';

const newProduct = await api.post('/products', {
  name: 'New Product',
  price: 99.99,
  category: 'electronics',
});
```

### PUT Request (Update)

```typescript
import { api } from '@/src/services/apiClient';

const updated = await api.put(`/products/${id}`, {
  name: 'Updated Name',
  price: 129.99,
});
```

### DELETE Request

```typescript
import { api } from '@/src/services/apiClient';

await api.delete(`/products/${id}`);
```

### Handling Errors

```typescript
import { api, ApiError } from '@/src/services/apiClient';

try {
  const data = await api.post('/login', credentials);
} catch (error) {
  if (error instanceof ApiError) {
    console.log(error.type); // e.g., 'VALIDATION_ERROR'
    console.log(error.message); // User-friendly message
    console.log(error.details); // Validation errors, if any
  }
}
```

### Checking API Health

```typescript
import { checkApiHealth } from '@/src/services/apiClient';

const isAvailable = await checkApiHealth();
if (!isAvailable) {
  console.warn('Backend API is currently unavailable');
}
```

## Network Status Hook

Monitor network connectivity in real-time:

```typescript
import { useNetworkStatus } from '@/src/hooks/useNetworkStatus';

function MyComponent() {
  const { isOnline, isApiAvailable, lastChecked } = useNetworkStatus();

  if (!isOnline) {
    return <div>No internet connection</div>;
  }

  if (!isApiAvailable) {
    return <div>Server is currently unavailable. Retrying...</div>;
  }

  return <div>All systems operational</div>;
}
```

## Retry Logic

The API client automatically retries failed requests up to 3 times with exponential backoff:

```
1st attempt: immediate
2nd attempt: after 2 seconds (2 * 1000ms)
3rd attempt: after 4 seconds (2 * 2000ms)
4th attempt: fails (exceeds max retries)
```

### Configurable Settings

```typescript
// In apiClient.ts
const apiConfig = {
  retries: 3, // Max retries
  retryDelay: 1000, // Base delay in ms
  timeout: parseInt(process.env.NEXT_PUBLIC_API_TIMEOUT || '10000'), // Timeout in ms
};
```

## Best Practices

### ✅ DO:

1. Always import `api` from `apiClient.ts` for consistency
2. Use `try-catch` blocks to handle API errors
3. Check `error instanceof ApiError` before accessing error properties
4. Use `NetworkErrorBoundary` at app layout level
5. Call `api.checkApiHealth()` before critical operations
6. Log errors for debugging in development mode
7. Use `useNetworkStatus` to show user-friendly connectivity messages

### ❌ DON'T:

1. Use direct `fetch()` calls - use `api.*()` instead
2. Hardcode API URLs - use environment variables
3. Store sensitive data in localStorage - use httpOnly cookies
4. Ignore network errors - always handle them gracefully
5. Show technical error messages to users - use user-friendly ones

## Environment Configuration

Create `.env.local` in the frontend root:

```env
NEXT_PUBLIC_API_URL=http://127.0.0.1:8000/api
NEXT_PUBLIC_API_TIMEOUT=10000
NEXT_PUBLIC_BACKEND_URL=http://127.0.0.1:8000
NEXT_PUBLIC_ENV=development
NEXT_PUBLIC_GOOGLE_CLIENT_ID=your_client_id_here
```

## Debugging

Enable request/response logging by setting:

```env
NEXT_PUBLIC_ENV=development
```

This will log all API requests and responses to the browser console with:
- Request method and endpoint
- Request body (if any)
- Response status and data
- Error details

## Health Check Endpoints

### Check API Availability

```
GET /api/health
Response:
{
  "status": "ok",
  "message": "API is healthy",
  "timestamp": "2024-01-15T10:30:00Z",
  "version": "1.0.0"
}
```

### Get Detailed Status

```
GET /api/health/status
Response:
{
  "status": "ok",
  "timestamp": "2024-01-15T10:30:00Z",
  "database": "connected",
  "disk_space": "500000 MB",
  "uptime": "..."
}
```

## Troubleshooting

### NetworkError when attempting to fetch resource

**Cause:** Backend server not running or unreachable

**Solution:**
1. Check backend is running: `php artisan serve`
2. Check frontend `.env.local` has correct API URL
3. Verify CORS configuration includes your frontend URL
4. Check browser console for CORS errors

### 401 Unauthorized

**Cause:** Auth token expired or invalid

**Solution:**
1. Token is automatically cleared
2. User redirected to login page
3. Session refreshed on next login

### 422 Validation Error

**Cause:** Invalid input data

**Solution:**
1. Check error message in `error.details`
2. Validate form input against backend requirements
3. Show validation errors to user

## Performance Tips

1. **Caching:** Implement response caching for frequently accessed data
2. **Debouncing:** Debounce rapid API calls (e.g., search)
3. **Pagination:** Use limit/offset for large datasets
4. **Lazy Loading:** Load data only when needed
5. **Compression:** Enable gzip compression on backend

## Security Considerations

1. **Token Storage:** Currently in localStorage (consider httpOnly cookies)
2. **HTTPS:** Always use HTTPS in production (not just localhost)
3. **CORS:** Only allow whitelisted origins
4. **Input Validation:** Validate all user input
5. **Output Encoding:** Encode API responses to prevent XSS
6. **Rate Limiting:** Implement rate limiting on backend

## Related Files

- Core API client: [src/services/apiClient.ts](src/services/apiClient.ts)
- Request/response interceptors: [src/services/apiInterceptor.ts](src/services/apiInterceptor.ts)
- Network status hook: [src/hooks/useNetworkStatus.ts](src/hooks/useNetworkStatus.ts)
- Auth hook: [src/hooks/useAuth.ts](src/hooks/useAuth.ts)
- Network error boundary: [src/components/NetworkErrorBoundary.tsx](src/components/NetworkErrorBoundary.tsx)
- Backend routes: [backend/routes/api.php](backend/routes/api.php)
- CORS config: [backend/config/cors.php](backend/config/cors.php)
