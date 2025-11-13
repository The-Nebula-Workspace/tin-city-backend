# WebSocket Troubleshooting Guide

## Issue: Location Updates Not Appearing in WebSocket Client

### Symptoms
- ✅ API returns success: `"is_on_route": true`
- ✅ WebSocket shows "Connected"
- ✅ Subscribed to route successfully
- ❌ No location update events appearing

### Root Cause
**Laravel Reverb WebSocket server is not running!**

---

## Solution: Start Reverb Server

### Step 1: Open New Terminal
Open a **separate terminal window** (don't close your Laravel server)

### Step 2: Start Reverb
```bash
php artisan reverb:start
```

You should see:
```
  INFO  Starting Reverb server.

  ┌ Application ────────────────────────────────────────────┐
  │ App ID ............. tin-city-metro                     │
  │ App Key ............ local-key                          │
  │ App Secret ......... local-secret                       │
  │ Host ............... localhost                          │
  │ Port ............... 8080                               │
  │ Scheme ............. http                               │
  └─────────────────────────────────────────────────────────┘

  INFO  Server running on http://localhost:8080
```

### Step 3: Keep It Running
**Leave this terminal open!** Reverb must stay running for WebSocket to work.

### Step 4: Test Again
1. Refresh your WebSocket test client: `http://localhost:8000/websocket-test.html`
2. Subscribe to route 7
3. Submit location via Postman
4. **You should now see the event!** 🎉

---

## Complete Terminal Setup

You need **3 terminals running simultaneously:**

### Terminal 1: Redis Server
```bash
redis-server
```
**Status:** Must show "Ready to accept connections"

### Terminal 2: Laravel Reverb (WebSocket)
```bash
php artisan reverb:start
```
**Status:** Must show "Server running on http://localhost:8080"

### Terminal 3: Laravel Application
```bash
php artisan serve
```
**Status:** Must show "Server running on [http://127.0.0.1:8000]"

### Terminal 4: Queue Worker (Optional but Recommended)
```bash
php artisan queue:work
```
**Status:** Shows "Processing:" when jobs run

---

## Verification Steps

### 1. Check Reverb is Running
```bash
# In PowerShell/CMD
netstat -an | findstr 8080
```
Should show: `TCP    0.0.0.0:8080    0.0.0.0:0    LISTENING`

### 2. Check Redis is Running
```bash
redis-cli ping
```
Should return: `PONG`

### 3. Check Laravel is Running
```bash
curl http://localhost:8000/api/v1/routes
```
Should return routes JSON

### 4. Check WebSocket Connection
Open browser console on `http://localhost:8000/websocket-test.html`

Should see:
```
Pusher : State changed : connecting -> connected
```

---

## Common Issues & Fixes

### Issue 1: Port 8080 Already in Use
**Error:** `Address already in use`

**Solution:**
```bash
# Find process using port 8080
netstat -ano | findstr 8080

# Kill the process (replace PID with actual number)
taskkill /PID <PID> /F

# Or use different port in .env
REVERB_PORT=8081
```

### Issue 2: Reverb Crashes Immediately
**Error:** Various startup errors

**Solution:**
```bash
# Clear config cache
php artisan config:clear

# Clear all caches
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Restart Reverb
php artisan reverb:start
```

### Issue 3: WebSocket Shows "Disconnected"
**Symptoms:** Status shows red "Disconnected"

**Solution:**
1. Check Reverb is running: `php artisan reverb:start`
2. Check `.env` has correct settings:
   ```
   REVERB_HOST=localhost
   REVERB_PORT=8080
   REVERB_SCHEME=http
   ```
3. Refresh browser page

### Issue 4: Events Not Broadcasting
**Symptoms:** Connected but no events appear

**Checklist:**
- [ ] Reverb is running
- [ ] Subscribed to correct route ID
- [ ] Location is validated as on-route
- [ ] Check Laravel logs: `storage/logs/laravel.log`

**Debug:**
```bash
# Check Reverb console output
# Should show messages when events broadcast
```

### Issue 5: "Connection Refused"
**Error:** `WebSocket connection to 'ws://localhost:8080' failed`

**Solution:**
1. Ensure Reverb is running
2. Check firewall isn't blocking port 8080
3. Try accessing: `http://localhost:8080` in browser

---

## Testing Flow (Step by Step)

### 1. Start All Services
```bash
# Terminal 1
redis-server

# Terminal 2
php artisan reverb:start

# Terminal 3
php artisan serve
```

### 2. Open WebSocket Test Client
```
http://localhost:8000/websocket-test.html
```
- Status should show: **Connected** (green)

### 3. Subscribe to Route
- Enter Route ID: **7**
- Click **Subscribe**
- Should see: "Subscribed to route 7"

### 4. Submit Location (Postman)
```bash
POST http://localhost:8000/api/v1/contributions/location
Authorization: Bearer YOUR_TOKEN

{
  "route_id": 7,
  "latitude": 9.8965,
  "longitude": 8.8583,
  "speed": 45.5
}
```

### 5. Watch for Event
**In WebSocket client, you should see:**
```
Location Update:
{
  "user_id": 1,
  "route_id": 7,
  "location": {
    "latitude": 9.8965,
    "longitude": 8.8583,
    "speed": 45.5,
    ...
  },
  "timestamp": "2025-11-13T..."
}
```

### 6. Check Reverb Console
**In Terminal 2 (Reverb), you should see:**
```
[2025-11-13 10:30:00] Broadcasting to route.7
```

---

## Debug Commands

### Check Broadcasting Configuration
```bash
php artisan tinker
```
```php
config('broadcasting.default'); // Should return: "reverb"
config('broadcasting.connections.reverb'); // Should show Reverb config
```

### Test Event Manually
```bash
php artisan tinker
```
```php
use App\Events\BusLocationUpdated;

$event = new BusLocationUpdated(1, 7, [
    'latitude' => 9.8965,
    'longitude' => 8.8583,
    'speed' => 45.5
]);

broadcast($event);
// Check WebSocket client for event
```

### Check Queue Jobs
```bash
# If using queue for broadcasts
php artisan queue:work --once

# Check queue status
php artisan queue:monitor
```

---

## Production Considerations

### Use Supervisor for Reverb
Create `/etc/supervisor/conf.d/reverb.conf`:
```ini
[program:reverb]
command=php /path/to/artisan reverb:start
autostart=true
autorestart=true
user=www-data
redirect_stderr=true
stdout_logfile=/var/log/reverb.log
```

### Use SSL in Production
```env
REVERB_SCHEME=https
REVERB_PORT=443
```

### Monitor Reverb
```bash
# Check if running
ps aux | grep reverb

# Check logs
tail -f storage/logs/laravel.log
```

---

## Quick Fix Checklist

When WebSocket isn't working:

1. [ ] Is Reverb running? → `php artisan reverb:start`
2. [ ] Is Redis running? → `redis-cli ping`
3. [ ] Is Laravel running? → `php artisan serve`
4. [ ] Config cached? → `php artisan config:clear`
5. [ ] Correct route ID? → Check subscription
6. [ ] Browser console errors? → Check F12 console
7. [ ] Reverb console output? → Check Terminal 2

---

## Success Indicators

✅ **Reverb Terminal shows:**
```
INFO  Server running on http://localhost:8080
[timestamp] Broadcasting to route.7
```

✅ **WebSocket Client shows:**
```
Status: Connected (green)
Subscribed to route 7
Location Update: {...}
```

✅ **Browser Console shows:**
```
Pusher : State changed : connecting -> connected
Pusher : Event received : bus.location.updated
```

---

## Still Not Working?

### Check Laravel Logs
```bash
tail -f storage/logs/laravel.log
```

### Enable Debug Mode
In `.env`:
```env
APP_DEBUG=true
LOG_LEVEL=debug
```

### Test with Simple Event
```php
// In tinker
broadcast(new \Illuminate\Broadcasting\BroadcastEvent('test', ['message' => 'hello']));
```

### Contact Support
Provide:
1. Reverb console output
2. Browser console errors
3. Laravel log errors
4. Output of `php artisan about`

---

## Summary

**The most common issue is forgetting to start Reverb!**

**Remember: You need 3 terminals running:**
1. `redis-server`
2. `php artisan reverb:start` ← **This is usually the missing one!**
3. `php artisan serve`

Once all three are running, WebSocket events will work perfectly! 🎉

