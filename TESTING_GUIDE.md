# Testing Guide - Real-time Bus Tracking

## Quick Setup

### 1. Start Services
```bash
redis-server                    # Terminal 1
php artisan reverb:start        # Terminal 2
php artisan serve               # Terminal 3
```

### 2. Get Auth Token
```bash
POST http://localhost:8000/api/v1/auth/login
{
  "email": "test1@example.com",
  "password": "password"
}
```
Copy the token from response.

---

## Test Scenarios

### Scenario 1: Submit Location & View Real-time Update

**1. Open WebSocket Client**
```
http://localhost:8000/websocket-test.html
```
- Subscribe to route 1

**2. Submit Location (Postman)**
```bash
POST /api/v1/contributions/location
Authorization: Bearer YOUR_TOKEN

{
  "route_id": 1,
  "latitude": 9.8965,
  "longitude": 8.8583,
  "speed": 45.5
}
```

**Expected:**
- ✅ WebSocket shows "Location Update" event
- ✅ Active Buses count updates

---

### Scenario 2: Submit Crowding Report

```bash
POST /api/v1/contributions/crowding
Authorization: Bearer YOUR_TOKEN

{
  "route_id": 1,
  "status": "standing",
  "crowding_level": 3,
  "latitude": 9.8965,
  "longitude": 8.8583
}
```

**Expected:**
- ✅ WebSocket shows "Crowding Update" event
- ✅ User earns 3 points

---

### Scenario 3: Get Latest Contributions

```bash
GET /api/v1/contributions/latest?route_id=1&type=location
Authorization: Bearer YOUR_TOKEN
```

**Expected:**
- ✅ Returns contributions array
- ✅ Returns active_buses array
- ✅ Shows active_buses_count

---



## Validation Tests

### Invalid Latitude
```bash
POST /api/v1/contributions/location
{
  "route_id": 1,
  "latitude": 95,  // Invalid!
  "longitude": 8.8583
}
```
**Expected:** 422 error - "latitude must be between -90 and 90"

### Invalid Status
```bash
POST /api/v1/contributions/crowding
{
  "route_id": 1,
  "status": "empty",  // Invalid!
  "crowding_level": 1
}
```
**Expected:** 422 error - "status must be: full, standing, or seats_available"

---

