# Complete Testing Guide - Real-time Bus Tracking

## Prerequisites ✅

1. **Database seeded with routes:**
   ```bash
   php artisan db:seed --class=RouteSeeder
   ```
   Result: 8 routes, 43 stops

2. **Services running:**
   ```bash
   # Terminal 1
   redis-server
   
   # Terminal 2
   php artisab:start
   
   # Terminal 3
   php artisan serve
   ```

3. **Auth token obtained:**
   ```bash
   POST http://localhost:8000/api/v1/auth/login
   {
     "email": "test1@example.com",
     "password": "password"
   }
   ```
   Copy the token from response.

---

## Test Scenario 1: Single User on Route 1

### Step 1: Open WebSocket Test Client
```
http://localhost:8000/websocket-test.html
```
- Enter Route ID: **1**
- Click **Subscribe**
- Status should show: **Connected**

### Step 2: Submit Location at Terminus (Start Point)
```bash
POST http://localhost:8000/api/v1/contributions/location
Authorization: Bearer YOUR_TOKEN
Content-Type: application/json

{
  "route_id": 1,
  "latitude": 9.8965,
  "longitude": 8.8583,
  "speed": 0,
  "heading": 90,
  "accuracy": 5
}
```

**Expected Results:**
- ✅ Response: `"is_on_route": true`
- ✅ Response: `"active_buses_count": 1`
- ✅ WebSocket client shows: "Location Update" event
- ✅ User earns 5 points

### Step 3: Submit Location at Jos Main Market (Mid Route)
```bash
POST http://localhost:8000/api/v1/contributions/location
Authorization: Bearer YOUR_TOKEN

{
  "route_id": 1,
  "latitude": 9.9145,
  "longitude": 8.8734,
  "speed": 45.5,
  "heading": 180
}
```

**Expected Results:**
- ✅ WebSocket shows new location update
- ✅ Speed shows 45.5 km/h

### Step 4: Submit Location at Bukuru (End Point)
```bash
POST http://localhost:8000/api/v1/contributions/location
Authorization: Bearer YOUR_TOKEN

{
  "route_id": 1,
  "latitude": 9.7965,
  "longitude": 8.8683,
  "speed": 20,
  "heading": 270
}
```

### Step 5: Get Latest Contributions
```bash
GET http://localhost:8000/api/v1/contributions/latest?route_id=1
Authorization: Bearer YOUR_TOKEN
```

**Expected Results:**
- ✅ Returns your 3 location contributions
- ✅ Shows 1 active bus (you)
- ✅ Active bus has latest location (Bukuru)

---

## Test Scenario 2: Crowding Reports

### Step 1: Report "Seats Available"
```bash
POST http://localhost:8000/api/v1/contributions/crowding
Authorization: Bearer YOUR_TOKEN

{
  "route_id": 1,
  "status": "seats_available",
  "crowding_level": 2,
  "latitude": 9.8965,
  "longitude": 8.8583
}
```

**Expected Results:**
- ✅ Response: `"contribution_id": X`
- ✅ WebSocket shows: "Crowding Update" event
- ✅ User earns 3 points

### Step 2: Report "Standing Room Only"
```bash
POST http://localhost:8000/api/v1/contributions/crowding
Authorization: Bearer YOUR_TOKEN

{
  "route_id": 1,
  "status": "standing",
  "crowding_level": 4,
  "latitude": 9.9145,
  "longitude": 8.8734
}
```

### Step 3: Report "Full Bus"
```bash
POST http://localhost:8000/api/v1/contributions/crowding
Authorization: Bearer YOUR_TOKEN

{
  "route_id": 1,
  "status": "full",
  "crowding_level": 5,
  "latitude": 9.7965,
  "longitude": 8.8683
}
```

### Step 4: Get Crowding Reports Only
```bash
GET http://localhost:8000/api/v1/contributions/latest?route_id=1&type=crowding
Authorization: Bearer YOUR_TOKEN
```

---

## Test Scenario 3: Multiple Routes

### Route 4: Bukuru to Rayfield (Shorter Route)

**Open Second Browser Tab:**
```
http://localhost:8000/websocket-test.html
```
- Enter Route ID: **4**
- Click **Subscribe**

**Submit Location on Route 4:**
```bash
POST http://localhost:8000/api/v1/contributions/location
Authorization: Bearer YOUR_TOKEN

{
  "route_id": 4,
  "latitude": 9.8876,
  "longitude": 8.8945,
  "speed": 35,
  "heading": 45
}
```

**Expected Results:**
- ✅ Route 4 WebSocket tab shows update
- ✅ Route 1 WebSocket tab shows nothing (isolated channels)

---

## Test Scenario 4: Invalid Location (Off Route)

### Submit Location Far from Route
```bash
POST http://localhost:8000/api/v1/contributions/location
Authorization: Bearer YOUR_TOKEN

{
  "route_id": 1,
  "latitude": 10.5000,
  "longitude": 9.5000,
  "speed": 60
}
```

**Expected Results:**
- ✅ Response: `"is_on_route": false`
- ✅ Location NOT stored in Redis
- ✅ No WebSocket broadcast
- ✅ Still saved in contributions table for analysis

---

## Test Scenario 5: Redis TTL (5-Minute Expiry)

### Step 1: Submit Location
```bash
POST http://localhost:8000/api/v1/contributions/location
Authorization: Bearer YOUR_TOKEN

{
  "route_id": 1,
  "latitude": 9.8965,
  "longitude": 8.8583,
  "speed": 40
}
```

