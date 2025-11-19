# WARP.md

This file provides guidance to WARP (warp.dev) when working with code in this repository.

## Project Overview

This is the **Jos Metro BOSS System Backend** - a Laravel-based API for powering real-time bus tracking, schedules, route optimization, and commuter notifications for public transit in Jos. The system provides secure authentication, data management for routes and schedules, real-time telemetry ingestion, and endpoints for mobile/web apps.

## Common Development Commands

### Setup & Environment
```bash
# Initial setup (installs dependencies, creates .env, generates key, runs migrations/seeds, builds assets)
composer setup

# Start development server with all services (server, queue, logs, vite)
composer dev

# Alternative: Start just the Laravel server
php artisan serve
```

### Database Operations
```bash
# Run migrations
php artisan migrate

# Fresh migration with seeding
php artisan migrate:fresh --seed

# Seed database only
php artisan db:seed
```

### Testing & Code Quality
```bash
# Run all tests
composer test
# OR
php artisan test

# Run specific test file
php artisan test tests/Feature/ExampleTest.php

# Format code with Laravel Pint
vendor/bin/pint

# List all routes
php artisan route:list
```

### Frontend Development
```bash
# Start Vite development server
npm run dev

# Build assets for production
npm run build
```

### Queue & Background Jobs
```bash
# Start queue worker
php artisan queue:listen --tries=1

# View real-time logs
php artisan pail --timeout=0
```

## Architecture Overview

### Core Domain Models
The application is built around public transit domain models:

- **Routes**: Bus routes with encoded polylines, start/end points, and distance
- **Stops**: Geolocated bus stops belonging to routes with order indexes
- **Buses**: Individual vehicles assigned to routes
- **Users**: Commuters and operators with role-based access, points system
- **Contributions**: User-generated content and feedback
- **Rewards/Badges**: Gamification system for user engagement
- **Chats**: Communication system tied to buses

### Key Relationships
- Routes have many Stops (ordered by `order_index`)
- Routes have many Buses 
- Buses belong to Routes and have many Chats
- Users have many Contributions, Chats, Rewards, and UserBadges
- All models use Laravel's Eloquent ORM with proper foreign key constraints

### Authentication & API
- Uses **Laravel Sanctum** for API authentication
- Role-based access control through User model `role` field
- API routes defined in `routes/api.php`
- RESTful API design for mobile/web client integration

### Technology Stack
- **Framework**: Laravel 12 (PHP 8.2+)
- **Database**: PostgreSQL (configurable to SQLite/MySQL)
- **Cache/Real-time**: Redis + Laravel Reverb (WebSockets)
- **Queue System**: Redis-based queues for background jobs
- **Frontend**: Vite + TailwindCSS 4.0
- **Storage**: AWS S3 integration
- **Testing**: PHPUnit with Laravel's testing utilities

## Development Workflow

1. **Database First**: Define migrations in `database/migrations/` for new features
2. **Model Relationships**: Update Eloquent models in `app/Models/` with proper relationships
3. **API Endpoints**: Add controllers and register routes in `routes/api.php`
4. **Request Validation**: Implement form requests for API validation
5. **Testing**: Add feature tests in `tests/Feature/` for new endpoints
6. **Seeding**: Create factories in `database/factories/` and seeders in `database/seeders/`

## Configuration Notes

### Environment Setup
- Copy `.env.example` to `.env` and configure database settings
- For PostgreSQL: Set `DB_CONNECTION=pgsql` with appropriate host/credentials  
- For Redis: Configure `QUEUE_CONNECTION=redis` and cache driver
- Set `SANCTUM_STATEFUL_DOMAINS` for SPA/web client integration

### Real-time Features
- Uses Laravel Reverb for WebSocket connections
- Queue workers handle background jobs for notifications and telemetry processing
- Configured for real-time location updates and ETA projections

## File Structure Highlights

- `app/Models/`: Core domain models with Eloquent relationships
- `app/Http/Resources/`: API resource transformers for consistent JSON responses
- `database/migrations/`: Database schema definitions with proper foreign keys
- `routes/api.php`: API endpoint definitions
- `tests/`: Feature and unit tests following Laravel conventions

The codebase follows Laravel conventions and is structured for scalability with proper separation of concerns between models, controllers, and services.