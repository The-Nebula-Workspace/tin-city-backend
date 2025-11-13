# Real-time Bus Tracking Setup Guide

## Quick Start

### 1. Install Dependencies (Already Done)
```bash
composer require predis/predis
```

### 2. Update Environment Variables

Your `.env` file has been updated with:
```env
BROADCAST_CONNECTION=reverb
QUEUE_CONNECTION=redis
CACHE_STORE=redis

REVERB_APP_ID=tin-city-metro
REVERB_APP_KEY=local-key
REVERB_APP_SECRET=local-secret
REVERB_HOST=localhost
REVERB_PORT=8080
REVERB_SCHEME=http
```

### 3. Start Required Services

**Terminal 1 - Redis Server:**
```bash
redis-server
```

**Terminal 2 - Laravel Reverb (WebSocket Server):**
```bash

```

**Terminal 3 - Laravel Application:**
```bash
php artisan serve
```

**Terminal 4 - Queue Worker (Optional but recommended):**
```bash
php artisan queue:work
```

### 4. Test the Implementation

#### Option A: Use WebSocket Test Client
1. Open browser: `http://localhost:8000/websocket-test.html`
2. Enter route ID (e.g., 1)
3. Click "Subscribe"
4. Keep this window open

#### Option B: Use Postman

See `POSTMAN_ENDPOINTS.md` for detailed API documentation.

---

## What Was Implemented

### ✅ New Files Created

1. **ContributionController** (`app/Http/Controllers/ContributionController.php`)
   - Handles location and crowding submissions
   - Validates user is on route
   - Awards points for contributions

2. **BusTrackingService** (`app/Services/BusTrackingService.php`)
   - Redis-based storage for active buses
   - Location validation using Haversine formula
   - 5-minute TTL for bus locations

3. **WebSocket Test Client** (`public/websocket-test.html`)
   - Real-time event monitoring
   - Active buses display
   - Route subscription interface

4. **Documentation**
   - `POSTMAN_ENDPOINTS.md` - Complete API reference
   - `SETUP_GUIDE.md` - This file

### ✅ Updated Files

1. **Events** (Updated to broadcast via WebSocket)
   - `BusLocationUpdated` - Broadcasts on `route.{id}` channel
   - `BusCrowdingUpdated` - Broadcasts crowding reports

2. **Routes** (`routes/api.php`)
   - Added contribution endpoints
   - Removed unused import

3. **Channels** (`routes/channels.php`)
   - Added public route channel for real-time tracking

4. **Environment** (`.env`)
   - Enabled Redis for cache and queue
   - Configured Reverb WebSocket server

---

## API Endpoints

### 1. Submit Location
```
POST /api/v1/contributions/location
```
- Requires authentication
- Validates GPS coordinates
- Stores in Redis (5-min TTL)
- Broadcasts via WebSocket
- Awards 5 points

### 2. Submit Crowding Report
```
POST /api/v1/contributions/crowding
```
- Requires authentication
- Reports bus status (full/standing/seats_available)
- Crowding level (1-5)
- Awards 3 points

### 3. Get Latest Contributions
```
GET /api/v1/contributions/latest?route_id=1
```
- Returns database contributions
- Returns active buses from Redis
- Supports filtering by type

---

## Testing Workflow

### Step 1: Create Test User & Route
```bash
php artisan tinker
```
```php
// Create test user
$user = User::create([
    'name' => 'Test User',
    'email' => 'test@example.com',
    'password' => Hash::make('password'),
    'phone' => '+2348012345678',
    'role' => 'user'
]);

// Create test route with stops
$route = Route::create([
    'name' => 'Terminus to Bukuru',
    'start_point' => 'Terminus',
    'end_point' => 'Bukuru',
    'encoded_polyline' => 'test_polyline',
    'distance_km' => 8.5
]);

// Add stops
$route->stops()->create([
    'name' => 'Terminus',
    'latitude' => 9.8965,
    'longitude' => 8.8583,
    'order_index' => 1
]);

$route->stops()->create([
    'name' => 'Bukuru',
    'latitude' => 9.7965,
    'longitude' => 8.8683,
    'order_index' => 2
]);
```

### Step 2: Get Auth Token
```bash
POST http://localhost:8000/api/v1/auth/login
Content-Type: application/json

{
  "email": "test@example.com",
  "password": "password"
}
```

Copy the token from response.

### Step 3: Open WebSocket Test Client
```
http://localhost:8000/websocket-test.html
```
- Enter route ID: 1
- Click Subscribe
- Watch for connection status

### Step 4: Submit Location (Postman)
```bash
POST http://localhost:8000/api/v1/contributions/location
Authorization: Bearer YOUR_TOKEN
Content-Type: application/json

{
  "route_id": 1,
  "latitude": 9.8965,
  "longitude": 8.8583,
  "speed": 45.5,
  "heading": 180,
  "accuracy": 10.5
}
```

### Step 5: Check WebSocket Client
You should see the location update appear in real-time!

### Step 6: Submit Crowding Report
```bash
POST http://localhost:8000/api/v1/contributions/crowding
Authorization: Bearer YOUR_TOKEN
Content-Type: application/json

{
  "route_id": 1,
  "status": "standing",
  "crowding_level": 3,
  "latitude": 9.8965,
  "longitude": 8.8583
}
```

### Step 7: Get Latest Data
```bash
GET http://localhost:8000/api/v1/contributions/latest?route_id=1
Authorization: Bearer YOUR_TOKEN
```

---

## Redis Data Verification

Check Redis data directly:
```bash
redis-cli

# View all keys
KEYS *

# Check active buses
SMEMBERS active_buses

# Check route-specific buses
SMEMBERS route_buses:1

# Get specific bus location
GET bus_location:1:1

# Check TTL
TTL bus_location:1:1
```

---

## Troubleshooting

### WebSocket Not Connecting
1. Ensure Reverb is running: `php artisan reverb:start`
2. Check port 8080 is not in use
3. Verify `.env` has correct Reverb settings

### Redis Connection Failed
1. Start Redis: `redis-server`
2. Check Redis is running: `redis-cli ping` (should return PONG)
3. Verify `.env` has `REDIS_HOST=127.0.0.1`

### Location Not Broadcasting
1. Check `BROADCAST_CONNECTION=reverb` in `.env`
2. Ensure user is authenticated
3. Verify route exists in database
4. Check Laravel logs: `storage/logs/laravel.log`

### Points Not Awarded
1. Ensure `QUEUE_CONNECTION=redis` in `.env`
2. Start queue worker: `php artisan queue:work`
3. Check `CheckUserBadges` job is running

---

## Architecture Overview

```
User App → POST /contributions/location
    ↓
ContributionController
    ↓
BusTrackingService → Redis (5-min TTL)
    ↓
BusLocationUpdated Event
    ↓
Reverb WebSocket Server
    ↓
Subscribed Clients (route.{id} channel)
```

---

## Next Steps

1. **Mobile Integration**: Use these endpoints in your mobile app
2. **Map Visualization**: Add Google Maps to show real-time bus markers
3. **ETA Calculation**: Use active bus locations to calculate arrival times
4. **Notifications**: Alert users when buses approach their stop
5. **Analytics**: Track route performance and crowding patterns

---

## Support

For issues or questions, check:
- Laravel logs: `storage/logs/laravel.log`
- Reverb logs: Console output from `php artisan reverb:start`
- Redis logs: Console output from `redis-server`
