# RideShare - Laravel Application

A comprehensive ride-sharing platform built with Laravel, featuring an admin dashboard, RESTful API, and trip management system.

## 🚀 Features

### Core Functionality
- **Trip Management**: Create, track, and manage ride trips
- **Driver Management**: Register and manage drivers with vehicle assignments
- **Vehicle Management**: Track available vehicles and their locations
- **User Management**: Handle user accounts and trip history
- **Real-time Analytics**: Dashboard with trip statistics and insights

### Admin Dashboard
- **Statistics Overview**: Real-time metrics for trips, drivers, and vehicles
- **Trip Management**: View, edit, and manage all trips
- **Driver Management**: Add, edit, and monitor driver status
- **Vehicle Management**: Track vehicle availability and locations
- **Analytics**: Detailed reports and charts for business insights

### API Endpoints
- **Trip Operations**: Create, update, and retrieve trip information
- **Status Updates**: Real-time trip status management
- **Swagger Documentation**: Auto-generated API documentation

## 🛠 Technology Stack

- **Backend**: Laravel 10.x
- **Frontend**: Blade templates with Tailwind CSS
- **Database**: MySQL/PostgreSQL
- **Authentication**: Laravel's built-in auth system
- **API Documentation**: L5-Swagger
- **Charts**: Chart.js for analytics
- **Icons**: Font Awesome

## 📋 Prerequisites

- PHP >= 8.1
- Composer
- MySQL/PostgreSQL
- Node.js & NPM (for frontend assets)

## 🚀 Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd rideshare
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node.js dependencies**
   ```bash
   npm install
   ```

4. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Configure database**
   Edit `.env` file with your database credentials:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=rideshare
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

6. **Run migrations and seeders**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

7. **Build frontend assets**
   ```bash
   npm run build
   ```

8. **Start the development server**
   ```bash
   php artisan serve
   ```

## 📁 Project Structure

```
app/
├── Console/Commands/          # Artisan commands
├── Enums/                     # Enum classes (TripStatus, VehicleType)
├── Http/
│   ├── Controllers/
│   │   ├── Admin/            # Admin dashboard controllers
│   │   └── Api/              # API controllers
│   ├── Middleware/           # Custom middleware
│   ├── Requests/             # Form request validation
│   └── Resources/            # API resources
├── Jobs/                     # Background jobs
├── Models/                   # Eloquent models
└── Providers/               # Service providers

database/
├── factories/               # Model factories for testing
├── migrations/              # Database migrations
└── seeders/                # Database seeders

resources/
└── views/
    ├── admin/              # Admin dashboard views
    └── layouts/            # Blade layout templates

routes/
├── admin.php              # Admin routes
├── api.php                # API routes
└── web.php                # Web routes
```

## 🔐 Authentication

### Admin Access
- Default admin credentials are seeded during installation
- Access admin dashboard at `/admin`
- Admin authentication middleware protects admin routes

### API Authentication
- API endpoints require proper authentication
- Use Laravel Sanctum for API token authentication

## 📊 Database Schema

### Core Tables
- **users**: User accounts and profiles
- **admins**: Admin user accounts
- **drivers**: Driver information and status
- **vehicles**: Vehicle details and availability
- **trips**: Trip records with status tracking

### Key Relationships
- Users can have multiple trips
- Drivers are assigned to vehicles
- Trips are associated with users, drivers, and vehicles

## 🚗 Trip Management

### Trip Status Flow
1. **Pending**: Trip created, waiting for driver assignment
2. **Assigned**: Driver assigned to trip
3. **In Progress**: Trip started
4. **Completed**: Trip finished successfully
5. **Cancelled**: Trip cancelled

### Trip Features
- Real-time status updates
- Fare calculation
- Pickup and dropoff location tracking
- Driver assignment system

## 📈 Analytics & Reporting

### Dashboard Metrics
- Total trips count
- Active trips
- Available drivers
- Available vehicles
- Monthly trip statistics

### Charts & Visualizations
- Monthly trip trends
- Driver performance metrics
- Revenue analytics
- Geographic trip distribution

## 🔌 API Documentation

### Base URL
```
http://localhost:8000/api
```

### Key Endpoints

#### Trips
- `GET /trips` - List all trips
- `POST /trips` - Create new trip
- `GET /trips/{id}` - Get trip details
- `PUT /trips/{id}/status` - Update trip status

#### Drivers
- `GET /drivers` - List available drivers
- `GET /drivers/{id}` - Get driver details

#### Vehicles
- `GET /vehicles` - List available vehicles
- `GET /vehicles/{id}/location` - Get vehicle location

### Swagger Documentation
Access auto-generated API documentation at:
```
http://localhost:8000/api/documentation
```

## 🧪 Testing

Run the test suite:
```bash
php artisan test
```

### Test Coverage
- Feature tests for trip booking
- Unit tests for models
- API endpoint testing
- Admin functionality testing

## 🚀 Deployment

### Production Setup
1. Set environment to production
2. Configure production database
3. Run migrations
4. Set up queue workers
5. Configure caching
6. Set up SSL certificates

### Environment Variables
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com
DB_CONNECTION=mysql
CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
```

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests for new functionality
5. Submit a pull request

## 📝 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🆘 Support

For support and questions:
- Create an issue in the repository
- Check the API documentation
- Review the Laravel documentation

## 🔄 Changelog

### Version 1.0.0
- Initial release
- Admin dashboard
- Trip management system
- API endpoints
- Basic analytics

---

**Built with ❤️ using Laravel**
