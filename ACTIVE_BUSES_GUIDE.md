# Active Buses Display Guide

## How Active Buses Work

### The Flow
1. **User submits location** → Stored in Redis (5-min TTL)
2. **WebSocket broadcasts event** → You see "Location Update"
3. **Active Buses section** → Fetches from API to display count

---

## Why Active Buses Needs Auth Token

The "Active Buses" section calls this API endpoint:
```
GET /api/v1/contributions/latest?route_id=X
```

This endpoint **requires authentication** to prevent abuse and protect user data.

---

## How to See Active Buses

### Step 1: Get Your Auth Token

**Option A: From Postman**
1. Login via Postman:
   ```
   POST http://localhost:8000/api/v1/auth/login
   {
     "email": "test1@example.com",
     "password": "password"
   }
   ```
2. Copy the `token` from response

**Option B: From Browser Console**
```javascript
fetch('http://localhost:8000/api/v1/auth/login', {
  method: 'POST',
  headers: {'Content-Type': 'application/json'},
  body: JSON.stringify({
    email: 'test1@example.com',
    password: 'password'
  })
})
.then(r => r.json())
.then(d => console.log('Token:', d.data.token));
```

### Step 2: Add Token to WebSocket Client

I've updated the WebSocket test client! Now it has an **Auth Token** field.

1. Refresh: `http://localhost:8000/websocket-test.html`
2. You'll see a new field: **"Auth Token (Optional - for Active Buses)"**
3. Paste your token there
4. Subscribe to route
5. Submit location via Postman
6. **Active Buses will now update!** 🎉

---

## What You'll See

### Without Token
```
Active Buses
0 buses active (no auth token)
💡 Add auth token above to see active buses
```

### With Token (No Active Buses)
```
Active Buses
0 buses active
No active buses on this route (locations expire after 5 minutes)
```

### With Token (Active Buses Present)
```
Active Buses
1 buses active

User 1
Lat: 9.8965, Lng: 8.8583
Speed: 45.5 km/h
Updated: 8:52:37 AM
```

---

## Testing Flow

### Complete Test Scenario

**1. Get Token (Postman)**
```bash
POST http://localhost:8000/api/v1/auth/login
{
  "email": "test1@example.com",
  "password": "password"
}
```
Copy the token from response.

**2. Open WebSocket Client**
```
http://localhost:8000/websocket-test.html
```

**3. Add Token**
- Paste token in "Auth Token" field
- Enter Route ID: 2
- Click Subscribe

**4. Submit Location (Postman)**
```bash
POST http://localhost:8000/api/v1/contributions/location
Authorization: Bearer YOUR_TOKEN

{
  "route_id": 2,
  "latitude": 9.8965,
  "longitude": 8.8583,
  "speed": 45.5
}
```

**5. Watch Updates**
- ✅ "Location Update" event appears
- ✅ "Active Buses" count updates to 1
- ✅ Bus details show in list

---

## How Active Buses Update

### Automatic Updates
Every time a location event is received, the client automatically calls:
```javascript
updateActiveBuses()
```

This fetches the latest data from:
```
GET /api/v1/contributions/latest?route_id=X
```

### Manual Refresh
You can also manually check active buses via Postman:
```bash
GET http://localhost:8000/api/v1/contributions/latest?route_id=2
Authorization: Bearer YOUR_TOKEN
```

Response includes:
```json
{
  "success": true,
  "data": {
    "contributions": [...],
    "active_buses": [
      {
        "user_id": 1,
        "route_id": 2,
        "latitude": 9.8965,
        "longitude": 8.8583,
        "speed": 45.5,
        "timestamp": "2025-11-13T08:52:37Z"
      }
    ],
    "active_buses_count": 1
  }
}
```

---

## Why Active Buses Might Show 0

### Reason 1: No Token Provided
**Solution:** Add your auth token to the field

### Reason 2: Location Expired (5 minutes)
**Solution:** Submit a new location

### Reason 3: Location Not Validated
If location is too far from route stops:
- API returns: `"is_on_route": false`
- Location NOT stored in Redis
- Won't appear in active buses

**Solution:** Use coordinates near a stop (within 50m)

### Reason 4: Wrong Route ID
**Solution:** Make sure route ID matches your submission

---

## Redis TTL (Time To Live)

Active buses are stored in Redis with **5-minute expiry**:

```
Submit location → Stored in Redis (5 min TTL)
After 5 minutes → Automatically removed
Active buses count → Decreases
```

This prevents showing stale/outdated bus locations.

---

## Verify Active Buses in Redis

### Check Redis Directly
```bash
redis-cli

# Check all active buses
SMEMBERS active_buses

# Check route-specific buses
SMEMBERS route_buses:2

# Get specific bus location
GET bus_location:2:1

# Check TTL (time remaining)
TTL bus_location:2:1
```

---

## Troubleshooting

### Issue: Active Buses Always Shows 0

**Check 1: Token Added?**
- Look for "Auth Token" field in WebSocket client
- Paste your token there

**Check 2: Token Valid?**
Test in Postman:
```bash
GET http://localhost:8000/api/v1/auth/user
Authorization: Bearer YOUR_TOKEN
```
Should return your user info.

**Check 3: Location Validated?**
API response should show:
```json
{
  "is_on_route": true,
  "active_buses_count": 1
}
```

**Check 4: Redis Running?**
```bash
redis-cli ping
```
Should return: `PONG`

**Check 5: Check Browser Console**
Press F12, look for errors in console.

---

## Multiple Users Testing

### Simulate Multiple Buses

**User 1:**
```bash
POST /api/v1/contributions/location
Authorization: Bearer TOKEN_1
{
  "route_id": 2,
  "latitude": 9.8965,
  "longitude": 8.8583
}
```

**User 2:**
```bash
POST /api/v1/contributions/location
Authorization: Bearer TOKEN_2
{
  "route_id": 2,
  "latitude": 9.9145,
  "longitude": 8.8734
}
```

**Result:**
```
Active Buses
2 buses active

User 1
Lat: 9.8965, Lng: 8.8583
Speed: 0 km/h

User 2
Lat: 9.9145, Lng: 8.8734
Speed: 0 km/h
```

---

## Summary

✅ **Location Updates** → Broadcast via WebSocket (no auth needed)  
✅ **Active Buses** → Fetched via API (auth required)  

**To see Active Buses:**
1. Get auth token (login via Postman)
2. Add token to WebSocket client
3. Subscribe to route
4. Submit location
5. Watch active buses update!

**Updated WebSocket client now has:**
- Auth token input field
- Better error messages
- Timestamp display
- Helpful hints

Refresh your browser and try it! 🚀

