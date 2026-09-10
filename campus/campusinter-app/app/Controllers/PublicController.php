<?php
/**
 * CAMPUS INTER - Public Controller
 * Pages publiques: recherche, formation, candidature
 */

declare(strict_types=1);

namespace CampusInter\Controllers;

use CampusInter\Helpers\Csrf;
use CampusInter\Helpers\RateLimiter;
use CampusInter\Helpers\Logger;
use CampusInter\Mail\Mailer;

class PublicController extends Controller
{
    /**
     * Page d'accueil
     */
    public function home(): void
    {
        $db = $this->db->getConnection();

        // Année active
        $stmt = $db->query("SELECT id, name FROM academic_years WHERE is_active = 1 LIMIT 1");
        $activeYear = $stmt->fetch();

        $this->view('public/home', [
            'title' => 'Trouvez votre formation',
            'activeYear' => $activeYear,
        ]);
    }

    /**
     * API: Obtenir les niveaux disponibles
     */
    public function apiLevels(): void
    {
        $db = $this->db->getConnection();
        $yearId = (int) ($_GET['year_id'] ?? 0);

        if ($yearId <= 0) {
            // Prendre l'année active
            $stmt = $db->query("SELECT id FROM academic_years WHERE is_active = 1 LIMIT 1");
            $year = $stmt->fetch();
            $yearId = $year ? (int) $year['id'] : 0;
        }

        $sql = "SELECT DISTINCT p.level 
                FROM programs p 
                JOIN program_campuses pc ON p.id = pc.program_id 
                WHERE p.status = 'active' AND pc.status = 'active'";
        $params = [];

        if ($yearId > 0) {
            $sql .= " AND p.academic_year_id = ?";
            $params[] = $yearId;
        }

        $sql .= " ORDER BY FIELD(p.level, 'Bac', 'Bac+1', 'Bac+2', 'Bac+3', 'Bac+4', 'Bac+5', 'Doctorat', 'Autre')";
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $levelsRaw = $stmt->fetchAll(\PDO::FETCH_COLUMN);
        $levels = array_map(fn($l) => ['id' => $l, 'name' => $l], $levelsRaw);

        $this->json(['success' => true, 'data' => $levels]);
    }

    /**
     * API: Obtenir les domaines pour un niveau donné
     */
    public function apiDomains(): void
    {
        $db = $this->db->getConnection();
        $level = $_GET['level'] ?? '';
        $yearId = (int) ($_GET['year_id'] ?? 0);

        if ($yearId <= 0) {
            $stmt = $db->query("SELECT id FROM academic_years WHERE is_active = 1 LIMIT 1");
            $year = $stmt->fetch();
            $yearId = $year ? (int) $year['id'] : 0;
        }

        $sql = "SELECT DISTINCT d.id, d.name 
                FROM domains d 
                JOIN programs p ON d.id = p.domain_id 
                JOIN program_campuses pc ON p.id = pc.program_id 
                WHERE d.status = 'active' AND p.status = 'active' AND pc.status = 'active'";
        $params = [];

        if ($level) {
            $sql .= " AND p.level = ?";
            $params[] = $level;
        }

        if ($yearId > 0) {
            $sql .= " AND p.academic_year_id = ?";
            $params[] = $yearId;
        }

        $sql .= " ORDER BY d.name ASC";
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $domains = $stmt->fetchAll();

        $this->json(['success' => true, 'data' => $domains]);
    }

    /**
     * API: Obtenir les spécialités pour un domaine donné
     */
    public function apiSpecialties(): void
    {
        $db = $this->db->getConnection();
        $domainId = (int) ($_GET['domain_id'] ?? 0);
        $level = $_GET['level'] ?? '';
        $yearId = (int) ($_GET['year_id'] ?? 0);

        if ($yearId <= 0) {
            $stmt = $db->query("SELECT id FROM academic_years WHERE is_active = 1 LIMIT 1");
            $year = $stmt->fetch();
            $yearId = $year ? (int) $year['id'] : 0;
        }

        $sql = "SELECT DISTINCT s.id, s.name 
                FROM specialties s 
                JOIN programs p ON s.id = p.specialty_id 
                JOIN program_campuses pc ON p.id = pc.program_id 
                WHERE s.status = 'active' AND p.status = 'active' AND pc.status = 'active'";
        $params = [];

        if ($domainId > 0) {
            $sql .= " AND s.domain_id = ?";
            $params[] = $domainId;
        }

        if ($level) {
            $sql .= " AND p.level = ?";
            $params[] = $level;
        }

        if ($yearId > 0) {
            $sql .= " AND p.academic_year_id = ?";
            $params[] = $yearId;
        }

        $sql .= " ORDER BY s.name ASC";
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $specialties = $stmt->fetchAll();

        $this->json(['success' => true, 'data' => $specialties]);
    }

