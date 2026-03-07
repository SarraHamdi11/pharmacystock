<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Pharmacy Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains configuration settings for the pharmacy management
    | system including stock thresholds, notification settings, and business rules.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Stock Management
    |--------------------------------------------------------------------------
    */
    'stock' => [
        // Low stock threshold (default: 10 units)
        'low_stock_threshold' => env('PHARMACY_LOW_STOCK_THRESHOLD', 10),
        
        // Critical stock threshold (default: 5 units)
        'critical_stock_threshold' => env('PHARMACY_CRITICAL_STOCK_THRESHOLD', 5),
        
        // Expiry warning days (default: 30 days before expiry)
        'expiry_warning_days' => env('PHARMACY_EXPIRY_WARNING_DAYS', 30),
        
        // Critical expiry days (default: 7 days before expiry)
        'critical_expiry_days' => env('PHARMACY_CRITICAL_EXPIRY_DAYS', 7),
        
        // Auto-reorder when stock reaches threshold
        'auto_reorder' => env('PHARMACY_AUTO_REORDER', false),
        
        // Default reorder quantity multiplier
        'reorder_multiplier' => env('PHARMACY_REORDER_MULTIPLIER', 2),
    ],

    /*
    |--------------------------------------------------------------------------
    | Notifications
    |--------------------------------------------------------------------------
    */
    'notifications' => [
        // Enable email notifications
        'email_enabled' => env('PHARMACY_EMAIL_NOTIFICATIONS', true),
        
        // Enable SMS notifications (requires Twilio setup)
        'sms_enabled' => env('PHARMACY_SMS_NOTIFICATIONS', false),
        
        // Enable in-app notifications
        'in_app_enabled' => env('PHARMACY_IN_APP_NOTIFICATIONS', true),
        
        // Notification recipients for critical alerts
        'critical_alerts' => [
            'emails' => explode(',', env('PHARMACY_CRITICAL_ALERT_EMAILS', '')),
            'phone_numbers' => explode(',', env('PHARMACY_CRITICAL_ALERT_PHONES', '')),
        ],
        
        // Daily/weekly report schedule
        'reports' => [
            'daily_summary' => env('PHARMACY_DAILY_REPORT', false),
            'weekly_summary' => env('PHARMACY_WEEKLY_REPORT', true),
            'monthly_summary' => env('PHARMACY_MONTHLY_REPORT', true),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Business Rules
    |--------------------------------------------------------------------------
    */
    'business' => [
        // Default currency
        'currency' => env('PHARMACY_CURRENCY', 'USD'),
        
        // Tax rate (decimal, e.g., 0.08 for 8%)
        'tax_rate' => env('PHARMACY_TAX_RATE', 0),
        
        // Prescription required for controlled medications
        'prescription_required' => env('PHARMACY_PRESCRIPTION_REQUIRED', true),
        
        // Maximum days for prescription validity
        'prescription_validity_days' => env('PHARMACY_PRESCRIPTION_VALIDITY', 30),
        
        // Age verification for certain medications
        'age_verification' => env('PHARMACY_AGE_VERIFICATION', true),
        
        // Minimum age for restricted medications
        'minimum_age' => env('PHARMACY_MINIMUM_AGE', 18),
        
        // Allow partial fulfillment of orders
        'allow_partial_fulfillment' => env('PHARMACY_PARTIAL_FULFILLMENT', true),
        
        // Maximum discount percentage
        'max_discount_percentage' => env('PHARMACY_MAX_DISCOUNT', 50),
    ],

    /*
    |--------------------------------------------------------------------------
    | Security & Compliance
    |--------------------------------------------------------------------------
    */
    'security' => [
        // Enable audit logging
        'audit_logging' => env('PHARMACY_AUDIT_LOGGING', true),
        
        // Retention period for audit logs (in days)
        'audit_retention_days' => env('PHARMACY_AUDIT_RETENTION', 365),
        
        // Require two-factor authentication
        'require_2fa' => env('PHARMACY_REQUIRE_2FA', false),
        
        // Session timeout (in minutes)
        'session_timeout' => env('PHARMACY_SESSION_TIMEOUT', 480),
        
        // Maximum login attempts
        'max_login_attempts' => env('PHARMACY_MAX_LOGIN_ATTEMPTS', 5),
        
        // Lockout duration (in minutes)
        'lockout_duration' => env('PHARMACY_LOCKOUT_DURATION', 15),
        
        // IP whitelisting for admin access
        'ip_whitelist' => env('PHARMACY_IP_WHITELIST', ''),
    ],

    /*
    |--------------------------------------------------------------------------
    | Reports & Analytics
    |--------------------------------------------------------------------------
    */
    'reports' => [
        // Default date range for reports
        'default_date_range' => env('PHARMACY_DEFAULT_DATE_RANGE', '30'),
        
        // Maximum date range for reports (in days)
        'max_date_range' => env('PHARMACY_MAX_DATE_RANGE', 365),
        
        // Enable advanced analytics
        'advanced_analytics' => env('PHARMACY_ADVANCED_ANALYTICS', true),
        
        // Cache duration for analytics (in seconds)
        'analytics_cache_duration' => env('PHARMACY_ANALYTICS_CACHE', 300),
        
        // Export formats
        'export_formats' => ['csv', 'pdf', 'excel'],
        
        // Email reports automatically
        'auto_email_reports' => env('PHARMACY_AUTO_EMAIL_REPORTS', false),
    ],

    /*
    |--------------------------------------------------------------------------
    | Integration Settings
    |--------------------------------------------------------------------------
    */
    'integrations' => [
        // Electronic Health Records (EHR) integration
        'ehr_enabled' => env('PHARMACY_EHR_ENABLED', false),
        
        // Insurance verification
        'insurance_verification' => env('PHARMACY_INSURANCE_VERIFICATION', false),
        
        // Payment gateway
        'payment_gateway' => env('PHARMACY_PAYMENT_GATEWAY', 'stripe'),
        
        // Accounting software integration
        'accounting_software' => env('PHARMACY_ACCOUNTING_SOFTWARE', 'none'),
        
        // Supplier ordering system
        'supplier_ordering' => env('PHARMACY_SUPPLIER_ORDERING', false),
    ],

    /*
    |--------------------------------------------------------------------------
    | UI/UX Settings
    |--------------------------------------------------------------------------
    */
    'ui' => [
        // Default theme
        'default_theme' => env('PHARMACY_DEFAULT_THEME', 'light'),
        
        // Items per page in tables
        'items_per_page' => env('PHARMACY_ITEMS_PER_PAGE', 25),
        
        // Enable dark mode
        'dark_mode_enabled' => env('PHARMACY_DARK_MODE', false),
        
        // Show tooltips
        'show_tooltips' => env('PHARMACY_SHOW_TOOLTIPS', true),
        
        // Animation speed
        'animation_speed' => env('PHARMACY_ANIMATION_SPEED', 'normal'),
        
        // Compact mode
        'compact_mode' => env('PHARMACY_COMPACT_MODE', false),
    ],

    /*
    |--------------------------------------------------------------------------
    | Performance Settings
    |--------------------------------------------------------------------------
    */
    'performance' => [
        // Enable caching
        'enable_caching' => env('PHARMACY_ENABLE_CACHE', true),
        
        // Cache duration for medication data (in seconds)
        'medication_cache_duration' => env('PHARMACY_MEDICATION_CACHE', 3600),
        
        // Cache duration for customer data (in seconds)
        'customer_cache_duration' => env('PHARMACY_CUSTOMER_CACHE', 1800),
        
        // Enable query optimization
        'optimize_queries' => env('PHARMACY_OPTIMIZE_QUERIES', true),
        
        // Enable lazy loading
        'lazy_loading' => env('PHARMACY_LAZY_LOADING', true),
        
        // Database connection pool size
        'connection_pool_size' => env('PHARMACY_CONNECTION_POOL', 10),
    ],

    /*
    |--------------------------------------------------------------------------
    | Backup & Maintenance
    |--------------------------------------------------------------------------
    */
    'backup' => [
        // Enable automatic backups
        'auto_backup' => env('PHARMACY_AUTO_BACKUP', true),
        
        // Backup frequency (daily, weekly, monthly)
        'backup_frequency' => env('PHARMACY_BACKUP_FREQUENCY', 'daily'),
        
        // Retention period for backups (in days)
        'backup_retention' => env('PHARMACY_BACKUP_RETENTION', 30),
        
        // Backup location
        'backup_location' => env('PHARMACY_BACKUP_LOCATION', 'local'),
        
        // Include sensitive data in backups
        'include_sensitive_data' => env('PHARMACY_BACKUP_SENSITIVE', false),
    ],

    /*
    |--------------------------------------------------------------------------
    | API Settings
    |--------------------------------------------------------------------------
    */
    'api' => [
        // Enable REST API
        'enable_api' => env('PHARMACY_ENABLE_API', true),
        
        // API version
        'version' => env('PHARMACY_API_VERSION', 'v1'),
        
        // Rate limiting (requests per minute)
        'rate_limit' => env('PHARMACY_API_RATE_LIMIT', 60),
        
        // API token expiration (in hours)
        'token_expiration' => env('PHARMACY_API_TOKEN_EXPIRATION', 24),
        
        // Enable API documentation
        'enable_docs' => env('PHARMACY_API_DOCS', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Legal & Compliance
    |--------------------------------------------------------------------------
    */
    'legal' => [
        // HIPAA compliance mode
        'hipaa_compliance' => env('PHARMACY_HIPAA_COMPLIANCE', false),
        
        // GDPR compliance mode
        'gdpr_compliance' => env('PHARMACY_GDPR_COMPLIANCE', false),
        
        // Data encryption at rest
        'encrypt_data_at_rest' => env('PHARMACY_ENCRYPT_DATA', false),
        
        // Data encryption in transit
        'encrypt_data_in_transit' => env('PHARMACY_ENCRYPT_TRANSIT', true),
        
        // Legal disclaimer text
        'disclaimer' => env('PHARMACY_DISCLAIMER', ''),
        
        // Terms of service
        'terms_of_service' => env('PHARMACY_TERMS', ''),
    ],
];
