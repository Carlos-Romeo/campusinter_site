-- =====================================================
-- CAMPUS INTER - Données de démonstration (Seeds)
-- =====================================================

-- Années académiques
INSERT INTO `academic_years` (`name`, `is_active`) VALUES
('2025-2026', 0),
('2026-2027', 1),
('2027-2028', 0);

-- Domaines
INSERT INTO `domains` (`name`, `slug`, `status`) VALUES
('Informatique', 'informatique', 'active'),
('Gestion', 'gestion', 'active'),
('Droit', 'droit', 'active'),
('Sciences et Technologie', 'sciences-et-technologie', 'active'),
('Santé', 'sante', 'active'),
('Éducation', 'education', 'active'),
('Autre', 'autre', 'active');

-- Spécialités
INSERT INTO `specialties` (`domain_id`, `name`, `slug`, `status`) VALUES
-- Informatique
(1, 'Génie Logiciel', 'genie-logiciel', 'active'),
(1, 'Réseaux et Systèmes', 'reseaux-et-systemes', 'active'),
(1, 'Cybersécurité', 'cybersecurite', 'active'),
(1, 'Intelligence Artificielle', 'intelligence-artificielle', 'active'),
(1, 'Data Science', 'data-science', 'active'),
-- Gestion
(2, 'Management', 'management', 'active'),
(2, 'Finance', 'finance', 'active'),
(2, 'Marketing', 'marketing', 'active'),
(2, 'Comptabilité', 'comptabilite', 'active'),
-- Droit
(3, 'Droit Privé', 'droit-prive', 'active'),
(3, 'Droit Public', 'droit-public', 'active'),
(3, 'Droit des Affaires', 'droit-des-affaires', 'active'),
-- Sciences et Technologie
(4, 'Génie Civil', 'genie-civil', 'active'),
(4, 'Énergie', 'energie', 'active'),
(4, 'Électrotechnique', 'electrotechnique', 'active'),
-- Santé
(5, 'Médecine', 'medecine', 'active'),
(5, 'Pharmacie', 'pharmacie', 'active'),
(5, 'Odontologie', 'odontologie', 'active'),
-- Éducation
(6, 'Sciences de l\'Éducation', 'sciences-de-education', 'active'),
(6, 'Formation des Enseignants', 'formation-des-enseignants', 'active');

-- Villes
INSERT INTO `cities` (`name`, `country`, `status`) VALUES
('Lomé', 'Togo', 'active'),
('Cotonou', 'Bénin', 'active'),
('Abidjan', 'Côte d\'Ivoire', 'active'),
('Dakar', 'Sénégal', 'active'),
('Ouagadougou', 'Burkina Faso', 'active'),
('Niamey', 'Niger', 'active'),
('Bamako', 'Mali', 'active'),
('Conakry', 'Guinée', 'active');

-- Institutions
INSERT INTO `institutions` (`name`, `description`, `website`, `status`) VALUES
('Université de Lomé', 'Principale université du Togo', 'https://www.univ-lome.tg', 'active'),
('Université d\'Abomey-Calavi', 'Université publique du Bénin', 'https://www.uac.bj', 'active'),
('Université Félix Houphouët-Boigny', 'Université de Cocody, Abidjan', 'https://www.univ-fhb.ci', 'active'),
('Institut Africain d\'Informatique', 'École supérieure informatique', 'https://www.iai-informatique.com', 'active'),
('École Supérieure de Gestion', 'ESG - Formation en gestion', NULL, 'active');

-- Campus
INSERT INTO `campuses` (`institution_id`, `city_id`, `name`, `address`, `status`) VALUES
-- Université de Lomé - Lomé
(1, 1, 'Campus Universitaire de Lomé', 'Boulevard du 13 Januar, Lomé', 'active'),
(1, 1, 'Campus Nord', 'Zone Universitaire, Lomé', 'active'),
-- Université d'Abomey-Calavi - Cotonou
(2, 2, 'Campus UAC Centre', 'Abomey-Calavi, Cotonou', 'active'),
(2, 2, 'Campus UAC Porto-Novo', 'Porto-Novo, Bénin', 'active'),
-- Université Félix Houphouët-Boigny - Abidjan
(3, 3, 'Campus de Cocody', 'Cocody, Abidjan', 'active'),
(3, 3, 'Campus de Yopougon', 'Yopougon, Abidjan', 'active'),
-- IAI - Lomé et Abidjan
(4, 1, 'IAI Lomé', 'Boulevard du 13 Januar, Lomé', 'active'),
(4, 3, 'IAI Abidjan', 'Plateau, Abidjan', 'active'),
-- ESG - Lomé et Cotonou
(5, 1, 'ESG Lomé', 'Agbalepedogan, Lomé', 'active'),
(5, 2, 'ESG Cotonou', 'Haie-Vive, Cotonou', 'active');