    /**
     * API: Obtenir les villes disponibles
     */
    public function apiCities(): void
    {
        $db = $this->db->getConnection();
        $level = $_GET['level'] ?? '';
        $domainId = (int) ($_GET['domain_id'] ?? 0);
        $specialtyId = (int) ($_GET['specialty_id'] ?? 0);
        $yearId = (int) ($_GET['year_id'] ?? 0);

        if ($yearId <= 0) {
            $stmt = $db->query("SELECT id FROM academic_years WHERE is_active = 1 LIMIT 1");
            $year = $stmt->fetch();
            $yearId = $year ? (int) $year['id'] : 0;
        }

        $sql = "SELECT DISTINCT ci.id, ci.name 
                FROM cities ci 
                JOIN campuses c ON ci.id = c.city_id 
                JOIN program_campuses pc ON c.id = pc.campus_id 
                JOIN programs p ON pc.program_id = p.id
                WHERE ci.status = 'active' AND c.status = 'active' AND pc.status = 'active' AND p.status = 'active'";
        $params = [];

        if ($level) {
            $sql .= " AND p.level = ?";
            $params[] = $level;
        }

        if ($domainId > 0) {
            $sql .= " AND p.domain_id = ?";
            $params[] = $domainId;
        }

        if ($specialtyId > 0) {
            $sql .= " AND p.specialty_id = ?";
            $params[] = $specialtyId;
        }

        if ($yearId > 0) {
            $sql .= " AND p.academic_year_id = ?";
            $params[] = $yearId;
        }

        $sql .= " ORDER BY ci.name ASC";
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $cities = $stmt->fetchAll();

        $this->json(['success' => true, 'data' => $cities]);
    }

