# Quick Reference - Real-time Bus Tracking API

## 🌱 Seed Database (First Time Setup)
```bash
php artisan db:seed --class=RouteSeeder  # 8 routes, 43 stops
php artisan db:seed --class=BusSeeder    # 37 buses
# Or seed everything:
php artisan migrate:fresh --seed
```

## 🚀 Start Services
```bash
redis-server                    # Terminal 1
php artisan reverb:start        # Terminal 2
php artisan serve               # Terminal 3
php artisan queue:work          # Terminal 4 (optional)
```

## 🔑 Get Auth Token
```bash
POST http://localhost:8000/api/v1/auth/login
{
  "email": "test@example.com",
  "password": "password"
}
```

## 📍 Submit Location
```bash
POST http://localhost:8000/api/v1/contributions/location
Authorization: Bearer YOUR_TOKEN

{
  "route_id": 1,
  "latitude": 9.8965,
  "longitude": 8.8583,
  "speed": 45.5,
  "heading": 180,
  "accuracy": 10.5
}
```
**Rewards:** 5 points | **Broadcasts:** `bus.location.updated`

## 👥 Submit Crowding
```bash
POST http://localhost:8000/api/v1/contributions/crowding
Authorization: Bearer YOUR_TOKEN

{
  "route_id": 1,
  "status": "standing",
  "crowding_level": 3,
  "latitude": 9.8965,
  "longitude": 8.8583
}
```
**Status:** `full` | `standing` | `seats_available`  
**Level:** 1 (empty) to 5 (packed)  
**Rewards:** 3 points | **Broadcasts:** `bus.crowding.updated`

## 📊 Get Latest Data
```bash
GET http://localhost:8000/api/v1/contributions/latest?route_id=1&type=location&limit=20
Authorization: Bearer YOUR_TOKEN
```

## 🚌 Get Buses
```bash
GET http://localhost:8000/api/v1/buses              # All buses (37)
GET http://localhost:8000/api/v1/buses?route_id=1   # Route 1 buses (8)
GET http://localhost:8000/api/v1/buses/route/1      # Route 1 buses with details
GET http://localhost:8000/api/v1/buses/1            # Specific bus (TB-001)
```

## 🌐 WebSocket Test
```
http://localhost:8000/websocket-test.html
```

## 🔍 Check Redis
```bash
redis-cli
KEYS *
SMEMBERS active_buses
GET bus_location:1:1
TTL bus_location:1:1
```

## 📦 Import Postman Collection
File: `postman_collection.json`

## 📚 Full Documentation
- **API Reference:** `POSTMAN_ENDPOINTS.md`
- **Setup Guide:** `SETUP_GUIDE.md`
- **Implementation Details:** `IMPLEMENTATION_SUMMARY.md`

