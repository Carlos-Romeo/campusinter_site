<?php
/**
 * CAMPUS INTER - Point d'entrée principal
 * Front controller unique
 */

declare(strict_types=1);

// Configuration d'erreurs
error_reporting(E_ALL);
ini_set('display_errors', '0');
ini_set('log_errors', '1');

// Démarrer la session
if (session_status() === PHP_SESSION_NONE) {
    session_start([
        'cookie_httponly' => true,
        'cookie_secure' => true,
        'cookie_samesite' => 'Strict',
        'use_strict_mode' => true,
    ]);
}

// Chargement des classes
require_once dirname(__DIR__) . '/vendor/autoload.php';

use CampusInter\Core\Router;
use CampusInter\Controllers\AuthController;
use CampusInter\Controllers\AdminController;
use CampusInter\Controllers\PublicController;
use CampusInter\Controllers\CsvImportController;

// Créer le routeur
$router = new Router();

// =====================================================
// ROUTES PUBLIQUES
// =====================================================
$router->get('/', [new PublicController(), 'home']);
$router->get('/programme', [new PublicController(), 'programDetail']);
$router->get('/preinscription', [new PublicController(), 'applicationForm']);
$router->get('/confirmation', [new PublicController(), 'confirmation']);

// =====================================================
// API PUBLIQUE
// =====================================================
$router->get('/api/levels', [new PublicController(), 'apiLevels']);
$router->get('/api/domains', [new PublicController(), 'apiDomains']);
$router->get('/api/specialties', [new PublicController(), 'apiSpecialties']);
$router->get('/api/cities', [new PublicController(), 'apiCities']);
$router->get('/api/programs/search', [new PublicController(), 'apiSearch']);
$router->post('/api/applications', [new PublicController(), 'apiSubmitApplication']);

// =====================================================
// ROUTES ADMIN - AUTH
// =====================================================
$router->get('/login', [new AuthController(), 'showLogin']);
$router->post('/login', [new AuthController(), 'login']);
$router->get('/logout', [new AuthController(), 'logout']);

// =====================================================
// MIDDLEWARE AUTH ADMIN
// =====================================================
$router->middleware('auth', function () {
    if (!AuthController::isLoggedIn()) {
        header('Location: /login');
        exit;
    }
    if (AuthController::isSessionExpired()) {
        session_destroy();
        header('Location: /login?expired=1');
        exit;
    }
});

// =====================================================
// ROUTES ADMIN (protégées)
// =====================================================
$admin = new AdminController();

$router->get('/admin', [$admin, 'dashboard'], ['auth']);

// Domaines
$router->get('/admin/domains', [$admin, 'domains'], ['auth']);
$router->get('/admin/domains/create', [$admin, 'domainCreate'], ['auth']);
$router->post('/admin/domains/create', [$admin, 'domainCreate'], ['auth']);
$router->get('/admin/domains/edit', [$admin, 'domainEdit'], ['auth']);
$router->post('/admin/domains/edit', [$admin, 'domainEdit'], ['auth']);
$router->get('/admin/domains/delete', [$admin, 'domainDelete'], ['auth']);

// Spécialités
$router->get('/admin/specialties', [$admin, 'specialties'], ['auth']);
$router->get('/admin/specialties/create', [$admin, 'specialtyCreate'], ['auth']);
$router->post('/admin/specialties/create', [$admin, 'specialtyCreate'], ['auth']);
$router->get('/admin/specialties/edit', [$admin, 'specialtyEdit'], ['auth']);
$router->post('/admin/specialties/edit', [$admin, 'specialtyEdit'], ['auth']);
$router->get('/admin/specialties/delete', [$admin, 'specialtyDelete'], ['auth']);

// Villes
$router->get('/admin/cities', [$admin, 'cities'], ['auth']);
$router->get('/admin/cities/create', [$admin, 'cityCreate'], ['auth']);
$router->post('/admin/cities/create', [$admin, 'cityCreate'], ['auth']);
$router->get('/admin/cities/edit', [$admin, 'cityEdit'], ['auth']);
$router->post('/admin/cities/edit', [$admin, 'cityEdit'], ['auth']);
$router->get('/admin/cities/delete', [$admin, 'cityDelete'], ['auth']);

// Établissements
$router->get('/admin/institutions', [$admin, 'institutions'], ['auth']);
$router->get('/admin/institutions/create', [$admin, 'institutionCreate'], ['auth']);
$router->post('/admin/institutions/create', [$admin, 'institutionCreate'], ['auth']);
$router->get('/admin/institutions/edit', [$admin, 'institutionEdit'], ['auth']);
$router->post('/admin/institutions/edit', [$admin, 'institutionEdit'], ['auth']);
$router->get('/admin/institutions/delete', [$admin, 'institutionDelete'], ['auth']);

// Campus
$router->get('/admin/campuses', [$admin, 'campuses'], ['auth']);
$router->get('/admin/campuses/create', [$admin, 'campusCreate'], ['auth']);
$router->post('/admin/campuses/create', [$admin, 'campusCreate'], ['auth']);
$router->get('/admin/campuses/edit', [$admin, 'campusEdit'], ['auth']);
$router->post('/admin/campuses/edit', [$admin, 'campusEdit'], ['auth']);
$router->get('/admin/campuses/delete', [$admin, 'campusDelete'], ['auth']);

// Formations
$router->get('/admin/programs', [$admin, 'programs'], ['auth']);
$router->get('/admin/programs/create', [$admin, 'programCreate'], ['auth']);
$router->post('/admin/programs/create', [$admin, 'programCreate'], ['auth']);
$router->get('/admin/programs/edit', [$admin, 'programEdit'], ['auth']);
$router->post('/admin/programs/edit', [$admin, 'programEdit'], ['auth']);
$router->get('/admin/programs/delete', [$admin, 'programDelete'], ['auth']);

// Années académiques
$router->get('/admin/academic-years', [$admin, 'academicYears'], ['auth']);
$router->get('/admin/academic-years/create', [$admin, 'academicYearCreate'], ['auth']);
$router->post('/admin/academic-years/create', [$admin, 'academicYearCreate'], ['auth']);
$router->get('/admin/academic-years/edit', [$admin, 'academicYearEdit'], ['auth']);
$router->post('/admin/academic-years/edit', [$admin, 'academicYearEdit'], ['auth']);

// Candidatures
$router->get('/admin/applications', [$admin, 'applications'], ['auth']);
$router->get('/admin/applications/show', [$admin, 'applicationShow'], ['auth']);
$router->post('/admin/applications/status', [$admin, 'applicationUpdateStatus'], ['auth']);

// Import CSV
$csvImport = new CsvImportController();
$router->get('/admin/csv-import', [$csvImport, 'index'], ['auth']);
$router->post('/admin/csv-import/analyze', [$csvImport, 'analyze'], ['auth']);
$router->post('/admin/csv-import/import', [$csvImport, 'import'], ['auth']);

// =====================================================
// DISPATCH
// =====================================================
$router->dispatch();
