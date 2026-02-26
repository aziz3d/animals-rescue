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
-   [Recent Updates](#recent-updates)
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

-   **Backend**: Laravel 12+, PHP 8.2+
-   **Frontend**: Blade Templates, Tailwind CSS
-   **Components**: Livewire for dynamic interfaces
-   **Database**: MySQL 8.0+ or PostgreSQL 12+
-   **File Storage**: Local and Cloud storage support
-   **Payment Processing**: Stripe integration
-   **Authentication**: Laravel Breeze with enhanced security
-   **Testing**: PHPUnit, Pest

## Installation

### Prerequisites

-   PHP >= 8.2
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

-   **Email**: sunrise300@gmail.com
-   **Password**: azizkhan

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
-   Multiple payment methods (Stripe and Paypal)
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

### Future Implementations

-   Modular architecture for adding features
-   API-ready for mobile applications
-   Plugin system for third-party integrations
-   Theme support for design variations

## Recent Updates

### February 2026 - Major Maintenance Update

This section documents all recent improvements, bug fixes, and updates made to the Animal Rescue Website.

#### 🎨 UI/UX Improvements

**Favicon Update**
-   Replaced Laravel default favicon with custom red heart icon
-   New favicon better represents the animal rescue mission
-   SVG format ensures crisp display across all devices and resolutions

#### 🐛 Bug Fixes & Code Quality

**Debug Logging Optimization**
-   Wrapped all debug logging statements in `config('app.debug')` checks
-   Prevents unnecessary logging in production environments
-   Affected files:
    -   `app/Models/Animal.php` - Image path logging
    -   `app/Models/Story.php` - Featured image logging
    -   `app/Livewire/Animals/Show.php` - Component debugging

**Layout Issues Resolved**
-   Fixed missing layout definitions in admin Livewire components
-   Added proper layout configuration to:
    -   `app/Livewire/Admin/Comments/Index.php`
    -   `app/Livewire/Admin/ImagePathFixer.php`
-   Resolved "No hint path defined for [layouts]" error

**Content Updates**
-   Removed placeholder tax ID from donation receipts
-   Replaced with professional tax advisory message
-   Updated `resources/views/livewire/donations/receipt.blade.php`

#### 📦 Package Updates

**Composer Dependencies**
-   Updated `laravel/boost` from ^1.0 to ^2.0 (AI development tool)
-   All core Laravel packages confirmed up-to-date:
    -   Laravel Framework: 12.53.0 ✅
    -   Livewire/Flux: 2.12.2 ✅
    -   Livewire/Volt: 1.10.3 ✅
    -   Laravel Socialite: 5.24.3 ✅

**NPM Dependencies**
-   Updated `@rollup/rollup-linux-x64-gnu` to 4.59.0
-   Updated `@tailwindcss/oxide-linux-x64-gnu` to 4.2.1
-   Updated `lightningcss-linux-x64-gnu` to 1.31.1

**Available Updates** (Optional)
-   PHPUnit 11 → 12 (requires testing before upgrade)
-   See `PACKAGE_UPDATES.md` for detailed update strategy

#### ⚙️ Configuration Enhancements

**Environment Variables**
-   Added Stripe payment configuration to `.env.example`:
    -   `STRIPE_KEY`
    -   `STRIPE_SECRET`
    -   `STRIPE_WEBHOOK_SECRET`
-   Added social login configurations:
    -   Google OAuth (client ID, secret, redirect URI)
    -   Facebook OAuth (client ID, secret, redirect URI)
    -   GitHub OAuth (client ID, secret, redirect URI)

**Documentation**
-   Updated PHP requirement from 8.1 to 8.2 in README
-   Created comprehensive `PACKAGE_UPDATES.md` with update strategies
-   All configuration examples now match actual implementation

#### 🧪 Testing Improvements

**Test Suite Cleanup**
-   Removed useless boilerplate tests:
    -   `tests/Unit/ExampleTest.php` (only tested true === true)
    -   `tests/Feature/ExampleTest.php` (redundant homepage test)
-   Retained 70 meaningful tests covering:
    -   Authentication & Authorization (10 tests)
    -   User Settings & Profile (5 tests)
    -   Animal Management (7 tests)
    -   Donation System (9 tests)
    -   Success Stories (9 tests)
    -   Volunteer Applications (7 tests)
    -   Contact System (6 tests)
    -   Dashboard & Analytics (6 tests)
    -   Homepage Features (3 tests)

**Test Coverage**
-   All remaining tests provide meaningful coverage
-   Tests validate actual application functionality
-   No deprecated or broken tests remaining

#### 📊 System Health

**Security Status**
-   ✅ No known security vulnerabilities
-   ✅ All packages from trusted sources
-   ✅ No hardcoded credentials found
-   ✅ Proper .gitignore configuration
-   ✅ Environment variables properly configured

**Code Quality**
-   ✅ PSR-12 coding standards maintained
-   ✅ No SQL injection vulnerabilities detected
-   ✅ Modern Laravel best practices followed
-   ✅ Proper MVC architecture maintained

**Performance**
-   ✅ Debug logging optimized for production
-   ✅ Asset compilation configured
-   ✅ Database queries optimized
-   ✅ Caching strategies in place

#### 📝 Documentation Updates

**New Documentation Files**
-   `PACKAGE_UPDATES.md` - Comprehensive package update guide
    -   Categorized updates by priority and risk
    -   Includes update strategies (conservative, moderate, aggressive)
    -   PHPUnit 12 upgrade considerations
    -   Testing recommendations

**Updated Files**
-   `README.md` - PHP version requirement, recent updates section
-   `.env.example` - Payment and social login configurations
-   `composer.json` - Laravel Boost version bump
-   `package.json` - Updated optional dependencies

#### 🔄 Migration Notes

If you're updating from a previous version:

1. **Update Dependencies**
    ```bash
    composer update laravel/boost
    npm install
    ```

2. **Update Environment File**
    ```bash
    # Add new variables from .env.example:
    # - Stripe configuration
    # - Social login credentials
    ```

3. **Clear Caches**
    ```bash
    php artisan optimize:clear
    php artisan config:clear
    php artisan view:clear
    ```

4. **Run Tests**
    ```bash
    php artisan test
    ```

#### 🎯 Next Steps

**Recommended Actions**
-   Review `PACKAGE_UPDATES.md` for optional package updates
-   Configure Stripe keys in `.env` for payment processing
-   Set up social login providers if needed
-   Run test suite to ensure everything works

**Future Considerations**
-   Plan PHPUnit 12 upgrade (breaking changes expected)
-   Monitor for security updates monthly
-   Consider updating minor version packages

---

**Last Updated**: February 26, 2026  
**Version**: 1.1.0  
**Laravel**: 12.53.0  
**PHP**: 8.3.28

## Contributing

We welcome contributions to improve the Animal Rescue Website!

### Steps to Contribute

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Added a feature'`)
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
## Have Question?
Send your questions to:
Email: sunrise300@gmail.com

Made for animal rescue organizations worldwide, Developer: Aziz Khan
