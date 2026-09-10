<?php
/**
 * CAMPUS INTER - Configuration principale
 * 
 * Ce fichier contient les paramètres de configuration.
 * En production, utiliser des variables d'environnement.
 */

return [
    // ===========================
    // ENVIRONNEMENT
    // ===========================
    'APP_ENV' => getenv('APP_ENV') ?: 'production',
    'APP_NAME' => 'Campus Inter',
    'APP_URL' => getenv('APP_URL') ?: 'https://campusinter.com',
    'APP_DEBUG' => getenv('APP_DEBUG') ?: false,

    // ===========================
    // BASE DE DONNÉES
    // ===========================
    'DB_HOST' => getenv('DB_HOST') ?: 'localhost',
    'DB_PORT' => getenv('DB_PORT') ?: '3306',
    'DB_NAME' => getenv('DB_NAME') ?: 'campusinter',
    'DB_USER' => getenv('DB_USER') ?: 'campususer',
    'DB_PASSWORD' => getenv('DB_PASSWORD') ?: 'campus123',
    'DB_CHARSET' => 'utf8mb4',

    // ===========================
    // EMAIL (SMTP)
    // ===========================
    'MAIL_HOST' => getenv('MAIL_HOST') ?: 'smtp.gmail.com',
    'MAIL_PORT' => (int)(getenv('MAIL_PORT') ?: 587),
    'MAIL_USERNAME' => getenv('MAIL_USERNAME') ?: '',
    'MAIL_PASSWORD' => getenv('MAIL_PASSWORD') ?: '',
    'MAIL_FROM' => getenv('MAIL_FROM') ?: 'noreply@campusinter.com',
    'MAIL_FROM_NAME' => getenv('MAIL_FROM_NAME') ?: 'Campus Inter',
    'MAIL_TO' => getenv('MAIL_TO') ?: 'admission@rabatam.ci.com',
    'MAIL_ENCRYPTION' => getenv('MAIL_ENCRYPTION') ?: 'tls',

    // ===========================
    // EMAIL CANDIDAT
    // ===========================
    'SEND_CONFIRMATION_EMAIL' => true,

    // ===========================
    // SÉCURITÉ
    // ===========================
    'CSRF_ENABLED' => true,
    'RATE_LIMIT_ENABLED' => true,
    'RATE_LIMIT_MAX_ATTEMPTS' => 5,
    'RATE_LIMIT_WINDOW' => 300, // 5 minutes en secondes
    'HONEYPOT_ENABLED' => true,
    'SESSION_LIFETIME' => 7200, // 2 heures

    // ===========================
    // FICHIERS
    // ===========================
    'UPLOAD_MAX_SIZE' => 5 * 1024 * 1024, // 5 MB
    'UPLOAD_ALLOWED_TYPES' => ['pdf', 'jpg', 'jpeg', 'png'],
    'CSV_MAX_ROWS' => 10000,

    // ===========================
    // PAGINATION
    // ===========================
    'PER_PAGE' => 15,
    'ADMIN_PER_PAGE' => 20,

    // ===========================
    // RÉFÉRENCES
    // ===========================
    'REFERENCE_PREFIX' => 'CI',
    'REFERENCE_YEAR_FORMAT' => 'Y',
];
