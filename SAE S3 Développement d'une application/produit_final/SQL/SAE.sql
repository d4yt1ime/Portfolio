DROP TABLE IF EXISTS personne CASCADE;
DROP TABLE IF EXISTS discipline CASCADE;
DROP TABLE IF EXISTS secretaire CASCADE;
DROP TABLE IF EXISTS categorie CASCADE;
DROP TABLE IF EXISTS annee CASCADE;
DROP TABLE IF EXISTS Diplome CASCADE;
DROP TABLE IF EXISTS enseignant CASCADE;
DROP TABLE IF EXISTS equipedirection CASCADE;
DROP TABLE IF EXISTS semestre CASCADE;
DROP TABLE IF EXISTS Niveau CASCADE;
DROP TABLE IF EXISTS directeur CASCADE;
DROP TABLE IF EXISTS departement CASCADE;
DROP TABLE IF EXISTS formation CASCADE;
DROP TABLE IF EXISTS Besoin CASCADE;
DROP TABLE IF EXISTS assigner CASCADE;
DROP TABLE IF EXISTS propose CASCADE;
DROP TABLE IF EXISTS connaitAussi CASCADE;
DROP TABLE IF EXISTS enseigne CASCADE;

CREATE TABLE personne(
   id_personne INT,
   nom VARCHAR(50) NOT NULL,
   prenom VARCHAR(50) NOT NULL,
   email VARCHAR(50),
   phone VARCHAR(50),
   motDePasse VARCHAR,
   PRIMARY KEY(id_personne)
);

CREATE TABLE discipline(
   idDiscipline INT,
   libelleDisc VARCHAR(25) NOT NULL,
   PRIMARY KEY(idDiscipline)
);

CREATE TABLE secretaire(
   id_personne INT,
   PRIMARY KEY(id_personne),
   FOREIGN KEY(id_personne) REFERENCES personne(id_personne) ON DELETE CASCADE
);

CREATE TABLE categorie(
   id_categorie SMALLINT,
   sigleCat VARCHAR(5) NOT NULL,
   libelleCat VARCHAR(50),
   serviceStatutaire SMALLINT NOT NULL,
   serviceComplémentaireReferentiel SMALLINT,
   ServiceComplémentaireEnseignement SMALLINT,
   PRIMARY KEY(id_categorie)
);

CREATE TABLE annee(
   AA SMALLINT,
   PRIMARY KEY(AA)
);

CREATE TABLE Diplome(
   idDiplome INT,
   libelle VARCHAR(50),
   PRIMARY KEY(idDiplome)
);

CREATE TABLE enseignant(
   id_personne INT,
   idDiscipline INT NOT NULL,
   id_categorie SMALLINT NOT NULL,
   AA SMALLINT NOT NULL,
   PRIMARY KEY(id_personne),
   FOREIGN KEY(id_personne) REFERENCES personne(id_personne) ON DELETE CASCADE,
   FOREIGN KEY(idDiscipline) REFERENCES discipline(idDiscipline) ON DELETE CASCADE,
   FOREIGN KEY(id_categorie) REFERENCES categorie(id_categorie) ON DELETE CASCADE,
   FOREIGN KEY(AA) REFERENCES annee(AA) ON DELETE CASCADE
);

CREATE TABLE equipedirection(
   id_personne INT,
   PRIMARY KEY(id_personne),
   FOREIGN KEY(id_personne) REFERENCES enseignant(id_personne) ON DELETE CASCADE
);

CREATE TABLE semestre(
   AA SMALLINT,
   S SMALLINT,
   PRIMARY KEY(AA, S),
   FOREIGN KEY(AA) REFERENCES annee(AA) ON DELETE CASCADE
);

CREATE TABLE Niveau(
   idDiplome INT,
   IdNiveau INT,
   Niveau SMALLINT,
   PRIMARY KEY(idDiplome, IdNiveau),
   FOREIGN KEY(idDiplome) REFERENCES Diplome(idDiplome) ON DELETE CASCADE
);

