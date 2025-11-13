# Route & Bus Seeding Guide

## Quick Seed Everything

### Option 1: Seed Everything (Users + Routes + Buses)
```bash
php artisan migrate:fresh --seed
```

### Option 2: Seed Only Routes
```bash
php artisan db:seed --class=RouteSeeder
```

### Option 3: Seed Only Buses
```bash
php artisan db:seed --class=BusSeeder
```

### Option 4: Seed Routes and Buses Together
```bash
php artisan db:seed --class=RouteSeeder
php artisan db:seed --class=BusSeeder
```

---

## What Gets Seeded

### 8 Jos Metro Routes with Stops

1. **Terminus to Bukuru** (8.5 km)
   - 7 stops: Terminus, Ahmadu Bello Way, Jos Main Market, Plateau Specialist Hospital, Rayfield, Lamingo, Bukuru Motor Park

2. **Terminus to Vom** (12.3 km)
   - 6 stops: Terminus, Secretariat Junction, Zaria Road, Gangare, Vom Junction, Vom Town

3. **Terminus to Barkin Ladi** (15.7 km)
   - 6 stops: Terminus, Bauchi Road, Hwolshe, Kuru, Barkin Ladi Junction, Barkin Ladi Town

4. **Bukuru to Rayfield** (5.2 km)
   - 5 stops: Bukuru Motor Park, Gyel, Gada Biu, Zawan, Rayfield

5. **Terminus to Angwan Rogo** (6.8 km)
   - 5 stops: Terminus, Murtala Mohammed Way, Farin Gada, Tudun Wada, Angwan Rogo

6. **Terminus to Dadin Kowa** (7.4 km)
   - 5 stops: Terminus, Polo Roundabout, Jenta Adamu, Jenta Mangoro, Dadin Kowa

7. **Terminus to Rikkos** (4.2 km)
   - 4 stops: Terminus, Bank Road, Ahmadu Bello Stadium, Rikkos

8. **Bukuru to JUTH** (9.1 km)
   - 5 stops: Bukuru Motor Park, Gyel Junction, Lamingo Junction, Secretariat, JUTH

### 37 Buses Across All Routes

**Bus Distribution:**
- Terminus to Bukuru: 8 buses (TB-001 to TB-008)
- Terminus to Vom: 6 buses (TV-001 to TV-006)
- Terminus to Barkin Ladi: 5 buses (TBL-001 to TBL-005)
- Bukuru to Rayfield: 4 buses (BR-001 to BR-004)
- Terminus to Angwan Rogo: 4 buses (TAR-001 to TAR-004)
- Terminus to Dadin Kowa: 4 buses (TDK-001 to TDK-004)
- Terminus to Rikkos: 3 buses (TR-001 to TR-003)
- Bukuru to JUTH: 3 buses (BJ-001 to BJ-003)

**Bus Naming Convention:**
- TB = Terminus-Bukuru
- TV = Terminus-Vom
- TBL = Terminus-Barkin Ladi
- BR = Bukuru-Rayfield
- TAR = Terminus-Angwan Rogo
- TDK = Terminus-Dadin Kowa
- TR = Terminus-Rikkos
- BJ = Bukuru-JUTH

---

## Verify Seeded Data

### Check Routes
```bash
php artisan tinker
```
```php
Route::count(); // Should return 8
Route::with('stops')->get();
```

### Check Stops
```php
Stop::count(); // Should return 43 total stops
Route::find(1)->stops; // Get stops for route 1
```

### Check Buses
```php
Bus::count(); // Should return 37 total buses
Route::find(1)->buses; // Get buses for route 1
Bus::where('route_id', 1)->get(); // All buses on route 1
```

### Via API
```bash
# Get all routes
GET http://localhost:8000/api/v1/routes

# Get route with stops
GET http://localhost:8000/api/v1/routes/1
```

---

## Test with Seeded Data

### Get Route 1 (Terminus to Bukuru)
```bash
GET http://localhost:8000/api/v1/routes/1
```

### Submit Location on Route 1
```bash
POST http://localhost:8000/api/v1/contributions/location
Authorization: Bearer YOUR_TOKEN

{
  "route_id": 1,
  "latitude": 9.8965,
  "longitude": 8.8583,
  "speed": 45.5
}
```

This location is at Terminus stop, so it will be validated as on-route!

### Submit Location on Route 4 (Bukuru to Rayfield)
```bash
POST http://localhost:8000/api/v1/contributions/location
Authorization: Bearer YOUR_TOKEN

{
  "route_id": 4,
  "latitude": 9.8876,
  "longitude": 8.8945,
  "speed": 35.0
}
```

This location is at Rayfield stop.

---

## GPS Coordinates Reference

All coordinates are real locations in Jos, Plateau State, Nigeria:

- **Terminus:** 9.8965°N, 8.8583°E
- **Jos Main Market:** 9.9145°N, 8.8734°E
- **Bukuru:** 9.7965°N, 8.8683°E
- **Rayfield:** 9.8876°N, 8.8945°E
- **JUTH:** 9.8734°N, 8.8912°E

---

## Troubleshooting

### Error: "SQLSTATE[23000]: Integrity constraint violation"
Routes already exist. Either:
1. Use `php artisan migrate:fresh --seed` to reset database
2. Manually delete routes: `Route::truncate(); Stop::truncate();`

### No Routes Showing
Check if seeder ran:
```bash
php artisan db:seed --class=RouteSeeder
```

Check database:
```bash
php artisan tinker
Route::count();
```

---

## Custom Seeding

To add your own routes, edit `database/seeders/RouteSeeder.php`:

```php
[
    'name' => 'Your Route Name',
    'start_point' => 'Start Location',
    'end_point' => 'End Location',
    'encoded_polyline' => 'google_maps_polyline',
    'distance_km' => 10.5,
    'stops' => [
        [
            'name' => 'Stop 1',
            'latitude' => 9.8965,
            'longitude' => 8.8583,
            'order_index' => 1
        ],
        // Add more stops...
    ],
],
```

Then run:
```bash
php artisan db:seed --class=RouteSeeder
```

---

## Next Steps

After seeding:

1. ✅ Get all routes: `GET /api/v1/routes`
2. ✅ Test location submission with real coordinates
3. ✅ Open WebSocket test client
4. ✅ Subscribe to route 1
5. ✅ Submit location and watch real-time update!
