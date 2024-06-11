CREATE OR REPLACE FUNCTION grade(in int, OUT fonction VARCHAR)
AS 
$$
BEGIN
    CASE 
        WHEN $1 IN (SELECT id_personne FROM vue_direction) THEN fonction := 'direction';
        WHEN $1 IN (SELECT id_personne FROM vue_chefdpt) THEN fonction := 'chefdedpt';
        WHEN $1 IN (SELECT id_personne FROM vue_secretaire) THEN fonction := 'secretaire';
        WHEN $1 IN (SELECT id_personne FROM vue_enseignant) THEN fonction := 'enseignant';
        ELSE fonction := 'personne'; 
    END CASE;
END;
$$
LANGUAGE PLPGSQL;

SELECT * FROM grade(7);
