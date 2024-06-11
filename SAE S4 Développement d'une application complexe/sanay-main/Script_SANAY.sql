-- Suppresion de la vue
DROP VIEW IF EXISTS historique_global;

-- Supressions des triggers et fonctions
DROP TRIGGER IF EXISTS trg_mission_changes ON MISSION;
DROP TRIGGER IF EXISTS trg_composantes_changes ON COMPOSANTE;
DROP TRIGGER IF EXISTS trg_prestataires_changes ON PRESTATAIRE;
DROP TRIGGER IF EXISTS trg_commercial_changes ON COMMERCIAL;
DROP TRIGGER IF EXISTS trg_client_changes ON CLIENT;
DROP TRIGGER IF EXISTS trg_activite_changes ON ACTIVITE;
DROP TRIGGER IF EXISTS trg_bon_de_livraison_changes ON BON_DE_LIVRAISON;

DROP FUNCTION IF EXISTS log_mission_changes;
DROP FUNCTION IF EXISTS log_composantes_changes;
DROP FUNCTION IF EXISTS log_prestataires_changes;
DROP FUNCTION IF EXISTS log_commercial_changes;
DROP FUNCTION IF EXISTS log_client_changes;
DROP FUNCTION IF EXISTS log_activite_changes;
DROP FUNCTION IF EXISTS log_bon_de_livraison_changes;

-- Suppression des tables existantes
DROP TABLE IF EXISTS travailleAvec;
DROP TABLE IF EXISTS estDans;
DROP TABLE IF EXISTS dirige;
DROP TABLE IF EXISTS DEMI_JOUR;
DROP TABLE IF EXISTS JOUR;
DROP TABLE IF EXISTS NB_HEURE;
DROP TABLE IF EXISTS ACTIVITE;
DROP TABLE IF EXISTS BON_DE_LIVRAISON;
DROP TABLE IF EXISTS MISSION;
DROP TABLE IF EXISTS COMPOSANTE;
DROP TABLE IF EXISTS Adresse;
DROP TABLE IF EXISTS TypeVoie;
DROP TABLE IF EXISTS Localite;
DROP TABLE IF EXISTS TEXTE;
DROP TABLE IF EXISTS GESTIONNAIRE;
DROP TABLE IF EXISTS ADMINISTRATEUR;
DROP TABLE IF EXISTS COMMERCIAL;
DROP TABLE IF EXISTS INTERLOCUTEUR;
DROP TABLE IF EXISTS CLIENT;
DROP TABLE IF EXISTS PRESTATAIRE;
DROP TABLE IF EXISTS PERSONNE;
DROP TABLE IF EXISTS mission_history;
DROP TABLE IF EXISTS composantes_history;
DROP TABLE IF EXISTS prestataires_history;
DROP TABLE IF EXISTS commercial_history;
DROP TABLE IF EXISTS client_history;
DROP TABLE IF EXISTS activite_history;
DROP TABLE IF EXISTS bon_de_livraison_history;

-- Création des tables
CREATE TABLE PERSONNE (
   id_personne SERIAL PRIMARY KEY,
   prenom VARCHAR(50) NOT NULL,
   nom VARCHAR(50) NOT NULL,
   email VARCHAR(50) UNIQUE,
   mdp VARCHAR(50)
);

CREATE TABLE PRESTATAIRE (
   id_personne SERIAL PRIMARY KEY,
   interne BOOLEAN,
   FOREIGN KEY(id_personne) REFERENCES PERSONNE(id_personne)
);

CREATE TABLE CLIENT (
   id_client SERIAL PRIMARY KEY,
   nom_client VARCHAR(50) NOT NULL,
   telephone_client VARCHAR(50)
);

CREATE TABLE INTERLOCUTEUR (
   id_personne SERIAL PRIMARY KEY,
   FOREIGN KEY(id_personne) REFERENCES PERSONNE(id_personne)
);

CREATE TABLE COMMERCIAL (
   id_personne SERIAL PRIMARY KEY,
   interne BOOLEAN,
   FOREIGN KEY(id_personne) REFERENCES PERSONNE(id_personne)
);

