# Jos Metro BOSS - Real-time Bus Tracking API Endpoints

## Base URL
```
http://localhost:8000/api/v1
```

## Authentication
All contribution endpoints require authentication. Include the bearer token in the Authorization header:
```
Authorization: Bearer YOUR_TOKEN_HERE
```

---

## 1. Submit Bus Location Contribution

**Endpoint:** `POST /api/v1/contributions/location`

**Description:** Users submit their current GPS location when on a bus. The system validates if they're on the route and stores the data in Redis for real-time tracking.

**Headers:**
```
Content-Type: application/json
Authorization: Bearer YOUR_TOKEN_HERE
```

**Request Body:**
```json
{
  "route_id": 1,
  "latitude": 9.8965,
  "longitude": 8.8583,
  "speed": 45.5,
  "heading": 180,
  "accuracy": 10.5
}
```

**Response (201 Created):**
```json
{
  "success": true,
  "message": "Location contribution recorded",
  "data": {
    "contribution_id": 1,
    "is_on_route": true,
    "active_buses_count": 3
  }
}
```

**Response (422 Validation Error):**
```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "latitude": ["The latitude field is required."]
  }
}
```

**Notes:**
- Latitude must be between -90 and 90
- Longitude must be between -180 and 180
- Speed is optional (km/h)
- Heading is optional (0-360 degrees)
- Accuracy is optional (meters)
- User earns 5 points for valid location contribution
- Location is stored in Redis with 5-minute TTL
- Broadcasts `bus.location.updated` event via WebSocket

---

## 2. Submit Bus Crowding Report

**Endpoint:** `POST /api/v1/contributions/crowding`

**Description:** Users report the current crowding status of the bus they're on.

**Headers:**
```
Content-Type: application/json
Authorization: Bearer YOUR_TOKEN_HERE
```

**Request Body:**
```json
{
  "route_id": 1,
  "status": "standing",
  "crowding_level": 3,
  "latitude": 9.8965,
  "longitude": 8.8583
}
```

**Field Values:**
- `status`: `"full"`, `"standing"`, or `"seats_available"`
- `crowding_level`: Integer from 1 (empty) to 5 (packed)

**Response (201 Created):**
```json
{
  "success": true,
  "message": "Crowding report submitted",
  "data": {
    "contribution_id": 2
  }
}
```

**Notes:**
- User earns 3 points for crowding report
- Broadcasts `bus.crowding.updated` event via WebSocket
- Stored in contributions table

---

## 3. Get Latest Contributions

**Endpoint:** `GET /api/v1/contributions/latest`

**Description:** Retrieve recent contributions and active buses for a specific route.

**Headers:**
```
Authorization: Bearer YOUR_TOKEN_HERE
```

**Query Parameters:**
- `route_id` (required): The route ID (integer)
- `type` (optional): Filter by type - `location`, `crowding`, or `activity`
- `limit` (optional): Number of results (1-100, default: 20)

**Example Request:**
```
GET /api/v1/contributions/latest?route_id=1&type=location&limit=10
```

**Response (200 OK):**
```json
{
  "success": true,
  "data": {
    "contributions": [
      {
        "id": 1,
        "user_id": 5,
        "route_id": 1,
        "type": "location",
        "data": {
          "latitude": 9.8965,
          "longitude": 8.8583,
          "speed": 45.5,
          "heading": 180,
          "accuracy": 10.5
        },
        "created_at": "2025-11-12T10:30:00Z",
        "user": {
          "id": 5,
          "name": "John Doe",
          "avatar": "https://example.com/avatar.jpg"
        }
      }
    ],
    "active_buses": [
      {
        "user_id": 5,
        "route_id": 1,
        "latitude": 9.8965,
        "longitude": 8.8583,
        "speed": 45.5,
        "heading": 180,
        "timestamp": "2025-11-12T10:30:00Z"
      }
    ],
    "active_buses_count": 1
  }
}
```

**Notes:**
- Returns both database contributions and Redis active buses
- Active buses are those with location updates in the last 5 minutes
- Contributions include user information

---

## WebSocket Events

### Channel: `route.{routeId}`

Subscribe to real-time updates for a specific route.

**Example:** `route.1` for route ID 1

### Event: `bus.location.updated`

Triggered when a user submits a location update.

**Payload:**
```json
{
  "user_id": 5,
  "route_id": 1,
  "location": {
    "latitude": 9.8965,
    "longitude": 8.8583,
    "speed": 45.5,
    "heading": 180,
    "accuracy": 10.5
  },
  "timestamp": "2025-11-12T10:30:00Z"
}
```

### Event: `bus.crowding.updated`

Triggered when a user reports crowding status.

**Payload:**
```json
{
  "route_id": 1,
  "status": "standing",
  "crowding_level": 3,
  "location": {
    "latitude": 9.8965,
    "longitude": 8.8583
  },
  "timestamp": "2025-11-12T10:30:00Z"
}
```

---

## Setup Instructions

### 1. Start Redis Server
```bash
redis-server
```

### 2. Start Laravel Reverb WebSocket Server
```bash
php artisan reverb:start
```

### 3. Start Laravel Application
```bash
php artisan serve
```

### 4. Test WebSocket Connection
Open in browser:
```
http://localhost:8000/websocket-test.html
```

---

## Testing Flow

### Step 1: Get Authentication Token
```bash
POST /api/v1/auth/login
{
  "email": "test@example.com",
  "password": "password"
}
```

### Step 2: Submit Location
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

### Step 3: Check WebSocket Test Page
- Open `http://localhost:8000/websocket-test.html`
- Enter route ID: 1
- Click "Subscribe"
- You should see the location update event appear in real-time

### Step 4: Submit Crowding Report
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

### Step 5: Get Latest Data
```bash
GET /api/v1/contributions/latest?route_id=1
Authorization: Bearer YOUR_TOKEN
```

---

## Redis Data Structure

### Active Buses Storage
- **Key Pattern:** `bus_location:{route_id}:{user_id}`
- **TTL:** 300 seconds (5 minutes)
- **Data:** JSON with location, speed, heading, timestamp

### Active Buses Set
- **Key:** `active_buses`
- **Type:** Set
- **Contains:** All active bus keys

### Route-Specific Set
- **Key Pattern:** `route_buses:{route_id}`
- **Type:** Set
- **Contains:** Active bus keys for specific route

---

## Error Codes

- `200` - Success
- `201` - Created
- `401` - Unauthorized (missing or invalid token)
- `422` - Validation Error
- `404` - Not Found (invalid route_id)
- `500` - Server Error

---

## Notes

- All timestamps are in ISO 8601 format (UTC)
- Location data expires after 5 minutes in Redis
- Users earn points for contributions (5 for location, 3 for crowding)
- WebSocket broadcasts are public for route channels
- GPS coordinates are validated against route stops (50m threshold)
