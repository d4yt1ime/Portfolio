<?php

class Model
{
    /**
     * Attribut contenant l'instance PDO
     */
    private $bd;

    /**
     * Attribut statique qui contiendra l'unique instance de Model
     */
    private static $instance = null;

    /**
     * Constructeur : effectue la connexion à la base de données.
     */
    private function __construct(){
        require "credentials.php";
        $this->bd = new PDO($dsn, $login, $mdp);
        $this->bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->bd->query("SET nameS 'utf8'");
    }

    /**
     * Méthode permettant de récupérer un modèle car le constructeur est privé (Implémentation du Design Pattern Singleton)
     */
    public static function getModel(){
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    //Fonction qui vérifie le mot de passe
    public function identification_Check(){
        /*
        On vérifie avant que les données sont bien transmisse et qu'elle correspondent aux type attendu
        La requete renvoie le mot de passe crypté qui a été enregistré dans la base de donnée à l'id donné
        Si une des conditions n'est pas rempli on renvoie false
        */
        if(isset($_POST["id"]) and preg_match("/^[0-9]+$/",$_POST["id"]) and isset($_POST["mdp"]) and $_POST["mdp"] != ""){
            $requete = $this->bd->prepare("SELECT id_personne AS id, motDePasse AS mdp, grade(:id) AS fonction FROM personne WHERE id_personne = :id");
            $requete->bindValue(":id",$_POST["id"]);
            $requete->execute();
            $res = $requete->fetch(PDO::FETCH_ASSOC);
            return $res;
        }
        return false;
    }

    public function ajouterUtilisateur($infos){
        $requete = $this->bd->prepare('INSERT INTO personne (id_personne, nom, prenom, email, phone, motDePasse) VALUES (:id, :nom, :prenom, :email, :phone, :mdp)');
        $requete->bindValue(":id",$infos["id"]);
        $requete->bindValue(":nom",$infos["nom"]);
        $requete->bindValue(":prenom",$infos["prenom"]);
        $requete->bindValue(":email",$infos["email"]);
        $requete->bindValue(":phone",$infos["phone"]);
        $requete->bindValue(":mdp",$infos["mdp"]);
        $requete->execute();
        if($infos["poste"]=="enseignant"){
            $requete = $this->bd->prepare("INSERT INTO enseignant (id_personne, idDiscipline, id_categorie, AA) VALUES (:id,
             (SELECT idDiscipline FROM discipline WHERE libelledisc = :disc), (SELECT id_categorie FROM categorie WHERE siglecat = :statut), :annee)");
            $requete->bindValue(":id",$infos["id"]);
            $requete->bindValue(":disc",$infos["discipline"]);
            $requete->bindValue(":statut",$infos["statut"]);
            $requete->bindValue(":annee",$infos["annee"]);
            $requete->execute();
            if(isset($infos["departements"])){
                foreach ($infos["departements"] as $v) {
                    $requete = $this->bd->prepare("INSERT INTO assigner (id_personne, idDepartement, AA, S) 
                    VALUES (:id, (SELECT idDepartement FROM departement WHERE libelledept = :libDept),:annee,:semestre)");
                    $requete->bindValue(":id",$infos["id"]);
                    $requete->bindValue(":libDept",$v);
                    $requete->bindValue(":annee",$infos["annee"]);
                    $requete->bindValue(":semestre",$infos["semestre"]);
                    $requete->execute();
                }
            }
            if($infos["direction"]=="true"){
                $requete = $this->bd->prepare("INSERT INTO equipedirection (id_personne) VALUES (:id)");
                $requete->bindValue(":id",$infos["id"]);
                $requete->execute();
            }
        }
        else {
            $requete = $this->bd->prepare("INSERT INTO secretaire (id_personne) VALUES (:id)");
            $requete->bindValue(":id",$infos["id"]);
            $requete->execute();
        }

    }

    public function id_in_db($id){
        $requete = $this->bd->prepare('SELECT * FROM personne WHERE :id = id_personne');
        $requete->bindValue(":id",$id);
        $requete->execute();
        $res = $requete->fetch(PDO::FETCH_NUM);
        if($res != false){
            return true;
        }
        return false;
    }

    public function getCatDiscDpt(){
        $requete = $this->bd->prepare('SELECT sigleCat FROM categorie');
        $requete->execute();
        $data["statut"] = $requete->fetchAll(PDO::FETCH_ASSOC);
        $requete = $this->bd->prepare('SELECT libelleDisc FROM discipline');
        $requete->execute();
        $data["discipline"] = $requete->fetchAll(PDO::FETCH_ASSOC);
        $requete = $this->bd->prepare('SELECT libelleDept FROM departement');
        $requete->execute();
        $data["departements"] = $requete->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }

    public function getList($offset = 0, $limit = 50, $order = "nom"){
        if(isset($_GET["recherche"]) and ! preg_match("/^ *$/",$_GET["recherche"])){
            $requete = $this->bd->prepare("SELECT id_personne,nom,prenom,grade(id_personne) AS fonction FROM personne WHERE nom ~* :rech OR prenom ~* :rech ORDER BY nom LIMIT :limit OFFSET :offset");
            $requete->bindValue(":rech", $_GET["recherche"],PDO::PARAM_STR);
        }
        else{
            $requete = $this->bd->prepare("SELECT id_personne,nom,prenom,grade(id_personne) AS fonction FROM personne ORDER BY nom LIMIT :limit OFFSET :offset");
        }
        $requete->bindValue(':limit', $limit);
        $requete->bindValue(':offset', $offset);
        $requete->execute();
        return $requete->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getInfoProfil($id){
        $requete = $this->bd->prepare("SELECT id_personne as id,nom,prenom,email,phone, grade(:id) AS fonction FROM personne WHERE id_personne = :id");
        $requete->bindValue(':id',$id);
        $requete->execute();
        $infos = $requete->fetch(PDO::FETCH_ASSOC);
        if($infos["fonction"] != "secretaire" and $infos["fonction"] != "personne"){
            $requete = $this->bd->prepare("SELECT libelledisc AS discipline, siglecat AS statut FROM enseignant 
            JOIN categorie USING (id_categorie) JOIN discipline USING (idDiscipline) WHERE id_personne = :id");
            $requete->bindValue(':id', $id);
            $requete->execute();
            $infos = array_merge($infos, $requete->fetch(PDO::FETCH_ASSOC));
            $requete = $this->bd->prepare("SELECT libelleDept AS depts FROM assigner JOIN 
            departement USING (idDepartement) WHERE assigner.id_personne = :id");
            $requete->bindValue(":id",$id);
            $requete->execute();
            $infos["depts"] = $requete->fetchAll(PDO::FETCH_ASSOC);
        }
        return $infos;
    }

    public function supprimerUtilisateur($id){
        $requete = $this->bd->prepare("DELETE FROM personne WHERE id_personne = :id");
        $requete->bindValue(':id',$id);
        $requete->execute();
    }

    public function getInfoDepartement($id) {
        $requete = $this->bd->prepare("SELECT * FROM departement WHERE id_personne = :id");
        $requete->bindValue(':id',$id);
        $requete->execute();
        $infos = $requete->fetch(PDO::FETCH_ASSOC);
        return $infos;       
    }

    public function updateProfil($infos){
        if ($infos['mdp'] == null){
        $requete = $this->bd->prepare("UPDATE personne SET nom = :nom, prenom = :prenom, email = :email, phone = :phone WHERE id_personne = :id");
        $requete->bindValue(":id",$infos["id"]);
        $requete->bindValue(":nom",$infos["nom"]);
        $requete->bindValue(":prenom",$infos["prenom"]);
        $requete->bindValue(":email",$infos["email"]);
        $requete->bindValue(":phone",$infos["phone"]);
        $requete->execute();
        } else {
            $requete = $this->bd->prepare("UPDATE personne SET nom = :nom, prenom = :prenom, email = :email, phone = :phone, motdepasse = :mdp WHERE id_personne = :id");
            $requete->bindValue(":id",$infos["id"]);
            $requete->bindValue(":nom",$infos["nom"]);
            $requete->bindValue(":prenom",$infos["prenom"]);
            $requete->bindValue(":email",$infos["email"]);
            $requete->bindValue(":phone",$infos["phone"]);
            $requete->bindValue(":mdp", $infos["mdp"]);
            $requete->execute();
        }
        if($infos["fonction"] != "secretaire" and $infos["fonction"] != "personne"){
            $requete = $this->bd->prepare("UPDATE enseignant SET idDiscipline = (SELECT idDiscipline FROM discipline WHERE libelledisc = :disc), 
                    id_categorie = (SELECT id_categorie FROM categorie WHERE siglecat = :statut) WHERE id_personne = :id");
            $requete->bindValue(":id",$infos["id"]);
            $requete->bindValue(":disc",$infos["discipline"]);
            $requete->bindValue(":statut",$infos["statut"]);
            $requete->execute();
        
            $requete = $this->bd->prepare("DELETE FROM assigner WHERE id_personne = :id");
            $requete->bindValue(":id", $infos["id"]);
            $requete->execute();
            if(isset($infos["departements"])){
                foreach ($infos["departements"] as $v) {
                    $requete = $this->bd->prepare("INSERT INTO assigner (id_personne, idDepartement, AA, S) 
                    VALUES (:id, (SELECT idDepartement FROM departement WHERE libelledept = :libDept),:annee,:semestre)");
                    $requete->bindValue(":id",$infos["id"]);
                    $requete->bindValue(":libDept",$v);
                    $requete->bindValue(":annee", $infos["annee"]);
                    $requete->bindValue(":semestre",$infos["semestre"]);
                    $requete->execute();
                }
            }
        }
    }

    public function getSemestre(){
        $requete = $this->bd->prepare("SELECT DISTINCT S FROM semestre");
        $requete->execute();
        return $requete->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getNomDepartement() {
        $requete = $this->bd->prepare("SELECT libelleDept, idDepartement AS id FROM departement");
        $requete->execute();
        return $requete->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getInfoDepartement2($id) {
        $requete = $this->bd->prepare("SELECT * FROM departement WHERE idDepartement = :id");
        $requete->bindValue(':id',$id);
        $requete->execute();
        $infos = $requete->fetch(PDO::FETCH_ASSOC);
        return $infos;       
    }

    public function getFormation(){
        $requete = $this->bd->prepare('SELECT idFormation AS id, nom FROM formation');
        $requete->execute();
        return $requete->fetchAll(PDO::FETCH_ASSOC);
       }
    
       public function getDiscipline() {
        $requete = $this->bd->prepare('SELECT idDiscipline AS id, libelleDisc AS nom FROM discipline');
        $requete -> execute();
        return $requete->fetchAll(PDO::FETCH_ASSOC);
    
       }
    
    public function ajouterBesoin() {
        $requete = $this->bd->prepare('DELETE FROM besoin WHERE aa = :annee AND s = :semestre AND iddepartement = (SELECT iddepartement FROM departement WHERE id_personne = :id) AND iddiscipline = :discipline AND idformation = :form');
        $requete->bindValue(":annee",$_POST["annee"]);
        $requete->bindValue(":semestre",$_POST["semestre"]);
        $requete->bindValue(":id",$_SESSION["id"]);
        $requete->bindValue(":discipline",$_POST["discipline"]);
        $requete->bindValue(":form",$_POST["formation"]);
        $requete->execute();

        $req = $this->bd->prepare('INSERT INTO besoin VALUES (:aa, :s, :idformation, :iddiscipline, (SELECT iddepartement FROM departement WHERE id_personne = :id), :besoin)');
        $req->bindValue(":aa",$_POST["annee"]);
        $req->bindValue(":s",$_POST["semestre"]);
        $req->bindValue(":idformation",$_POST["formation"]);
        $req->bindValue(":id",$_SESSION["id"]);
        $req->bindValue(":iddiscipline",$_POST["discipline"]);
        $req->bindValue(":besoin",$_POST["besoin"]);
        $req->execute(); 
       }

    public function ajouterHeure(){
        $requete = $this->bd->prepare('INSERT INTO enseigne (id_personne, idDiscipline, idDepartement, AA, S, nbHeureEns, typeH) VALUES (:id,
         (select idDiscipline FROM  enseignant WHERE id_personne = :id), :idDpt, :aa, :s, :nbHE, :typeH)');
        $requete->bindValue(":id",$_SESSION["id"]);
        $requete->bindValue(":aa",$_POST["annee"]);
        $requete->bindValue(":s",$_POST["semestre"]);
        $requete->bindValue(":nbHE",$_POST["heure"]);
        $requete->bindValue(":typeH",$_POST["type_h"]);
        $requete->bindValue(":idDpt",$_POST["dept"]);
        $requete->execute();
    }

    public function getAnnee(){
        $requete = $this->bd->prepare("SELECT AA FROM annee");
        $requete->execute();
        return $requete->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDpt(){
        $requete = $this->bd->prepare('SELECT idDepartement AS id, libelleDept AS nom FROM departement');
        $requete->execute();
        return $requete->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getNomFormationPropose($id){
        $requete = $this->bd->prepare('SELECT nom from propose join formation using (idformation) where iddepartement = :id');
        $requete->bindValue(':id',$id);
        $requete->execute();
        $res = $requete->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }

    public function getEffectifDpt($id){
        $requete = $this->bd->prepare('SELECT count(*) as nb from assigner where iddepartement = :id');
        $requete->bindValue(':id',$id);
        $requete->execute();
        $res = $requete->fetch(PDO::FETCH_ASSOC);
        return $res['nb'];
    }

    public function getBesoinHeureDpt($id){
        $requete = $this->bd->prepare('SELECT SUM(besoin_heure) from besoin where iddepartement = :id');
        $requete->bindValue(':id',$id);
        $requete->execute();
        $res = $requete->fetch(PDO::FETCH_ASSOC);
        return $res;
    }

    public function getDemande(){
        $requete = $this->bd->prepare('SELECT * from besoin JOIN demandes USING (idDepartement) JOIN personne USING (id_personne) JOIN departement using (iddepartement) JOIN discipline using (iddiscipline)');
        if ($_SESSION['permission']=='chefdedpt'){
            $requete = $this->bd->prepare('SELECT * from besoin JOIN demandes USING (idDepartement) JOIN personne USING (id_personne) JOIN departement using (iddepartement) JOIN discipline using (iddiscipline) where demandes.id_personne = :id');
            $requete->bindValue(':id',$_SESSION['id']);
        }
        $requete->execute();
        return $requete->fetchAll(PDO::FETCH_ASSOC);
       }

    public function getLog(){
        $requete = $this->bd->prepare('SELECT * from log ORDER BY date_modif DESC');
        $requete->execute();
        return $requete->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getHeure($id){
        if(isset($_POST["filter"])){
            if($_POST["filter"]=="annee"){
                $requete = $this->bd->prepare('SELECT AA AS label, SUM(nbHeureEns) AS heures from enseigne JOIN departement USING (idDepartement) where enseigne.id_personne = :id GROUP BY AA');
            }
            elseif($_POST["filter"]=="semestre"){
                $requete = $this->bd->prepare('SELECT S AS label, SUM(nbHeureEns) AS heures from enseigne JOIN departement USING (idDepartement) where enseigne.id_personne = :id GROUP BY S');
            }
            elseif($_POST["filter"]=="departement"){
                $requete = $this->bd->prepare('SELECT libelleDept AS label, SUM(nbHeureEns) AS heures from enseigne JOIN departement USING (idDepartement) where enseigne.id_personne = :id GROUP BY libelleDept');
            }
            else{
                $requete = $this->bd->prepare('SELECT typeH AS label, SUM(nbHeureEns) AS heures from enseigne JOIN departement USING (idDepartement) where enseigne.id_personne = :id GROUP BY typeH');
            }
        }
        else{
            $requete = $this->bd->prepare('SELECT typeH AS label, SUM(nbHeureEns) AS heures from enseigne JOIN departement USING (idDepartement) where enseigne.id_personne = :id GROUP BY typeH');
        }
        $requete->bindValue(':id',$id);
        $requete->execute();
        return $requete->fetchAll(PDO::FETCH_ASSOC);
   }

   public function getIUT(){
        if(isset($_POST["filter"]) and isset($_POST["choix"])){
            if($_POST["choix"]=="annee"){
                if($_POST["filter"]=="heures"){
                    $requete = $this->bd->prepare('SELECT AA AS label, SUM(nbHeureEns) AS nb from enseigne JOIN departement USING (idDepartement) GROUP BY AA ORDER BY AA');
                }
                if($_POST["filter"]=="statut"){
                    $requete = $this->bd->prepare('SELECT AA AS label, sigleCat AS sigle, count(sigleCat) AS nb from enseignant JOIN categorie USING (id_categorie) GROUP BY AA,sigleCat ORDER BY AA');
                }
            }
            elseif($_POST["choix"]=="semestre"){
                if($_POST["filter"]=="heures"){
                    $requete = $this->bd->prepare('SELECT AA AS label, S AS sigle, SUM(nbHeureEns) AS nb from enseigne JOIN departement USING (idDepartement) GROUP BY S,AA ORDER BY AA');
                }   
                if($_POST["filter"]=="statut"){
                    $requete = $this->bd->prepare('SELECT sigleCat AS label, S AS sigle, count(sigleCat) AS nb from enseignant JOIN categorie USING (id_categorie) JOIN semestre USING (AA) WHERE AA = 2024 GROUP BY S,sigleCat');
                }
            }
            elseif($_POST["choix"]=="departement"){
                if($_POST["filter"]=="heures"){
                    $requete = $this->bd->prepare('SELECT AA AS sigle, libelleDept AS label, SUM(nbHeureEns) AS nb from enseigne JOIN departement USING (idDepartement) GROUP BY libelleDept,AA ORDER BY AA');
                }
                if($_POST["filter"]=="statut"){
                    $requete = $this->bd->prepare('SELECT libelleDept AS label, sigleCat AS sigle ,count(sigleCat) AS nb from enseignant JOIN categorie USING (id_categorie) JOIN enseigne USING (id_personne) JOIN departement USING (idDepartement) WHERE enseigne.AA = 2024 GROUP BY sigleCat,libelleDept');
                }
            }
            elseif($_POST["choix"]=="discipline"){
                if($_POST["filter"]=="heures"){
                    $requete = $this->bd->prepare('SELECT libelleDisc AS label, AA AS sigle , SUM(nbHeureEns) AS nb from enseigne JOIN discipline USING (idDiscipline) GROUP BY AA,libelleDisc ORDER BY AA');
                }
                if($_POST["filter"]=="statut"){
                    $requete = $this->bd->prepare('SELECT libelleDisc AS label, sigleCat AS sigle, count(sigleCat) AS nb from enseignant JOIN categorie USING (id_categorie) JOIN  discipline USING (idDiscipline) GROUP BY sigleCat,libelleDisc');
                }
            }
            elseif($_POST["choix"]=="formation"){
                if($_POST["filter"]=="heures"){
                    $requete = $this->bd->prepare('SELECT AA as sigle, nom AS label, SUM(besoin_heure) AS nb FROM besoin JOIN formation USING (idFormation) GROUP BY AA,nom ORDER BY AA');
                }
                if($_POST["filter"]=="statut"){
                $requete = $this->bd->prepare('SELECT nom AS label, sigleCat AS sigle, count(sigleCat) AS nb from enseignant JOIN categorie USING (id_categorie) JOIN enseigne USING (id_personne) JOIN departement USING (idDepartement) JOIN propose USING (idDepartement) JOIN formation USING (idFormation) WHERE enseigne.AA = 2024 GROUP BY nom,sigleCat,libelleDept');
                }
            }
        }
        else{
            $requete = $this->bd->prepare('SELECT AA AS label, SUM(nbHeureEns) AS nb from enseigne JOIN departement USING (idDepartement) GROUP BY AA ORDER BY AA');
        }
        $requete->execute();
        return $requete->fetchAll(PDO::FETCH_ASSOC);
    }
}