CREATE TABLE ADMINISTRATEUR (
   id_personne SERIAL PRIMARY KEY,
   FOREIGN KEY(id_personne) REFERENCES PERSONNE(id_personne)
);

CREATE TABLE GESTIONNAIRE (
   id_personne SERIAL PRIMARY KEY,
   FOREIGN KEY(id_personne) REFERENCES PERSONNE(id_personne)
);

CREATE TABLE TEXTE (
   id_texte INT PRIMARY KEY
);

CREATE TABLE Localite (
   id_localite SERIAL PRIMARY KEY,
   cp INT,
   ville VARCHAR(50)
);

CREATE TABLE TypeVoie (
   id_type_voie SERIAL PRIMARY KEY,
   libelle VARCHAR(50)
);

CREATE TABLE Adresse (
   id_adresse SERIAL PRIMARY KEY,
   numero INT,
   nom_voie VARCHAR(50),
   id_type_voie SERIAL NOT NULL,
   id_localite SERIAL NOT NULL,
   FOREIGN KEY(id_type_voie) REFERENCES TypeVoie(id_type_voie),
   FOREIGN KEY(id_localite) REFERENCES Localite(id_localite)
);

CREATE TABLE COMPOSANTE (
   id_composante SERIAL PRIMARY KEY,
   nom_composante VARCHAR(50),
   id_adresse SERIAL NOT NULL,
   id_client SERIAL NOT NULL,
   FOREIGN KEY(id_adresse) REFERENCES Adresse(id_adresse),
   FOREIGN KEY(id_client) REFERENCES CLIENT(id_client)
);

CREATE TABLE MISSION (
   id_mission SERIAL PRIMARY KEY,
   type_bdl VARCHAR(50) NOT NULL,
   nom_mission VARCHAR(50),
   date_debut VARCHAR(50),
   id_composante SERIAL NOT NULL,
   FOREIGN KEY(id_composante) REFERENCES COMPOSANTE(id_composante)
);

CREATE TABLE BON_DE_LIVRAISON (
   id_bdl SERIAL PRIMARY KEY,
   est_valide BOOLEAN,
   mois VARCHAR(50),
   commentaire VARCHAR(50),
   signatureInterlocuteur VARCHAR(50),
   signaturePrestataire VARCHAR(50),
   id_interlocuteur INT,
   id_prestataire SERIAL NOT NULL,
   id_mission SERIAL NOT NULL,
   FOREIGN KEY(id_interlocuteur) REFERENCES INTERLOCUTEUR(id_personne),
   FOREIGN KEY(id_prestataire) REFERENCES PRESTATAIRE(id_personne),
   FOREIGN KEY(id_mission) REFERENCES MISSION(id_mission)
);

CREATE TABLE ACTIVITE (
   id_activite SERIAL PRIMARY KEY,
   commentaire VARCHAR(50),
   date_bdl VARCHAR(50),
   id_personne SERIAL NOT NULL,
   id_bdl SERIAL NOT NULL,
   FOREIGN KEY(id_personne) REFERENCES PRESTATAIRE(id_personne),
   FOREIGN KEY(id_bdl) REFERENCES BON_DE_LIVRAISON(id_bdl)
);

CREATE TABLE NB_HEURE (
   id_activite SERIAL PRIMARY KEY,
   nb_heure INT,
   FOREIGN KEY(id_activite) REFERENCES ACTIVITE(id_activite)
);

CREATE TABLE JOUR (
   id_activite SERIAL PRIMARY KEY,
   journee BOOLEAN,
   debut_heure_supp INT,
   fin_heure_supp INT,
   FOREIGN KEY(id_activite) REFERENCES ACTIVITE(id_activite)
);

CREATE TABLE DEMI_JOUR (
   id_activite SERIAL PRIMARY KEY,
   nb_demi_journee INT, 
   FOREIGN KEY(id_activite) REFERENCES ACTIVITE(id_activite)
);

CREATE TABLE dirige (
   id_composante SERIAL,
   id_personne SERIAL,
   PRIMARY KEY(id_composante, id_personne),
   FOREIGN KEY(id_composante) REFERENCES COMPOSANTE(id_composante),
   FOREIGN KEY(id_personne) REFERENCES INTERLOCUTEUR(id_personne)
);

