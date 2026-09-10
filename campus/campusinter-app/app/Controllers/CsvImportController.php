<?php
/**
 * CAMPUS INTER - CSV Import Controller
 * Import des formations via CSV
 */

declare(strict_types=1);

namespace CampusInter\Controllers;

use CampusInter\Helpers\Csrf;
use CampusInter\Helpers\Logger;

class CsvImportController extends Controller
{
    /**
     * Afficher la page d'import
     */
    public function index(): void
    {
        $db = $this->db->getConnection();

        $stmt = $db->query("SELECT id, name FROM academic_years ORDER BY name DESC");
        $academicYears = $stmt->fetchAll();

        $this->view('admin/csv-import/index', [
            'title' => 'Import des formations',
            'academicYears' => $academicYears,
        ]);
    }

    /**
     * Analyser le fichier CSV
     */
    public function analyze(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Méthode non autorisée.'], 405);
            return;
        }

        if (!Csrf::verify($_SERVER['HTTP_X_CSRF_TOKEN'] ?? '')) {
            $this->json(['success' => false, 'message' => 'Token invalide.'], 403);
            return;
        }

        if (!isset($_FILES['csv_file'])) {
            $this->json(['success' => false, 'message' => 'Aucun fichier envoyé.'], 400);
            return;
        }

        $file = $_FILES['csv_file'];

