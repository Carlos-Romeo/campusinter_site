<?php
/**
 * CAMPUS INTER - Admin Controller
 * Gestion du dashboard et des opérations CRUD admin
 */

declare(strict_types=1);

namespace CampusInter\Controllers;

use CampusInter\Helpers\Csrf;
use CampusInter\Helpers\Logger;

class AdminController extends Controller
{
    /**
     * Dashboard principal
     */
    public function dashboard(): void
    {
        $db = $this->db->getConnection();

        // Statistiques
        $stats = [];

        // Candidatures aujourd'hui
        $stmt = $db->query("SELECT COUNT(*) as count FROM applications WHERE DATE(created_at) = CURDATE()");
        $stats['today'] = (int) $stmt->fetch()['count'];

        // Candidatures cette semaine
        $stmt = $db->query("SELECT COUNT(*) as count FROM applications WHERE YEARWEEK(created_at, 1) = YEARWEEK(CURDATE(), 1)");
        $stats['week'] = (int) $stmt->fetch()['count'];

        // Total candidatures
        $stmt = $db->query("SELECT COUNT(*) as count FROM applications");
        $stats['total'] = (int) $stmt->fetch()['count'];

        // Formations actives
        $stmt = $db->query("SELECT COUNT(*) as count FROM programs WHERE status = 'active'");
        $stats['programs'] = (int) $stmt->fetch()['count'];

        // Établissements
        $stmt = $db->query("SELECT COUNT(*) as count FROM institutions WHERE status = 'active'");
        $stats['institutions'] = (int) $stmt->fetch()['count'];

        // Campus
        $stmt = $db->query("SELECT COUNT(*) as count FROM campuses WHERE status = 'active'");
        $stats['campuses'] = (int) $stmt->fetch()['count'];

        // Dernières candidatures
        $stmt = $db->query("
            SELECT a.*, p.name as program_name, c.name as campus_name
            FROM applications a
            JOIN programs p ON a.program_id = p.id
            JOIN campuses c ON a.campus_id = c.id
            ORDER BY a.created_at DESC
            LIMIT 10
        ");
        $recentApplications = $stmt->fetchAll();

        $this->view('admin/dashboard', [
            'title' => 'Dashboard',
            'stats' => $stats,
            'recentApplications' => $recentApplications,
        ]);
    }

    // =====================================================
    // DOMAINS
    // =====================================================

    public function domains(): void
    {
        $db = $this->db->getConnection();
        $search = $_GET['search'] ?? '';
        
        $sql = "SELECT * FROM domains";
        $params = [];
        
        if ($search) {
            $sql .= " WHERE name LIKE ?";
            $params[] = "%{$search}%";
        }
        
        $sql .= " ORDER BY name ASC";
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $domains = $stmt->fetchAll();

        $this->view('admin/domains/index', [
            'title' => 'Domaines',
            'domains' => $domains,
            'search' => $search,
        ]);
    }

    public function domainCreate(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Csrf::verify()) {
                $this->json(['success' => false, 'message' => 'Token invalide'], 403);
                return;
            }

            $name = trim($_POST['name'] ?? '');
            if (empty($name)) {
                $this->json(['success' => false, 'message' => 'Le nom est requis.'], 400);
                return;
            }

            $slug = $this->slugify($name);
            $db = $this->db->getConnection();

            // Vérifier l'unicité
            $stmt = $db->prepare("SELECT id FROM domains WHERE name = ?");
            $stmt->execute([$name]);
            if ($stmt->fetch()) {
                $this->json(['success' => false, 'message' => 'Ce domaine existe déjà.'], 400);
                return;
            }

            $stmt = $db->prepare("INSERT INTO domains (name, slug, status) VALUES (?, ?, 'active')");
            $stmt->execute([$name, $slug]);

            Logger::info("Domaine créé: {$name}", 'system');
            $this->json(['success' => true, 'message' => 'Domaine créé avec succès.']);
            return;
        }