CREATE TABLE directeur(
   id_personne INT,
   PRIMARY KEY(id_personne),
   FOREIGN KEY(id_personne) REFERENCES enseignant(id_personne) ON DELETE CASCADE
);

CREATE TABLE departement(
   idDepartement INT,
   sigleDept VARCHAR(50) NOT NULL,
   libelleDept VARCHAR(50),
   id_personne INT NOT NULL,
   PRIMARY KEY(idDepartement),
   UNIQUE(id_personne),
   FOREIGN KEY(id_personne) REFERENCES enseignant(id_personne) ON DELETE CASCADE
);

CREATE TABLE formation(
   idFormation INT,
   nom VARCHAR(50) NOT NULL,
   idDiplome INT NOT NULL,
   IdNiveau INT NOT NULL,
   PRIMARY KEY(idFormation),
   FOREIGN KEY(idDiplome, IdNiveau) REFERENCES Niveau(idDiplome, IdNiveau) ON DELETE CASCADE
);

CREATE TABLE Besoin(
   AA SMALLINT,
   S SMALLINT,
   idFormation INT,
   idDiscipline INT,
   idDepartement INT,
   besoin_heure INT NOT NULL,
   PRIMARY KEY(AA, S, idFormation, idDiscipline, idDepartement),
   FOREIGN KEY(AA, S) REFERENCES semestre(AA, S) ON DELETE CASCADE,
   FOREIGN KEY(idFormation) REFERENCES formation(idFormation) ON DELETE CASCADE,
   FOREIGN KEY(idDiscipline) REFERENCES discipline(idDiscipline) ON DELETE CASCADE,
   FOREIGN KEY(idDepartement) REFERENCES departement(idDepartement) ON DELETE CASCADE
);

CREATE TABLE assigner(
   id_personne INT,
   idDepartement INT,
   AA SMALLINT,
   S SMALLINT,
   quotite DECIMAL(2,2),
   PRIMARY KEY(id_personne, idDepartement, AA, S),
   FOREIGN KEY(id_personne) REFERENCES personne(id_personne) ON DELETE CASCADE,
   FOREIGN KEY(idDepartement) REFERENCES departement(idDepartement) ON DELETE CASCADE,
   FOREIGN KEY(AA, S) REFERENCES semestre(AA, S) ON DELETE CASCADE
);

CREATE TABLE propose(
   idDepartement INT,
   idFormation INT,
   PRIMARY KEY(idDepartement, idFormation),
   FOREIGN KEY(idDepartement) REFERENCES departement(idDepartement) ON DELETE CASCADE,
   FOREIGN KEY(idFormation) REFERENCES formation(idFormation) ON DELETE CASCADE
);

CREATE TABLE connaitAussi(
   id_personne INT,
   idDiscipline INT,
   PRIMARY KEY(id_personne, idDiscipline),
   FOREIGN KEY(id_personne) REFERENCES enseignant(id_personne) ON DELETE CASCADE,
   FOREIGN KEY(idDiscipline) REFERENCES discipline(idDiscipline) ON DELETE CASCADE
);

CREATE TABLE enseigne(
   id_personne INT,
   idDiscipline INT,
   idDepartement INT,
   AA SMALLINT,
   S SMALLINT,
   nbHeureEns SMALLINT,
   typeH VARCHAR(100),
   FOREIGN KEY(id_personne) REFERENCES enseignant(id_personne) ON DELETE CASCADE,
   FOREIGN KEY(idDiscipline) REFERENCES discipline(idDiscipline) ON DELETE CASCADE,
   FOREIGN KEY(idDepartement) REFERENCES departement(idDepartement) ON DELETE CASCADE,
   FOREIGN KEY(AA, S) REFERENCES semestre(AA, S) ON DELETE CASCADE
);


INSERT INTO annee VALUES (2020);
INSERT INTO annee VALUES (2021);
INSERT INTO annee VALUES (2022);
INSERT INTO annee VALUES (2023);
INSERT INTO annee VALUES (2024);