CREATE TABLE estDans (
   id_composante SERIAL,
   id_personne SERIAL,
   PRIMARY KEY(id_composante, id_personne),
   FOREIGN KEY(id_composante) REFERENCES COMPOSANTE(id_composante),
   FOREIGN KEY(id_personne) REFERENCES COMMERCIAL(id_personne)
);

CREATE TABLE travailleAvec (
   id_personne SERIAL,
   id_mission SERIAL,
   PRIMARY KEY(id_personne, id_mission),
   FOREIGN KEY(id_personne) REFERENCES PRESTATAIRE(id_personne),
   FOREIGN KEY(id_mission) REFERENCES MISSION(id_mission)
);

-- Création des tables d'historique
CREATE TABLE mission_history (
    history_id SERIAL PRIMARY KEY,
    id_mission INT,
    action VARCHAR(50),
    old_value JSONB,
    new_value JSONB,
    changed_at TIMESTAMPTZ DEFAULT NOW()
);

CREATE TABLE composantes_history (
    history_id SERIAL PRIMARY KEY,
    id_composante INT,
    action VARCHAR(50),
    old_value JSONB,
    new_value JSONB,
    changed_at TIMESTAMPTZ DEFAULT NOW()
);

CREATE TABLE prestataires_history (
    history_id SERIAL PRIMARY KEY,
    id_prestataire INT,
    action VARCHAR(50),
    old_value JSONB,
    new_value JSONB,
    changed_at TIMESTAMPTZ DEFAULT NOW()
);

CREATE TABLE commercial_history (
    history_id SERIAL PRIMARY KEY,
    id_commercial INT,
    action VARCHAR(50),
    old_value JSONB,
    new_value JSONB,
    changed_at TIMESTAMPTZ DEFAULT NOW()
);

CREATE TABLE client_history (
    history_id SERIAL PRIMARY KEY,
    id_client INT,
    action VARCHAR(50),
    old_value JSONB,
    new_value JSONB,
    changed_at TIMESTAMPTZ DEFAULT NOW()
);

CREATE TABLE activite_history (
    history_id SERIAL PRIMARY KEY,
    id_activite INT,
    action VARCHAR(50),
    old_value JSONB,
    new_value JSONB,
    changed_at TIMESTAMPTZ DEFAULT NOW()
);

CREATE TABLE bon_de_livraison_history (
    history_id SERIAL PRIMARY KEY,
    id_bdl INT,
    action VARCHAR(50),
    old_value JSONB,
    new_value JSONB,
    changed_at TIMESTAMPTZ DEFAULT NOW()
);

-- Création des fonctions de trigger
CREATE OR REPLACE FUNCTION log_mission_changes() RETURNS TRIGGER AS $$
BEGIN
    IF (TG_OP = 'INSERT') THEN
        INSERT INTO mission_history (id_mission, action, new_value)
        VALUES (NEW.id_mission, 'INSERT', to_jsonb(NEW));
        RETURN NEW;
    ELSIF (TG_OP = 'UPDATE') THEN
        INSERT INTO mission_history (id_mission, action, old_value, new_value)
        VALUES (OLD.id_mission, 'UPDATE', to_jsonb(OLD), to_jsonb(NEW));
        RETURN NEW;
    ELSIF (TG_OP = 'DELETE') THEN
        INSERT INTO mission_history (id_mission, action, old_value)
        VALUES (OLD.id_mission, 'DELETE', to_jsonb(OLD));
        RETURN OLD;
    END IF;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION log_composantes_changes() RETURNS TRIGGER AS $$
BEGIN
    IF (TG_OP = 'INSERT') THEN
        INSERT INTO composantes_history (id_composante, action, new_value)
        VALUES (NEW.id_composante, 'INSERT', to_jsonb(NEW));
        RETURN NEW;
    ELSIF (TG_OP = 'UPDATE') THEN
        INSERT INTO composantes_history (id_composante, action, old_value, new_value)
        VALUES (OLD.id_composante, 'UPDATE', to_jsonb(OLD), to_jsonb(NEW));
        RETURN NEW;
    ELSIF (TG_OP = 'DELETE') THEN
        INSERT INTO composantes_history (id_composante, action, old_value)
        VALUES (OLD.id_composante, 'DELETE', to_jsonb(OLD));
        RETURN OLD;
    END IF;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION log_prestataires_changes() RETURNS TRIGGER AS $$
