# Jos Metro Bus Fleet Information

## Overview

**Total Buses:** 37  
**Total Routes:** 8  
**Bus Naming Convention:** {Route Code}-{Number}

---

## Bus Distribution by Route

### Route 1: Terminus to Bukuru (8 buses)
**Distance:** 8.5 km | **Stops:** 7

**Buses:**
- TB-001, TB-002, TB-003, TB-004
- TB-005, TB-006, TB-007, TB-008

**Route Code:** TB (Terminus-Bukuru)

---

### Route 2: Terminus to Vom (6 buses)
**Distance:** 12.3 km | **Stops:** 6

**Buses:**
- TV-001, TV-002, TV-003
- TV-004, TV-005, TV-006

**Route Code:** TV (Terminus-Vom)

---

### Route 3: Terminus to Barkin Ladi (5 buses)
**Distance:** 15.7 km | **Stops:** 6

**Buses:**
- TBL-001, TBL-002, TBL-003
- TBL-004, TBL-005

**Route Code:** TBL (Terminus-Barkin Ladi)

---

### Route 4: Bukuru to Rayfield (4 buses)
**Distance:** 5.2 km | **Stops:** 5

**Buses:**
- BR-001, BR-002, BR-003, BR-004

**Route Code:** BR (Bukuru-Rayfield)

---

### Route 5: Terminus to Angwan Rogo (4 buses)
**Distance:** 6.8 km | **Stops:** 5

**Buses:**
- TAR-001, TAR-002, TAR-003, TAR-004

**Route Code:** TAR (Terminus-Angwan Rogo)

---

### Route 6: Terminus to Dadin Kowa (4 buses)
**Distance:** 7.4 km | **Stops:** 5

**Buses:**
- TDK-001, TDK-002, TDK-003, TDK-004

**Route Code:** TDK (Terminus-Dadin Kowa)

---

### Route 7: Terminus to Rikkos (3 buses)
**Distance:** 4.2 km | **Stops:** 4

**Buses:**
- TR-001, TR-002, TR-003

**Route Code:** TR (Terminus-Rikkos)

---

### Route 8: Bukuru to JUTH (3 buses)
**Distance:** 9.1 km | **Stops:** 5

**Buses:**
- BJ-001, BJ-002, BJ-003

**Route Code:** BJ (Bukuru-JUTH)

---

## Bus Code Reference

| Code | Route Name | Buses |
|------|------------|-------|
| TB | Terminus to Bukuru | 8 |
| TV | Terminus to Vom | 6 |
| TBL | Terminus to Barkin Ladi | 5 |
| BR | Bukuru to Rayfield | 4 |
| TAR | Terminus to Angwan Rogo | 4 |
| TDK | Terminus to Dadin Kowa | 4 |
| TR | Terminus to Rikkos | 3 |
| BJ | Bukuru to JUTH | 3 |

---

## API Endpoints for Buses

### Get All Buses
```bash
GET /api/v1/buses
```

### Get Buses for Specific Route
```bash
GET /api/v1/buses?route_id=1
# or
GET /api/v1/buses/route/1
```

### Get Specific Bus
```bash
GET /api/v1/buses/1
```

---

## Example Queries

### Get all buses on Route 1 (Terminus to Bukuru)
```bash
GET http://localhost:8000/api/v1/buses/route/1
```

**Response:**
```json
{
  "success": true,
  "data": {
    "route": {
      "id": 1,
      "name": "Terminus to Bukuru",
      "start_point": "Terminus",
      "end_point": "Bukuru"
    },
    "buses": [
      {"id": 1, "name": "TB-001", "route_id": 1},
      {"id": 2, "name": "TB-002", "route_id": 1},
      {"id": 3, "name": "TB-003", "route_id": 1},
      {"id": 4, "name": "TB-004", "route_id": 1},
      {"id": 5, "name": "TB-005", "route_id": 1},
      {"id": 6, "name": "TB-006", "route_id": 1},
      {"id": 7, "name": "TB-007", "route_id": 1},
      {"id": 8, "name": "TB-008", "route_id": 1}
    ],
    "total_buses": 8
  }
}
```

### Get details of bus TB-001
```bash
GET http://localhost:8000/api/v1/buses/1
```

---

## Database Queries

### Count buses per route
```php
Route::withCount('buses')->get();
```

### Get all buses with their routes
```php
Bus::with('route')->get();
```

### Find buses by name pattern
```php
Bus::where('name', 'LIKE', 'TB-%')->get(); // All Terminus-Bukuru buses
```

### Get route with its buses
```php
Route::with('buses')->find(1);
```

---

## Bus Allocation Strategy

Buses are allocated based on:

1. **Route Distance**
   - Longer routes get more buses
   - Example: Barkin Ladi (15.7 km) = 5 buses

2. **Route Popularity**
   - Major routes get more buses
   - Example: Terminus to Bukuru = 8 buses

3. **Operational Efficiency**
   - Shorter routes need fewer buses
   - Example: Rikkos (4.2 km) = 3 buses

---

## Future Enhancements

### Bus Properties to Add
- [ ] Capacity (number of seats)
- [ ] Bus type (standard, luxury, mini)
- [ ] Registration number
- [ ] Year of manufacture
- [ ] Status (active, maintenance, retired)
- [ ] Driver assignment
- [ ] GPS device ID
- [ ] Last maintenance date

### Example Extended Schema
```php
Schema::table('buses', function (Blueprint $table) {
    $table->integer('capacity')->default(40);
    $table->enum('type', ['standard', 'luxury', 'mini'])->default('standard');
    $table->string('registration_number')->nullable();
    $table->year('year')->nullable();
    $table->enum('status', ['active', 'maintenance', 'retired'])->default('active');
    $table->string('gps_device_id')->nullable();
});
```

---

## Testing Bus Endpoints

### Test 1: Get All Buses
```bash
GET http://localhost:8000/api/v1/buses
```
**Expected:** 37 buses

### Test 2: Filter by Route
```bash
GET http://localhost:8000/api/v1/buses?route_id=1
```
**Expected:** 8 buses (TB-001 to TB-008)

### Test 3: Get Specific Bus
```bash
GET http://localhost:8000/api/v1/buses/1
```
**Expected:** TB-001 with route details

### Test 4: Get Buses for Route
```bash
GET http://localhost:8000/api/v1/buses/route/4
```
**Expected:** 4 buses (BR-001 to BR-004)

---

## Verify Seeded Data

```bash
php artisan tinker
```

```php
// Total buses
Bus::count(); // 37

// Buses per route
Route::withCount('buses')->get()->pluck('buses_count', 'name');

// Get all TB buses
Bus::where('name', 'LIKE', 'TB-%')->get();

// Route 1 buses
Route::find(1)->buses;
```

---

## Integration with Real-time Tracking

When users submit location contributions, they can optionally specify which bus they're on:

```json
POST /api/v1/contributions/location
{
  "route_id": 1,
  "bus_id": 1,  // Optional: TB-001
  "latitude": 9.8965,
  "longitude": 8.8583,
  "speed": 45.5
}
```

This allows tracking specific buses rather than just user locations.

---

## Summary

✅ 37 buses seeded across 8 routes  
✅ Logical naming convention (route code + number)  
✅ Distribution based on route distance and popularity  
✅ 3 API endpoints for bus management  
✅ Ready for real-time tracking integration  
✅ Scalable for future enhancements