INSERT INTO semestre VALUES (2020,1);
INSERT INTO semestre VALUES (2020,2);
INSERT INTO semestre VALUES (2021,1);
INSERT INTO semestre VALUES (2021,2);
INSERT INTO semestre VALUES (2022,1);
INSERT INTO semestre VALUES (2022,2);
INSERT INTO semestre VALUES (2023,1);
INSERT INTO semestre VALUES (2023,2);
INSERT INTO semestre VALUES (2024,1);
INSERT INTO semestre VALUES (2024,2);

INSERT INTO personne VALUES
(123, 'Di', 'Evoluzion', 'dievoluzion@gmail.com', '9876543210', '$2y$10$sIigogCcCwd5sS5sc.ZgLOhEgrBSYhIO9EnBm0V.RrXjzs2nKo6Fi' ),

(1, 'Dupont', 'Jean', 'jean.dupont@univ-paris13.com', '1234567890', '$2y$10$sIigogCcCwd5sS5sc.ZgLOhEgrBSYhIO9EnBm0V.RrXjzs2nKo6Fi' ),
(2, 'Martin', 'Sophie', 'sophie.martin@univ-paris13.com', '2345678901', '$2y$10$sIigogCcCwd5sS5sc.ZgLOhEgrBSYhIO9EnBm0V.RrXjzs2nKo6Fi' ),
(3, 'Lefevre', 'Pierre', 'pierre.lefevre@univ-paris13.com', '3456789012', '$2y$10$sIigogCcCwd5sS5sc.ZgLOhEgrBSYhIO9EnBm0V.RrXjzs2nKo6Fi' ),
(4, 'Dubois', 'Isabelle', 'isabelle.dubois@univ-paris13.com', '4567890123', '$2y$10$sIigogCcCwd5sS5sc.ZgLOhEgrBSYhIO9EnBm0V.RrXjzs2nKo6Fi' ),
(5, 'Bertrand', 'Marie', 'marie.bertrand@univ-paris13.com', '5678901234', '$2y$10$sIigogCcCwd5sS5sc.ZgLOhEgrBSYhIO9EnBm0V.RrXjzs2nKo6Fi' ),
(6, 'Lemoine', 'François', 'francois.lemoine@univ-paris13.com', '6789012345', '$2y$10$sIigogCcCwd5sS5sc.ZgLOhEgrBSYhIO9EnBm0V.RrXjzs2nKo6Fi' ),
(7, 'Roux', 'Julie', 'julie.roux@univ-paris13.com', '7890123456', '$2y$10$sIigogCcCwd5sS5sc.ZgLOhEgrBSYhIO9EnBm0V.RrXjzs2nKo6Fi' ),
(8, 'Blanchard', 'Luc', 'luc.blanchard@univ-paris13.com', '8901234567', '$2y$10$sIigogCcCwd5sS5sc.ZgLOhEgrBSYhIO9EnBm0V.RrXjzs2nKo6Fi' ),
(9, 'Fournier', 'Catherine', 'catherine.fournier@univ-paris13.com', '9012345678', '$2y$10$sIigogCcCwd5sS5sc.ZgLOhEgrBSYhIO9EnBm0V.RrXjzs2nKo6Fi' ),
(10, 'Moreau', 'Michel', 'michel.moreau@univ-paris13.com', '1234567890', '$2y$10$sIigogCcCwd5sS5sc.ZgLOhEgrBSYhIO9EnBm0V.RrXjzs2nKo6Fi' ),
(11, 'Girard', 'Émilie', 'emilie.girard@univ-paris13.com', '2345678901', '$2y$10$sIigogCcCwd5sS5sc.ZgLOhEgrBSYhIO9EnBm0V.RrXjzs2nKo6Fi' ),
(12, 'Leroux', 'Paul', 'paul.leroux@univ-paris13.com', '3456789012', '$2y$10$sIigogCcCwd5sS5sc.ZgLOhEgrBSYhIO9EnBm0V.RrXjzs2nKo6Fi' ),
(13, 'Caron', 'Caroline', 'caroline.caron@univ-paris13.com', '4567890123', '$2y$10$sIigogCcCwd5sS5sc.ZgLOhEgrBSYhIO9EnBm0V.RrXjzs2nKo6Fi' ),
(14, 'Marchand', 'Thomas', 'thomas.marchand@univ-paris13.com', '5678901234', '$2y$10$sIigogCcCwd5sS5sc.ZgLOhEgrBSYhIO9EnBm0V.RrXjzs2nKo6Fi' ),
(15, 'Dufresne', 'Anne', 'anne.dufresne@univ-paris13.com', '6789012345', '$2y$10$sIigogCcCwd5sS5sc.ZgLOhEgrBSYhIO9EnBm0V.RrXjzs2nKo6Fi' ),
(16, 'Lévesque', 'Alexandre', 'alexandre.levesque@univ-paris13.com', '7890123456', '$2y$10$sIigogCcCwd5sS5sc.ZgLOhEgrBSYhIO9EnBm0V.RrXjzs2nKo6Fi' ),
(17, 'Boucher', 'Valérie', 'valerie.boucher@univ-paris13.com', '8901234567', '$2y$10$sIigogCcCwd5sS5sc.ZgLOhEgrBSYhIO9EnBm0V.RrXjzs2nKo6Fi' ),
(18, 'Roy', 'Mathieu', 'mathieu.roy@univ-paris13.com', '9012345678', '$2y$10$sIigogCcCwd5sS5sc.ZgLOhEgrBSYhIO9EnBm0V.RrXjzs2nKo6Fi' ),
(19, 'Deschamps', 'Nathalie', 'nathalie.deschamps@univ-paris13.com', '1234567890', '$2y$10$sIigogCcCwd5sS5sc.ZgLOhEgrBSYhIO9EnBm0V.RrXjzs2nKo6Fi' ),
(20, 'Lalande', 'Philippe', 'philippe.lalande@univ-paris13.com', '2345678901', '$2y$10$sIigogCcCwd5sS5sc.ZgLOhEgrBSYhIO9EnBm0V.RrXjzs2nKo6Fi' ),
(21, 'Lavoie', 'Sylvie', 'sylvie.lavoie@univ-paris13.com', '3456789012', '$2y$10$sIigogCcCwd5sS5sc.ZgLOhEgrBSYhIO9EnBm0V.RrXjzs2nKo6Fi' ),
(22, 'Léger', 'Marc', 'marc.leger@univ-paris13.com', '4567890123', '$2y$10$sIigogCcCwd5sS5sc.ZgLOhEgrBSYhIO9EnBm0V.RrXjzs2nKo6Fi' ),
(23, 'Gagné', 'Christine', 'christine.gagne@univ-paris13.com', '5678901234', '$2y$10$sIigogCcCwd5sS5sc.ZgLOhEgrBSYhIO9EnBm0V.RrXjzs2nKo6Fi' ),
(24, 'Thibault', 'Daniel', 'daniel.thibault@univ-paris13.com', '6789012345', '$2y$10$sIigogCcCwd5sS5sc.ZgLOhEgrBSYhIO9EnBm0V.RrXjzs2nKo6Fi' ),
(25, 'Gagnon', 'Josée', 'josee.gagnon@univ-paris13.com', '7890123456', '$2y$10$sIigogCcCwd5sS5sc.ZgLOhEgrBSYhIO9EnBm0V.RrXjzs2nKo6Fi' ),
(26, 'Lachance', 'Patrick', 'patrick.lachance@univ-paris13.com', '8901234567', '$2y$10$sIigogCcCwd5sS5sc.ZgLOhEgrBSYhIO9EnBm0V.RrXjzs2nKo6Fi' ),
(27, 'Ferland', 'Louise', 'louise.ferland@univ-paris13.com', '9012345678', '$2y$10$sIigogCcCwd5sS5sc.ZgLOhEgrBSYhIO9EnBm0V.RrXjzs2nKo6Fi' ),
(28, 'Côté', 'Christian', 'christian.cote@univ-paris13.com', '1234567890', '$2y$10$sIigogCcCwd5sS5sc.ZgLOhEgrBSYhIO9EnBm0V.RrXjzs2nKo6Fi' ),
(29, 'Beauchamp', 'Sophie', 'sophie.beauchamp@univ-paris13.com', '2345678901', '$2y$10$sIigogCcCwd5sS5sc.ZgLOhEgrBSYhIO9EnBm0V.RrXjzs2nKo6Fi' ),
(30, 'Trudeau', 'Robert', 'robert.trudeau@univ-paris13.com', '3456789012', '$2y$10$sIigogCcCwd5sS5sc.ZgLOhEgrBSYhIO9EnBm0V.RrXjzs2nKo6Fi' ),
(31, 'Pelletier', 'Éric', 'eric.pelletier@univ-paris13.com', '4567890123', '$2y$10$sIigogCcCwd5sS5sc.ZgLOhEgrBSYhIO9EnBm0V.RrXjzs2nKo6Fi' ),
(32, 'Rousseau', 'Martine', 'martine.rousseau@univ-paris13.com', '5678901234', '$2y$10$sIigogCcCwd5sS5sc.ZgLOhEgrBSYhIO9EnBm0V.RrXjzs2nKo6Fi' ),
(33, 'Leclerc', 'Françoise', 'francoise.leclerc@univ-paris13.com', '6789012345', '$2y$10$sIigogCcCwd5sS5sc.ZgLOhEgrBSYhIO9EnBm0V.RrXjzs2nKo6Fi' ),
(34, 'Bernard', 'Alain', 'alain.bernard@univ-paris13.com', '7890123456', '$2y$10$sIigogCcCwd5sS5sc.ZgLOhEgrBSYhIO9EnBm0V.RrXjzs2nKo6Fi' ),
(35, 'Lépine', 'Julien', 'julien.lepine@univ-paris13.com', '8901234567', '$2y$10$sIigogCcCwd5sS5sc.ZgLOhEgrBSYhIO9EnBm0V.RrXjzs2nKo6Fi' ),
(36, 'St-Pierre', 'Valérie', 'valerie.stpierre@univ-paris13.com', '9012345678', '$2y$10$sIigogCcCwd5sS5sc.ZgLOhEgrBSYhIO9EnBm0V.RrXjzs2nKo6Fi' ),
(37, 'Lafontaine', 'Denis', 'denis.lafontaine@univ-paris13.com', '1234567890', '$2y$10$sIigogCcCwd5sS5sc.ZgLOhEgrBSYhIO9EnBm0V.RrXjzs2nKo6Fi' ),
(38, 'Cormier', 'Isabelle', 'isabelle.cormier@univ-paris13.com', '2345678901', '$2y$10$sIigogCcCwd5sS5sc.ZgLOhEgrBSYhIO9EnBm0V.RrXjzs2nKo6Fi' ),
(39, 'Leblanc', 'Sébastien', 'sebastien.leblanc@univ-paris13.com', '3456789012', '$2y$10$sIigogCcCwd5sS5sc.ZgLOhEgrBSYhIO9EnBm0V.RrXjzs2nKo6Fi' ),
(40, 'Martel', 'Marie-Pierre', 'mariepierre.martel@univ-paris13.com', '4567890123', '$2y$10$sIigogCcCwd5sS5sc.ZgLOhEgrBSYhIO9EnBm0V.RrXjzs2nKo6Fi' ),

