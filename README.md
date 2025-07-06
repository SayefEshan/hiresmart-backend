# HireSmart Backend

A robust job platform backend that connects employers with candidates, built with Laravel, PostgreSQL, and Redis. Features include JWT authentication, role-based access control, job matching algorithms, and automated background tasks.

## 🚀 Quick Start

### Prerequisites

-   Docker & Docker Compose
-   Git
-   Postman (for API testing)

### Setup Instructions

1. **Clone the repository**

```bash
git clone https://github.com/YOUR_USERNAME/hiresmart-backend.git
cd hiresmart-backend
```

2. **Environment setup**

```bash
cp .env.example .env
```

3. **Start Docker containers**

```bash
docker compose up -d
```

4. **Install dependencies**

```bash
docker compose exec app composer install
```

5. **Generate application keys**

```bash
docker compose exec app php artisan key:generate
docker compose exec app php artisan jwt:secret
```

6. **Run database migrations and seeders**

```bash
docker compose exec app php artisan migrate
docker compose exec app php artisan db:seed
```

7. **Access the application**

-   API: http://localhost:8000
-   Database: localhost:5432 (PostgreSQL)
-   Redis: localhost:6379

## 🏗️ Design Choices & Architecture

### 1. **Technology Stack**

-   **Laravel 11**: Modern PHP framework with excellent ecosystem
-   **PostgreSQL**: Robust RDBMS with advanced features and JSON support
-   **Redis**: High-performance caching and queue management
-   **Docker**: Consistent development and deployment environment
-   **JWT**: Stateless authentication for API scalability

### 2. **Architecture Decisions**

#### **Service Layer Pattern**

-   **Why**: Separates business logic from controllers, making code more maintainable and testable
-   **Implementation**: All business logic resides in service classes (`app/Services/`)
-   **Benefit**: Controllers remain thin and focused on HTTP concerns

#### **Repository Pattern (Skipped)**

-   **Why skipped**: Laravel's Eloquent ORM already provides excellent abstraction
-   **Alternative**: Used model scopes and methods for reusable queries
-   **Benefit**: Reduced complexity without sacrificing functionality

#### **API Resources**

-   **Why**: Consistent API responses with proper data transformation
-   **Implementation**: JsonResource classes for all API outputs
-   **Benefit**: Decouples internal models from external API structure

#### **Form Requests**

-   **Why**: Centralized validation and authorization
-   **Implementation**: Separate request classes for each endpoint
-   **Benefit**: Clean controllers and reusable validation rules

### 3. **Security Features**

-   **JWT Authentication**: Stateless, scalable authentication
-   **Role-Based Access Control**: Using Spatie Laravel Permission
-   **Rate Limiting**: Prevents abuse on login and application endpoints
-   **SQL Injection Protection**: Eloquent ORM and parameterized queries
-   **XSS Protection**: Laravel's built-in escaping and CSP headers

### 4. **Performance Optimizations**

-   **Redis Caching**: 5-minute cache for job listings
-   **Database Indexing**: Strategic indexes on frequently queried columns
-   **Eager Loading**: Prevents N+1 queries with proper relationship loading
-   **Queue System**: Background processing for heavy tasks

### 5. **Background Processing**

-   **Job Matching**: Automated candidate-job matching based on skills, location, salary, and experience
-   **Scheduled Tasks**:
    -   Daily: Archive jobs older than 30 days
    -   Weekly: Remove unverified users
    -   Every 6 hours: Process job matching

## 🔧 Environment Configuration

### Key Environment Variables

```env
# Application
APP_NAME=HireSmart
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database
DB_CONNECTION=pgsql
DB_HOST=db                    # 'db' for Docker, 'localhost' for local
DB_PORT=5432
DB_DATABASE=hiresmart
DB_USERNAME=postgres
DB_PASSWORD=password

# Redis
REDIS_HOST=redis             # 'redis' for Docker, 'localhost' for local
REDIS_PASSWORD=null
REDIS_PORT=6379

# Cache & Queue
CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis

# JWT
JWT_SECRET=your-generated-secret
JWT_TTL=60                   # Token lifetime in minutes
JWT_REFRESH_TTL=20160       # Refresh lifetime in minutes

# Application Settings
DEFAULT_PER_PAGE=15
MAX_PER_PAGE=100

# Cache TTL (seconds)
CACHE_TTL_JOB_LISTINGS=300        # 5 minutes
CACHE_TTL_APPLICATION_STATS=600   # 10 minutes

# Rate Limiting
RATE_LIMIT_LOGIN_ATTEMPTS=5
RATE_LIMIT_LOGIN_DECAY_MINUTES=15
RATE_LIMIT_APPLICATION_SUBMISSIONS=10
RATE_LIMIT_APPLICATION_DECAY_MINUTES=60

# Job Settings
JOB_ARCHIVE_AFTER_DAYS=30

# User Settings
REMOVE_UNVERIFIED_AFTER_DAYS=7
```

## 📚 Project Structure

```
hiresmart-backend/
├── app/
│   ├── Console/Commands/     # Scheduled tasks & commands
│   ├── Http/
│   │   ├── Controllers/      # API controllers
│   │   ├── Middleware/       # Custom middleware
│   │   ├── Requests/         # Form request validation
│   │   └── Resources/        # API resources
│   ├── Jobs/                 # Background jobs
│   ├── Models/               # Eloquent models
│   └── Services/             # Business logic
├── config/                   # Configuration files
├── database/
│   ├── migrations/           # Database migrations
│   └── seeders/              # Database seeders
├── docker/                   # Docker configuration
├── docs/                     # Documentation

```

### API Testing with Postman

1. **Import the Postman Collection**

    - Download the [Postman Collection](HireSmart%20API.postman_collection.json)
    - Open Postman and click "Import"
    - Select the downloaded JSON file

2. **Configure Environment**

    - The collection includes variables for `base_url`, `access_token`, etc.
    - Default `base_url` is set to `http://localhost:8000/api`

3. **Testing Flow**

    - Start with Authentication → Register/Login
    - The collection automatically stores tokens and IDs
    - Test employer features: Create jobs, view applications
    - Test candidate features: Browse jobs, apply
    - Test admin features: View metrics

4. **Collection Features**
    - Automatic token management
    - Pre-request scripts for dynamic data
    - Test scripts for response validation
    - Example requests for all endpoints

## 📚 Documentation

-   **[Database Schema](docs/database-erd.md)** - Entity relationship diagram
-   **[Postman Collection](docs/HireSmart%20API.postman_collection.json)** - Import for easy API testing

## 🤝 Test Credentials

After running seeders, you can use these accounts:

### Admin

-   **Email**: admin@hiresmart.com
-   **Password**: admin123

### Employers

-   john@techstartup.com / password123
-   sarah@digitalagency.com / password123
-   michael@ecommerce.com / password123

### Candidates

-   alice@example.com / password123
-   bob@example.com / password123
-   emma@example.com / password123
-   david@example.com / password123