BEGIN
    IF (TG_OP = 'INSERT') THEN
        INSERT INTO prestataires_history (id_prestataire, action, new_value)
        VALUES (NEW.id_personne, 'INSERT', to_jsonb(NEW));
        RETURN NEW;
    ELSIF (TG_OP = 'UPDATE') THEN
        INSERT INTO prestataires_history (id_prestataire, action, old_value, new_value)
        VALUES (OLD.id_personne, 'UPDATE', to_jsonb(OLD), to_jsonb(NEW));
        RETURN NEW;
    ELSIF (TG_OP = 'DELETE') THEN
        INSERT INTO prestataires_history (id_prestataire, action, old_value)
        VALUES (OLD.id_personne, 'DELETE', to_jsonb(OLD));
        RETURN OLD;
    END IF;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION log_commercial_changes() RETURNS TRIGGER AS $$
BEGIN
    IF (TG_OP = 'INSERT') THEN
        INSERT INTO commercial_history (id_commercial, action, new_value)
        VALUES (NEW.id_personne, 'INSERT', to_jsonb(NEW));
        RETURN NEW;
    ELSIF (TG_OP = 'UPDATE') THEN
        INSERT INTO commercial_history (id_commercial, action, old_value, new_value)
        VALUES (OLD.id_personne, 'UPDATE', to_jsonb(OLD), to_jsonb(NEW));
        RETURN NEW;
    ELSIF (TG_OP = 'DELETE') THEN
        INSERT INTO commercial_history (id_commercial, action, old_value)
        VALUES (OLD.id_personne, 'DELETE', to_jsonb(OLD));
        RETURN OLD;
    END IF;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION log_client_changes() RETURNS TRIGGER AS $$
BEGIN
    IF (TG_OP = 'INSERT') THEN
        INSERT INTO client_history (id_client, action, new_value)
        VALUES (NEW.id_client, 'INSERT', to_jsonb(NEW));
        RETURN NEW;
    ELSIF (TG_OP = 'UPDATE') THEN
        INSERT INTO client_history (id_client, action, old_value, new_value)
        VALUES (OLD.id_client, 'UPDATE', to_jsonb(OLD), to_jsonb(NEW));
        RETURN NEW;
    ELSIF (TG_OP = 'DELETE') THEN
        INSERT INTO client_history (id_client, action, old_value)
        VALUES (OLD.id_client, 'DELETE', to_jsonb(OLD));
        RETURN OLD;
    END IF;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION log_activite_changes() RETURNS TRIGGER AS $$
BEGIN
    IF (TG_OP = 'INSERT') THEN
        INSERT INTO activite_history (id_activite, action, new_value)
        VALUES (NEW.id_activite, 'INSERT', to_jsonb(NEW));
        RETURN NEW;
    ELSIF (TG_OP = 'UPDATE') THEN
        INSERT INTO activite_history (id_activite, action, old_value, new_value)
        VALUES (OLD.id_activite, 'UPDATE', to_jsonb(OLD), to_jsonb(NEW));
        RETURN NEW;
    ELSIF (TG_OP = 'DELETE') THEN
        INSERT INTO activite_history (id_activite, action, old_value)
        VALUES (OLD.id_activite, 'DELETE', to_jsonb(OLD));
        RETURN OLD;
    END IF;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION log_bon_de_livraison_changes() RETURNS TRIGGER AS $$
BEGIN
    IF (TG_OP = 'INSERT') THEN
        INSERT INTO bon_de_livraison_history (id_bdl, action, new_value)
        VALUES (NEW.id_bdl, 'INSERT', to_jsonb(NEW));
        RETURN NEW;
    ELSIF (TG_OP = 'UPDATE') THEN
        INSERT INTO bon_de_livraison_history (id_bdl, action, old_value, new_value)
        VALUES (OLD.id_bdl, 'UPDATE', to_jsonb(OLD), to_jsonb(NEW));
        RETURN NEW;
    ELSIF (TG_OP = 'DELETE') THEN
        INSERT INTO bon_de_livraison_history (id_bdl, action, old_value)
        VALUES (OLD.id_bdl, 'DELETE', to_jsonb(OLD));
        RETURN OLD;
    END IF;