(100, 'AAAAAA', 'Oui', 'AAAAAA@gmail.com', '1234255545', '$2y$10$sIigogCcCwd5sS5sc.ZgLOhEgrBSYhIO9EnBm0V.RrXjzs2nKo6Fi');

INSERT INTO discipline VALUES (1, 'MATH');
INSERT INTO discipline VALUES (2, 'INFO-PROG');
INSERT INTO discipline VALUES (3, 'INFO-INDUSTRIEL');
INSERT INTO discipline VALUES (4, 'INFO-RESEAU');
INSERT INTO discipline VALUES (5, 'INFO-BUREAUTIQUE');
INSERT INTO discipline VALUES (6, 'ECOGESTION');
INSERT INTO discipline VALUES (7, 'ELECTRONIQUE');
INSERT INTO discipline VALUES (8, 'DROIT');
INSERT INTO discipline VALUES (9, 'ANGLAIS');
INSERT INTO discipline VALUES (10, 'COMMUNICATION');
INSERT INTO discipline VALUES (11, 'ESPAGNOL');

INSERT INTO categorie (id_categorie, sigleCat, libelleCat, serviceStatutaire, serviceComplémentaireReferentiel, ServiceComplémentaireEnseignement)
VALUES
(1, 'PR', 'Catégorie 1', 40, 10, 5),
(2, 'MCF', 'Catégorie 2', 35, 15, 7),
(3, 'ESAS', 'Catégorie 3', 45, 5, 3),
(4, 'PAST', 'Catégorie 4', 40, 10, 5),
(5, 'ATER', 'Catégorie 5', 35, 15, 7),
(6, 'VAC', 'Vacataire', 45, 5, 3),
(7, 'DOC', 'Catégorie 7', 40, 10, 5),
(8, 'CDD', 'Contrat durée déterminée', 35, 15, 7),
(9, 'CDI', 'Contrat durée indéterminée', 45, 5, 3);