        $this->view('admin/domains/form', [
            'title' => 'Ajouter un domaine',
            'domain' => null,
        ]);
    }

    public function domainEdit(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $db = $this->db->getConnection();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Csrf::verify()) {
                $this->json(['success' => false, 'message' => 'Token invalide'], 403);
                return;
            }

            $name = trim($_POST['name'] ?? '');
            $status = $_POST['status'] ?? 'active';

            if (empty($name)) {
                $this->json(['success' => false, 'message' => 'Le nom est requis.'], 400);
                return;
            }

            $slug = $this->slugify($name);

            $stmt = $db->prepare("UPDATE domains SET name = ?, slug = ?, status = ? WHERE id = ?");
            $stmt->execute([$name, $slug, $status, $id]);

            Logger::info("Domaine modifié: ID {$id}", 'system');
            $this->json(['success' => true, 'message' => 'Domaine modifié avec succès.']);
            return;
        }

        $stmt = $db->prepare("SELECT * FROM domains WHERE id = ?");
        $stmt->execute([$id]);
        $domain = $stmt->fetch();

        if (!$domain) {
            $this->notFound();
            return;
        }

        $this->view('admin/domains/form', [
            'title' => 'Modifier le domaine',
            'domain' => $domain,
        ]);
    }

    public function domainDelete(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $db = $this->db->getConnection();

        // Vérifier les dépendances
        $stmt = $db->prepare("SELECT COUNT(*) as count FROM specialties WHERE domain_id = ?");
        $stmt->execute([$id]);
        $hasSpecialties = (int) $stmt->fetch()['count'] > 0;

        $stmt = $db->prepare("SELECT COUNT(*) as count FROM programs WHERE domain_id = ?");
        $stmt->execute([$id]);
        $hasPrograms = (int) $stmt->fetch()['count'] > 0;

        if ($hasSpecialties || $hasPrograms) {
            $this->json(['success' => false, 'message' => 'Impossible de supprimer: ce domaine a des dépendances.'], 400);
            return;
        }

        $stmt = $db->prepare("DELETE FROM domains WHERE id = ?");
        $stmt->execute([$id]);

        Logger::info("Domaine supprimé: ID {$id}", 'system');
        $this->json(['success' => true, 'message' => 'Domaine supprimé avec succès.']);
    }

    // =====================================================
    // SPECIALTIES
    // =====================================================

    public function specialties(): void
    {
        $db = $this->db->getConnection();
        $search = $_GET['search'] ?? '';
        $domainId = $_GET['domain_id'] ?? '';
        
        $sql = "SELECT s.*, d.name as domain_name FROM specialties s JOIN domains d ON s.domain_id = d.id";
        $conditions = [];
        $params = [];
        
        if ($search) {
            $conditions[] = "s.name LIKE ?";
            $params[] = "%{$search}%";
        }
        if ($domainId) {
            $conditions[] = "s.domain_id = ?";
            $params[] = (int) $domainId;
        }
        
        if ($conditions) {
            $sql .= " WHERE " . implode(" AND ", $conditions);
        }
        
        $sql .= " ORDER BY d.name, s.name ASC";
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $specialties = $stmt->fetchAll();

        // Pour le filtre
        $stmt = $db->query("SELECT id, name FROM domains WHERE status = 'active' ORDER BY name");
        $domains = $stmt->fetchAll();

        $this->view('admin/specialties/index', [
            'title' => 'Spécialités',
            'specialties' => $specialties,
            'domains' => $domains,
            'search' => $search,
            'domainId' => $domainId,
        ]);
    }

    public function specialtyCreate(): void
    {
        $db = $this->db->getConnection();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Csrf::verify()) {
                $this->json(['success' => false, 'message' => 'Token invalide'], 403);
                return;
            }

            $domainId = (int) ($_POST['domain_id'] ?? 0);
            $name = trim($_POST['name'] ?? '');

            if (empty($name) || $domainId <= 0) {
                $this->json(['success' => false, 'message' => 'Tous les champs sont requis.'], 400);
                return;
            }

            $slug = $this->slugify($name);

            $stmt = $db->prepare("INSERT INTO specialties (domain_id, name, slug, status) VALUES (?, ?, ?, 'active')");
            $stmt->execute([$domainId, $name, $slug]);

            Logger::info("Spécialité créée: {$name}", 'system');
            $this->json(['success' => true, 'message' => 'Spécialité créée avec succès.']);
            return;
        }

        $stmt = $db->query("SELECT id, name FROM domains WHERE status = 'active' ORDER BY name");
        $domains = $stmt->fetchAll();

        $this->view('admin/specialties/form', [
            'title' => 'Ajouter une spécialité',
            'specialty' => null,
            'domains' => $domains,
        ]);
    }

    public function specialtyEdit(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $db = $this->db->getConnection();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Csrf::verify()) {
                $this->json(['success' => false, 'message' => 'Token invalide'], 403);
                return;
            }

            $domainId = (int) ($_POST['domain_id'] ?? 0);
            $name = trim($_POST['name'] ?? '');
            $status = $_POST['status'] ?? 'active';

            if (empty($name) || $domainId <= 0) {
                $this->json(['success' => false, 'message' => 'Tous les champs sont requis.'], 400);
                return;
            }

            $slug = $this->slugify($name);

            $stmt = $db->prepare("UPDATE specialties SET domain_id = ?, name = ?, slug = ?, status = ? WHERE id = ?");
            $stmt->execute([$domainId, $name, $slug, $status, $id]);

            Logger::info("Spécialité modifiée: ID {$id}", 'system');
            $this->json(['success' => true, 'message' => 'Spécialité modifiée avec succès.']);
            return;
        }

        $stmt = $db->prepare("SELECT * FROM specialties WHERE id = ?");
        $stmt->execute([$id]);
        $specialty = $stmt->fetch();

        if (!$specialty) {
            $this->notFound();
            return;
        }

        $stmt = $db->query("SELECT id, name FROM domains WHERE status = 'active' ORDER BY name");
        $domains = $stmt->fetchAll();

        $this->view('admin/specialties/form', [
            'title' => 'Modifier la spécialité',
            'specialty' => $specialty,
            'domains' => $domains,
        ]);
    }

    public function specialtyDelete(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $db = $this->db->getConnection();

        $stmt = $db->prepare("SELECT COUNT(*) as count FROM programs WHERE specialty_id = ?");
        $stmt->execute([$id]);
        $hasPrograms = (int) $stmt->fetch()['count'] > 0;

        if ($hasPrograms) {
            $this->json(['success' => false, 'message' => 'Impossible de supprimer: cette spécialité a des formations.'], 400);
            return;
        }

        $stmt = $db->prepare("DELETE FROM specialties WHERE id = ?");
        $stmt->execute([$id]);

        Logger::info("Spécialité supprimée: ID {$id}", 'system');
        $this->json(['success' => true, 'message' => 'Spécialité supprimée avec succès.']);
    }

    // =====================================================
    // CITIES
    // =====================================================

    public function cities(): void
    {
        $db = $this->db->getConnection();
        $search = $_GET['search'] ?? '';
        
        $sql = "SELECT * FROM cities";
        $params = [];
        
        if ($search) {
            $sql .= " WHERE name LIKE ?";
            $params[] = "%{$search}%";
        }
        
        $sql .= " ORDER BY name ASC";
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $cities = $stmt->fetchAll();

        $this->view('admin/cities/index', [
            'title' => 'Villes',
            'cities' => $cities,
            'search' => $search,
        ]);
    }

    public function cityCreate(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Csrf::verify()) {
                $this->json(['success' => false, 'message' => 'Token invalide'], 403);
                return;
            }

            $name = trim($_POST['name'] ?? '');
            $country = trim($_POST['country'] ?? 'Togo');

            if (empty($name)) {
                $this->json(['success' => false, 'message' => 'Le nom est requis.'], 400);
                return;
            }

            $db = $this->db->getConnection();
            $stmt = $db->prepare("INSERT INTO cities (name, country, status) VALUES (?, ?, 'active')");
            $stmt->execute([$name, $country]);

            Logger::info("Ville créée: {$name}", 'system');
            $this->json(['success' => true, 'message' => 'Ville créée avec succès.']);
            return;
        }

        $this->view('admin/cities/form', [
            'title' => 'Ajouter une ville',
            'city' => null,
        ]);
    }

    public function cityEdit(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $db = $this->db->getConnection();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Csrf::verify()) {
                $this->json(['success' => false, 'message' => 'Token invalide'], 403);
                return;
            }

            $name = trim($_POST['name'] ?? '');
            $country = trim($_POST['country'] ?? 'Togo');
            $status = $_POST['status'] ?? 'active';

            if (empty($name)) {
                $this->json(['success' => false, 'message' => 'Le nom est requis.'], 400);
                return;
            }

            $stmt = $db->prepare("UPDATE cities SET name = ?, country = ?, status = ? WHERE id = ?");
            $stmt->execute([$name, $country, $status, $id]);

            Logger::info("Ville modifiée: ID {$id}", 'system');
            $this->json(['success' => true, 'message' => 'Ville modifiée avec succès.']);
            return;
        }

        $stmt = $db->prepare("SELECT * FROM cities WHERE id = ?");
        $stmt->execute([$id]);
        $city = $stmt->fetch();

        if (!$city) {
            $this->notFound();
            return;
        }

        $this->view('admin/cities/form', [
            'title' => 'Modifier la ville',
            'city' => $city,
        ]);
    }

    public function cityDelete(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $db = $this->db->getConnection();

        $stmt = $db->prepare("SELECT COUNT(*) as count FROM campuses WHERE city_id = ?");
        $stmt->execute([$id]);
        $hasCampuses = (int) $stmt->fetch()['count'] > 0;

        if ($hasCampuses) {
            $this->json(['success' => false, 'message' => 'Impossible de supprimer: cette ville a des campus.'], 400);
            return;
        }

        $stmt = $db->prepare("DELETE FROM cities WHERE id = ?");
        $stmt->execute([$id]);

        Logger::info("Ville supprimée: ID {$id}", 'system');
        $this->json(['success' => true, 'message' => 'Ville supprimée avec succès.']);
    }

    // =====================================================
    // INSTITUTIONS
    // =====================================================

    public function institutions(): void
    {
        $db = $this->db->getConnection();
        $search = $_GET['search'] ?? '';
        
        $sql = "SELECT * FROM institutions";
        $params = [];
        
        if ($search) {
            $sql .= " WHERE name LIKE ?";
            $params[] = "%{$search}%";
        }
        
        $sql .= " ORDER BY name ASC";
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $institutions = $stmt->fetchAll();

        $this->view('admin/institutions/index', [
            'title' => 'Établissements',
            'institutions' => $institutions,
            'search' => $search,
        ]);
    }

    public function institutionCreate(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Csrf::verify()) {
                $this->json(['success' => false, 'message' => 'Token invalide'], 403);
                return;
            }

            $name = trim($_POST['name'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $website = trim($_POST['website'] ?? '');

            if (empty($name)) {
                $this->json(['success' => false, 'message' => 'Le nom est requis.'], 400);
                return;
            }

            $db = $this->db->getConnection();
            $stmt = $db->prepare("INSERT INTO institutions (name, description, website, status) VALUES (?, ?, ?, 'active')");
            $stmt->execute([$name, $description, $website]);

            Logger::info("Institution créée: {$name}", 'system');
            $this->json(['success' => true, 'message' => 'Institution créée avec succès.']);
            return;
        }

        $this->view('admin/institutions/form', [
            'title' => 'Ajouter un établissement',
            'institution' => null,
        ]);
    }

    public function institutionEdit(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $db = $this->db->getConnection();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Csrf::verify()) {
                $this->json(['success' => false, 'message' => 'Token invalide'], 403);
                return;
            }

            $name = trim($_POST['name'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $website = trim($_POST['website'] ?? '');
            $status = $_POST['status'] ?? 'active';

            if (empty($name)) {
                $this->json(['success' => false, 'message' => 'Le nom est requis.'], 400);
                return;
            }

            $stmt = $db->prepare("UPDATE institutions SET name = ?, description = ?, website = ?, status = ? WHERE id = ?");
            $stmt->execute([$name, $description, $website, $status, $id]);

            Logger::info("Institution modifiée: ID {$id}", 'system');
            $this->json(['success' => true, 'message' => 'Institution modifiée avec succès.']);
            return;
        }

        $stmt = $db->prepare("SELECT * FROM institutions WHERE id = ?");
        $stmt->execute([$id]);
        $institution = $stmt->fetch();

        if (!$institution) {
            $this->notFound();
            return;
        }

        $this->view('admin/institutions/form', [
            'title' => 'Modifier l\'établissement',
            'institution' => $institution,
        ]);
    }

    public function institutionDelete(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $db = $this->db->getConnection();

        $stmt = $db->prepare("SELECT COUNT(*) as count FROM campuses WHERE institution_id = ?");
        $stmt->execute([$id]);
        $hasCampuses = (int) $stmt->fetch()['count'] > 0;

        if ($hasCampuses) {
            $this->json(['success' => false, 'message' => 'Impossible de supprimer: cet établissement a des campus.'], 400);
            return;
        }

        $stmt = $db->prepare("DELETE FROM institutions WHERE id = ?");
        $stmt->execute([$id]);

        Logger::info("Institution supprimée: ID {$id}", 'system');
        $this->json(['success' => true, 'message' => 'Établissement supprimé avec succès.']);
    }

    // =====================================================
    // CAMPUSES
    // =====================================================

    public function campuses(): void
    {
        $db = $this->db->getConnection();
        $search = $_GET['search'] ?? '';
        
        $sql = "SELECT c.*, i.name as institution_name, ci.name as city_name 
                FROM campuses c 
                JOIN institutions i ON c.institution_id = i.id 
                JOIN cities ci ON c.city_id = ci.id";
        $params = [];
        
        if ($search) {
            $sql .= " WHERE c.name LIKE ?";
            $params[] = "%{$search}%";
        }
        
        $sql .= " ORDER BY i.name, c.name ASC";
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $campuses = $stmt->fetchAll();

        $this->view('admin/campuses/index', [
            'title' => 'Campus',
            'campuses' => $campuses,
            'search' => $search,
        ]);
    }

    public function campusCreate(): void
    {
        $db = $this->db->getConnection();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Csrf::verify()) {
                $this->json(['success' => false, 'message' => 'Token invalide'], 403);
                return;
            }

            $institutionId = (int) ($_POST['institution_id'] ?? 0);
            $cityId = (int) ($_POST['city_id'] ?? 0);
            $name = trim($_POST['name'] ?? '');
            $address = trim($_POST['address'] ?? '');

            if (empty($name) || $institutionId <= 0 || $cityId <= 0) {
                $this->json(['success' => false, 'message' => 'Tous les champs obligatoires sont requis.'], 400);
                return;
            }

            $stmt = $db->prepare("INSERT INTO campuses (institution_id, city_id, name, address, status) VALUES (?, ?, ?, ?, 'active')");
            $stmt->execute([$institutionId, $cityId, $name, $address]);

            Logger::info("Campus créé: {$name}", 'system');
            $this->json(['success' => true, 'message' => 'Campus créé avec succès.']);
            return;
        }

        $stmt = $db->query("SELECT id, name FROM institutions WHERE status = 'active' ORDER BY name");
        $institutions = $stmt->fetchAll();

        $stmt = $db->query("SELECT id, name FROM cities WHERE status = 'active' ORDER BY name");
        $cities = $stmt->fetchAll();

        $this->view('admin/campuses/form', [
            'title' => 'Ajouter un campus',
            'campus' => null,
            'institutions' => $institutions,
            'cities' => $cities,
        ]);
    }

    public function campusEdit(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $db = $this->db->getConnection();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Csrf::verify()) {
                $this->json(['success' => false, 'message' => 'Token invalide'], 403);
                return;
            }

            $institutionId = (int) ($_POST['institution_id'] ?? 0);
            $cityId = (int) ($_POST['city_id'] ?? 0);
            $name = trim($_POST['name'] ?? '');
            $address = trim($_POST['address'] ?? '');
            $status = $_POST['status'] ?? 'active';

            if (empty($name) || $institutionId <= 0 || $cityId <= 0) {
                $this->json(['success' => false, 'message' => 'Tous les champs obligatoires sont requis.'], 400);
                return;
            }

            $stmt = $db->prepare("UPDATE campuses SET institution_id = ?, city_id = ?, name = ?, address = ?, status = ? WHERE id = ?");
            $stmt->execute([$institutionId, $cityId, $name, $address, $status, $id]);

            Logger::info("Campus modifié: ID {$id}", 'system');
            $this->json(['success' => true, 'message' => 'Campus modifié avec succès.']);
            return;
        }

        $stmt = $db->prepare("SELECT * FROM campuses WHERE id = ?");
        $stmt->execute([$id]);
        $campus = $stmt->fetch();

        if (!$campus) {
            $this->notFound();
            return;
        }

        $stmt = $db->query("SELECT id, name FROM institutions WHERE status = 'active' ORDER BY name");
        $institutions = $stmt->fetchAll();

        $stmt = $db->query("SELECT id, name FROM cities WHERE status = 'active' ORDER BY name");
        $cities = $stmt->fetchAll();

        $this->view('admin/campuses/form', [
            'title' => 'Modifier le campus',
            'campus' => $campus,
            'institutions' => $institutions,
            'cities' => $cities,
        ]);
    }

    public function campusDelete(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $db = $this->db->getConnection();

        $stmt = $db->prepare("SELECT COUNT(*) as count FROM program_campuses WHERE campus_id = ?");
        $stmt->execute([$id]);
        $hasPrograms = (int) $stmt->fetch()['count'] > 0;

        if ($hasPrograms) {
            $this->json(['success' => false, 'message' => 'Impossible de supprimer: ce campus a des formations.'], 400);
            return;
        }

        $stmt = $db->prepare("DELETE FROM campuses WHERE id = ?");
        $stmt->execute([$id]);

        Logger::info("Campus supprimé: ID {$id}", 'system');
        $this->json(['success' => true, 'message' => 'Campus supprimé avec succès.']);
    }

    // =====================================================
    // PROGRAMS
    // =====================================================

    public function programs(): void
    {
        $db = $this->db->getConnection();
        $search = $_GET['search'] ?? '';
        $domainId = $_GET['domain_id'] ?? '';
        $level = $_GET['level'] ?? '';
        
        $sql = "SELECT p.*, d.name as domain_name, s.name as specialty_name, ay.name as academic_year_name
                FROM programs p
                JOIN domains d ON p.domain_id = d.id
                LEFT JOIN specialties s ON p.specialty_id = s.id
                JOIN academic_years ay ON p.academic_year_id = ay.id";
        $conditions = [];
        $params = [];
        
        if ($search) {
            $conditions[] = "p.name LIKE ?";
            $params[] = "%{$search}%";
        }
        if ($domainId) {
            $conditions[] = "p.domain_id = ?";
            $params[] = (int) $domainId;
        }
        if ($level) {
            $conditions[] = "p.level = ?";
            $params[] = $level;
        }
        
        if ($conditions) {
            $sql .= " WHERE " . implode(" AND ", $conditions);
        }
        
        $sql .= " ORDER BY p.name ASC";
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $programs = $stmt->fetchAll();

        $stmt = $db->query("SELECT id, name FROM domains WHERE status = 'active' ORDER BY name");
        $domains = $stmt->fetchAll();

        $this->view('admin/programs/index', [
            'title' => 'Formations',
            'programs' => $programs,
            'domains' => $domains,
            'search' => $search,
            'domainId' => $domainId,
            'level' => $level,
        ]);
    }

    public function programCreate(): void
    {
        $db = $this->db->getConnection();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Csrf::verify()) {
                $this->json(['success' => false, 'message' => 'Token invalide'], 403);
                return;
            }

            $academicYearId = (int) ($_POST['academic_year_id'] ?? 0);
            $domainId = (int) ($_POST['domain_id'] ?? 0);
            $specialtyId = (int) ($_POST['specialty_id'] ?? 0) ?: null;
            $name = trim($_POST['name'] ?? '');
            $level = $_POST['level'] ?? '';
            $description = trim($_POST['description'] ?? '');
            $duration = trim($_POST['duration'] ?? '');
            $campusIds = $_POST['campus_ids'] ?? [];

            if (empty($name) || $academicYearId <= 0 || $domainId <= 0 || empty($level)) {
                $this->json(['success' => false, 'message' => 'Tous les champs obligatoires sont requis.'], 400);
                return;
            }

            $stmt = $db->prepare("
                INSERT INTO programs (academic_year_id, domain_id, specialty_id, name, level, description, duration, status)
                VALUES (?, ?, ?, ?, ?, ?, ?, 'active')
            ");
            $stmt->execute([$academicYearId, $domainId, $specialtyId, $name, $level, $description, $duration]);
            $programId = $db->getConnection()->lastInsertId();

            // Associer aux campus
            if (!empty($campusIds)) {
                $placeholders = [];
                $values = [];
                foreach ($campusIds as $campusId) {
                    $placeholders[] = "(?, ?, ?, 'active')";
                    $values[] = $programId;
                    $values[] = (int) $campusId;
                    $values[] = $academicYearId;
                }
                $sql = "INSERT INTO program_campuses (program_id, campus_id, academic_year_id, status) VALUES " . implode(', ', $placeholders);
                $stmt = $db->prepare($sql);
                $stmt->execute($values);
            }

            Logger::info("Formation créée: {$name}", 'system');
            $this->json(['success' => true, 'message' => 'Formation créée avec succès.']);
            return;
        }

        $stmt = $db->query("SELECT id, name FROM academic_years ORDER BY name DESC");
        $academicYears = $stmt->fetchAll();

        $stmt = $db->query("SELECT id, name FROM domains WHERE status = 'active' ORDER BY name");
        $domains = $stmt->fetchAll();

        $stmt = $db->query("SELECT c.id, c.name, i.name as institution_name FROM campuses c JOIN institutions i ON c.institution_id = i.id WHERE c.status = 'active' ORDER BY i.name, c.name");
        $campuses = $stmt->fetchAll();

        $this->view('admin/programs/form', [
            'title' => 'Ajouter une formation',
            'program' => null,
            'academicYears' => $academicYears,
            'domains' => $domains,
            'campuses' => $campuses,
        ]);
    }

    public function programEdit(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $db = $this->db->getConnection();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Csrf::verify()) {
                $this->json(['success' => false, 'message' => 'Token invalide'], 403);
                return;
            }

            $academicYearId = (int) ($_POST['academic_year_id'] ?? 0);
            $domainId = (int) ($_POST['domain_id'] ?? 0);
            $specialtyId = (int) ($_POST['specialty_id'] ?? 0) ?: null;
            $name = trim($_POST['name'] ?? '');
            $level = $_POST['level'] ?? '';
            $description = trim($_POST['description'] ?? '');
            $duration = trim($_POST['duration'] ?? '');
            $status = $_POST['status'] ?? 'active';
            $campusIds = $_POST['campus_ids'] ?? [];

            if (empty($name) || $academicYearId <= 0 || $domainId <= 0 || empty($level)) {
                $this->json(['success' => false, 'message' => 'Tous les champs obligatoires sont requis.'], 400);
                return;
            }

            $stmt = $db->prepare("
                UPDATE programs SET academic_year_id = ?, domain_id = ?, specialty_id = ?, name = ?, level = ?, description = ?, duration = ?, status = ?
                WHERE id = ?
            ");
            $stmt->execute([$academicYearId, $domainId, $specialtyId, $name, $level, $description, $duration, $status, $id]);

            // Mettre à jour les campus
            $stmt = $db->prepare("DELETE FROM program_campuses WHERE program_id = ?");
            $stmt->execute([$id]);

            if (!empty($campusIds)) {
                $placeholders = [];
                $values = [];
                foreach ($campusIds as $campusId) {
                    $placeholders[] = "(?, ?, ?, 'active')";
                    $values[] = $id;
                    $values[] = (int) $campusId;
                    $values[] = $academicYearId;
                }
                $sql = "INSERT INTO program_campuses (program_id, campus_id, academic_year_id, status) VALUES " . implode(', ', $placeholders);
                $stmt = $db->prepare($sql);
                $stmt->execute($values);
            }

            Logger::info("Formation modifiée: ID {$id}", 'system');
            $this->json(['success' => true, 'message' => 'Formation modifiée avec succès.']);
            return;
        }

        $stmt = $db->prepare("SELECT * FROM programs WHERE id = ?");
        $stmt->execute([$id]);
        $program = $stmt->fetch();

        if (!$program) {
            $this->notFound();
            return;
        }

        // Campus associés
        $stmt = $db->prepare("SELECT campus_id FROM program_campuses WHERE program_id = ?");
        $stmt->execute([$id]);
        $programCampuses = $stmt->fetchAll(PDO::FETCH_COLUMN);

        $stmt = $db->query("SELECT id, name FROM academic_years ORDER BY name DESC");
        $academicYears = $stmt->fetchAll();

        $stmt = $db->query("SELECT id, name FROM domains WHERE status = 'active' ORDER BY name");
        $domains = $stmt->fetchAll();

        $stmt = $db->query("SELECT c.id, c.name, i.name as institution_name FROM campuses c JOIN institutions i ON c.institution_id = i.id WHERE c.status = 'active' ORDER BY i.name, c.name");
        $campuses = $stmt->fetchAll();

        $this->view('admin/programs/form', [
            'title' => 'Modifier la formation',
            'program' => $program,
            'programCampuses' => $programCampuses,
            'academicYears' => $academicYears,
            'domains' => $domains,
            'campuses' => $campuses,
        ]);
    }

    public function programDelete(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $db = $this->db->getConnection();

        $stmt = $db->prepare("SELECT COUNT(*) as count FROM applications WHERE program_id = ?");
        $stmt->execute([$id]);
        $hasApplications = (int) $stmt->fetch()['count'] > 0;

        if ($hasApplications) {
            $this->json(['success' => false, 'message' => 'Impossible de supprimer: cette formation a des candidatures.'], 400);
            return;
        }

        $stmt = $db->prepare("DELETE FROM program_campuses WHERE program_id = ?");
        $stmt->execute([$id]);

        $stmt = $db->prepare("DELETE FROM programs WHERE id = ?");
        $stmt->execute([$id]);

        Logger::info("Formation supprimée: ID {$id}", 'system');
        $this->json(['success' => true, 'message' => 'Formation supprimée avec succès.']);
    }

    // =====================================================
    // ACADEMIC YEARS
    // =====================================================

    public function academicYears(): void
    {
        $db = $this->db->getConnection();
        $stmt = $db->query("SELECT * FROM academic_years ORDER BY name DESC");
        $years = $stmt->fetchAll();

        $this->view('admin/academic-years/index', [
            'title' => 'Années académiques',
            'years' => $years,
        ]);
    }

    public function academicYearCreate(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Csrf::verify()) {
                $this->json(['success' => false, 'message' => 'Token invalide'], 403);
                return;
            }

            $name = trim($_POST['name'] ?? '');
            $isActive = isset($_POST['is_active']) ? 1 : 0;

            if (empty($name)) {
                $this->json(['success' => false, 'message' => 'Le nom est requis.'], 400);
                return;
            }

            $db = $this->db->getConnection();

            // Si c'est l'année active, désactiver les autres
            if ($isActive) {
                $db->exec("UPDATE academic_years SET is_active = 0");
            }

            $stmt = $db->prepare("INSERT INTO academic_years (name, is_active) VALUES (?, ?)");
            $stmt->execute([$name, $isActive]);

            Logger::info("Année académique créée: {$name}", 'system');
            $this->json(['success' => true, 'message' => 'Année académique créée avec succès.']);
            return;
        }

        $this->view('admin/academic-years/form', [
            'title' => 'Ajouter une année académique',
            'year' => null,
        ]);
    }

    public function academicYearEdit(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $db = $this->db->getConnection();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Csrf::verify()) {
                $this->json(['success' => false, 'message' => 'Token invalide'], 403);
                return;
            }

            $name = trim($_POST['name'] ?? '');
            $isActive = isset($_POST['is_active']) ? 1 : 0;

            if (empty($name)) {
                $this->json(['success' => false, 'message' => 'Le nom est requis.'], 400);
                return;
            }

            // Si c'est l'année active, désactiver les autres
            if ($isActive) {
                $db->exec("UPDATE academic_years SET is_active = 0");
            }

            $stmt = $db->prepare("UPDATE academic_years SET name = ?, is_active = ? WHERE id = ?");
            $stmt->execute([$name, $isActive, $id]);

            Logger::info("Année académique modifiée: ID {$id}", 'system');
            $this->json(['success' => true, 'message' => 'Année académique modifiée avec succès.']);
            return;
        }

        $stmt = $db->prepare("SELECT * FROM academic_years WHERE id = ?");
        $stmt->execute([$id]);
        $year = $stmt->fetch();

        if (!$year) {
            $this->notFound();
            return;
        }

        $this->view('admin/academic-years/form', [
            'title' => 'Modifier l\'année académique',
            'year' => $year,
        ]);
    }

    // =====================================================
    // APPLICATIONS
    // =====================================================

    public function applications(): void
    {
        $db = $this->db->getConnection();
        $search = $_GET['search'] ?? '';
        $status = $_GET['status'] ?? '';
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $perPage = 20;
        $offset = ($page - 1) * $perPage;

        $sql = "SELECT a.*, p.name as program_name, c.name as campus_name, ay.name as academic_year_name
                FROM applications a
                JOIN programs p ON a.program_id = p.id
                JOIN campuses c ON a.campus_id = c.id
                JOIN academic_years ay ON a.academic_year_id = ay.id";
        $conditions = [];
        $params = [];

        if ($search) {
            $conditions[] = "(a.reference LIKE ? OR a.first_name LIKE ? OR a.last_name LIKE ? OR a.email LIKE ?)";
            $params[] = "%{$search}%";
            $params[] = "%{$search}%";
            $params[] = "%{$search}%";
            $params[] = "%{$search}%";
        }
        if ($status) {
            $conditions[] = "a.status = ?";
            $params[] = $status;
        }

        if ($conditions) {
            $sql .= " WHERE " . implode(" AND ", $conditions);
        }

        // Compter le total
        $countSql = "SELECT COUNT(*) as total FROM applications a JOIN programs p ON a.program_id = p.id JOIN campuses c ON a.campus_id = c.id JOIN academic_years ay ON a.academic_year_id = ay.id";
        if ($conditions) {
            $countSql .= " WHERE " . implode(" AND ", $conditions);
        }
        $stmt = $db->prepare($countSql);
        $stmt->execute($params);
        $total = (int) $stmt->fetch()['total'];
        $totalPages = ceil($total / $perPage);

        $sql .= " ORDER BY a.created_at DESC LIMIT ? OFFSET ?";
        $params[] = $perPage;
        $params[] = $offset;
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $applications = $stmt->fetchAll();

        $this->view('admin/applications/index', [
            'title' => 'Candidatures',
            'applications' => $applications,
            'search' => $search,
            'status' => $status,
            'page' => $page,
            'totalPages' => $totalPages,
            'total' => $total,
        ]);
    }

    public function applicationShow(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $db = $this->db->getConnection();

        $stmt = $db->prepare("
            SELECT a.*, p.name as program_name, p.level as program_level, p.description as program_description,
                   c.name as campus_name, c.address as campus_address,
                   i.name as institution_name,
                   ci.name as city_name,
                   ay.name as academic_year_name
            FROM applications a
            JOIN programs p ON a.program_id = p.id
            JOIN campuses c ON a.campus_id = c.id
            JOIN institutions i ON c.institution_id = i.id
            JOIN cities ci ON c.city_id = ci.id
            JOIN academic_years ay ON a.academic_year_id = ay.id
            WHERE a.id = ?
        ");
        $stmt->execute([$id]);
        $application = $stmt->fetch();

        if (!$application) {
            $this->notFound();
            return;
        }

        $this->view('admin/applications/show', [
            'title' => 'Détail candidature',
            'application' => $application,
        ]);
    }

    public function applicationUpdateStatus(): void
    {
        $id = (int) ($_POST['id'] ?? 0);
        $status = $_POST['status'] ?? '';

        $validStatuses = ['new', 'processing', 'accepted', 'rejected', 'archived'];
        if (!in_array($status, $validStatuses)) {
            $this->json(['success' => false, 'message' => 'Statut invalide.'], 400);
            return;
        }

        $db = $this->db->getConnection();
        $stmt = $db->prepare("UPDATE applications SET status = ? WHERE id = ?");
        $stmt->execute([$status, $id]);

        Logger::info("Statut candidature mis à jour: ID {$id} → {$status}", 'system');
        $this->json(['success' => true, 'message' => 'Statut mis à jour avec succès.']);
    }

    // =====================================================
    // UTILITAIRES
    // =====================================================

    private function slugify(string $text): string
    {
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);
        $text = preg_replace('~[^-\w]+~', '', $text);
        $text = trim($text, '-');
        $text = preg_replace('~-+~', '-', $text);
        return strtolower($text);
    }
}