END;
$$ LANGUAGE plpgsql;

-- Création des triggers
CREATE TRIGGER trg_mission_changes
AFTER INSERT OR UPDATE OR DELETE ON MISSION
FOR EACH ROW EXECUTE FUNCTION log_mission_changes();

CREATE TRIGGER trg_composantes_changes
AFTER INSERT OR UPDATE OR DELETE ON COMPOSANTE
FOR EACH ROW EXECUTE FUNCTION log_composantes_changes();

CREATE TRIGGER trg_prestataires_changes
AFTER INSERT OR UPDATE OR DELETE ON PRESTATAIRE
FOR EACH ROW EXECUTE FUNCTION log_prestataires_changes();

CREATE TRIGGER trg_commercial_changes
AFTER INSERT OR UPDATE OR DELETE ON COMMERCIAL
FOR EACH ROW EXECUTE FUNCTION log_commercial_changes();

CREATE TRIGGER trg_client_changes
AFTER INSERT OR UPDATE OR DELETE ON CLIENT
FOR EACH ROW EXECUTE FUNCTION log_client_changes();

CREATE TRIGGER trg_activite_changes
AFTER INSERT OR UPDATE OR DELETE ON ACTIVITE
FOR EACH ROW EXECUTE FUNCTION log_activite_changes();

CREATE TRIGGER trg_bon_de_livraison_changes
AFTER INSERT OR UPDATE OR DELETE ON BON_DE_LIVRAISON
FOR EACH ROW EXECUTE FUNCTION log_bon_de_livraison_changes();

-- Création de la vue matérialisée
CREATE OR REPLACE VIEW historique_global AS
SELECT 
    history_id,
    'composantes' AS "table modifiée",
    id_composante AS id,
    action,
    old_value AS "ancienne valeur",
    new_value AS "nouvelle valeur",
    TO_CHAR(changed_at, 'YYYY-MM-DD HH24:MI:SS') AS "changé le"
FROM 
    composantes_history
UNION ALL
SELECT 
    history_id,
    'prestataires' AS "table modifiée",
    id_prestataire AS id,
    action,
    old_value AS "ancienne valeur",
    new_value AS "nouvelle valeur",
    TO_CHAR(changed_at, 'YYYY-MM-DD HH24:MI:SS') AS "changé le"
FROM 
    prestataires_history
UNION ALL
SELECT 
    history_id,
    'commercial' AS "table modifiée",
    id_commercial AS id,
    action,
    old_value AS "ancienne valeur",
    new_value AS "nouvelle valeur",
    TO_CHAR(changed_at, 'YYYY-MM-DD HH24:MI:SS') AS "changé le"
FROM 
    commercial_history
UNION ALL
SELECT 
    history_id,
    'client' AS "table modifiée",
    id_client AS id,
    action,
    old_value AS "ancienne valeur",
    new_value AS "nouvelle valeur",
    TO_CHAR(changed_at, 'YYYY-MM-DD HH24:MI:SS') AS "changé le"
FROM 
    client_history
UNION ALL
SELECT 
    history_id,
    'activite' AS "table modifiée",
    id_activite AS id,
    action,
    old_value AS "ancienne valeur",
    new_value AS "nouvelle valeur",
    TO_CHAR(changed_at, 'YYYY-MM-DD HH24:MI:SS') AS "changé le"
FROM 
    activite_history
UNION ALL
SELECT 
    history_id,
    'bon_de_livraison' AS "table modifiée",
    id_bdl AS id,
    action,
    old_value AS "ancienne valeur",
    new_value AS "nouvelle valeur",
    TO_CHAR(changed_at, 'YYYY-MM-DD HH24:MI:SS') AS "changé le"
FROM 
    bon_de_livraison_history;