INSERT INTO enseignant VALUES 
(1,2,9,2020),
(2,1,1,2020),
(3,2,9,2020),
(4,3,2,2020),
(5,4,9,2020),
(6,5,4,2021),
(7,6,5,2021),
(14,5,9,2021),
(15,2,5,2023),
(16,3,6,2022),
(17,4,7,2022),
(18,6,8,2020),
(19,7,2,2020),
(20,1,1,2020),
(21,2,9,2020),
(22,3,2,2020),
(23,4,9,2020),
(24,5,4,2021),
(25,6,5,2021),
(26,7,6,2021),
(27,8,9,2021),
(28,9,9,2023),
(29,10,9,2023),
(30,11,3,2023),
(31,1,9,2023),
(32,2,9,2023),
(33,3,9,2024),
(34,4,7,2024),
(35,5,9,2024),
(36,6,8,2024),
(37,7,8,2024),
(38,8,8,2024),
(39,9,9,2024),
(40,10,9,2024),

(100,1,9,2024);

INSERT INTO directeur VALUES 
(1);

INSERT INTO secretaire VALUES 
(8),
(9),
(10),
(11),
(12),
(13);

INSERT INTO equipedirection VALUES
(1),
(14),
(15),
(16),
(17),
(18),
(19);

INSERT INTO departement VALUES 
(1, 'SD', 'Science des données', 2),
(2, 'RT', 'Réseaux & Télécoms', 3),
(3, 'INFO', 'Informatique', 4),
(4, 'GEII', 'Génie éléctrique et informatique industrielle', 5),
(5, 'GEA', 'Gestion des entreprises et des administrations', 6),
(6, 'CJ', 'Carrières juridiques', 7);

