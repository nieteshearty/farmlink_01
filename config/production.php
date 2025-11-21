<?php
/**
 * Production Configuration for FARMLINK
 * 
 * This file contains production-specific settings.
 * Copy this to config.php and modify for your production environment.
 */

// Security Configuration - ENABLE FOR PRODUCTION
define('FORCE_HTTPS', true);           // Force HTTPS redirects
define('SECURE_COOKIES', true);        // Enable secure cookies
define('SESSION_TIMEOUT', 1800);       // 30 minutes session timeout

// Database Configuration - UPDATE FOR PRODUCTION
define('DB_HOST', 'localhost');        // Your production database host
define('DB_NAME', 'farmlink');         // Your production database name
define('DB_USER', 'farmlink_user');    // Your production database user (NOT root)
define('DB_PASS', 'your_secure_password'); // Your production database password

// Application Configuration
define('APP_ENV', 'production');
define('DEBUG_MODE', false);           // Disable debug mode in production
define('ERROR_REPORTING', false);      // Disable error reporting to users

// File Upload Configuration
define('MAX_FILE_SIZE', 5242880);      // 5MB max file size
define('ALLOWED_IMAGE_TYPES', ['jpg', 'jpeg', 'png', 'gif', 'webp']);

// Email Configuration (if implementing email notifications)
define('SMTP_HOST', 'your-smtp-server.com');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', 'your-email@domain.com');
define('SMTP_PASSWORD', 'your-email-password');
define('SMTP_ENCRYPTION', 'tls');

// API Rate Limiting (requests per minute)
define('API_RATE_LIMIT', 60);

// Allowed domains for CORS (production domains only)
define('ALLOWED_ORIGINS', [
    'https://yourdomain.com',
    'https://www.yourdomain.com'
]);

// Security Keys (generate unique keys for production)
define('CSRF_SECRET', 'your-unique-csrf-secret-key-here');
define('ENCRYPTION_KEY', 'your-unique-encryption-key-here');

// Logging Configuration
define('LOG_ERRORS', true);
define('LOG_FILE', __DIR__ . '/../logs/farmlink.log');

// Cache Configuration
define('ENABLE_CACHE', true);
define('CACHE_DURATION', 3600); // 1 hour

?>