-- Insertion de personnes
INSERT INTO PERSONNE (prenom, nom, email, mdp)
VALUES ('Jean', 'Dupont', 'jean@example.com', 'motdepasse1'),
       ('Marie', 'Martin', 'marie@example.com', 'motdepasse2'),
       ('Pierre', 'Durand', 'pierre@example.com', 'motdepasse3'),
       ('Karim', 'Ahmoud', 'karim@example.com', 'motdepasse4'),
       ('David', 'Hébert', 'hebert@exemple.com', 'motdepasse5'),
       ('Franck', 'Butelle', 'butelle@exemple.com', 'motdepasse6'),
       ('Aurelie', 'Nassiet', 'nassiet@exemple.com', 'motdepasse7'),
       ('Vague', 'Kris', 'kris@exemple.com', 'motdepasse8'),
       ('Marya', 'Latif', 'latifmarya@gmail.com', 'mdp'),
       ('Aboubakar', 'Baouchi', 'aboubakar.baouchi@gmail.com', 'motdepasse10'),
       ('India', 'Cabo', 'ic@gmail.com', 'hihi');

-- Insertion de clients
INSERT INTO CLIENT (nom_client)
VALUES ('Client A'),
       ('Client B');

-- Insertion de localités et types de voie
INSERT INTO LOCALITE (cp, ville)
VALUES (95120, 'Ermont'),
       (93800, 'Epinay-sur-Seine');

INSERT INTO TYPEVOIE (libelle)
VALUES ('Avenue'),
       ('Rue'),
       ('Chemin');

-- Insertion d'un administateur
INSERT INTO administrateur VALUES (11);

-- Insertion d'adresses
INSERT INTO ADRESSE (numero, nom_voie, id_type_voie, id_localite)
VALUES (1, 'nomVoie1', 1, 1),
       (2, 'nomVoie2', 2, 2),
       (1, 'nomVoie3', 2, 2);

-- Insertion de composantes
INSERT INTO COMPOSANTE (nom_composante, id_client, id_adresse)
VALUES ('Composante X', 1, 1),
       ('Composante Y', 1, 2),
       ('Finance', 2, 3);

-- Insertion de missions
INSERT INTO MISSION (nom_mission, type_bdl, id_composante)
VALUES ('mission1', 'Heure', 1),
       ('mission2', 'Journée', 2),
       ('mission3', 'Demi-journée', 3);

-- Insertion de prestataires
INSERT INTO PRESTATAIRE (id_personne, interne)
VALUES (1, true),
       (2, true),
       (3, false),
       (4, true);

-- Insertion de commerciaux
INSERT INTO COMMERCIAL (id_personne, interne)
VALUES (2, true),
       (9, true);

-- Insertion de gestionnaires
INSERT INTO GESTIONNAIRE (id_personne)
VALUES (9),
       (10),
       (11);

-- Insertion d'interlocuteurs
INSERT INTO INTERLOCUTEUR (id_personne)
VALUES (5),
       (6),
       (7),
       (8);

-- Lien interlocuteurs composantes
INSERT INTO DIRIGE (id_composante, id_personne)
VALUES (1, 5),
       (2, 6),
       (2, 8),
       (3, 7);

-- Lien commercial composantes
INSERT INTO estDans (id_composante, id_personne)
VALUES (1, 2),
       (2, 2),
       (3, 2);

-- Lien prestataire missions
INSERT INTO travailleAvec (id_personne, id_mission)
VALUES (1, 2),
       (1, 3),
       (3, 1);

-- Insertion de bons de livraison
INSERT INTO BON_DE_LIVRAISON (id_interlocuteur, id_mission, id_prestataire, mois)
VALUES (5, 1, 3, '2024-01'),
       (6, 2, 2, '2024-02'),
       (7, 3, 1, '2024-01');

-- Insertion d'activités
INSERT INTO ACTIVITE (commentaire, date_bdl, id_personne, id_bdl)
VALUES ('com1', '2023-12-01', 1, 1),
       ('com2', '2023-12-01', 1, 2),
       ('com3', '2023-12-01', 3, 3);

-- Insertion d'heures
INSERT INTO NB_HEURE (id_activite, nb_heure)
VALUES (1, 70),
       (2, 67);

-- Insertion de demi-journées
INSERT INTO DEMI_JOUR (id_activite, nb_demi_journee)
VALUES (3, 2);