INSERT INTO diplome VALUES
(1, 'BUT'),
(2, 'LP');

INSERT INTO Niveau VALUES
(1,1,1),
(1,2,2),
(1,3,3),
(2,1,1),
(2,2,2),
(2,3,3);

INSERT INTO formation VALUES
(1,'BUT1',1,1),
(2,'BUT2',1,2),
(3,'BUT3',1,3),
(4,'LP1',2,1),
(5,'LP2',2,2),
(6,'LP3',2,3);

INSERT INTO propose VALUES
(1,1),
(1,2),
(1,3),
(1,4),
(1,5),
(1,6),
(2,1),
(2,2),
(2,3),
(2,4),
(2,5),
(2,6),
(3,1),
(3,2),
(3,3),
(3,4),
(3,5),
(3,6),
(4,1),
(4,2),
(4,3),
(5,4),
(5,5),
(5,6),
(6,1),
(6,2),
(6,3),
(6,4),
(6,5),
(6,6);

INSERT INTO assigner   VALUES 
(20,1,2020,1),
(21,3,2020,1),
(22,4,2020,2),
(23,2,2020,2),
(24,5,2021,1),
(25,5,2021,1),
(26,4,2021,2),
(27,6,2021,2),
(28,3,2023,1),
(29,5,2023,1),
(30,6,2023,2),
(31,1,2023,2),
(32,3,2023,2),
(33,4,2024,1),
(34,2,2024,1),
(35,5,2024,1),
(36,5,2024,2),
(37,4,2024,2),
(38,6,2024,2),
(39,3,2024,1),
(40,5,2024,2);

