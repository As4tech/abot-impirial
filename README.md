# Abot Imperial - Hospitality & Restaurant Management System

<div align="center">

![Abot Imperial Logo](https://via.placeholder.com/200x80/1e40af/ffffff?text=Abot+Imperial)

**A comprehensive hospitality and restaurant management system built with Laravel**

[![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20.svg?style=flat-square&logo=laravel)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.3+-777BB4.svg?style=flat-square&logo=php)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-8.0+-4479A1.svg?style=flat-square&logo=mysql)](https://mysql.com)
[![License](https://img.shields.io/badge/License-MIT-green.svg?style=flat-square)](LICENSE)

</div>

## 📋 Overview

Abot Imperial is a modern, feature-rich hospitality and restaurant management system designed to streamline operations for hotels, restaurants, and hospitality businesses. The system provides comprehensive tools for order management, inventory tracking, kitchen operations, financial reporting, and customer service.

## ✨ Key Features

### 🏨 Hospitality Management
- **Room Management**: Complete room inventory with types and status tracking
- **Reservation System**: Advanced booking and reservation management
- **Check-in/Check-out**: Streamlined guest arrival and departure processes
- **Room Service**: Integrated room service ordering and tracking

### 🍽️ Restaurant Operations
- **Point of Sale (POS)**: Modern POS system with order management
- **Menu Management**: Dynamic menu items and pricing
- **Kitchen Display System (KOT)**: Real-time kitchen order tracking
- **Table Management**: Restaurant table layout and status tracking
- **Order Processing**: Complete order lifecycle from creation to payment

### 📦 Inventory & Stock Management
- **Product Management**: Comprehensive product catalog with categories
- **Stock Tracking**: Real-time inventory monitoring and alerts
- **Purchase Management**: Purchase order creation and tracking
- **Stock Movements**: Detailed inventory movement tracking
- **Low Stock Alerts**: Automated notifications for inventory replenishment

### 💰 Financial Management
- **Payment Processing**: Multiple payment methods and transaction tracking
- **Expense Management**: Expense categories and tracking
- **Sales Reporting**: Comprehensive sales analytics and reporting
- **Revenue Tracking**: Detailed revenue analysis and forecasting

### 👥 User Management & Security
- **Role-Based Access Control**: Granular permissions system
- **User Management**: Complete user administration
- **Audit Trail**: Comprehensive activity logging
- **Multi-Location Support**: Manage multiple business locations

### 📊 Reporting & Analytics
- **Sales Reports**: Detailed sales analysis and trends
- **Inventory Reports**: Stock movement and usage analytics
- **Financial Reports**: Revenue, expense, and profit reporting
- **Operational Reports**: Kitchen performance and order analytics

## 🛠️ Technology Stack

- **Backend**: Laravel 11.x (PHP 8.3+)
- **Database**: MySQL 8.0+
- **Frontend**: Blade Templates with Tailwind CSS
- **Authentication**: Laravel Sanctum
- **Permissions**: Spatie Laravel Permission
- **File Storage**: Laravel Filesystem
- **Validation**: Laravel Form Request Validation

## 📦 Installation

### Prerequisites
- PHP 8.3 or higher
- MySQL 8.0 or higher
- Composer 2.0 or higher
- Node.js 18+ (for asset compilation)

### Setup Instructions

1. **Clone the Repository**
   ```bash
   git clone https://github.com/as4tech/abot-imperial.git
   cd abot-imperial
   ```

2. **Install Dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment Configuration**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Configure Database**
   ```bash
   Edit .env file with your database credentials:
   DB_DATABASE=abot_imperial
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

5. **Run Database Migrations**
   ```bash
   php artisan migrate
   ```

6. **Seed Initial Data**
   ```bash
   php artisan db:seed
   ```

7. **Create Storage Link**
   ```bash
   php artisan storage:link
   ```

8. **Compile Assets**
   ```bash
   npm run build
   ```

9. **Start the Application**
   ```bash
   php artisan serve
   ```

## 🔧 Configuration

### Environment Variables
Key environment variables to configure:

```env
APP_NAME="Abot Imperial"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=abot_imperial
DB_USERNAME=your_username
DB_PASSWORD=your_password

MAIL_MAILER=smtp
MAIL_HOST=your-mail-host
MAIL_PORT=587
MAIL_USERNAME=your-email
MAIL_PASSWORD=your-password
```

### Default Credentials
After seeding, you can login with:
- **Email**: admin@abotimperial.com
- **Password**: password

## 📚 Documentation

### User Guide
- [Getting Started](docs/getting-started.md)
- [User Roles & Permissions](docs/permissions.md)
- [Order Management](docs/orders.md)
- [Inventory Management](docs/inventory.md)
- [Reporting](docs/reports.md)

### Developer Guide
- [API Documentation](docs/api.md)
- [Database Schema](docs/database.md)
- [Development Setup](docs/development.md)
- [Contributing Guidelines](CONTRIBUTING.md)

## 🚀 Features in Detail

### Point of Sale (POS)
- Modern, responsive POS interface
- Real-time order tracking
- Multiple payment methods
- Receipt generation (standard and thermal)
- Order history and management

### Kitchen Operations
- Real-time Kitchen Order Ticket (KOT) display
- Order status tracking (Pending → Preparing → Served)
- Bulk order processing
- Order filtering by date and status
- Integration with restaurant POS

### Inventory Management
- Product catalog with categories
- Real-time stock tracking
- Purchase order management
- Stock movement history
- Low stock alerts and notifications

### Reporting System
- Sales analytics and trends
- Inventory movement reports
- Financial reporting
- Operational metrics
- Export capabilities

## 🔐 Security Features

- Role-based access control (RBAC)
- User authentication and authorization
- Activity logging and audit trails
- Data validation and sanitization
- CSRF protection
- SQL injection prevention

## 🤝 Contributing

We welcome contributions to Abot Imperial! Please read our [Contributing Guidelines](CONTRIBUTING.md) for details on:

- Code of Conduct
- Pull Request Process
- Coding Standards
- Bug Reporting
- Feature Requests

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🆘 Support

For support and assistance:

- 📧 Email: support@abotimperial.com
- 📞 Phone: +1 (555) 123-4567
- 💬 Live Chat: Available on our website
- 📖 Documentation: [docs.abotimperial.com](https://docs.abotimperial.com)

## 🗺️ Roadmap

### Upcoming Features
- [ ] Mobile App (iOS/Android)
- [ ] Advanced Analytics Dashboard
- [ ] Multi-Currency Support
- [ ] API Integration Layer
- [ ] Cloud Deployment Options
- [ ] Advanced Reporting Features
- [ ] Customer Loyalty Program
- [ ] Online Ordering Integration

### Version History
- **v2.0.0** - Current stable release
- **v1.5.0** - Added inventory management
- **v1.0.0** - Initial release

## 🏆 Acknowledgments

- Built with [Laravel](https://laravel.com) - The PHP Framework for Web Artisans
- UI components by [Tailwind CSS](https://tailwindcss.com)
- Icons by [Heroicons](https://heroicons.com)
- Permissions by [Spatie Laravel Permission](https://github.com/spatie/laravel-permission)

---

<div align="center">

**© 2024 Abot Imperial. All rights reserved.**

Made with ❤️ for the hospitality industry

</div>