### Step 2: Check Active Buses Immediately
```bash
GET http://localhost:8000/api/v1/contributions/latest?route_id=1
Authorization: Bearer YOUR_TOKEN
```
**Result:** `"active_buses_count": 1`

### Step 3: Wait 6 Minutes

### Step 4: Check Active Buses Again
```bash
GET http://localhost:8000/api/v1/contributions/latest?route_id=1
Authorization: Bearer YOUR_TOKEN
```
**Result:** `"active_buses_count": 0` (expired from Redis)

---

## Test Scenario 6: Validation Errors

### Invalid Latitude
```bash
POST http://localhost:8000/api/v1/contributions/location
Authorization: Bearer YOUR_TOKEN

{
  "route_id": 1,
  "latitude": 95.0000,
  "longitude": 8.8583
}
```
**Expected:** 422 error - "latitude must be between -90 and 90"

### Invalid Status
```bash
POST http://localhost:8000/api/v1/contributions/crowding
Authorization: Bearer YOUR_TOKEN

{
  "route_id": 1,
  "status": "empty",
  "crowding_level": 1,
  "latitude": 9.8965,
  "longitude": 8.8583
}
```
**Expected:** 422 error - "status must be: full, standing, or seats_available"

### Missing Route ID
```bash
POST http://localhost:8000/api/v1/contributions/location
Authorization: Bearer YOUR_TOKEN

{
  "latitude": 9.8965,
  "longitude": 8.8583
}
```
**Expected:** 422 error - "route_id field is required"

---

## Test Scenario 7: Points & Gamification

### Check Initial Points
```bash
GET http://localhost:8000/api/v1/auth/user
Authorization: Bearer YOUR_TOKEN
```
Note the `points` value.

### Submit 3 Locations (5 points each)
Submit location 3 times using different coordinates.

### Submit 2 Crowding Reports (3 points each)
Submit crowding 2 times.

### Check Points Again
```bash
GET http://localhost:8000/api/v1/auth/user
Authorization: Bearer YOUR_TOKEN
```
**Expected:** Points increased by 21 (15 + 6)

---

## Test Scenario 8: All Routes Overview

### Get All Routes
```bash
GET http://localhost:8000/api/v1/routes
```

**Expected Response:**
```json
{
  "data": [
    {
      "id": 1,
      "name": "Terminus to Bukuru",
      "distance_km": 8.5,
      "stops": [...]
    },
    // ... 7 more routes
  ]
}
```

### Get Specific Route with Stops
```bash
GET http://localhost:8000/api/v1/routes/1
```

---

## Verify Redis Data

### Check Active Buses
```bash
redis-cli
SMEMBERS active_buses
```

### Check Route-Specific Buses
```bash
SMEMBERS route_buses:1
```

### Get Bus Location
```bash
GET bus_location:1:1
```

### Check TTL
```bash
TTL bus_location:1:1
```
Should show ~300 seconds (5 minutes)

---

## Performance Testing

### Rapid Location Updates (Simulate Moving Bus)
Submit location every 5 seconds with incrementing coordinates:

```bash
# Location 1
POST /api/v1/contributions/location
{"route_id": 1, "latitude": 9.8965, "longitude": 8.8583}

# Wait 5 seconds

# Location 2
POST /api/v1/contributions/location
{"route_id": 1, "latitude": 9.9012, "longitude": 8.8621}

# Wait 5 seconds

# Location 3
POST /api/v1/contributions/location
{"route_id": 1, "latitude": 9.9145, "longitude": 8.8734}
```

**Watch WebSocket client update in real-time!**

---

## Troubleshooting

### No WebSocket Events
1. Check Reverb is running: `php artisan reverb:start`
2. Check browser console for errors
3. Verify route ID matches subscription

### Location Not Validated as On-Route
1. Check route has stops: `GET /api/v1/routes/{id}`
2. Verify coordinates are within 50m of a stop
3. Use exact stop coordinates for testing

### Active Buses Count is 0
1. Check Redis is running: `redis-cli ping`
2. Verify location was validated as on-route
3. Check TTL hasn't expired (5 minutes)

### Points Not Awarded
1. Start queue worker: `php artisan queue:work`
2. Check logs: `storage/logs/laravel.log`

---

## Success Checklist

- [ ] 8 routes seeded successfully
- [ ] Redis server running
- [ ] Reverb WebSocket server running
- [ ] Laravel app running
- [ ] Auth token obtained
- [ ] WebSocket test client connected
- [ ] Location submission successful
- [ ] Real-time event received in browser
- [ ] Crowding report submitted
- [ ] Active buses visible in API response
- [ ] Points awarded to user
- [ ] Redis data verified

---

## Next Steps

1. **Mobile App Integration:** Use these endpoints in your mobile app
2. **Map Visualization:** Add Google Maps with real-time markers
3. **Push Notifications:** Alert users when buses approach
4. **Analytics Dashboard:** Track route performance
5. **Driver App:** Separate interface for bus drivers

---

## Support Files

- **API Documentation:** `POSTMAN_ENDPOINTS.md`
- **Setup Guide:** `SETUP_GUIDE.md`
- **Seeding Guide:** `SEEDING_GUIDE.md`
- **Quick Reference:** `QUICK_REFERENCE.md`
- **Postman Collection:** `postman_collection.json`

