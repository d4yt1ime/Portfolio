<?php
/**
 * Classe Model pour gérer les interactions avec la base de données.\n
 * Implémente le design pattern Singleton pour garantir une unique instance.
 */
class Model
{
    /**
     * @var PDO $bd Instance de PDO pour la connexion à la base de données.
    */
    private $bd;

    /**
     * Attribut statique qui contiendra l'unique instance de Model
     */
    private static $instance = null;

    /**
     * Constructeur : effectue la connexion à la base de données.\n
     * 
     * Charge les informations d'identification à partir du fichier credentials.php.\n
     * Initialise l'instance PDO et configure les attributs de connexion.
     */
    private function __construct()
    {
        include "credentials.php";
        $this->bd = new PDO($dsn, $login, $mdp);
        $this->bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->bd->query("SET NAMES 'utf8'");
    }

    /**
     * Méthode permettant de récupérer l'unique instance de Model.\n
     * 
     * Si l'instance n'existe pas, elle est créée. Sinon, l'instance existante est retournée.\n
     * 
     * @return Model Instance unique de la classe Model.
     */
    public static function getModel()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Méthode permettant d'insérer une ligne dans la table personne.\n
    * 
    * @param string $nom Nom de la personne.
    * @param string $prenom Prénom de la personne.
    * @param string $email Adresse email de la personne.
    * @param string $mdp Mot de passe de la personne.
    * @return bool True si l'insertion a réussi, False sinon.
    */
    public function createPersonne($nom, $prenom, $email, $mdp)
    {
        $req = $this->bd->prepare('INSERT INTO PERSONNE(nom, prenom, email, mdp) VALUES(:nom, :prenom, :email, :mdp);');
        $req->bindValue(':nom', $nom);
        $req->bindValue(':prenom', $prenom);
        $req->bindValue(':email', $email);
        $req->bindValue(':mdp', $mdp);
        $req->execute();
        return (bool)$req->rowCount();

    }

    /* -------------------------------------------------------------------------
                            Méthodes DashBoard
        ------------------------------------------------------------------------*/

