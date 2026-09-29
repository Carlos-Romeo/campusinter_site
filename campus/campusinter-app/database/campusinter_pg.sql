-- Campus Inter - PostgreSQL Schema
-- Converted from MySQL/MariaDB schema

-- Enable UUID extension (optional, for future use)
-- CREATE EXTENSION IF NOT EXISTS "uuid-ossp";

-- ======================================
-- Table: academic_years
-- ======================================
CREATE TABLE IF NOT EXISTS academic_years (
    id SERIAL PRIMARY KEY,
    name VARCHAR(20) NOT NULL,
    is_active BOOLEAN NOT NULL DEFAULT FALSE,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE UNIQUE INDEX IF NOT EXISTS uk_academic_year_name ON academic_years(name);

-- ======================================
-- Table: admin_users
-- ======================================
CREATE TABLE IF NOT EXISTS admin_users (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(180) NOT NULL,
    password VARCHAR(255) NOT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'active' CHECK (status IN ('active', 'inactive')),
    last_login TIMESTAMP NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE UNIQUE INDEX IF NOT EXISTS uk_admin_email ON admin_users(email);

-- ======================================
-- Table: cities
-- ======================================
CREATE TABLE IF NOT EXISTS cities (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    country VARCHAR(100) NOT NULL DEFAULT 'Togo',
    status VARCHAR(20) NOT NULL DEFAULT 'active' CHECK (status IN ('active', 'inactive')),
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE UNIQUE INDEX IF NOT EXISTS uk_city_name_country ON cities(name, country);

-- ======================================
-- Table: institutions
-- ======================================
CREATE TABLE IF NOT EXISTS institutions (
    id SERIAL PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    description TEXT NULL,
    logo VARCHAR(255) NULL,
    website VARCHAR(255) NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'active' CHECK (status IN ('active', 'inactive')),
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE UNIQUE INDEX IF NOT EXISTS uk_institution_name ON institutions(name);

-- ======================================
-- Table: campuses
-- ======================================
CREATE TABLE IF NOT EXISTS campuses (
    id SERIAL PRIMARY KEY,
    institution_id INTEGER NOT NULL REFERENCES institutions(id) ON UPDATE CASCADE,
    city_id INTEGER NOT NULL REFERENCES cities(id) ON UPDATE CASCADE,
    name VARCHAR(200) NOT NULL,
    address VARCHAR(255) NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'active' CHECK (status IN ('active', 'inactive')),
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE UNIQUE INDEX IF NOT EXISTS uk_campus_name_institution ON campuses(institution_id, name);
CREATE INDEX IF NOT EXISTS fk_campus_city ON campuses(city_id);

-- ======================================
-- Table: domains
-- ======================================
CREATE TABLE IF NOT EXISTS domains (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(120) NOT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'active' CHECK (status IN ('active', 'inactive')),
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE UNIQUE INDEX IF NOT EXISTS uk_domain_name ON domains(name);
CREATE UNIQUE INDEX IF NOT EXISTS uk_domain_slug ON domains(slug);

-- ======================================
-- Table: specialties
-- ======================================
CREATE TABLE IF NOT EXISTS specialties (
    id SERIAL PRIMARY KEY,
    domain_id INTEGER NOT NULL REFERENCES domains(id) ON UPDATE CASCADE,
    name VARCHAR(150) NOT NULL,
    slug VARCHAR(170) NOT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'active' CHECK (status IN ('active', 'inactive')),
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE UNIQUE INDEX IF NOT EXISTS uk_specialty_name_domain ON specialties(domain_id, name);
CREATE UNIQUE INDEX IF NOT EXISTS uk_specialty_slug ON specialties(slug);

-- ======================================
-- Table: programs
-- ======================================
CREATE TABLE IF NOT EXISTS programs (
    id SERIAL PRIMARY KEY,
    academic_year_id INTEGER NOT NULL REFERENCES academic_years(id) ON UPDATE CASCADE,
    domain_id INTEGER NOT NULL REFERENCES domains(id) ON UPDATE CASCADE,
    specialty_id INTEGER NULL REFERENCES specialties(id) ON DELETE SET NULL ON UPDATE CASCADE,
    name VARCHAR(200) NOT NULL,
    level VARCHAR(20) NOT NULL CHECK (level IN ('Bac', 'Bac+1', 'Bac+2', 'Bac+3', 'Bac+4', 'Bac+5', 'Doctorat', 'Autre')),
    description TEXT NULL,
    duration VARCHAR(50) NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'active' CHECK (status IN ('active', 'inactive')),
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX IF NOT EXISTS fk_program_academic_year ON programs(academic_year_id);
CREATE INDEX IF NOT EXISTS fk_program_domain ON programs(domain_id);
CREATE INDEX IF NOT EXISTS fk_program_specialty ON programs(specialty_id);
CREATE INDEX IF NOT EXISTS idx_program_level ON programs(level);
CREATE INDEX IF NOT EXISTS idx_program_status ON programs(status);

-- ======================================
-- Table: program_campuses
-- ======================================
CREATE TABLE IF NOT EXISTS program_campuses (
    id SERIAL PRIMARY KEY,
    program_id INTEGER NOT NULL REFERENCES programs(id) ON DELETE CASCADE ON UPDATE CASCADE,
    campus_id INTEGER NOT NULL REFERENCES campuses(id) ON DELETE CASCADE ON UPDATE CASCADE,
    academic_year_id INTEGER NOT NULL REFERENCES academic_years(id) ON UPDATE CASCADE,
    status VARCHAR(20) NOT NULL DEFAULT 'active' CHECK (status IN ('active', 'inactive')),
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE UNIQUE INDEX IF NOT EXISTS uk_program_campus_year ON program_campuses(program_id, campus_id, academic_year_id);
CREATE INDEX IF NOT EXISTS fk_pc_campus ON program_campuses(campus_id);
CREATE INDEX IF NOT EXISTS fk_pc_academic_year ON program_campuses(academic_year_id);

-- ======================================
-- Table: applications
-- ======================================
CREATE TABLE IF NOT EXISTS applications (
    id SERIAL PRIMARY KEY,
    reference VARCHAR(20) NOT NULL,
    academic_year_id INTEGER NOT NULL REFERENCES academic_years(id) ON UPDATE CASCADE,
    program_id INTEGER NOT NULL REFERENCES programs(id) ON UPDATE CASCADE,
    campus_id INTEGER NOT NULL REFERENCES campuses(id) ON UPDATE CASCADE,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(180) NOT NULL,
    phone VARCHAR(30) NULL,
    birth_date DATE NULL,
    last_diploma VARCHAR(150) NULL,
    last_diploma_institution VARCHAR(200) NULL,
    last_diploma_year INTEGER NULL,
    bac_year INTEGER NULL,
    bac_series VARCHAR(50) NULL,
    bac_average DECIMAL(4,2) NULL,
    last_diploma_average DECIMAL(4,2) NULL,
    message TEXT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'new' CHECK (status IN ('new', 'processing', 'accepted', 'rejected', 'archived')),
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE UNIQUE INDEX IF NOT EXISTS uk_application_reference ON applications(reference);
CREATE INDEX IF NOT EXISTS fk_app_academic_year ON applications(academic_year_id);
CREATE INDEX IF NOT EXISTS fk_app_program ON applications(program_id);
CREATE INDEX IF NOT EXISTS fk_app_campus ON applications(campus_id);
CREATE INDEX IF NOT EXISTS idx_app_status ON applications(status);
CREATE INDEX IF NOT EXISTS idx_app_created ON applications(created_at);
CREATE INDEX IF NOT EXISTS idx_app_email ON applications(email);

-- ======================================
-- Table: payments
-- ======================================
CREATE TABLE IF NOT EXISTS payments (
    id SERIAL PRIMARY KEY,
    application_id INTEGER NOT NULL REFERENCES applications(id) ON UPDATE CASCADE,
    amount DECIMAL(10,2) NOT NULL,
    currency VARCHAR(10) NOT NULL DEFAULT 'FCFA',
    provider VARCHAR(50) NULL,
    transaction_reference VARCHAR(100) NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'pending' CHECK (status IN ('pending', 'paid', 'failed', 'cancelled')),
    paid_at TIMESTAMP NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX IF NOT EXISTS fk_payment_application ON payments(application_id);
CREATE INDEX IF NOT EXISTS idx_payment_status ON payments(status);

-- ======================================
-- Table: logs
-- ======================================
CREATE TABLE IF NOT EXISTS logs (
    id BIGSERIAL PRIMARY KEY,
    level VARCHAR(20) NOT NULL DEFAULT 'info' CHECK (level IN ('info', 'warning', 'error', 'critical')),
    category VARCHAR(50) NOT NULL,
    message TEXT NOT NULL,
    context JSONB NULL,
    ip_address VARCHAR(45) NULL,
    user_agent VARCHAR(255) NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX IF NOT EXISTS idx_log_level ON logs(level);
CREATE INDEX IF NOT EXISTS idx_log_category ON logs(category);
CREATE INDEX IF NOT EXISTS idx_log_created ON logs(created_at);

-- ======================================
-- Table: rate_limits
-- ======================================
CREATE TABLE IF NOT EXISTS rate_limits (
    id BIGSERIAL PRIMARY KEY,
    ip_address VARCHAR(45) NOT NULL,
    endpoint VARCHAR(100) NOT NULL,
    attempts INTEGER NOT NULL DEFAULT 1,
    first_attempt_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    last_attempt_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE UNIQUE INDEX IF NOT EXISTS uk_rate_limit ON rate_limits(ip_address, endpoint);
CREATE INDEX IF NOT EXISTS idx_rate_limit_cleanup ON rate_limits(first_attempt_at);

-- ======================================
-- Table: schema_migrations (for tracking)
-- ======================================
CREATE TABLE IF NOT EXISTS schema_migrations (
    id SERIAL PRIMARY KEY,
    migration_name VARCHAR(255) NOT NULL,
    executed_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE UNIQUE INDEX IF NOT EXISTS uk_migration_name ON schema_migrations(migration_name);

-- ======================================
-- Function: update_updated_at trigger
-- ======================================
CREATE OR REPLACE FUNCTION update_updated_at_column()
RETURNS TRIGGER AS $$
BEGIN
    NEW.updated_at = CURRENT_TIMESTAMP;
    RETURN NEW;
END;
$$ language 'plpgsql';

-- Apply triggers to all tables with updated_at
CREATE TRIGGER update_academic_years_updated_at BEFORE UPDATE ON academic_years FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();
CREATE TRIGGER update_admin_users_updated_at BEFORE UPDATE ON admin_users FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();
CREATE TRIGGER update_cities_updated_at BEFORE UPDATE ON cities FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();
CREATE TRIGGER update_institutions_updated_at BEFORE UPDATE ON institutions FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();
CREATE TRIGGER update_campuses_updated_at BEFORE UPDATE ON campuses FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();
CREATE TRIGGER update_domains_updated_at BEFORE UPDATE ON domains FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();
CREATE TRIGGER update_specialties_updated_at BEFORE UPDATE ON specialties FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();
CREATE TRIGGER update_programs_updated_at BEFORE UPDATE ON programs FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();
CREATE TRIGGER update_program_campuses_updated_at BEFORE UPDATE ON program_campuses FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();
CREATE TRIGGER update_applications_updated_at BEFORE UPDATE ON applications FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();
CREATE TRIGGER update_payments_updated_at BEFORE UPDATE ON payments FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();

-- ======================================
-- Seed Data
-- ======================================

-- Academic Years
INSERT INTO academic_years (name, is_active) VALUES
('2025-2026', FALSE),
('2026-2027', TRUE),
('2027-2028', FALSE)
ON CONFLICT (name) DO NOTHING;

-- Admin Users (password: admin123)
INSERT INTO admin_users (name, email, password, status) VALUES
('Administrateur', 'admin@campusinter.com', '$2y$12$q.f490/dLwGKw44tzqlW9OWEd8gyhCXK4vhqfCQfGCwnwwFU.XjUm', 'active')
ON CONFLICT (email) DO NOTHING;

-- Cities
INSERT INTO cities (name, country, status) VALUES
('Lomé', 'Togo', 'active'),
('Cotonou', 'Bénin', 'active'),
('Abidjan', 'Côte d''Ivoire', 'active'),
('Dakar', 'Sénégal', 'active'),
('Ouagadougou', 'Burkina Faso', 'active'),
('Niamey', 'Niger', 'active'),
('Bamako', 'Mali', 'active'),
('Conakry', 'Guinée', 'active')
ON CONFLICT (name, country) DO NOTHING;

-- Institutions
INSERT INTO institutions (name, description, website, status) VALUES
('Université de Lomé', 'Principale université du Togo', 'https://www.univ-lome.tg', 'active'),
('Université d''Abomey-Calavi', 'Université publique du Bénin', 'https://www.uac.bj', 'active'),
('Université Félix Houphouët-Boigny', 'Université de Cocody, Abidjan', 'https://www.univ-fhb.ci', 'active'),
('Institut Africain d''Informatique', 'École supérieure informatique', 'https://www.iai-informatique.com', 'active'),
('École Supérieure de Gestion', 'ESG - Formation en gestion', NULL, 'active')
ON CONFLICT (name) DO NOTHING;

-- Campuses
INSERT INTO campuses (institution_id, city_id, name, address, status) VALUES
(1, 1, 'Campus Universitaire de Lomé', 'Boulevard du 13 Januar, Lomé', 'active'),
(1, 1, 'Campus Nord', 'Zone Universitaire, Lomé', 'active'),
(2, 2, 'Campus UAC Centre', 'Abomey-Calavi, Cotonou', 'active'),
(2, 2, 'Campus UAC Porto-Novo', 'Porto-Novo, Bénin', 'active'),
(3, 3, 'Campus de Cocody', 'Cocody, Abidjan', 'active'),
(3, 3, 'Campus de Yopougon', 'Yopougon, Abidjan', 'active'),
(4, 1, 'IAI Lomé', 'Boulevard du 13 Januar, Lomé', 'active'),
(4, 3, 'IAI Abidjan', 'Plateau, Abidjan', 'active'),
(5, 1, 'ESG Lomé', 'Agbalepedogan, Lomé', 'active'),
(5, 2, 'ESG Cotonou', 'Haie-Vive, Cotonou', 'active')
ON CONFLICT (institution_id, name) DO NOTHING;

-- Domains
INSERT INTO domains (name, slug, status) VALUES
('Informatique', 'informatique', 'active'),
('Gestion', 'gestion', 'active'),
('Droit', 'droit', 'active'),
('Sciences et Technologie', 'sciences-et-technologie', 'active'),
('Santé', 'sante', 'active'),
('Éducation', 'education', 'active'),
('Autre', 'autre', 'active')
ON CONFLICT (name) DO NOTHING;

-- Specialties
INSERT INTO specialties (domain_id, name, slug, status) VALUES
(1, 'Génie Logiciel', 'genie-logiciel', 'active'),
(1, 'Réseaux et Systèmes', 'reseaux-et-systemes', 'active'),
(1, 'Cybersécurité', 'cybersecurite', 'active'),
(1, 'Intelligence Artificielle', 'intelligence-artificielle', 'active'),
(1, 'Data Science', 'data-science', 'active'),
(2, 'Management', 'management', 'active'),
(2, 'Finance', 'finance', 'active'),
(2, 'Marketing', 'marketing', 'active'),
(2, 'Comptabilité', 'comptabilite', 'active'),
(3, 'Droit Privé', 'droit-prive', 'active'),
(3, 'Droit Public', 'droit-public', 'active'),
(3, 'Droit des Affaires', 'droit-des-affaires', 'active'),
(4, 'Génie Civil', 'genie-civil', 'active'),
(4, 'Énergie', 'energie', 'active'),
(4, 'Électrotechnique', 'electrotechnique', 'active'),
(5, 'Médecine', 'medecine', 'active'),
(5, 'Pharmacie', 'pharmacie', 'active'),
(5, 'Odontologie', 'odontologie', 'active'),
(6, 'Sciences de l''Éducation', 'sciences-de-education', 'active'),
(6, 'Formation des Enseignants', 'formation-des-enseignants', 'active')
ON CONFLICT (domain_id, name) DO NOTHING;

-- Programs
INSERT INTO programs (academic_year_id, domain_id, specialty_id, name, level, description, duration, status) VALUES
(2, 1, 1, 'Master Génie Logiciel', 'Bac+5', 'Formation approfondie en développement logiciel, architecture et gestion de projets IT.', '2 ans', 'active'),
(2, 1, 2, 'Master Réseaux et Systèmes', 'Bac+5', 'Spécialisation en administration réseau, systèmes distribués et cloud computing.', '2 ans', 'active'),
(2, 1, 3, 'Master Cybersécurité', 'Bac+5', 'Protection des systèmes d''information, audit de sécurité et réponse aux incidents.', '2 ans', 'active'),
(2, 1, 4, 'Master Intelligence Artificielle', 'Bac+5', 'Apprentissage automatique, deep learning et systèmes intelligents.', '2 ans', 'active'),
(2, 1, 5, 'Master Data Science', 'Bac+5', 'Analyse de données, statistiques avancées et visualisation.', '2 ans', 'active'),
(2, 2, 6, 'Licence Management', 'Bac+3', 'Fondamentaux du management et de l''organisation.', '3 ans', 'active'),
(2, 2, 7, 'Master Finance', 'Bac+5', 'Finance d''entreprise, marchés financiers et gestion de portefeuille.', '2 ans', 'active'),
(2, 2, 8, 'Master Marketing Digital', 'Bac+5', 'Stratégies marketing, communication digitale et e-commerce.', '2 ans', 'active'),
(2, 3, 10, 'Licence Droit Privé', 'Bac+3', 'Droit civil, droit commercial et procédures judiciaires.', '3 ans', 'active'),
(2, 4, 13, 'Master Génie Civil', 'Bac+5', 'Construction, structures, matériaux et genie parasismique.', '2 ans', 'active'),
(2, 5, 16, 'Doctorat Médecine', 'Doctorat', 'Formation médicale complète sur 6 ans.', '6 ans', 'active'),
(1, 1, 1, 'Master Génie Logiciel', 'Bac+5', 'Formation en développement logiciel.', '2 ans', 'active'),
(1, 2, 6, 'Licence Management', 'Bac+3', 'Fondamentaux du management.', '3 ans', 'active');

-- Program Campuses
INSERT INTO program_campuses (program_id, campus_id, academic_year_id, status) VALUES
(1, 1, 2, 'active'),
(1, 7, 2, 'active'),
(1, 5, 2, 'active'),
(2, 1, 2, 'active'),
(2, 7, 2, 'active'),
(3, 5, 2, 'active'),
(3, 8, 2, 'active'),
(4, 1, 2, 'active'),
(4, 5, 2, 'active'),
(5, 1, 2, 'active'),
(6, 1, 2, 'active'),
(6, 9, 2, 'active'),
(6, 3, 2, 'active'),
(7, 1, 2, 'active'),
(7, 10, 2, 'active'),
(8, 9, 2, 'active'),
(8, 10, 2, 'active'),
(9, 1, 2, 'active'),
(9, 3, 2, 'active'),
(10, 5, 2, 'active'),
(10, 6, 2, 'active'),
(11, 5, 2, 'active');

-- Sample Applications
INSERT INTO applications (reference, academic_year_id, program_id, campus_id, first_name, last_name, email, phone, birth_date, last_diploma, last_diploma_institution, last_diploma_year, bac_year, bac_series, bac_average, last_diploma_average, message, status) VALUES
('CI-2026-000001', 2, 11, 5, 'Roméo Carlos', 'Doe', 'romeo.afanvi@gmail.com', '+228 96 79 49 42', '2003-09-26', 'Licence en genie logiciel', 'ESIG GLOBAL SUCCES', 2026, 2022, 'D', 12.00, 11.83, 'lqmskjfl qfjlsqkjfapoizf qdkjvlsdnqlskdjmqlsjf', 'archived'),
('CI-2026-000002', 2, 11, 5, 'John doh', 'Doe', 'romeo.afanvi@gmail.com', '+228 96 79 49 42', '2026-09-26', 'Licence en genie logiciel', 'ESIG GLOBAL SUCCES', 2018, 2016, 'C', 13.50, 13.92, 'mllksdqjflk sfzaoflksqdjsqdufiomlsqkdjfmqsl jfoa flmsqjlksqjflmksj', 'new'),
('CI-2026-000003', 2, 11, 5, 'Roméo Carlos', 'Doe', 'romeo.afanvi@gmail.com', '+228 96 79 49 42', '2005-09-27', 'Licence en genie logiciel', 'ESIG GLOBAL SUCCES', 2024, 2021, 'D', 12.11, 14.00, 'qsldfmjsqldkf sqfqsfjpozae mfsqglsdfs', 'accepted');