-- Formations
INSERT INTO `programs` (`academic_year_id`, `domain_id`, `specialty_id`, `name`, `level`, `description`, `duration`, `status`) VALUES
-- Année active 2026-2027
(2, 1, 1, 'Master Génie Logiciel', 'Bac+5', 'Formation approfondie en développement logiciel, architecture et gestion de projets IT.', '2 ans', 'active'),
(2, 1, 2, 'Master Réseaux et Systèmes', 'Bac+5', 'Spécialisation en administration réseau, systèmes distribués et cloud computing.', '2 ans', 'active'),
(2, 1, 3, 'Master Cybersécurité', 'Bac+5', 'Protection des systèmes d\'information, audit de sécurité et réponse aux incidents.', '2 ans', 'active'),
(2, 1, 4, 'Master Intelligence Artificielle', 'Bac+5', 'Apprentissage automatique, deep learning et systèmes intelligents.', '2 ans', 'active'),
(2, 1, 5, 'Master Data Science', 'Bac+5', 'Analyse de données, statistiques avancées et visualisation.', '2 ans', 'active'),
(2, 2, 6, 'Licence Management', 'Bac+3', 'Fondamentaux du management et de l\'organisation.', '3 ans', 'active'),
(2, 2, 7, 'Master Finance', 'Bac+5', 'Finance d\'entreprise, marchés financiers et gestion de portefeuille.', '2 ans', 'active'),
(2, 2, 8, 'Master Marketing Digital', 'Bac+5', 'Stratégies marketing, communication digitale et e-commerce.', '2 ans', 'active'),
(2, 3, 10, 'Licence Droit Privé', 'Bac+3', 'Droit civil, droit commercial et procédures judiciaires.', '3 ans', 'active'),
(2, 4, 13, 'Master Génie Civil', 'Bac+5', 'Construction, structures, matériaux et genie parasismique.', '2 ans', 'active'),
(2, 5, 16, 'Doctorat Médecine', 'Doctorat', 'Formation médicale complète sur 6 ans.', '6 ans', 'active'),
-- Année 2025-2026
(1, 1, 1, 'Master Génie Logiciel', 'Bac+5', 'Formation en développement logiciel.', '2 ans', 'active'),
(1, 2, 6, 'Licence Management', 'Bac+3', 'Fondamentaux du management.', '3 ans', 'active');

-- Formations - Campus (pivot)
INSERT INTO `program_campuses` (`program_id`, `campus_id`, `academic_year_id`, `status`) VALUES
-- Master Génie Logiciel dans plusieurs campus
(1, 1, 2, 'active'),
(1, 7, 2, 'active'),
(1, 5, 2, 'active'),
-- Master Réseaux dans Lomé
(2, 1, 2, 'active'),
(2, 7, 2, 'active'),
-- Master Cybersécurité dans Abidjan
(3, 5, 2, 'active'),
(3, 8, 2, 'active'),
-- Master IA dans Lomé et Abidjan
(4, 1, 2, 'active'),
(4, 5, 2, 'active'),
-- Master Data Science dans Lomé
(5, 1, 2, 'active'),
-- Licence Management
(6, 1, 2, 'active'),
(6, 9, 2, 'active'),
(6, 3, 2, 'active'),
-- Master Finance
(7, 1, 2, 'active'),
(7, 10, 2, 'active'),
-- Master Marketing
(8, 9, 2, 'active'),
(8, 10, 2, 'active'),
-- Licence Droit
(9, 1, 2, 'active'),
(9, 3, 2, 'active'),
-- Master Génie Civil
(10, 5, 2, 'active'),
(10, 6, 2, 'active'),
-- Doctorat Médecine
(11, 5, 2, 'active');

-- Admin par défaut (mot de passe: Admin@123)
INSERT INTO `admin_users` (`name`, `email`, `password`, `status`) VALUES
('Administrateur', 'admin@campusinter.com', '$2y$12$q.f490/dLwGKw44tzqlW9OWEd8gyhCXK4vhqfCQfGCwnwwFU.XjUm', 'active');
-- Mot de passe hashé pour 'Admin@123'