INSERT INTO connaitAussi VALUES
(21,3),
(21,5),
(22,4),
(25,8),
(26,4),
(29,6);

INSERT INTO enseigne VALUES
(20,1,1,2020,1,15,'statutaire'),
(21,2,3,2020,1,12,'statutaire'),
(21,2,3,2022,1,12,'statutaire'),
(21,2,3,2021,1,12,'statutaire'),
(21,2,3,2021,1,14,'complementaire'),
(22,3,4,2020,2,8,'statutaire'),
(23,4,2,2020,2,7,'statutaire'),
(24,5,5,2021,1,20,'complementaire'),
(25,6,5,2021,1,12,'complementaire'),
(26,7,4,2021,2,1,'statutaire'),
(27,8,6,2021,2,25,'statutaire'),
(28,9,3,2023,1,4,'complementaire'),
(29,10,5,2023,1,41,'complementaire'),
(30,11,6,2023,2,12,'complementaire'),
(31,1,1,2023,2,1,'statutaire'),
(32,2,3,2023,2,23,'statutaire'),
(33,3,4,2024,1,11,'statutaire'),
(34,4,2,2024,1,5,'statutaire'),
(35,5,5,2024,1,6,'statutaire'),
(36,6,5,2024,2,12,'statutaire'),
(37,7,4,2024,2,5,'statutaire'),
(38,8,6,2024,2,6,'statutaire'),
(39,9,3,2024,1,7,'statutaire'),
(40,10,5,2024,2,9,'statutaire'),
(100, 1, 1, 2022, 1, 10, 'statutaire'),
(100, 1, 1, 2022, 2, 12, 'statutaire'),
(100, 1, 1, 2022, 2, 1, 'complementaire'),
(100, 1, 1, 2023, 2, 1, 'statutaire'),
(100, 1, 1, 2023, 2, 20, 'statutaire'),
(100, 1, 1, 2023, 2, 1, 'complementaire'),
(100, 1, 1, 2024, 1, 10, 'statutaire'),
(100, 1, 1, 2024, 2, 12, 'statutaire'),
(100, 1, 1, 2024, 2, 1, 'statutaire'),
(100, 1, 1, 2024, 2, 20, 'statutaire'),
(100, 1, 1, 2024, 2, 1, 'complementaire'),
(100, 1, 1, 2024, 1, 1, 'complementaire'),
(100, 1, 2, 2024, 1, 1, 'statutaire'),
(100, 1, 3, 2024, 1, 1, 'statutaire'),
(100, 1, 1, 2024, 1, 2, 'complementaire'),
(100, 1, 1, 2024, 1, 1, 'complementaire');


CREATE OR REPLACE VIEW vue_enseignant 
AS
SELECT id_personne, nom, prenom 
FROM personne 
JOIN enseignant USING(id_personne);

CREATE OR REPLACE VIEW vue_directeur 
AS
SELECT id_personne, nom, prenom 
FROM personne 
JOIN directeur USING(id_personne);

CREATE OR REPLACE VIEW vue_secretaire 
AS
SELECT id_personne, nom, prenom 
FROM personne 
JOIN secretaire USING(id_personne);

CREATE OR REPLACE VIEW vue_chefdpt 
AS
SELECT id_personne, nom, prenom 
FROM personne 
JOIN departement USING(id_personne);

CREATE OR REPLACE VIEW vue_direction 
AS
SELECT id_personne, nom, prenom 
FROM personne 
JOIN equipedirection USING(id_personne);
