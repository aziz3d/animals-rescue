# Animal Rescue Website

![Animal Rescue Website](public/screenshot/design-layout.png)

A comprehensive, modern website for animal rescue organizations built with Laravel and Livewire. This platform helps rescues manage animals, volunteers, donations, and success stories while providing an engaging experience for visitors to adopt, volunteer, and support the cause.

## Table of Contents

-   [Features](#features)
-   [Tech Stack](#tech-stack)
-   [Installation](#installation)
-   [Configuration](#configuration)
-   [Key Modules](#key-modules)
-   [Admin Panel](#admin-panel)
-   [Customization](#customization)
-   [Contributing](#contributing)
-   [License](#license)

## Features

### For Visitors

-   **Animal Adoption**: Browse available animals with detailed profiles, photos, and adoption information
-   **Success Stories**: Read heartwarming stories of rescued animals finding forever homes
-   **Volunteer Program**: Learn about volunteer opportunities and apply online
-   **Donation System**: Secure donation processing with multiple payment options
-   **Contact Forms**: Easy communication with the rescue organization
-   **Responsive Design**: Mobile-friendly interface that works on all devices
-   **Accessibility**: WCAG 2.1 AA compliant design for inclusive access

### For Administrators

-   **Animal Management**: Complete CRUD operations for animal profiles with medical records
-   **Adoption Tracking**: Monitor adoption applications and status
-   **Volunteer Management**: Review applications and manage volunteer schedules
-   **Donation Tracking**: Financial reporting and donor management
-   **Content Management**: Update website content, images, and settings
-   **Statistics Dashboard**: Real-time metrics and analytics
-   **SEO Tools**: Meta tags, sitemaps, and search engine optimization

## Tech Stack

-   **Backend**: Laravel 10+, PHP 8.1+
-   **Frontend**: Blade Templates, Tailwind CSS, Alpine.js
-   **Components**: Livewire for dynamic interfaces
-   **Database**: MySQL 8.0+ or PostgreSQL 12+
-   **File Storage**: Local and Cloud storage support
-   **Payment Processing**: Stripe integration
-   **Authentication**: Laravel Breeze with enhanced security
-   **Testing**: PHPUnit, Pest

## Installation

### Prerequisites

-   PHP >= 8.1
-   Composer
-   MySQL or PostgreSQL
-   Node.js and NPM
-   Git

### Steps

1. **Clone the repository**

    ```bash
    git clone https://github.com/aziz3d/animals-rescue.git
    cd animals-rescue
    ```

2. **Install PHP dependencies**

    ```bash
    composer install
    ```

3. **Install JavaScript dependencies**

    ```bash
    npm install
    npm run dev
    ```

4. **Copy and configure environment file**

    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

5. **Configure database in `.env`**

    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=your_database_name
    DB_USERNAME=your_username
    DB_PASSWORD=your_password
    ```

6. **Run database migrations and seeders**

    ```bash
    php artisan migrate --seed
    ```

7. **Start the development server**

    ```bash
    php artisan serve
    ```

8. **Compile assets for production**
    ```bash
    npm run build
    ```

## Configuration

### Environment Variables

Key configuration options in `.env`:

```env
# Application Settings
APP_NAME="Lovely Paws Rescue"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=animal_rescue
DB_USERNAME=root
DB_PASSWORD=

# Mail Configuration
MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=info@lovelypawsrescue.org
MAIL_FROM_NAME="${APP_NAME}"

# Payment Processing
STRIPE_KEY=your_stripe_key
STRIPE_SECRET=your_stripe_secret
```

### Initial Admin User

After seeding, login with:

-   **Email**: admin@example.com
-   **Password**: password

## Key Modules

### Animals Management

-   Detailed animal profiles with photos, medical history, and behavioral notes
-   Adoption status tracking (available, pending, adopted)
-   Breed, age, gender, and size information
-   Featured animals for homepage promotion

### Adoption System

-   Online adoption applications
-   Application review workflow
-   Status tracking and notifications
-   Post-adoption follow-ups

### Volunteer Program

-   Volunteer opportunity listings
-   Online application system
-   Application review and approval
-   Volunteer scheduling and management

### Donation Platform

-   One-time and recurring donations
-   Multiple payment methods (Stripe)
-   Donation tiers and custom amounts
-   Receipt generation and donor management

### Success Stories

-   Story creation and management
-   Featured stories promotion
-   Category organization (Adoptions, Rescues, Events)
-   Social sharing capabilities

### Contact Management

-   Inquiry tracking system
-   Automated responses
-   Category-based routing
-   Status management

## Admin Login

User: sunrise300@gmail.com
Password: azizkhan

## Admin Panel

The admin panel provides comprehensive management tools:

### Dashboard

-   Key metrics and statistics
-   Quick action buttons
-   Recent activity feed
-   System health monitoring

### Settings

-   **General Settings**: Site name, description, SEO
-   **Hero Section**: Homepage banner customization
-   **Ready to Make a Difference**: Call-to-action section
-   **Footer Settings**: Contact information and links
-   **Animal Settings**: Display preferences
-   **Story Settings**: Homepage story configuration
-   **Volunteer Settings**: Program details and forms
-   **Donation Settings**: Payment options and messages
-   **Contact Settings**: Form configurations

### Content Management

-   Animal profiles with photos and details
-   Success stories with rich text editing
-   Volunteer opportunities
-   Static pages (About, Privacy Policy, etc.)

## Customization

### Branding

-   Custom logo and favicon upload
-   Color scheme customization
-   Typography adjustments
-   Social media integration

### Content

-   Fully editable homepage sections
-   Customizable call-to-action areas
-   Flexible page content management
-   Multi-language support preparation

### Extensions

-   Modular architecture for adding features
-   API-ready for mobile applications
-   Plugin system for third-party integrations
-   Theme support for design variations

## Contributing

We welcome contributions to improve the Animal Rescue Website!

### Steps to Contribute

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

### Development Guidelines

-   Follow PSR-12 coding standards
-   Write meaningful commit messages
-   Include tests for new features
-   Update documentation as needed
-   Ensure responsive design compatibility

### Reporting Issues

-   Use the GitHub issue tracker
-   Provide detailed reproduction steps
-   Include screenshots for UI issues
-   Specify browser and device information

## License

This project is open source software licensed under the [MIT License](LICENSE).

## Support

For support, please open an issue on GitHub or contact the maintainers.

---

_Made for animal rescue organizations worldwide_