        // Vérifier les erreurs upload
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $this->json(['success' => false, 'message' => 'Erreur lors de l\'envoi du fichier.'], 400);
            return;
        }

        // Vérifier l'extension
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if ($ext !== 'csv') {
            $this->json(['success' => false, 'message' => 'Le fichier doit être au format CSV.'], 400);
            return;
        }

        // Vérifier la taille (max 5MB)
        if ($file['size'] > 5 * 1024 * 1024) {
            $this->json(['success' => false, 'message' => 'Le fichier est trop volumineux (max 5MB).'], 400);
            return;
        }

        // Lire le CSV
        $handle = fopen($file['tmp_name'], 'r');
        if (!$handle) {
            $this->json(['success' => false, 'message' => 'Impossible de lire le fichier.'], 400);
            return;
        }

        // Lire l'en-tête
        $header = fgetcsv($handle, 0, ';');
        if (!$header) {
            fclose($handle);
            $this->json(['success' => false, 'message' => 'Le fichier est vide ou mal formaté.'], 400);
            return;
        }

        // Nettoyer l'en-tête
        $header = array_map(fn($h) => trim(mb_strtolower($h, 'UTF-8')), $header);

        // Colonnes attendues
        $requiredColumns = ['nom', 'domaine', 'niveau'];
        $optionalColumns = ['specialite', 'description', 'duree', 'campus', 'etablissement', 'ville'];
        $allColumns = array_merge($requiredColumns, $optionalColumns);

        // Vérifier les colonnes manquantes
        $missingColumns = array_diff($requiredColumns, $header);
        if (!empty($missingColumns)) {
            fclose($handle);
            $this->json([
                'success' => false,
                'message' => 'Colonnes manquantes : ' . implode(', ', $missingColumns),
                'expected_columns' => $allColumns,
            ], 400);
            return;
        }

        // Lire les données
        $rows = [];
        $lineNum = 1;
        while (($row = fgetcsv($handle, 0, ';')) !== false) {
            $lineNum++;
            if (count($row) < count($header)) {
                $row = array_pad($row, count($header), '');
            }
            $rowData = array_combine($header, $row);
            $rowData['_line'] = $lineNum;
            $rows[] = $rowData;
        }
        fclose($handle);

        // Analyser les données
        $db = $this->db->getConnection();
        $analysis = $this->analyzeRows($rows, $db);

        // Stocker en session pour l'import
        $_SESSION['csv_data'] = $rows;
        $_SESSION['csv_analysis'] = $analysis;

        $this->json([
            'success' => true,
            'data' => [
                'total_rows' => count($rows),
                'valid_rows' => $analysis['valid_count'],
                'duplicates' => $analysis['duplicate_count'],
                'errors' => $analysis['error_count'],
                'errors_details' => $analysis['errors'],
                'preview' => array_slice($rows, 0, 5),
            ],
        ]);
    }

    /**
     * Importer les données validées
     */
    public function import(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Méthode non autorisée.'], 405);
            return;
        }

        if (!Csrf::verify($_SERVER['HTTP_X_CSRF_TOKEN'] ?? '')) {
            $this->json(['success' => false, 'message' => 'Token invalide.'], 403);
            return;
        }

        $academicYearId = (int) ($_POST['academic_year_id'] ?? 0);
        if ($academicYearId <= 0) {
            $this->json(['success' => false, 'message' => 'Année académique requise.'], 400);
            return;
        }

        $rows = $_SESSION['csv_data'] ?? [];
        $analysis = $_SESSION['csv_analysis'] ?? [];

        if (empty($rows) || empty($analysis)) {
            $this->json(['success' => false, 'message' => 'Aucune donnée à importer. Veuillez d\'abord analyser un fichier.'], 400);
            return;
        }

        $db = $this->db->getConnection();
        $imported = 0;
        $skipped = 0;
        $errors = [];

        foreach ($rows as $row) {
            $lineNum = $row['_line'] ?? '?';

            // Vérifier si c'est une ligne valide
            if (in_array($lineNum, $analysis['valid_lines'] ?? [])) {
                try {
                    $result = $this->importRow($row, $academicYearId, $db);
                    if ($result) {
                        $imported++;
                    } else {
                        $skipped++;
                    }
                } catch (\Exception $e) {
                    $errors[] = "Ligne {$lineNum}: " . $e->getMessage();
                    Logger::error("Erreur import ligne {$lineNum}: " . $e->getMessage(), 'import');
                }
            } else {
                $skipped++;
            }
        }

        // Nettoyer la session
        unset($_SESSION['csv_data'], $_SESSION['csv_analysis']);

        Logger::info("Import CSV terminé: {$imported} importées, {$skipped} ignorées", 'import');

        $this->json([
            'success' => true,
            'message' => "Import terminé: {$imported} formations importées, {$skipped} ignorées.",
            'imported' => $imported,
            'skipped' => $skipped,
            'errors' => $errors,
        ]);
    }

    /**
     * Analyser les lignes du CSV
     */
    private function analyzeRows(array $rows, \PDO $db): array
    {
        $validCount = 0;
        $duplicateCount = 0;
        $errorCount = 0;
        $errors = [];
        $validLines = [];
        $seen = [];

        // Récupérer les données existantes
        $existingPrograms = $this->getExistingPrograms($db);
        $existingDomains = $this->getExistingDomains($db);
        $existingSpecialties = $this->getExistingSpecialties($db);
        $existingCities = $this->getExistingCities($db);
        $existingInstitutions = $this->getExistingInstitutions($db);
        $existingCampuses = $this->getExistingCampuses($db);

        foreach ($rows as $row) {
            $lineNum = $row['_line'];
            $lineErrors = [];

            // Vérifier les champs requis
            if (empty($row['nom'])) {
                $lineErrors[] = 'Nom manquant';
            }
            if (empty($row['domaine'])) {
                $lineErrors[] = 'Domaine manquant';
            }
            if (empty($row['niveau'])) {
                $lineErrors[] = 'Niveau manquant';
            }

            // Vérifier le domaine
            if (!empty($row['domaine']) && !isset($existingDomains[mb_strtolower(trim($row['domaine']), 'UTF-8')])) {
                $lineErrors[] = 'Domaine inconnu: ' . $row['domaine'];
            }

            // Vérifier la spécialité
            if (!empty($row['specialite']) && !empty($row['domaine'])) {
                $domainSlug = $this->slugify($row['domaine']);
                $specKey = $domainSlug . '|' . $this->slugify($row['specialite']);
                if (!isset($existingSpecialties[$specKey])) {
                    $lineErrors[] = 'Spécialité inconnue: ' . $row['specialite'];
                }
            }

            // Vérifier la ville
            if (!empty($row['ville']) && !isset($existingCities[mb_strtolower(trim($row['ville']), 'UTF-8')])) {
                $lineErrors[] = 'Ville inconnue: ' . $row['ville'];
            }

            // Vérifier l'établissement
            if (!empty($row['etablissement']) && !isset($existingInstitutions[mb_strtolower(trim($row['etablissement']), 'UTF-8')])) {
                $lineErrors[] = 'Établissement inconnu: ' . $row['etablissement'];
            }

            // Vérifier le campus
            if (!empty($row['campus'])) {
                $campusKey = mb_strtolower(trim($row['campus']), 'UTF-8');
                if (!isset($existingCampuses[$campusKey])) {
                    $lineErrors[] = 'Campus inconnu: ' . $row['campus'];
                }
            }

            // Vérifier les doublons dans le CSV
            $uniqueKey = mb_strtolower(trim($row['nom'] ?? ''), 'UTF-8') . '|' . mb_strtolower(trim($row['niveau'] ?? ''), 'UTF-8');
            if (isset($seen[$uniqueKey])) {
                $lineErrors[] = 'Doublon dans le fichier (ligne ' . $seen[$uniqueKey] . ')';
                $duplicateCount++;
            } else {
                $seen[$uniqueKey] = $lineNum;
            }

            // Vérifier si la formation existe déjà
            $programSlug = $this->slugify($row['nom'] ?? '');
            if (isset($existingPrograms[$programSlug])) {
                $lineErrors[] = 'Formation déjà existante';
            }

            if (!empty($lineErrors)) {
                $errors[$lineNum] = $lineErrors;
                $errorCount++;
            } else {
                $validCount++;
                $validLines[] = $lineNum;
            }
        }

        return [
            'valid_count' => $validCount,
            'duplicate_count' => $duplicateCount,
            'error_count' => $errorCount,
            'errors' => $errors,
            'valid_lines' => $validLines,
        ];
    }

    /**
     * Importer une ligne
     */
    private function importRow(array $row, int $academicYearId, \PDO $db): bool
    {
        $name = trim($row['nom'] ?? '');
        if (empty($name)) return false;

        $domainName = trim($row['domaine'] ?? '');
        $specialtyName = trim($row['specialite'] ?? '');
        $level = trim($row['niveau'] ?? '');
        $description = trim($row['description'] ?? '');
        $duration = trim($row['duree'] ?? '');

        // Récupérer le domaine
        $stmt = $db->prepare("SELECT id FROM domains WHERE LOWER(name) = LOWER(?)");
        $stmt->execute([$domainName]);
        $domain = $stmt->fetch();
        if (!$domain) return false;

        // Récupérer la spécialité (optionnelle)
        $specialtyId = null;
        if ($specialtyName) {
            $stmt = $db->prepare("SELECT id FROM specialties WHERE domain_id = ? AND LOWER(name) = LOWER(?)");
            $stmt->execute([$domain['id'], $specialtyName]);
            $specialty = $stmt->fetch();
            if ($specialty) {
                $specialtyId = $specialty['id'];
            }
        }

        // Valider le niveau
        $validLevels = ['Bac', 'Bac+1', 'Bac+2', 'Bac+3', 'Bac+4', 'Bac+5', 'Doctorat', 'Autre'];
        if (!in_array($level, $validLevels)) {
            $level = 'Autre';
        }

        // Créer la formation
        $slug = $this->slugify($name);
        $stmt = $db->prepare("
            INSERT INTO programs (academic_year_id, domain_id, specialty_id, name, level, description, duration, status)
            VALUES (?, ?, ?, ?, ?, ?, ?, 'active')
        ");
        $stmt->execute([$academicYearId, $domain['id'], $specialtyId, $name, $level, $description, $duration]);
        $programId = $db->lastInsertId();

        // Associer aux campus si spécifiés
        $campusName = trim($row['campus'] ?? '');
        $institutionName = trim($row['etablissement'] ?? '');
        $cityName = trim($row['ville'] ?? '');

        if ($campusName || $institutionName) {
            $campusIds = $this->findCampuses($campusName, $institutionName, $cityName, $db);
            foreach ($campusIds as $campusId) {
                $stmt = $db->prepare("
                    INSERT IGNORE INTO program_campuses (program_id, campus_id, academic_year_id, status)
                    VALUES (?, ?, ?, 'active')
                ");
                $stmt->execute([$programId, $campusId, $academicYearId]);
            }
        }

        return true;
    }

    /**
     * Trouver les campus correspondants
     */
    private function findCampuses(string $campusName, string $institutionName, string $cityName, \PDO $db): array
    {
        $sql = "SELECT c.id FROM campuses c 
                JOIN institutions i ON c.institution_id = i.id 
                JOIN cities ci ON c.city_id = ci.id 
                WHERE c.status = 'active'";
        $params = [];

        if ($campusName) {
            $sql .= " AND LOWER(c.name) = LOWER(?)";
            $params[] = $campusName;
        }
        if ($institutionName) {
            $sql .= " AND LOWER(i.name) = LOWER(?)";
            $params[] = $institutionName;
        }
        if ($cityName) {
            $sql .= " AND LOWER(ci.name) = LOWER(?)";
            $params[] = $cityName;
        }

        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(\PDO::FETCH_COLUMN);
    }

    private function getExistingPrograms(\PDO $db): array
    {
        $stmt = $db->query("SELECT slug FROM programs");
        $programs = [];
        while ($row = $stmt->fetch()) {
            $programs[$row['slug']] = true;
        }
        return $programs;
    }

    private function getExistingDomains(\PDO $db): array
    {
        $stmt = $db->query("SELECT LOWER(name) as name FROM domains");
        $domains = [];
        while ($row = $stmt->fetch()) {
            $domains[$row['name']] = true;
        }
        return $domains;
    }

    private function getExistingSpecialties(\PDO $db): array
    {
        $stmt = $db->query("SELECT d.slug as domain_slug, s.slug as spec_slug FROM specialties s JOIN domains d ON s.domain_id = d.id");
        $specialties = [];
        while ($row = $stmt->fetch()) {
            $specialties[$row['domain_slug'] . '|' . $row['spec_slug']] = true;
        }
        return $specialties;
    }

    private function getExistingCities(\PDO $db): array
    {
        $stmt = $db->query("SELECT LOWER(name) as name FROM cities");
        $cities = [];
        while ($row = $stmt->fetch()) {
            $cities[$row['name']] = true;
        }
        return $cities;
    }

    private function getExistingInstitutions(\PDO $db): array
    {
        $stmt = $db->query("SELECT LOWER(name) as name FROM institutions");
        $institutions = [];
        while ($row = $stmt->fetch()) {
            $institutions[$row['name']] = true;
        }
        return $institutions;
    }

    private function getExistingCampuses(\PDO $db): array
    {
        $stmt = $db->query("SELECT LOWER(name) as name FROM campuses");
        $campuses = [];
        while ($row = $stmt->fetch()) {
            $campuses[$row['name']] = true;
        }
        return $campuses;
    }

    private function slugify(string $text): string
    {
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);
        $text = preg_replace('~[^-\w]+~', '', $text);
        $text = trim($text, '-');
        $text = preg_replace('~-+~', '-', $text);
        return strtolower($text);
    }
}
