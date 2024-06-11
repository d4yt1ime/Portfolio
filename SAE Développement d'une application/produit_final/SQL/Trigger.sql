DROP TYPE IF EXISTS event CASCADE;
DROP TABLE IF EXISTS log CASCADE;
DROP TABLE IF EXISTS demandes CASCADE;


CREATE TYPE event as 
    ENUM ('INSERT', 'DELETE', 'UPDATE');

CREATE TABLE log(
    action event,
    date_modif timestamp default current_timestamp,
    nom VARCHAR,
    prenom VARCHAR,
    id_personne INT
);


CREATE OR REPLACE FUNCTION fonction_log() 
RETURNS TRIGGER AS 
$$ 
BEGIN 
    IF TG_OP = 'INSERT' THEN
        INSERT INTO log (action, nom, prenom, id_personne) VALUES (TG_OP::event, NEW.nom, NEW.prenom, NEW.id_personne);
    ELSIF TG_OP = 'UPDATE' THEN 
        INSERT INTO log (action, nom, prenom, id_personne) VALUES (TG_OP::event, NEW.nom, NEW.prenom, NEW.id_personne);
    ELSE 
        INSERT INTO log (action, nom, prenom, id_personne) VALUES (TG_OP::event, OLD.nom, OLD.prenom, OLD.id_personne);
    END IF;
    RETURN NULL;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER personne_trig
    AFTER
    UPDATE or INSERT or DELETE on personne
        FOR EACH ROW
        EXECUTE PROCEDURE fonction_log();

CREATE TABLE demandes(
    id_personne INT,
    idDepartement INT,
    FOREIGN KEY(idDepartement) REFERENCES departement(idDepartement) ON DELETE CASCADE
);


CREATE OR REPLACE FUNCTION fonction_demande() 
RETURNS TRIGGER AS 
$$ 
BEGIN 
    IF TG_OP = 'INSERT' THEN
        INSERT INTO demandes (id_personne, idDepartement) VALUES ((SELECT id_personne FROM departement WHERE idDepartement = NEW.idDepartement), NEW.idDepartement);
    END IF;
    RETURN NULL;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER besoin_trig
    AFTER
    INSERT on besoin
        FOR EACH ROW
        EXECUTE PROCEDURE fonction_demande();