    /**
 * Méthode permettant de récupérer toutes les informations des missions en fonction de la composante, la société et les prestataires assignés.\n
 * 
 * Cette méthode exécute une requête SQL qui joint plusieurs tables : mission, composante, client, travailleavec et personne.\n
 * Elle retourne une liste de missions avec les informations du client, de la composante, et des prestataires assignés (ou 'Aucun' s'il n'y en a pas).\n
 * 
 * @return array|false Un tableau associatif contenant les informations des missions et des prestataires, ou false en cas d'échec.\n
 */
    public function getDashboardGestionnaire()
    {
        $req = $this->bd->prepare("SELECT c.nom_client, co.nom_composante, m.nom_mission, COALESCE(p.nom, 'Aucun') AS nom, COALESCE(p.prenom, 'Aucun') AS prenom, ta.id_personne as id_prestataire, ta.id_mission 
        FROM mission m
            JOIN composante co ON m.id_composante = co.id_composante 
            JOIN client c ON co.id_client = c.id_client 
            LEFT JOIN travailleavec ta ON m.id_mission = ta.id_mission 
            LEFT JOIN personne p ON ta.id_personne = p.id_personne;");
        $req->execute();
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }

    /* -------------------------------------------------------------------------
                         Méthodes getAll...
     ------------------------------------------------------------------------*/
    /**
     *  Méthode permettant de récupérer la liste des composantes.
     * @return array|false Un tableau associatif contenant la liste des composantes, ou false en cas d'échec.
     */
    public function getAllComposantes()
    {
        $req = $this->bd->prepare('SELECT id_composante AS id, nom_composante, nom_client FROM CLIENT JOIN COMPOSANTE using(id_client)');
        $req->execute();
        return $req->fetchall();
    }

    /**
 * Méthode permettant de récupérer la liste de tous les commerciaux avec une composante. 
 * @return array|false Un tableau associatif contenant la liste des commerciaux avec leur composante, ou false en cas d'échec.
 */
    public function getAllCommerciaux()
    {
        $req = $this->bd->prepare('SELECT personne.id_personne AS id, nom, prenom, nom_composante FROM estdans JOIN composante USING(id_composante) JOIN personne USING(id_personne);');
        $req->execute();
        return $req->fetchall();
    }

    /**
     * Méthode permettant de récupérer la liste de tous les commerciaux.
     *@return array|false Un tableau associatif contenant la liste des commerciaux, ou false en cas d'échec.
    */
    public function getCommerciaux()
    {
        $req = $this->bd->prepare('SELECT id_personne AS id, nom, prenom FROM commercial JOIN personne USING(id_personne);');
        $req->execute();
        return $req->fetchall();
    }

   /**
 * Méthode permettant de récupérer la liste de tous les prestataires.
 * @return array|false Un tableau associatif contenant la liste des prestataires, ou false en cas d'échec.
 */
    public function getAllPrestataires()
    {
        $req = $this->bd->prepare('SELECT p.id_personne AS id, nom, prenom, interne FROM PERSONNE p JOIN PRESTATAIRE pr ON p.id_personne =  pr.id_personne;');
        $req->execute();
        return $req->fetchall();
    }

    /**
 * Méthode permettant de récupérer la liste de toutes les sociétés.
 * @return array|false Un tableau associatif contenant la liste des sociétés, ou false en cas d'échec.
 */
    public function getAllClients()
    {
        $req = $this->bd->prepare('SELECT id_client AS id, nom_client, telephone_client FROM CLIENT;');
        $req->execute();
        return $req->fetchall();
    }

    /**
 * Méthode permettant de récupérer la liste de tous les gestionnaires.
 * @return array|false Un tableau associatif contenant la liste des gestionnaires, ou false en cas d'échec.
 */
    public function getAllGestionnaires()
    {
        $req = $this->bd->prepare('SELECT id_personne AS id, nom, prenom FROM GESTIONNAIRE JOIN PERSONNE USING(id_personne);');
        $req->execute();
        return $req->fetchall();
    }

   /**
 * Méthode permettant de récupérer le nom, prénom et email d'une personne en fonction de son identifiant.
 * @param int $id L'identifiant de la personne.
 * @return mixed Un tableau associatif contenant les informations de la personne, ou false en cas d'échec.
 */
    public function getInfosPersonne($id)
    {
        $req = $this->bd->prepare('SELECT id_personne, nom, prenom, email FROM PERSONNE WHERE id_personne = :id');
        $req->bindValue(':id', $id, PDO::PARAM_INT);
        $req->execute();
        return $req->fetchall()[0];
    }

    /* -------------------------------------------------------------------------
                            Méthodes Composante
       ------------------------------------------------------------------------*/
    /**
     * Méthode permettant de récupérer l'id d'un composant à l'aide de son nom et la société à laquelle il appartient
     * @param string $composante Le nom de la composante.
     * @param string $client Le nom du client.
    * @return mixed Un tableau associatif contenant l'identifiant de la composante, ou false en cas d'échec.
    */
    public function getIdComposante($composante, $client)
    {
        $req = $this->bd->prepare('SELECT id_composante FROM COMPOSANTE JOIN CLIENT USING(id_client)
                     WHERE nom_composante = :composante and nom_client = :client ');
        $req->bindValue(':client', $client);
        $req->bindValue(':composante', $composante);
        $req->execute();
        return $req->fetch(PDO::FETCH_ASSOC);
    }

    /**
    * Méthode permettant de récupérer les informations d'une composante
    * @param int $id L'identifiant de la composante.
    * @return mixed Un tableau associatif contenant les informations de la composante, ou false en cas d'échec.
    */
    public function getInfosComposante($id)
    {
        $req = $this->bd->prepare('SELECT id_composante, nom_composante, nom_client, numero, nom_voie, cp, ville, libelle
       FROM CLIENT JOIN COMPOSANTE using(id_client) JOIN ADRESSE USING(id_adresse) JOIN LOCALITE USING(id_localite) JOIN TYPEVOIE USING(id_type_voie) WHERE id_composante = :id');
        $req->bindValue(':id', $id, PDO::PARAM_INT);
        $req->execute();
        return $req->fetchall()[0];
    }

    /**
     * Méthode permettant de récupérer la liste des prestataires d'une composante
    * @param int $id L'identifiant de la composante.
    * @return array|false Un tableau associatif contenant la liste des prestataires, ou false en cas d'échec.
    */
    public function getPrestatairesComposante($id)
    {
        $req = $this->bd->prepare('SELECT DISTINCT id_personne, nom, prenom
       FROM PERSONNE JOIN PRESTATAIRE USING(id_personne) 
           JOIN TRAVAILLEAVEC USING(id_personne) 
           JOIN MISSION USING(id_mission)
       WHERE id_composante = :id');

        $req->bindValue(':id', $id, PDO::PARAM_INT);
        $req->execute();
        return $req->fetchall();
    }

    /**
     * Méthode permettant de récupérer la liste des commerciaux d'une composante
     * @param int $id L'identifiant de la composante.
    * @return array|false Un tableau associatif contenant la liste des commerciaux, ou false en cas d'échec.
    */
    public function getCommerciauxComposante($id)
    {
        $req = $this->bd->prepare('SELECT DISTINCT id_personne, nom, prenom
       FROM PERSONNE JOIN COMMERCIAL USING(id_personne) 
           JOIN ESTDANS USING(id_personne) 
       WHERE id_composante = :id');

        $req->bindValue(':id', $id, PDO::PARAM_INT);
        $req->execute();
        return $req->fetchall();
    }

    /**
     * Méthode permettant de récupérer la liste des interlocuteurs d'une composante
     * @param int $id L'identifiant de la composante.
    * @return array|false Un tableau associatif contenant la liste des interlocuteurs, ou false en cas d'échec.
    */
    public function getInterlocuteursComposante($id)
    {
        $req = $this->bd->prepare('SELECT DISTINCT id_personne, nom, prenom
       FROM PERSONNE JOIN INTERLOCUTEUR USING(id_personne) 
           JOIN DIRIGE USING(id_personne) 
       WHERE id_composante = :id');

        $req->bindValue(':id', $id, PDO::PARAM_INT);
        $req->execute();
        return $req->fetchall();
    }

    /**
     * Méthode permettant de récupérer la liste des bons de livraison liés d'une composante
     * @param int $id_composante L'identifiant de la composante.
    * @return array|false Un tableau associatif contenant la liste des bons de livraison, ou false en cas d'échec.
    */
    public function getBdlComposante($id_composante)
    {
        $req = $this->bd->prepare('SELECT DISTINCT id_prestataire, id_bdl, nom, prenom, mois
       FROM PERSONNE JOIN PRESTATAIRE USING(id_personne) 
           JOIN BON_DE_LIVRAISON ON id_personne = id_prestataire 
           JOIN MISSION USING(id_mission)
       WHERE id_composante = :id');

        $req->bindValue(':id', $id_composante);
        $req->execute();
        return $req->fetchall();
    }

    /* -------------------------------------------------------------------------
                                Méthodes Societe
       ------------------------------------------------------------------------*/
    /**
     * Méthode peremettant de récupérer la liste des interlocuteurs d'une société
     * @param int $id L'identifiant de la société.
     * @return array|false Un tableau associatif contenant la liste des interlocuteurs, ou false en cas d'échec.
    */
    public function getInterlocuteursSociete($id)
    {
        $req = $this->bd->prepare('SELECT DISTINCT id_personne, nom, prenom
       FROM PERSONNE JOIN INTERLOCUTEUR USING(id_personne) 
           JOIN DIRIGE USING(id_personne) JOIN COMPOSANTE USING(id_composante) JOIN CLIENT using(id_client) WHERE id_client = :id');

        $req->bindValue(':id', $id, PDO::PARAM_INT);
        $req->execute();
        return $req->fetchall();
    }

    /**
     * Méthode permettant de récupérer les informations d'une société
    * @param int $id L'identifiant de la société.
    * @return mixed Un tableau associatif contenant les informations de la société, ou false en cas d'échec.
    */
    public function getInfosSociete($id)
    {
        $req = $this->bd->prepare('SELECT id_client, nom_client, telephone_client FROM CLIENT WHERE id_client = :id');
        $req->bindValue(':id', $id, PDO::PARAM_INT);
        $req->execute();
        return $req->fetchall()[0];
    }

    /**
     * Méthode permettant de récupérer la liste des composantes d'une société
     * @param int $id L'identifiant de la société.
    * @return array|false Un tableau associatif contenant la liste des composantes, ou false en cas d'échec.
    */
    public function getComposantesSociete($id)
    {
        $req = $this->bd->prepare('SELECT id_composante, nom_composante FROM COMPOSANTE JOIN CLIENT using(id_client) WHERE id_client = :id');
        $req->bindValue(':id', $id, PDO::PARAM_INT);
        $req->execute();
        return $req->fetchall();
    }

    /* -------------------------------------------------------------------------
                            Méthodes assigner...
       ------------------------------------------------------------------------*/
    /**
     * Méthode permettant d'assigner un interlocuteur à une composante en connaissant le nom de la composante et de la société
    * @param string $composante Le nom de la composante.
    * @param string $client Le nom de la société.
    * @param string $email L'email de l'interlocuteur.
    * @return bool Retourne true si l'opération d'insertion a réussi, false sinon.
    */
    public function assignerInterlocuteurComposante($composante, $client, $email)
    {
        $req = $this->bd->prepare("INSERT INTO dirige (id_personne, id_composante) SELECT  (SELECT id_personne FROM PERSONNE WHERE email=:email), (SELECT c.id_composante FROM COMPOSANTE c JOIN CLIENT cl ON c.id_client = cl.id_client WHERE c.nom_composante = :nom_compo  AND cl.nom_client = :nom_client)");
        $req->bindValue(':nom_compo', $composante, PDO::PARAM_STR);
        $req->bindValue(':nom_client', $client, PDO::PARAM_STR);
        $req->bindValue(':email', $email, PDO::PARAM_STR);
        $req->execute();
        return (bool)$req->rowCount();
    }

    /**
     * Méthode permettant d'assigner un interlocuteur à une composante en connaissant l'identifiant de la composante
    * @param int $id_composante L'identifiant de la composante.
    * @param string $email L'email de l'interlocuteur.
    * @return bool Retourne true si l'opération d'insertion a réussi, false sinon.
     */
    public function assignerInterlocuteurComposanteByIdComposante($id_composante, $email)
    {
        $req = $this->bd->prepare("INSERT INTO dirige (id_personne, id_composante) SELECT  (SELECT id_personne FROM PERSONNE WHERE email=:email), :id_composante");
        $req->bindValue(':id_composante', $id_composante);
        $req->bindValue(':email', $email);
        $req->execute();
        return (bool)$req->rowCount();
    }

    /**
     * Méthode permettant d'assigner un interlocuteur à une composante en connaissant le nom de la composante et l'identifiant de la société
    * @param int $id_client L'identifiant de la société.
    * @param string $email L'email de l'interlocuteur.
    * @param string $composante Le nom de la composante.
    * @return bool Retourne true si l'opération d'insertion a réussi, false sinon.
    */
    public function assignerInterlocuteurComposanteByIdClient($id_client, $email, $composante)
    {
        $req = $this->bd->prepare("INSERT INTO dirige (id_personne, id_composante) SELECT  
                                                    (SELECT id_personne FROM PERSONNE WHERE email=:email), 
                                                    (SELECT id_composante FROM COMPOSANTE WHERE id_client = :id_client and nom_composante = :composante)");
        $req->bindValue(':composante', $composante);
        $req->bindValue(':id_client', $id_client);
        $req->bindValue(':email', $email);
        $req->execute();
        return (bool)$req->rowCount();
    }

    /* -------------------------------------------------------------------------
                                Méthodes add...
       ------------------------------------------------------------------------*/
   /**
    * Méthode permettant d'ajouter une personne dans la table prestataire en connaissant son email
    * @param string $email L'email de la personne à ajouter en tant que prestataire.
    * @return bool Retourne true si l'opération d'insertion a réussi, false sinon.
    */
    public function addPrestataire($email)
    {
        $req = $this->bd->prepare("INSERT INTO PRESTATAIRE (id_personne) SELECT id_personne FROM personne WHERE email = :email");
        $req->bindValue(':email', $email, PDO::PARAM_STR);
        $req->execute();
        return (bool)$req->rowCount();
    }

    /**
     * Méthode permettant d'ajouter une personne dans la table interlocuteur en connaissant son email
     * @param string $email L'email de la personne à ajouter en tant qu'interlocuteur.
    * @return bool Retourne true si l'opération d'insertion a réussi, false sinon.
    */
    public function addInterlocuteur($email)
    {
        $req = $this->bd->prepare("INSERT INTO INTERLOCUTEUR (id_personne) SELECT id_personne FROM personne WHERE email = :email");
        $req->bindValue(':email', $email);
        $req->execute();
        return (bool)$req->rowCount();
    }

    /**
     * Méthode permettant d'ajouter une personne dans la table commercial en connaissant son email
    * @param string $email L'email de la personne à ajouter en tant que commercial.
    * @return bool Retourne true si l'opération d'insertion a réussi, false sinon.
    */
    public function addCommercial($email)
    {
        $req = $this->bd->prepare("INSERT INTO COMMERCIAL (id_personne) SELECT id_personne FROM personne WHERE email = :email");
        $req->bindValue(':email', $email, PDO::PARAM_STR);
        $req->execute();
        return (bool)$req->rowCount();
    }

    /**
     * Méthode permettant d'ajouter une personne dans la table gestionnaire en connaissant son email
    * @param string $email L'email de la personne à ajouter en tant que gestionnaire.
    * @return bool Retourne true si l'opération d'insertion a réussi, false sinon.
    */
    public function addGestionnaire($email)
    {
        $req = $this->bd->prepare("INSERT INTO GESTIONNAIRE (id_personne) SELECT id_personne FROM personne WHERE email = :email");
        $req->bindValue(':email', $email, PDO::PARAM_STR);
        $req->execute();
        return (bool)$req->rowCount();
    }

    /**
     * Méthode permettant d'ajouter un client dans la table client avec ses informations
     * @param string $client Le nom du client.
    * @param string $tel Le numéro de téléphone du client.
    * @return bool Retourne true si l'opération d'insertion a réussi, false sinon.
    */
    public function addClient($client, $tel)
    {
        $req = $this->bd->prepare("INSERT INTO client(nom_client, telephone_client) VALUES( :nom_client, :tel)");
        $req->bindValue(':nom_client', $client, PDO::PARAM_STR);
        $req->bindValue(':tel', $tel, PDO::PARAM_STR);
        $req->execute();
        return (bool)$req->rowCount();
    }

   /**
     * Méthode permettant d'ajouter une composante en ajoutant les informations de son adresse dans la table adresse puis les informations de la composante dans la table composante
     * @param string $libelleVoie Le libellé du type de voie (ex : rue, avenue).
     * @param string $cp Le code postal de la localité.
     * @param string $numVoie Le numéro de la voie.
     * @param string $nomVoie Le nom de la voie.
     * @param string $nom_client Le nom du client auquel la composante est associée.
     * @param string $nom_compo Le nom de la composante.
     * @return bool Retourne true si l'opération d'insertion a réussi, false sinon.
     */
    public function addComposante($libelleVoie, $cp, $numVoie, $nomVoie, $nom_client, $nom_compo)
    {
        $req = $this->bd->prepare("INSERT INTO ADRESSE(numero, nom_voie, id_type_voie, id_localite) SELECT :num, :nomVoie, (SELECT id_type_voie FROM TypeVoie WHERE libelle = :libelleVoie), (SELECT id_localite FROM localite WHERE cp = :cp)");
        $req->bindValue(':num', $numVoie, PDO::PARAM_STR);
        $req->bindValue(':nomVoie', $nomVoie, PDO::PARAM_STR);
        $req->bindValue(':libelleVoie', $libelleVoie, PDO::PARAM_STR);
        $req->bindValue(':cp', $cp, PDO::PARAM_STR);
        $req->execute();
        $req = $this->bd->prepare("INSERT INTO COMPOSANTE(nom_composante, id_adresse, id_client) SELECT :nom_compo, (SELECT id_adresse FROM adresse ORDER BY id_adresse DESC LIMIT 1), (SELECT id_client FROM CLIENT WHERE nom_client = :nom_client)");
        $req->bindValue(':nom_client', $nom_client, PDO::PARAM_STR);
        $req->bindValue(':nom_compo', $nom_compo, PDO::PARAM_STR);
        $req->execute();
        return (bool)$req->rowCount();
    }

    /**
     * Méthode permettant d'ajouter une mission avec ses informations et les identifiants de la composante et de la société auxquelles elle est liée
     * @param string $type Le type de la mission.
     * @param string $nom Le nom de la mission.
     * @param string $date La date de début de la mission.
     * @param string $nom_composante Le nom de la composante liée à la mission.
     * @param string $nom_client Le nom du client lié à la composante.
     * @param int $id_prestataire L'identifiant du prestataire associé à la mission.
     * @return bool Retourne true si l'opération d'insertion a réussi, false sinon.
     */
    public function addMission($type, $nom, $date, $nom_composante, $id_prestataire){
    // Préparation de la requête pour insérer la mission
    $req = $this->bd->prepare("INSERT INTO MISSION (type_bdl, nom_mission, date_debut, id_composante) 
        SELECT :type, :nom, :date, co.id_composante
        FROM COMPOSANTE co 
        WHERE LOWER(co.nom_composante) = LOWER(:nom_composante)");

    $req->bindValue(':nom', $nom);
    $req->bindValue(':type', $type);
    $req->bindValue(':date', $date);
    $req->bindValue(':nom_composante', $nom_composante);
    
    $req->execute();

    // Récupérer l'id de la mission insérée
    $mission_id = $this->bd->lastInsertId();

    // Vérifier que la mission a bien été insérée avant d'ajouter le prestataire
    if ($mission_id) {
       // Préparation de la requête pour lier la mission et le prestataire
       $req = $this->bd->prepare("
       INSERT INTO travailleavec (id_personne, id_mission)
       VALUES (:id_personne, :id_mission)
   ");
   
   // Liaison des valeurs aux paramètres de la requête
    $req->bindValue(':id_personne', $id_prestataire);
    $req->bindValue(':id_mission', $mission_id);
    
     // Exécution de la requête
    $req->execute();

        // Retourner le résultat de la dernière insertion
        return (bool)$req->rowCount();
    } else {
        // En cas d'échec de l'insertion de la mission, retourner false
        return false;
    }
}

    /**
     * Méthode permettant d'ajouter une activité en fonction de si il s'agit d'un bon de livraison de type Heure
     * @param string $commentaire Le commentaire associé à l'activité.
     * @param int $id_bdl L'identifiant du bon de livraison associé à l'activité.
     * @param int $id_personne L'identifiant de la personne associée à l'activité.
     * @param string $date_bdl La date du bon de livraison associé à l'activité.
     * @param int $nb_heure Le nombre d'heures associé à l'activité.
     * @return bool Retourne true si l'opération d'insertion a réussi, false sinon.
     */
    public function addNbHeureActivite($commentaire, $id_bdl, $id_personne, $date_bdl, $nb_heure)
    {
        $req = $this->bd->prepare("INSERT INTO ACTIVITE (commentaire, id_bdl, id_personne, date_bdl) VALUES(:commentaire, :id_bdl, :id_personne, :date_bdl)");
        $req->bindValue(':commentaire', $commentaire);
        $req->bindValue(':id_bdl', $id_bdl);
        $req->bindValue(':id_personne', $id_personne);
        $req->bindValue(':date_bdl', $date_bdl);
        $req->execute();
        $req = $this->bd->prepare("INSERT INTO NB_HEURE SELECT (SELECT id_activite FROM activite ORDER BY id_activite DESC LIMIT 1), :nb_heure");
        $req->bindValue(':nb_heure', $nb_heure);
        $req->execute();
        return (bool)$req->rowCount();
    }

    /**
     * Méthode permettant d'ajouter une activité en fonction de si il s'agit d'un bon de livraison de type Demi-Journée
    * @param string $commentaire Le commentaire associé à l'activité.
    * @param int $id_bdl L'identifiant du bon de livraison associé à l'activité.
    * @param int $id_personne L'identifiant de la personne associée à l'activité.
    * @param string $date_bdl La date du bon de livraison associé à l'activité.
    * @param int $nb_dj Le nombre de demi-journées associé à l'activité.
    * @return bool Retourne true si l'opération d'insertion a réussi, false sinon.
    */
    public function addDemiJournee($commentaire, $id_bdl, $id_personne, $date_bdl, $nb_dj)
    {
        $req = $this->bd->prepare("INSERT INTO ACTIVITE (commentaire, id_bdl, id_personne, date_bdl) VALUES(:commentaire, :id_bdl, :id_personne, :date_bdl)");
        $req->bindValue(':commentaire', $commentaire);
        $req->bindValue(':id_bdl', $id_bdl);
        $req->bindValue(':id_personne', $id_personne);
        $req->bindValue(':date_bdl', $date_bdl);
        $req->execute();
        $req = $this->bd->prepare("INSERT INTO DEMI_JOUR SELECT (SELECT id_activite FROM activite ORDER BY id_activite DESC LIMIT 1), :nb_dj");
        $req->bindValue(':nb_dj', $nb_dj);
        $req->execute();
        return (bool)$req->rowCount();
    }

    /**
     * Méthode permettant d'ajouter une activité en fonction de si il s'agit d'un bon de livraison de type Journée
    * @param string $commentaire Le commentaire associé à l'activité.
    * @param int $id_bdl L'identifiant du bon de livraison associé à l'activité.
    * @param int $id_personne L'identifiant de la personne associée à l'activité.
    * @param string $date_bdl La date du bon de livraison associé à l'activité.
    * @param int $nb_jour Le nombre de journées associé à l'activité.
    * @return bool Retourne true si l'opération d'insertion a réussi, false sinon.
    */
    public function addJourneeJour($commentaire, $id_bdl, $id_personne, $date_bdl, $nb_jour)
    {
        $req = $this->bd->prepare("INSERT INTO ACTIVITE (commentaire, id_bdl, id_personne, date_bdl) VALUES(:commentaire, :id_bdl, :id_personne, :date_bdl)");
        $req->bindValue(':commentaire', $commentaire);
        $req->bindValue(':id_bdl', $id_bdl);
        $req->bindValue(':id_personne', $id_personne);
        $req->bindValue(':date_bdl', $date_bdl);
        $req->execute();
        $req = $this->bd->prepare("INSERT INTO JOUR(id_activite, journee) SELECT (SELECT id_activite FROM activite ORDER BY id_activite DESC LIMIT 1), :nb_jour");
        $req->bindValue(':nb_jour', $nb_jour);
        $req->execute();
        return (bool)$req->rowCount();
    }

    /**
     * Méthode permettant d'ajouter un bon de livraison dans la table BON_DE_LIVRAISON avec seulement les informations comme le mois, la mission et le prestataire.
     * @param string $nom_mission Le nom de la mission.
    * @param string $nom_composante Le nom de la composante.
    * @param string $mois Le mois du bon de livraison.
    * @param int $id_prestataire L'identifiant du prestataire.
    * @return bool Retourne true si l'insertion a réussi, sinon retourne false.
    */
    public function addBdlInMission($nom_mission, $nom_composante, $mois, $id_prestataire)
    {
        try {
            $req = $this->bd->prepare("INSERT INTO BON_DE_LIVRAISON(mois, id_mission, id_prestataire) SELECT :mois, 
                                                                               (SELECT id_mission FROM MISSION JOIN COMPOSANTE USING(id_composante) WHERE nom_mission = :mission and nom_composante = :composante),
                                                                               :id_prestataire");
            $req->bindValue(':mission', $nom_mission);
            $req->bindValue(':composante', $nom_composante);
            $req->bindValue(':mois', $mois);
            $req->bindValue(':id_prestataire', $id_prestataire);
            $req->execute();
            return (bool)$req->rowCount();
        } catch (PDOException $e) {
            error_log('Erreur PHP : ' . $e->getMessage());
            echo 'Une des informations est mauvaise';
        }
    }

    /**
     * Méthode permettant d'assigner un prestataire à une mission et lui créée un bon de livraison
    * @param string $email L'email du prestataire à assigner.
    * @param string $mission Le nom de la mission.
    * @param int $id_composante L'identifiant de la composante.
    * @return bool Retourne true si l'assignation du prestataire et la création du bon de livraison ont réussi, sinon retourne false.
    */
    public function assignerPrestataire($email, $mission, $id_composante)
    {
        $req = $this->bd->prepare("INSERT INTO travailleAvec (id_personne, id_mission) SELECT  (SELECT p.id_personne FROM PERSONNE p WHERE p.email = :email), (SELECT m.id_mission FROM MISSION m JOIN COMPOSANTE USING(id_composante) WHERE nom_mission = :nom_mission and id_composante = :id_composante)");
        $req->bindValue(':email', $email, PDO::PARAM_STR);
        $req->bindValue(':nom_mission', $mission, PDO::PARAM_STR);
        $req->bindValue(':id_composante', $id_composante);
        $req->execute();
        $req = $this->bd->prepare("INSERT INTO BON_DE_LIVRAISON(id_prestataire, id_mission, mois)  SELECT  (SELECT p.id_personne FROM PERSONNE p WHERE p.email = :email),  (SELECT m.id_mission FROM MISSION m JOIN COMPOSANTE USING(id_composante) WHERE nom_mission = :nom_mission and id_composante = :id_composante), (SELECT TO_CHAR(NOW(), 'YYYY-MM') AS date_format)");
        $req->bindValue(':email', $email, PDO::PARAM_STR);
        $req->bindValue(':nom_mission', $mission, PDO::PARAM_STR);
        $req->bindValue(':id_composante', $id_composante);
        $req->execute();
        return (bool)$req->rowCount();
    }

    /**
     * Méthode permettant d'assigner un commercial à une composante d'un client.
   * @param string $email L'email du commercial à assigner.
    * @param string $composante Le nom de la composante.
     * @param string $client Le nom du client auquel appartient la composante.
    * @return bool Retourne true si l'assignation du commercial à la composante a réussi, sinon retourne false.
     */
    public function assignerCommercial($email, $composante, $client)
    {
        $req = $this->bd->prepare("INSERT INTO estDans (id_personne, id_composante) SELECT  (SELECT p.id_personne FROM PERSONNE p WHERE p.email = :email), (SELECT c.id_composante FROM COMPOSANTE c JOIN CLIENT USING(id_client) WHERE nom_composante = :composante AND nom_client = :client)");
        $req->bindValue(':email', $email);
        $req->bindValue(':composante', $composante);
        $req->bindValue(':client', $client);
        $req->execute();
        return (bool)$req->rowCount();
    }

    /**
    * Méthode permettant d'assigner un commercial à une composante en connaissant l'identifiant de la composante.
    * @param string $email L'email du commercial à assigner.
    * @param int $id_composante L'identifiant de la composante à laquelle assigner le commercial.
    * @return bool Retourne true si l'assignation du commercial à la composante a réussi, sinon retourne false.
     */
    public function assignerCommercialByIdComposante($email, $id_composante)
    {
        $req = $this->bd->prepare("INSERT INTO estDans (id_personne, id_composante) SELECT  (SELECT p.id_personne FROM PERSONNE p WHERE p.email = :email), :id_composante");
        $req->bindValue(':email', $email);
        $req->bindValue(':id_composante', $id_composante);
        $req->execute();
        return (bool)$req->rowCount();
    }

    /**
    * Méthode permettant de récupérer la liste des bons de livraison d'un prestataire.
    * @param int $id_pr L'identifiant du prestataire.
    * @return array|false Retourne un tableau contenant les informations des bons de livraison du prestataire spécifié, ou false en cas d'erreur.
    */
    public function getAllBdlPrestataire($id_pr)
    {
        $req = $this->bd->prepare("SELECT id_bdl, mois, nom_mission FROM bon_de_livraison JOIN prestataire ON id_personne = id_prestataire JOIN MISSION USING(id_mission) WHERE id_personne = :id");
        $req->bindValue(':id', $id_pr, PDO::PARAM_INT);
        $req->execute();
        return $req->fetchall();
    }

    /**
 * Méthode permettant de récupérer toutes les activités de type "Nombre d'heures" associées à un bon de livraison spécifié par son identifiant.
 * @param int $id_bdl L'identifiant du bon de livraison.
 * @return array|false Retourne un tableau contenant les informations des activités de type "Nombre d'heures" associées au bon de livraison spécifié, ou false en cas d'erreur.
 */
    public function getAllNbHeureActivite($id_bdl)
    {
        $req = $this->bd->prepare("SELECT nb_heure, a.commentaire, date_bdl FROM NB_HEURE JOIN ACTIVITE a USING(id_activite) JOIN BON_DE_LIVRAISON using(id_bdl) WHERE id_bdl = :id_bdl ORDER BY date_bdl");
        $req->bindValue(':id_bdl', $id_bdl);
        $req->execute();
        return $req->fetchall(PDO::FETCH_ASSOC);
    }

    /**
 * Méthode permettant de récupérer toutes les activités de type "Demi-journée" associées à un bon de livraison spécifié par son identifiant.
 * @param int $id_bdl L'identifiant du bon de livraison.
 * @return array|false Retourne un tableau contenant les informations des activités de type "Demi-journée" associées au bon de livraison spécifié, ou false en cas d'erreur.
 */
    public function getAllDemiJourActivite($id_bdl)
    {
        $req = $this->bd->prepare("SELECT nb_demi_journee, a.commentaire, date_bdl FROM DEMI_JOUR JOIN ACTIVITE a USING(id_activite) JOIN BON_DE_LIVRAISON using(id_bdl) WHERE id_bdl = :id_bdl ORDER BY date_bdl");
        $req->bindValue(':id_bdl', $id_bdl);
        $req->execute();
        return $req->fetchall(PDO::FETCH_ASSOC);
    }

    /**
 * Méthode permettant de récupérer toutes les activités de type "Journée" associées à un bon de livraison spécifié par son identifiant.
 * @param int $id_bdl L'identifiant du bon de livraison.
 * @return array|false Retourne un tableau contenant les informations des activités de type "Journée" associées au bon de livraison spécifié, ou false en cas d'erreur.
 */
    public function getAllJourActivite($id_bdl)
    {
        $req = $this->bd->prepare("SELECT journee, a.commentaire, date_bdl FROM JOUR JOIN ACTIVITE a USING(id_activite) JOIN BON_DE_LIVRAISON using(id_bdl) WHERE id_bdl = :id_bdl ORDER BY date_bdl");
        $req->bindValue(':id_bdl', $id_bdl);
        $req->execute();
        return $req->fetchall(PDO::FETCH_ASSOC);
    }

    /**
 * Méthode permettant de mettre à jour le statut de validation d'un bon de livraison dans la table BON_DE_LIVRAISON.
 * @param int $id_bdl L'identifiant du bon de livraison à mettre à jour.
 * @param int $id_interlocuteur L'identifiant de l'interlocuteur à associer au bon de livraison.
 * @param bool $valide Le statut de validation à attribuer au bon de livraison (true pour validé, false pour non validé).
 * @return bool Retourne true si la mise à jour a été effectuée avec succès, sinon false.
 */
    public function setEstValideBdl($id_bdl, $id_interlocuteur, $valide)
    {
        $req = $this->bd->prepare("UPDATE BON_DE_LIVRAISON SET est_valide = :valide, id_interlocuteur = :id_interlocuteur WHERE id_bdl = :id_bdl");
        $req->bindValue(':id_interlocuteur', $id_interlocuteur);
        $req->bindValue(':id_bdl', $id_bdl);
        $req->bindValue(':valide', $valide);
        $req->execute();
        return (bool)$req->rowCount();

    }

    /** 
    * Méthode permettant de mettre à jour le nom d'une personne dans la table PERSONNE.
    * @param int $id L'identifiant de la personne à mettre à jour.
    * @param string $nom Le nouveau nom à attribuer à la personne.
    * @return bool Retourne true si la mise à jour a été effectuée avec succès, sinon false.
    */
    public function setNomPersonne($id, $nom)
    {
        $req = $this->bd->prepare("UPDATE PERSONNE SET nom = :nom WHERE id_personne = :id");
        $req->bindValue(':id', $id, PDO::PARAM_INT);
        $req->bindValue(':nom', $nom, PDO::PARAM_STR);
        $req->execute();
        return (bool)$req->rowCount();
    }

    /**
 * Méthode permettant de mettre à jour le prénom d'une personne dans la table PERSONNE.
 * @param int $id L'identifiant de la personne à mettre à jour.
 * @param string $prenom Le nouveau prénom à attribuer à la personne.
 * @return bool Retourne true si la mise à jour a été effectuée avec succès, sinon false.
 */
    public function setPrenomPersonne($id, $prenom)
    {
        $req = $this->bd->prepare("UPDATE PERSONNE SET prenom = :prenom WHERE id_personne = :id");
        $req->bindValue(':id', $id, PDO::PARAM_INT);
        $req->bindValue(':prenom', $prenom, PDO::PARAM_STR);
        $req->execute();
        return (bool)$req->rowCount();
    }

    /**
 * Méthode permettant de mettre à jour l'adresse e-mail d'une personne dans la table PERSONNE.
 * @param int $id L'identifiant de la personne à mettre à jour.
 * @param string $email La nouvelle adresse e-mail à attribuer à la personne.
 * @return bool Retourne true si la mise à jour a été effectuée avec succès, sinon false.
 */
    public function setEmailPersonne($id, $email)
    {
        $req = $this->bd->prepare("UPDATE PERSONNE SET email = :email WHERE id_personne = :id");
        $req->bindValue(':id', $id, PDO::PARAM_INT);
        $req->bindValue(':email', $email, PDO::PARAM_STR);
        $req->execute();
        return (bool)$req->rowCount();
    }

    /**
 * Méthode permettant de mettre à jour le mot de passe d'une personne dans la table PERSONNE.
 * @param int $id L'identifiant de la personne à mettre à jour.
 * @param string $mdp Le nouveau mot de passe à attribuer à la personne.
 * @return bool Retourne true si la mise à jour a été effectuée avec succès, sinon false.
 */
    public function setMdpPersonne($id, $mdp)
    {
        $req = $this->bd->prepare("UPDATE PERSONNE SET mdp = :mdp WHERE id_personne = :id");
        $req->bindValue(':id', $id, PDO::PARAM_INT);
        $req->bindValue(':mdp', $mdp, PDO::PARAM_STR);
        $req->execute();
        return (bool)$req->rowCount();
    }

    /**
 * Méthode permettant de mettre à jour le nom d'un client dans la table CLIENT.
 * @param int $id L'identifiant du client à mettre à jour.
 * @param string $nom Le nouveau nom à attribuer au client.
 * @return bool Retourne true si la mise à jour a été effectuée avec succès, sinon false.
 */
    public function setNomClient($id, $nom)
    {
        $req = $this->bd->prepare("UPDATE CLIENT SET nom_client = :nom WHERE id_client = :id");
        $req->bindValue(':id', $id, PDO::PARAM_INT);
        $req->bindValue(':nom', $nom, PDO::PARAM_STR);
        $req->execute();
        return (bool)$req->rowCount();
    }

    /**
 * Méthode permettant de mettre à jour le numéro de téléphone d'un client dans la table CLIENT.
 * @param int $id L'identifiant du client à mettre à jour.
 * @param string $tel Le nouveau numéro de téléphone à attribuer au client.
 * @return bool Retourne true si la mise à jour a été effectuée avec succès, sinon false.
 */
    public function setTelClient($id, $tel)
    {
        $req = $this->bd->prepare("UPDATE CLIENT SET telephone_client = :tel WHERE id_client = :id");
        $req->bindValue(':id', $id, PDO::PARAM_INT);
        $req->bindValue(':tel', $tel, PDO::PARAM_STR);
        $req->execute();
        return (bool)$req->rowCount();
    }

    /**
 * Méthode permettant de mettre à jour le nom d'une composante dans la table COMPOSANTE.
 * @param int $id L'identifiant de la composante à mettre à jour.
 * @param string $nom Le nouveau nom à attribuer à la composante.
 * @return bool Retourne true si la mise à jour a été effectuée avec succès, sinon false.
 */
    public function setNomComposante($id, $nom)
    {
        $req = $this->bd->prepare("UPDATE COMPOSANTE SET nom_composante = :nom WHERE id_composante = :id");
        $req->bindValue(':id', $id);
        $req->bindValue(':nom', $nom);
        $req->execute();
        return (bool)$req->rowCount();
    }

    /**
 * Méthode permettant de mettre à jour le numéro d'une adresse dans la table ADRESSE.
 * @param int $id L'identifiant de la composante dont l'adresse doit être mise à jour.
 * @param string $num Le nouveau numéro à attribuer à l'adresse.
 * @return bool Retourne true si la mise à jour a été effectuée avec succès, sinon false.
 */
    public function setNumeroAdresse($id, $num)
    {
        $req = $this->bd->prepare("UPDATE ADRESSE SET numero = :num WHERE id_adresse = (SELECT id_adresse FROM ADRESSE JOIN COMPOSANTE USING(id_adresse) WHERE id_composante = :id)");
        $req->bindValue(':id', $id);
        $req->bindValue(':num', $num);
        $req->execute();
        return (bool)$req->rowCount();
    }

    /**
 * Méthode permettant de mettre à jour le nom de la voie d'une adresse dans la table ADRESSE.
 * @param int $id L'identifiant de la composante dont l'adresse doit être mise à jour.
 * @param string $nom Le nouveau nom de la voie à attribuer à l'adresse.
 * @return bool Retourne true si la mise à jour a été effectuée avec succès, sinon false.
 */
    public function setNomVoieAdresse($id, $nom)
    {
        $req = $this->bd->prepare("UPDATE ADRESSE SET nom_voie = :nom WHERE id_adresse = (SELECT id_adresse FROM ADRESSE JOIN COMPOSANTE USING(id_adresse) WHERE id_composante = :id)");
        $req->bindValue(':id', $id);
        $req->bindValue(':nom', $nom);
        $req->execute();
        return (bool)$req->rowCount();
    }

    /**
 * Méthode permettant de mettre à jour le code postal d'une localité dans la table LOCALITE.
 * @param int $id L'identifiant de la composante dont l'adresse doit être mise à jour.
 * @param string $cp Le nouveau code postal à attribuer à la localité.
 * @return bool Retourne true si la mise à jour a été effectuée avec succès, sinon false.
 */
    public function setCpLocalite($id, $cp)
    {
        $req = $this->bd->prepare("UPDATE ADRESSE SET id_localite = (SELECT id_localite FROM LOCALITE WHERE cp = :cp)
               WHERE id_adresse = (SELECT id_adresse FROM ADRESSE JOIN COMPOSANTE USING(id_adresse) WHERE id_composante = :id)");
        $req->bindValue(':id', $id);
        $req->bindValue(':cp', $cp);
        $req->execute();
        return (bool)$req->rowCount();
    }

    /**
 * Méthode permettant de mettre à jour le nom d'une ville d'une localité dans la table LOCALITE.
 * @param int $id L'identifiant de la composante dont l'adresse doit être mise à jour.
 * @param string $ville Le nouveau nom de la ville à attribuer à la localité.
 * @return bool Retourne true si la mise à jour a été effectuée avec succès, sinon false.
 */
    public function setVilleLocalite($id, $ville)
    {
        $req = $this->bd->prepare("UPDATE ADRESSE SET id_localite = (SELECT id_localite FROM LOCALITE WHERE LOWER(ville) = LOWER(:ville))
               WHERE id_adresse = (SELECT id_adresse FROM ADRESSE JOIN COMPOSANTE USING(id_adresse) WHERE id_composante = :id)");
        $req->bindValue(':id', $id);
        $req->bindValue(':ville', $ville);
        $req->execute();
        return (bool)$req->rowCount();
    }

    /**
 * Méthode permettant de mettre à jour le libellé du type de voie d'une adresse dans la table ADRESSE.
 * @param int $id L'identifiant de la composante dont l'adresse doit être mise à jour.
 * @param string $libelle Le nouveau libellé du type de voie à attribuer à l'adresse.
 * @return bool Retourne true si la mise à jour a été effectuée avec succès, sinon false.
 */
    public function setLibelleTypevoie($id, $libelle)
    {
        $req = $this->bd->prepare("UPDATE ADRESSE SET id_type_voie = (SELECT id_type_voie FROM TYPEVOIE WHERE LOWER(libelle) = LOWER(:libelle))
               WHERE id_adresse = (SELECT id_adresse FROM COMPOSANTE JOIN ADRESSE USING(id_adresse) WHERE id_composante = :id)");
        $req->bindValue(':id', $id);
        $req->bindValue(':libelle', $libelle);
        $req->execute();
        return (bool)$req->rowCount();
    }

    /**
 * Méthode permettant de mettre à jour le client d'une composante dans la table COMPOSANTE.
 * @param int $id L'identifiant de la composante dont le client doit être mis à jour.
 * @param string $client Le nouveau client à attribuer à la composante.
 * @return bool Retourne true si la mise à jour a été effectuée avec succès, sinon false.
 */
    public function setClientComposante($id, $client)
    {
        $req = $this->bd->prepare("UPDATE COMPOSANTE SET id_client = (SELECT id_client FROM CLIENT WHERE LOWER(nom_client) = LOWER(:client))
                  WHERE id_composante = :id");
        $req->bindValue(':id', $id);
        $req->bindValue(':client', $client);
        $req->execute();
        return (bool)$req->rowCount();
    }

    /**
 * Méthode permettant de mettre à jour le commentaire d'une activité dans la table ACTIVITE.
 * @param int $id L'identifiant de l'activité dont le commentaire doit être mis à jour.
 * @param string $commentaire Le nouveau commentaire à attribuer à l'activité.
 * @return bool Retourne true si la mise à jour a été effectuée avec succès, sinon false.
 */
    public function setCommentaireActivite($id, $commentaire)
    {
        $req = $this->bd->prepare("UPDATE ACTIVITE SET commentaire = :commentaire WHERE id_activite = :id");
        $req->bindValue(':id', $id, PDO::PARAM_INT);
        $req->bindValue(':commentaire', $commentaire, PDO::PARAM_STR);
        $req->execute();
        return (bool)$req->rowCount();
    }

    /**
 * Méthode permettant de mettre à jour la date d'un bon de livraison dans la table ACTIVITE.
 * @param int $id L'identifiant de l'activité dont la date du bon de livraison doit être mise à jour.
 * @param string $date La nouvelle date du bon de livraison à attribuer à l'activité.
 * @return bool Retourne true si la mise à jour a été effectuée avec succès, sinon false.
 */ 
    public function setDateBdlActivite($id, $date)
    {
        $req = $this->bd->prepare("UPDATE ACTIVITE SET date_bdl = :date WHERE id_activite = :id");
        $req->bindValue(':id', $id, PDO::PARAM_INT);
        $req->bindValue(':date', $date, PDO::PARAM_STR);
        $req->execute();
        return (bool)$req->rowCount();
    }

    /**
 * Méthode permettant de mettre à jour le nombre d'heures dans la table NB_HEURE.
 * @param int $id L'identifiant de l'activité dont le nombre d'heures doit être mis à jour.
 * @param string $heure Le nouveau nombre d'heures à attribuer à l'activité.
 * @return bool Retourne true si la mise à jour a été effectuée avec succès, sinon false.
 */
    public function setNbHeure($id, $heure)
    {
        $req = $this->bd->prepare("UPDATE NB_HEURE SET nb_heure = :heure WHERE id_activite = :id");
        $req->bindValue(':id', $id, PDO::PARAM_INT);
        $req->bindValue(':heure', $heure, PDO::PARAM_STR);
        $req->execute();
        return (bool)$req->rowCount();
    }

    /**
 * Méthode permettant de mettre à jour l'heure de début d'une plage horaire dans la table PLAGE_HORAIRE.
 * @param int $id L'identifiant de l'activité dont l'heure de début de la plage horaire doit être mise à jour.
 * @param string $heure La nouvelle heure de début de la plage horaire à attribuer à l'activité.
 * @return bool Retourne true si la mise à jour a été effectuée avec succès, sinon false.
 */
    public function setDebutHeurePlageHoraire($id, $heure)
    {
        $req = $this->bd->prepare("UPDATE PLAGE_HORAIRE SET debut_heure = :heure WHERE id_activite = :id");
        $req->bindValue(':id', $id, PDO::PARAM_INT);
        $req->bindValue(':heure', $heure, PDO::PARAM_STR);
        $req->execute();
        return (bool)$req->rowCount();
    }

    /**
 * Méthode permettant de mettre à jour l'heure de fin d'une plage horaire dans la table PLAGE_HORAIRE.
 * @param int $id L'identifiant de l'activité dont l'heure de fin de la plage horaire doit être mise à jour.
 * @param string $heure La nouvelle heure de fin de la plage horaire à attribuer à l'activité.
 * @return bool Retourne true si la mise à jour a été effectuée avec succès, sinon false.
 */
    public function setFinHeurePlageHoraire($id, $heure)
    {
        $req = $this->bd->prepare("UPDATE PLAGE_HORAIRE SET fin_heure = :heure WHERE id_activite = :id");
        $req->bindValue(':id', $id, PDO::PARAM_INT);
        $req->bindValue(':heure', $heure, PDO::PARAM_STR);
        $req->execute();
        return (bool)$req->rowCount();
    }

    /**
 * Méthode permettant de mettre à jour le nombre de demi-journées dans la table DEMI_JOUR.
 * @param int $id L'identifiant de l'activité dont le nombre de demi-journées doit être mis à jour.
 * @param int $demi_journee Le nouveau nombre de demi-journées à attribuer à l'activité.
 * @return bool Retourne true si la mise à jour a été effectuée avec succès, sinon false.
 */
    public function setDemiJournee($id, $demi_journee)
    {
        $req = $this->bd->prepare("UPDATE DEMI_JOUR SET nb_demi_journee = :dj WHERE id_activite = :id");
        $req->bindValue(':id', $id);
        $req->bindValue(':dj', $demi_journee);
        $req->execute();
        return (bool)$req->rowCount();
    }

    /**
 * Méthode permettant de mettre à jour le nombre de jours dans la table JOUR.
 * @param int $id L'identifiant de l'activité dont le nombre de jours doit être mis à jour.
 * @param string $jour Le nouveau nombre de jours à attribuer à l'activité.
 * @return bool Retourne true si la mise à jour a été effectuée avec succès, sinon false.
 */
    public function setJourneeJour($id, $jour)
    {
        $req = $this->bd->prepare("UPDATE JOUR SET journee = :jour WHERE id_activite = :id");
        $req->bindValue(':id', $id, PDO::PARAM_INT);
        $req->bindValue(':jour', $jour, PDO::PARAM_STR);
        $req->execute();
        return (bool)$req->rowCount();
    }

    /**
 * Méthode permettant de mettre à jour l'heure de début des heures supplémentaires dans la table JOUR.
 * @param int $id L'identifiant de l'activité dont l'heure de début des heures supplémentaires doit être mise à jour.
 * @param string $debut La nouvelle heure de début des heures supplémentaires à attribuer à l'activité.
 * @return bool Retourne true si la mise à jour a été effectuée avec succès, sinon false.
 */
    public function setDebutHeureSuppJour($id, $debut)
    {
        $req = $this->bd->prepare("UPDATE JOUR SET debut_heure_supp = :debut WHERE id_activite = :id");
        $req->bindValue(':id', $id, PDO::PARAM_INT);
        $req->bindValue(':debut', $debut, PDO::PARAM_STR);
        $req->execute();
        return (bool)$req->rowCount();
    }

    /**
 * Méthode permettant de mettre à jour l'heure de fin des heures supplémentaires dans la table JOUR.
 * @param int $id L'identifiant de l'activité dont l'heure de fin des heures supplémentaires doit être mise à jour.
 * @param string $fin La nouvelle heure de fin des heures supplémentaires à attribuer à l'activité.
 * @return bool Retourne true si la mise à jour a été effectuée avec succès, sinon false.
 */
    public function setFinHeureSuppJour($id, $fin)
    {
        $req = $this->bd->prepare("UPDATE JOUR SET fin_heure_supp = :fin WHERE id_activite = :id");
        $req->bindValue(':id', $id, PDO::PARAM_INT);
        $req->bindValue(':fin', $fin, PDO::PARAM_STR);
        $req->execute();
        return (bool)$req->rowCount();
    }

    /* -------------------------------------------------------------------------
                            Fonction Commercial
        ------------------------------------------------------------------------*/
    /**
 * Méthode permettant d'obtenir le tableau de bord pour un commercial.
 * @param int $id_co L'identifiant du commercial pour lequel récupérer le tableau de bord.
 * @return array Retourne un tableau contenant les informations sur les missions et les bons de livraison associés au commercial.
 */
    public function getDashboardCommercial($id_co)
    {
        $req = $this->bd->prepare('SELECT nom_client, nom_composante, nom_mission, nom, prenom, ta.id_mission, id_bdl, id_prestataire FROM client JOIN composante c USING(id_client) JOIN mission USING(id_composante) JOIN travailleavec ta USING(id_mission) JOIN PERSONNE p ON ta.id_personne = p.id_personne JOIN estDans ed on ed.id_composante = c.id_composante JOIN BON_DE_LIVRAISON on id_prestataire = ta.id_personne WHERE ed.id_personne=:id');
        $req->bindValue(':id', $id_co, PDO::PARAM_INT);
        $req->execute();
        return $req->fetchall(PDO::FETCH_ASSOC);
    }

    /**
 * Méthode permettant d'obtenir le tableau de bord pour un prestataire.
 * @param int $id_prestataire L'identifiant du prestataire pour lequel récupérer le tableau de bord.
 * @return array Retourne un tableau contenant les informations sur les missions associées au prestataire.
 */
    public function getDashboardPrestataire($id_prestataire)
    {
        $req = $this->bd->prepare('SELECT nom_client, nom_composante, nom_mission, id_mission FROM client JOIN composante c USING(id_client) JOIN mission USING(id_composante) JOIN travailleavec ta USING(id_mission) JOIN PERSONNE p ON ta.id_personne = p.id_personne WHERE ta.id_personne=:id');
        $req->bindValue(':id', $id_prestataire);
        $req->execute();
        return $req->fetchall(PDO::FETCH_ASSOC);
    }

    /**
 * Méthode permettant d'obtenir les informations sur l'interlocuteur associé à un commercial.
 * @param int $id_co L'identifiant du commercial pour lequel récupérer les informations sur l'interlocuteur.
 * @return array Retourne un tableau contenant les informations sur l'interlocuteur associé au commercial.
 */
    public function getInterlocuteurForCommercial($id_co)
    {
        $req = $this->bd->prepare('SELECT nom, prenom, nom_client, nom_composante FROM dirige JOIN composante USING(id_composante) JOIN client USING(id_client) JOIN personne USING(id_personne) JOIN estDans ed USING(id_composante) WHERE ed.id_personne = :id');
        $req->bindValue(':id', $id_co, PDO::PARAM_INT);
        $req->execute();
        return $req->fetchall();
    }

/**
 * Méthode permettant d'obtenir les prestataires associés à un commercial.
 * @param int $id_co L'identifiant du commercial pour lequel récupérer les prestataires associés.
 * @return array Retourne un tableau contenant les informations sur les prestataires associés au commercial.
 */
    public function getPrestataireForCommercial($id_co)
    {
        $req = $this->bd->prepare('SELECT DISTINCT nom, prenom, ta.id_personne as id FROM client JOIN composante USING(id_client) JOIN mission USING(id_composante) JOIN travailleavec ta USING(id_mission) JOIN PERSONNE p ON ta.id_personne = p.id_personne JOIN estDans ed USING(id_composante) WHERE ed.id_personne = :id');
        $req->bindValue(':id', $id_co, PDO::PARAM_INT);
        $req->execute();
        return $req->fetchall();
    }

    /**
 * Méthode permettant d'obtenir les composantes associées à un commercial.
 * @param int $id_commercial L'identifiant du commercial pour lequel récupérer les composantes associées.
 * @return array Retourne un tableau contenant les informations sur les composantes associées au commercial.
 */
    public function getComposantesForCommercial($id_commercial)
    {
        $req = $this->bd->prepare('SELECT id_composante AS id, nom_composante, nom_client FROM CLIENT JOIN COMPOSANTE using(id_client) JOIN estDans USING(id_composante) WHERE id_personne = :id');
        $req->bindValue(':id', $id_commercial);
        $req->execute();
        return $req->fetchall(PDO::FETCH_ASSOC);
    }

    /**
 * Méthode permettant d'obtenir le type et le mois d'un bon de livraison spécifié par son identifiant.
 * @param int $id_bdl L'identifiant du bon de livraison pour lequel récupérer le type et le mois.
 * @return array|false Retourne un tableau contenant le type et le mois du bon de livraison, ou FALSE si aucun résultat n'est trouvé.
 */
    public function getBdlTypeAndMonth($id_bdl)
    {
        $req = $this->bd->prepare("SELECT id_bdl, type_bdl, mois FROM BON_DE_LIVRAISON JOIN MISSION USING(id_mission) WHERE id_bdl = :id");
        $req->bindValue(':id', $id_bdl);
        $req->execute();
        return $req->fetch();
    }

    /**
 * Méthode permettant d'obtenir les bons de livraison d'un prestataire pour une mission spécifiée.
 * @param int $id_mission L'identifiant de la mission pour laquelle récupérer les bons de livraison.
 * @param int $id_prestataire L'identifiant du prestataire pour lequel récupérer les bons de livraison.
 * @return array Retourne un tableau contenant les bons de livraison du prestataire pour la mission spécifiée.
 */

    public function getBdlsOfPrestataireByIdMission($id_mission, $id_prestataire)
    {
        $req = $this->bd->prepare("SELECT id_bdl, nom_mission, mois FROM BON_DE_LIVRAISON JOIN MISSION USING(id_mission) WHERE id_mission = :id_mission and id_prestataire = :id_prestataire");
        $req->bindValue(':id_mission', $id_mission);
        $req->bindValue(':id_prestataire', $id_prestataire);
        $req->execute();
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
 * Méthode permettant d'obtenir l'identifiant de l'activité en fonction de sa date et de l'identifiant du bon de livraison.
 * @param string $date_activite La date de l'activité pour laquelle récupérer l'identifiant.
 * @param int $id_bdl L'identifiant du bon de livraison associé à l'activité.
 * @return int|false Retourne l'identifiant de l'activité, ou FALSE si aucun résultat n'est trouvé.
 */
    public function getIdActivite($date_activite, $id_bdl)
    {
        $req = $this->bd->prepare('SELECT id_activite FROM activite WHERE id_bdl = :id_bdl and date_bdl = :date');
        $req->bindValue(':id_bdl', $id_bdl);
        $req->bindValue(':date', $date_activite);
        $req->execute();
        return $req->fetch()[0];
    }

    /* -------------------------------------------------------------------------
                        Fonction Interlocuteur
    ------------------------------------------------------------------------*/
    /**
 * Méthode permettant d'obtenir le tableau de bord de l'interlocuteur.
 * @param int $id_in L'identifiant de l'interlocuteur pour lequel récupérer le tableau de bord.
 * @return array Retourne un tableau contenant les informations sur les missions associées à l'interlocuteur.
 */
    public function dashboardInterlocuteur($id_in)
    {
        $req = $this->bd->prepare("SELECT nom_mission, date_debut, nom, prenom, id_bdl FROM mission m JOIN travailleAvec USING(id_mission) JOIN personne p USING(id_personne) JOIN bon_de_livraison bdl ON m.id_mission= bdl.id_mission WHERE bdl.id_personne = :id");
        $req->bindValue(':id', $id_in, PDO::PARAM_INT);
        $req->execute();
        return $req->fetchall();
    }

    /**
 * Méthode permettant d'obtenir l'adresse e-mail du commercial associé à un interlocuteur.
 * @param int $id_in L'identifiant de l'interlocuteur pour lequel récupérer l'adresse e-mail du commercial.
 * @return array Retourne un tableau contenant l'adresse e-mail du commercial associé à l'interlocuteur.
 */
    public function getEmailCommercialForInterlocuteur($id_in)
    {
        $req = $this->bd->prepare("SELECT email FROM dirige d JOIN estDans ed USING(id_composante) JOIN personne com ON ed.id_personne = com.id_personne WHERE d.id_personne = :id");
        $req->bindValue(':id', $id_in, PDO::PARAM_INT);
        $req->execute();
        return $req->fetchall();
    }

    /**
 * Méthode permettant de récupérer les informations du tableau de bord du contact client.
 * @return array|false Retourne un tableau contenant les informations sur les missions du contact client, ou false en cas d'erreur.
 */
    public function getClientContactDashboardData()
    {
        $req = $this->bd->prepare('SELECT nom_mission, date_debut, nom, prenom, id_bdl, ta.id_mission, ta.id_personne as id_prestataire FROM mission m JOIN travailleAvec ta USING(id_mission) JOIN personne p USING(id_personne) JOIN bon_de_livraison bdl ON m.id_mission= bdl.id_mission WHERE bdl.id_interlocuteur = :id;');
        $req->bindValue(':id', $_SESSION['id']);
        $req->execute();
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
 * Méthode permettant de récupérer les clients associés au commercial actuellement connecté.
 * @return array Retourne un tableau contenant les clients associés au commercial.
 */
    public function getClientForCommercial()
    {
        $req = $this->bd->prepare('SELECT DISTINCT id_client AS id, nom_client, telephone_client FROM CLIENT JOIN COMPOSANTE USING(id_client) JOIN ESTDANS USING(id_composante) WHERE id_personne = :id;');
        $req->bindValue(':id', $_SESSION['id']);
        $req->execute();
        return $req->fetchall();
    }

    /**
 * Renvoie la liste des adresses e-mail des commerciaux assignés à la mission de l'interlocuteur client.
 * @param int $idClientContact L'identifiant de l'interlocuteur client.
 * @return array|false Retourne un tableau contenant les adresses e-mail des commerciaux associés à la mission de l'interlocuteur client, ou false en cas d'erreur.
 */
    public function getComponentCommercialsEmails($idClientContact)
    {
        $req = $this->bd->prepare('SELECT email FROM dirige d JOIN estDans ed USING(id_composante) JOIN personne com ON ed.id_personne = com.id_personne WHERE d.id_personne = :id;');
        $req->bindValue(':id', $idClientContact);
        $req->execute();
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
 * Récupère l'adresse e-mail dans la base de données grâce à l'identifiant de la personne.
 * @param int $id L'identifiant de la personne.
 * @return string|false Retourne l'adresse e-mail de la personne, ou false en cas d'erreur.
 */
    function getEmailById($id)
    {
        $req = $this->bd->prepare('SELECT email FROM personne WHERE id_personne = :id;');
        $req->bindValue(':id', $id);
        $req->execute();
        $req->fetch(PDO::FETCH_ASSOC);
    }

   /**
 * Vérifie si l'adresse e-mail saisie existe dans la base de données.
 *
 * @param string $mail L'adresse e-mail à vérifier.
 * @return bool Retourne true si l'adresse e-mail existe, sinon false.
 */
    public function mailExists($mail)
    {
        $req = $this->bd->prepare('SELECT email FROM PERSONNE WHERE email = :mail;');
        $req->bindValue(':mail', $mail);
        $req->execute();
        $email = $req->fetch(PDO::FETCH_ASSOC);
        return sizeof($email) != 0;
    }

    /**
 * Récupère les bons de livraison associés à un interlocuteur client et un prestataire donnés.
 * @param int $id_pr L'identifiant du prestataire.
 * @param int $id_in L'identifiant de l'interlocuteur client.
 * @return array|false Retourne un tableau contenant les bons de livraison associés à l'interlocuteur client et au prestataire spécifiés, ou false en cas d'erreur.
 */
    public function getBdlPrestaForInterlocuteur($id_pr, $id_in)
    {
        $req = $this->bd->prepare("SELECT id_bdl, mois, nom_mission FROM BON_DE_LIVRAISON bdl JOIN MISSION m USING(id_mission) JOIN travailleAvec ta USING(id_mission) JOIN COMPOSANTE USING(id_composante) JOIN dirige d USING(id_composante) WHERE ta.id_personne = :id_pres AND d.id_personne = :id_inter");
        $req->bindValue(':id_inter', $id_pr, PDO::PARAM_INT);
        $req->bindValue(':id_pres', $id_in, PDO::PARAM_INT);
        $req->execute();
        return $req->fetchall();
    }

    /* -------------------------------------------------------------------------
                            Fonction Prestataire
        ------------------------------------------------------------------------*/
    /**
 * Récupère les informations sur l'interlocuteur client pour un prestataire donné.
 * @param int $id_pr L'identifiant du prestataire.
 * @return array|false Retourne un tableau contenant les informations sur l'interlocuteur client, ou false en cas d'erreur.
 */
    public function getInterlocuteurForPrestataire($id_pr)
    {
        $req = $this->bd->prepare('SELECT nom, prenom, nom_client, nom_composante FROM dirige d JOIN composante USING(id_composante) JOIN client USING(id_client) JOIN personne p ON p.id_personne = d.id_personne  JOIN MISSION m USING(id_composante) JOIN travailleAvec ta USING(id_mission) WHERE ta.id_personne = :id');
        $req->bindValue(':id', $id_pr, PDO::PARAM_INT);
        $req->execute();
        return $req->fetchall();
    }

    /* -------------------------------------------------------------------------
                            AUTRE
        ------------------------------------------------------------------------*/
    /**
 * Vérifie que le mot de passe correspond au courriel. Si les informations sont valides, démarre une session avec les données de la personne associée au courriel.
 * @param string $mail L'adresse e-mail à vérifier.
 * @param string $password Le mot de passe à vérifier.
 * @return bool Retourne true si le mot de passe correspond au courriel et démarre une session avec les informations de la personne, sinon retourne false.
 */
    public function checkMailPassword($mail, $password)
    {
        $req = $this->bd->prepare('SELECT * FROM PERSONNE WHERE email = :mail');
        $req->bindValue(':mail', $mail);
        $req->execute();
        $realPassword = $req->fetchAll(PDO::FETCH_ASSOC);

        if ($realPassword) {
            if ($realPassword[0]['mdp'] == $password) {
                if (isset($_SESSION)) {
                    session_destroy();
                }
                if (session_status() == PHP_SESSION_NONE) {
                    session_start();
                }
                if (isset($_SESSION['id'])) {
                    unset($_SESSION['id']);
                }
                $_SESSION['id'] = $realPassword[0]['id_personne'];
                $_SESSION['nom'] = $realPassword[0]['nom'];
                $_SESSION['prenom'] = $realPassword[0]['prenom'];
                $_SESSION['email'] = $realPassword[0]['email'];
                return true;
            }
        }
        return false;
    }

    /**
 * Vérifie les rôles de la personne connectée.\n
 * Si la personne a un seul rôle, retourne simplement le nom de ce rôle.\n
 * Si la personne a plusieurs rôles, retourne une liste des rôles sous forme de tableau.
 *
 * @return string|array Le nom du rôle ou une liste des rôles sous forme de tableau.
 */
    public function hasSeveralRoles()
    {
        $roles = [];
        $req = $this->bd->prepare('SELECT * FROM PRESTATAIRE WHERE id_personne = :id');
        $req->bindValue(':id', $_SESSION['id']);
        $req->execute();
        if ($req->fetch(PDO::FETCH_ASSOC)) {
            $roles[] = 'prestataire';
        }

        $req = $this->bd->prepare('SELECT * FROM GESTIONNAIRE WHERE id_personne = :id');
        $req->bindValue(':id', $_SESSION['id']);
        $req->execute();
        if ($req->fetch(PDO::FETCH_ASSOC)) {
            $roles[] = 'gestionnaire';
        }

        $req = $this->bd->prepare('SELECT * FROM COMMERCIAL WHERE id_personne = :id');
        $req->bindValue(':id', $_SESSION['id']);
        $req->execute();
        if ($req->fetch(PDO::FETCH_ASSOC)) {
            $roles[] = 'commercial';
        }

        $req = $this->bd->prepare('SELECT * FROM INTERLOCUTEUR WHERE id_personne = :id');
        $req->bindValue(':id', $_SESSION['id']);
        $req->execute();
        if ($req->fetch(PDO::FETCH_ASSOC)) {
            $roles[] = 'interlocuteur';
        }

        $req = $this->bd->prepare('SELECT * FROM ADMINISTRATEUR WHERE id_personne = :id');
        $req->bindValue(':id', $_SESSION['id']);
        $req->execute();
        if ($req->fetch(PDO::FETCH_ASSOC)) {
            $roles[] = 'administrateur';
        }

        if (sizeof($roles) > 1) {
            return ['roles' => $roles];
        }

        return $roles[0];
    }

    /**
 * Vérifie si une personne avec l'adresse e-mail donnée existe dans la base de données.
 *
 * @param string $email L'adresse e-mail à vérifier.
 * @return bool True si la personne existe, sinon False.
 */
    public function checkPersonneExiste($email)
    {
        $req = $this->bd->prepare('SELECT EXISTS (SELECT 1 FROM PERSONNE WHERE email = :email) AS personne_existe;');
        $req->bindValue(':email', $email);
        $req->execute();
        return $req->fetch()[0] == 't';
    }

    /**
 * Vérifie si une composante avec le nom et le client donnés existe dans la base de données.
 *
 * @param string $nom_compo Le nom de la composante à vérifier.
 * @param string $nom_client Le nom du client associé à la composante.
 * @return bool True si la composante existe, sinon False.
 */
    public function checkComposanteExiste($nom_compo, $nom_client)
    {
        $req = $this->bd->prepare('SELECT EXISTS (SELECT 1 FROM COMPOSANTE JOIN CLIENT USING(id_client) WHERE nom_composante = :nom_composante AND nom_client = :nom_client) AS composante_existe');
        $req->bindValue(':nom_composante', $nom_compo);
        $req->bindValue(':nom_client', $nom_client);
        $req->execute();
        return $req->fetch()[0] == 't';
    }

    /**
 * Vérifie si une société avec le nom donné existe dans la base de données.
 *
 * @param string $nom_client Le nom de la société à vérifier.
 * @return bool True si la société existe, sinon False.
 */
    public function checkSocieteExiste($nom_client)
    {
        $req = $this->bd->prepare('SELECT EXISTS (SELECT 1 FROM CLIENT WHERE nom_client = :nom_client) AS client_existe');
        $req->bindValue(':nom_client', $nom_client);
        $req->execute();
        return $req->fetch()[0] == 't';
    }

    /**
 * Vérifie si une mission avec le nom donné et associée à la composante donnée existe dans la base de données.
 *
 * @param string $nom_mission Le nom de la mission à vérifier.
 * @param string $nom_compo Le nom de la composante associée à la mission.
 * @return bool True si la mission existe, sinon False.
 */
    public function checkMissionExiste($nom_mission, $nom_compo)
    {
        $req = $this->bd->prepare('SELECT EXISTS (SELECT 1 FROM MISSION JOIN COMPOSANTE USING(id_composante) WHERE nom_composante = :nom_compo AND nom_mission = :nom_mission) AS mission_existe');
        $req->bindValue(':nom_compo', $nom_compo);
        $req->bindValue(':nom_mission', $nom_mission);
        $req->execute();
        return $req->fetch()[0] == 't';
    }

    /**
 * Vérifie si un interlocuteur avec l'adresse e-mail donnée existe dans la base de données.
 *
 * @param string $email L'adresse e-mail de l'interlocuteur à vérifier.
 * @return bool True si l'interlocuteur existe, sinon False.
 */
    public function checkInterlocuteurExiste($email)
    {
        $req = $this->bd->prepare('SELECT EXISTS (SELECT 1 FROM PERSONNE JOIN INTERLOCUTEUR USING(id_personne) WHERE email = :email) AS interlocuteur_existe');
        $req->bindValue(':email', $email);
        $req->execute();
        return $req->fetch()[0] == 't';
    }

    /**
 * Vérifie si un commercial avec l'adresse e-mail donnée existe dans la base de données.
 *
 * @param string $email L'adresse e-mail du commercial à vérifier.
 * @return bool True si le commercial existe, sinon False.
 */
    public function checkCommercialExiste($email)
    {
        $req = $this->bd->prepare('SELECT EXISTS (SELECT 1 FROM PERSONNE JOIN COMMERCIAL USING(id_personne) WHERE email = :email) AS commercial_existe');
        $req->bindValue(':email', $email);
        $req->execute();
        return $req->fetch()[0] == 't';
    }

    /**
 * Vérifie si un prestataire avec l'adresse e-mail donnée existe dans la base de données.
 *
 * @param string $email L'adresse e-mail du prestataire à vérifier.
 * @return bool True si le prestataire existe, sinon False.
 */
    public function checkPrestataireExiste($email)
    {
        $req = $this->bd->prepare('SELECT EXISTS (SELECT 1 FROM PERSONNE JOIN PRESTATAIRE USING(id_personne) WHERE email = :email) AS prestataire_existe');
        $req->bindValue(':email', $email);
        $req->execute();
        return $req->fetch()[0] == 't';
    }

    /**
 * Vérifie si un gestionnaire avec l'adresse e-mail donnée existe dans la base de données.
 *
 * @param string $email L'adresse e-mail du gestionnaire à vérifier.
 * @return bool True si le gestionnaire existe, sinon False.
 */
public function checkGestionnaireExiste($prenom, $nom, $email)
{
    $req = $this->bd->prepare('SELECT EXISTS (
        SELECT 1 FROM PERSONNE JOIN GESTIONNAIRE USING(id_personne) 
        WHERE email = :email OR (prenom = :prenom AND nom = :nom)
    ) AS gestionnaire_existe');
    $req->bindValue(':email', $email);
    $req->bindValue(':prenom', $prenom);
    $req->bindValue(':nom', $nom);
    $req->execute();
    return $req->fetch()['gestionnaire_existe'] == 1;
}

    /**
 * Vérifie si une activité avec l'identifiant de bon de livraison donné et la date donnée existe dans la base de données.
 *
 * @param int $id_bdl L'identifiant du bon de livraison.
 * @param string $date_activite La date de l'activité.
 * @return bool True si l'activité existe, sinon False.
 */
    public function checkActiviteExiste($id_bdl, $date_activite)
    {
        $req = $this->bd->prepare('SELECT EXISTS (SELECT 1 FROM ACTIVITE WHERE id_bdl = :id_bdl and date_bdl = :date_activite)');
        $req->bindValue(':id_bdl', $id_bdl);
        $req->bindValue(':date_activite', $date_activite);
        $req->execute();
        return $req->fetch()[0] == 't';
    }
}