    /**
     * API: Rechercher des formations
     */
    public function apiSearch(): void
    {
        $db = $this->db->getConnection();

        $level = $_GET['level'] ?? '';
        $domainId = (int) ($_GET['domain_id'] ?? 0);
        $specialtyId = (int) ($_GET['specialty_id'] ?? 0);
        $cityId = (int) ($_GET['city_id'] ?? 0);
        $yearId = (int) ($_GET['year_id'] ?? 0);
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $perPage = 12;
        $offset = ($page - 1) * $perPage;

        if ($yearId <= 0) {
            $stmt = $db->query("SELECT id FROM academic_years WHERE is_active = 1 LIMIT 1");
            $year = $stmt->fetch();
            $yearId = $year ? (int) $year['id'] : 0;
        }

        if ($yearId <= 0) {
            $this->json(['success' => true, 'data' => [], 'total' => 0, 'totalPages' => 0]);
            return;
        }

        // Construire la requête
        $sql = "SELECT DISTINCT p.id, p.name, p.level, p.description, p.duration,
                       d.name as domain_name, s.name as specialty_name,
                       COUNT(DISTINCT pc.campus_id) as campus_count
                FROM programs p
                JOIN domains d ON p.domain_id = d.id
                LEFT JOIN specialties s ON p.specialty_id = s.id
                JOIN program_campuses pc ON p.id = pc.program_id
                JOIN campuses c ON pc.campus_id = c.id";
        $conditions = ["p.status = 'active'", "pc.status = 'active'", "c.status = 'active'"];
        $params = [];

        $conditions[] = "p.academic_year_id = ?";
        $params[] = $yearId;

        if ($level) {
            $conditions[] = "p.level = ?";
            $params[] = $level;
        }

        if ($domainId > 0) {
            $conditions[] = "p.domain_id = ?";
            $params[] = $domainId;
        }

        if ($specialtyId > 0) {
            $conditions[] = "p.specialty_id = ?";
            $params[] = $specialtyId;
        }

        if ($cityId > 0) {
            $conditions[] = "c.city_id = ?";
            $params[] = $cityId;
        }

        $sql .= " WHERE " . implode(" AND ", $conditions);
        $sql .= " GROUP BY p.id, p.name, p.level, p.description, p.duration, d.name, s.name";

        // Compter le total
        $countSql = "SELECT COUNT(*) as total FROM ({$sql}) as sub";
        $stmt = $db->prepare($countSql);
        $stmt->execute($params);
        $total = (int) $stmt->fetch()['total'];
        $totalPages = (int) ceil($total / $perPage);

        // Paginer
        $sql .= " ORDER BY p.name ASC LIMIT ? OFFSET ?";
        $params[] = $perPage;
        $params[] = $offset;
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $programs = $stmt->fetchAll();

        // Pour chaque formation, récupérer les campus
        foreach ($programs as &$program) {
            $stmt = $db->prepare("
                SELECT c.name as campus_name, c.address, i.name as institution_name, ci.name as city_name
                FROM program_campuses pc
                JOIN campuses c ON pc.campus_id = c.id
                JOIN institutions i ON c.institution_id = i.id
                JOIN cities ci ON c.city_id = ci.id
                WHERE pc.program_id = ? AND pc.academic_year_id = ? AND pc.status = 'active' AND c.status = 'active'
            ");
            $stmt->execute([$program['id'], $yearId]);
            $program['campuses'] = $stmt->fetchAll();
        }
        unset($program);

        $this->json([
            'success' => true,
            'data' => $programs,
            'total' => $total,
            'totalPages' => $totalPages,
            'page' => $page,
        ]);
    }

    /**
     * Page détail d'une formation
     */
    public function programDetail(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $db = $this->db->getConnection();

        // Année active
        $stmt = $db->query("SELECT id, name FROM academic_years WHERE is_active = 1 LIMIT 1");
        $activeYear = $stmt->fetch();

        if (!$activeYear) {
            $this->notFound();
            return;
        }

        $stmt = $db->prepare("
            SELECT p.*, d.name as domain_name, s.name as specialty_name
            FROM programs p
            JOIN domains d ON p.domain_id = d.id
            LEFT JOIN specialties s ON p.specialty_id = s.id
            WHERE p.id = ? AND p.status = 'active' AND p.academic_year_id = ?
        ");
        $stmt->execute([$id, $activeYear['id']]);
        $program = $stmt->fetch();

        if (!$program) {
            $this->notFound();
            return;
        }

        // Campus associés
        $stmt = $db->prepare("
            SELECT c.*, i.name as institution_name, ci.name as city_name, pc.id as pivot_id
            FROM program_campuses pc
            JOIN campuses c ON pc.campus_id = c.id
            JOIN institutions i ON c.institution_id = i.id
            JOIN cities ci ON c.city_id = ci.id
            WHERE pc.program_id = ? AND pc.academic_year_id = ? AND pc.status = 'active' AND c.status = 'active'
        ");
        $stmt->execute([$program['id'], $activeYear['id']]);
        $campuses = $stmt->fetchAll();

        $this->view('public/program-detail', [
            'title' => $program['name'],
            'program' => $program,
            'campuses' => $campuses,
            'activeYear' => $activeYear,
        ]);
    }

    /**
     * Page formulaire de préinscription
     */
    public function applicationForm(): void
    {
        $programId = (int) ($_GET['program_id'] ?? 0);
        $campusId = (int) ($_GET['campus_id'] ?? 0);
        $db = $this->db->getConnection();

        // Année active
        $stmt = $db->query("SELECT id, name FROM academic_years WHERE is_active = 1 LIMIT 1");
        $activeYear = $stmt->fetch();

        if (!$activeYear || $programId <= 0 || $campusId <= 0) {
            $this->notFound();
            return;
        }

        // Vérifier que la formation existe et est active
        $stmt = $db->prepare("
            SELECT p.*, d.name as domain_name, s.name as specialty_name
            FROM programs p
            JOIN domains d ON p.domain_id = d.id
            LEFT JOIN specialties s ON p.specialty_id = s.id
            WHERE p.id = ? AND p.status = 'active' AND p.academic_year_id = ?
        ");
        $stmt->execute([$programId, $activeYear['id']]);
        $program = $stmt->fetch();

        if (!$program) {
            $this->notFound();
            return;
        }

        // Vérifier que le campus est associé à cette formation
        $stmt = $db->prepare("
            SELECT c.*, i.name as institution_name, ci.name as city_name
            FROM program_campuses pc
            JOIN campuses c ON pc.campus_id = c.id
            JOIN institutions i ON c.institution_id = i.id
            JOIN cities ci ON c.city_id = ci.id
            WHERE pc.program_id = ? AND pc.campus_id = ? AND pc.academic_year_id = ? AND pc.status = 'active'
        ");
        $stmt->execute([$programId, $campusId, $activeYear['id']]);
        $campus = $stmt->fetch();

        if (!$campus) {
            $this->notFound();
            return;
        }

        $this->view('public/application-form', [
            'title' => 'Préinscription',
            'program' => $program,
            'campus' => $campus,
            'activeYear' => $activeYear,
        ]);
    }

    /**
     * API: Soumettre une candidature
     */
    public function apiSubmitApplication(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Méthode non autorisée.'], 405);
            return;
        }

        // Rate limiting
        if (!RateLimiter::check('application', 10, 600)) {
            $this->json(['success' => false, 'message' => 'Trop de tentatives. Veuillez réessayer dans 10 minutes.'], 429);
            return;
        }

        // CSRF
        if (!Csrf::verify($_SERVER['HTTP_X_CSRF_TOKEN'] ?? '')) {
            $this->json(['success' => false, 'message' => 'Token de sécurité invalide.'], 403);
            return;
        }

        $data = $this->getJsonInput();

        // Honeypot
        if (!empty($data['website'] ?? $_POST['website'] ?? '')) {
            // Fausse réponse pour les bots
            $this->json(['success' => true, 'message' => 'Votre candidature a été enregistrée.', 'reference' => 'CI-SPAM-000000']);
            return;
        }

        // Validation
        $errors = $this->validateApplication($data);
        if (!empty($errors)) {
            $this->json(['success' => false, 'message' => 'Veuillez corriger les erreurs.', 'errors' => $errors], 400);
            return;
        }

        $db = $this->db->getConnection();

        // Vérifier année active
        $stmt = $db->query("SELECT id, name FROM academic_years WHERE is_active = 1 LIMIT 1");
        $activeYear = $stmt->fetch();
        if (!$activeYear) {
            $this->json(['success' => false, 'message' => 'Aucune année académique active.'], 400);
            return;
        }

        // Vérifier formation
        $programId = (int) ($data['program_id'] ?? 0);
        $campusId = (int) ($data['campus_id'] ?? 0);

        $stmt = $db->prepare("SELECT * FROM programs WHERE id = ? AND status = 'active' AND academic_year_id = ?");
        $stmt->execute([$programId, $activeYear['id']]);
        $program = $stmt->fetch();

        if (!$program) {
            $this->json(['success' => false, 'message' => 'Formation non disponible.'], 400);
            return;
        }

        // Vérifier campus
        $stmt = $db->prepare("SELECT c.*, i.name as institution_name, ci.name as city_name FROM program_campuses pc JOIN campuses c ON pc.campus_id = c.id JOIN institutions i ON c.institution_id = i.id JOIN cities ci ON c.city_id = ci.id WHERE pc.program_id = ? AND pc.campus_id = ? AND pc.academic_year_id = ? AND pc.status = 'active'");
        $stmt->execute([$programId, $campusId, $activeYear['id']]);
        $campus = $stmt->fetch();

        if (!$campus) {
            $this->json(['success' => false, 'message' => 'Ce campus ne propose pas cette formation.'], 400);
            return;
        }

        // Générer la référence
        $reference = $this->generateReference($db, $activeYear['name']);

        // Nettoyer les données
        $cleanData = [
            'reference' => $reference,
            'academic_year_id' => $activeYear['id'],
            'program_id' => $programId,
            'campus_id' => $campusId,
            'first_name' => trim($data['first_name'] ?? ''),
            'last_name' => trim($data['last_name'] ?? ''),
            'email' => strtolower(trim($data['email'] ?? '')),
            'phone' => trim($data['phone'] ?? ''),
            'birth_date' => !empty($data['birth_date']) ? $data['birth_date'] : null,
            'last_diploma' => trim($data['last_diploma'] ?? ''),
            'last_diploma_institution' => trim($data['last_diploma_institution'] ?? ''),
            'last_diploma_year' => !empty($data['last_diploma_year']) ? (int) $data['last_diploma_year'] : null,
            'bac_year' => !empty($data['bac_year']) ? (int) $data['bac_year'] : null,
            'bac_series' => trim($data['bac_series'] ?? ''),
            'bac_average' => !empty($data['bac_average']) ? (float) $data['bac_average'] : null,
            'last_diploma_average' => !empty($data['last_diploma_average']) ? (float) $data['last_diploma_average'] : null,
            'message' => trim($data['message'] ?? ''),
        ];

        // Enregistrer en base
        try {
            $stmt = $db->prepare("
                INSERT INTO applications 
                (reference, academic_year_id, program_id, campus_id, first_name, last_name, email, phone, birth_date,
                 last_diploma, last_diploma_institution, last_diploma_year,
                 bac_year, bac_series, bac_average, last_diploma_average,
                 message, status)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'new')
            ");
            $stmt->execute([
                $cleanData['reference'],
                $cleanData['academic_year_id'],
                $cleanData['program_id'],
                $cleanData['campus_id'],
                $cleanData['first_name'],
                $cleanData['last_name'],
                $cleanData['email'],
                $cleanData['phone'],
                $cleanData['birth_date'],
                $cleanData['last_diploma'],
                $cleanData['last_diploma_institution'],
                $cleanData['last_diploma_year'],
                $cleanData['bac_year'],
                $cleanData['bac_series'],
                $cleanData['bac_average'],
                $cleanData['last_diploma_average'],
                $cleanData['message'],
            ]);

            Logger::info("Candidature créée: {$reference}", 'system', [
                'program' => $program['name'],
                'campus' => $campus['name'],
            ]);

            // Envoyer emails
            $mailer = new Mailer();
            $mailer->sendApplicationToAdmin($cleanData, $program, $campus);
            $mailer->sendConfirmationToCandidate($cleanData, $program, $campus);

            $this->json([
                'success' => true,
                'message' => 'Votre préinscription a bien été enregistrée.',
                'reference' => $reference,
                'program' => $program['name'],
                'campus' => $campus['name'],
            ]);
        } catch (\Exception $e) {
            Logger::error("Erreur création candidature: " . $e->getMessage(), 'system');
            $this->json(['success' => false, 'message' => 'Une erreur est survenue. Veuillez réessayer.'], 500);
        }
    }

    /**
     * Page de confirmation
     */
    public function confirmation(): void
    {
        $reference = $_GET['ref'] ?? '';
        $programName = $_GET['program'] ?? '';
        $campusName = $_GET['campus'] ?? '';

        $this->view('public/confirmation', [
            'title' => 'Préinscription confirmée',
            'reference' => htmlspecialchars($reference, ENT_QUOTES, 'UTF-8'),
            'programName' => htmlspecialchars($programName, ENT_QUOTES, 'UTF-8'),
            'campusName' => htmlspecialchars($campusName, ENT_QUOTES, 'UTF-8'),
        ]);
    }

    // =====================================================
    // MÉTHODES PRIVÉES
    // =====================================================

    private function validateApplication(array $data): array
    {
        $errors = [];

        if (empty(trim($data['first_name'] ?? ''))) {
            $errors['first_name'] = 'Le prénom est requis.';
        }

        if (empty(trim($data['last_name'] ?? ''))) {
            $errors['last_name'] = 'Le nom est requis.';
        }

        $email = trim($data['email'] ?? '');
        if (empty($email)) {
            $errors['email'] = 'L\'email est requis.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'L\'email n\'est pas valide.';
        }

        if (!empty($data['phone'])) {
            $phone = preg_replace('/[^0-9+\-\s()]/', '', $data['phone']);
            if (strlen($phone) < 8) {
                $errors['phone'] = 'Le numéro de téléphone n\'est pas valide.';
            }
        }

        if (!empty($data['birth_date'])) {
            $date = \DateTime::createFromFormat('Y-m-d', $data['birth_date']);
            if (!$date || $date->format('Y-m-d') !== $data['birth_date']) {
                $errors['birth_date'] = 'La date de naissance n\'est pas valide.';
            }
        }

        if (!empty($data['bac_average'])) {
            $avg = (float) $data['bac_average'];
            if ($avg < 0 || $avg > 20) {
                $errors['bac_average'] = 'La moyenne du bac doit être entre 0 et 20.';
            }
        }

        if (!empty($data['last_diploma_average'])) {
            $avg = (float) $data['last_diploma_average'];
            if ($avg < 0 || $avg > 20) {
                $errors['last_diploma_average'] = 'La moyenne du diplôme doit être entre 0 et 20.';
            }
        }

        return $errors;
    }

    private function generateReference(\PDO $db, string $yearName): string
    {
        $prefix = 'CI';
        $year = substr($yearName, 0, 4);

        // Trouver le dernier numéro
        $stmt = $db->prepare("SELECT reference FROM applications WHERE reference LIKE ? ORDER BY id DESC LIMIT 1");
        $stmt->execute(["{$prefix}-{$year}-%"]);
        $last = $stmt->fetch();

        if ($last) {
            $lastNum = (int) substr($last['reference'], -6);
            $newNum = $lastNum + 1;
        } else {
            $newNum = 1;
        }

        return sprintf("%s-%s-%06d", $prefix, $year, $newNum);
    }
}
