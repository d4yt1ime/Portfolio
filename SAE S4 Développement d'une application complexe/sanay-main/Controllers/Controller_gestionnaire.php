<?php

class Controller_gestionnaire extends Controller
{
    /**
     * @inheritDoc
     */
    public function action_default()
    {
        $this->action_dashboard();
    }

    /**
     * Renvoie le tableau de bord du gestionnaire avec les variables adéquates
     * @return void
     */
    public function action_dashboard()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['role'])) {
            unset($_SESSION['role']);
        }
        $_SESSION['role'] = 'gestionnaire';
        if (isset($_SESSION['id'])) {
            $bd = Model::getModel();
            $bdlLink = '?controller=gestionnaire&action=mission_bdl';
            $buttonLink = '?controller=gestionnaire&action=ajout_mission_form';
            $headerDashboard = ['Société', 'Composante', 'Nom Mission', 'Préstataire assigné', 'Bon de livraison'];
            $data = ['menu' => $this->action_get_navbar(), 'bdlLink' => $bdlLink, 'buttonLink' => $buttonLink, 'header' => $headerDashboard, 'dashboard' => $bd->getDashboardGestionnaire()];
            return $this->render('gestionnaire_missions', $data);
        } else {
            echo 'Une erreur est survenue lors du chargement du tableau de bord';
        }
    }

    /**
     * Action qui retourne les éléments du menu pour le gestionnaire
     * @return array[]
     */
    public function action_get_navbar()
    {
        return [['link' => '?controller=gestionnaire&action=clients', 'name' => 'Société'],
            ['link' => '?controller=gestionnaire&action=composantes', 'name' => 'Composantes'],
            ['link' => '?controller=gestionnaire&action=missions', 'name' => 'Missions'],
            ['link' => '?controller=gestionnaire&action=prestataires', 'name' => 'Prestataires'],
            ['link' => '?controller=gestionnaire&action=commerciaux', 'name' => 'Commerciaux']];
    }

    /**
     * Renvoie la vue qui montre les informations de l'utilisateur connecté
     * @return void
     */
    public function action_infos()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $this->render('infos', ['menu' => $this->action_get_navbar()]);
    }

    /*--------------------------------------------------------------------------------------*/
    /*                                Fonctions de mise à jour                              */
    /*--------------------------------------------------------------------------------------*/

    /**
     * Met à jour les informations de l'utilisateur connecté
     * @return void
     */
    public function action_maj_infos()
    {
        maj_infos_personne(); // fonction dans Utils
        $this->action_infos();
    }

    /**
     * Met à jour les informations du client
     * @return void
     */
    public function action_maj_infos_client()
    {
        maj_infos_client(); // fonction dans Utils
        $this->action_infos_client();
    }

    /**
     * Met à jour les informations de la personne
     * @return void
     */
    public function action_maj_infos_personne()
    {
        maj_infos_personne(); // fonction dans Utils
        $this->action_infos_personne();
    }

    /**
     * Met à jour les informations de la composante
     * @return void
     */
    public function action_maj_infos_composante()
    {
        maj_infos_composante(); // fonction dans Utils
        $this->action_infos_composante();
    }

    /**
     * Vérifie qu'il existe un id qui fait référence à une personne de la base de données et renvoie la vue qui affiche les données
     * @return void
     */
    public function action_infos_personne()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_GET['id'])) {
            $bd = Model::getModel();
            $data = ['person' => $bd->getInfosPersonne(e($_GET['id'])), 'menu' => $this->action_get_navbar()];
            $this->render("infos_personne", $data);
        }
    }

    /**
     * Renvoie la liste de toutes les composantes\n
     * La vérification de l'identifiant de Session permet de s'assurer que la personne est connectée en faisant partie de la base de données
     * @return void
     */
    public function action_composantes()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['id'])) {
            $bd = Model::getModel();
            $buttonLink = '?controller=gestionnaire&action=ajout_composante_form';
            $title = 'Composantes';
            $cardLink = '?controller=gestionnaire&action=infos_composante';
            $data = ['title' => $title, 'person' => $bd->getAllComposantes(), 'buttonLink' => $buttonLink, 'cardLink' => $cardLink, 'menu' => $this->action_get_navbar()];
            $this->render("liste", $data);
        }
    }

    /**
     * Renvoie la liste de tous les clients\n
     * La vérification de l'identifiant de Session permet de s'assurer que la personne est connectée en faisant partie de la base de données
     * @return void
     */
    public function action_clients()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['id'])) {
            $bd = Model::getModel();
            $buttonLink = '?controller=gestionnaire&action=ajout_client_form';
            $cardLink = '?controller=gestionnaire&action=infos_client';
            $data = ['title' => 'Société', 'buttonLink' => $buttonLink, 'cardLink' => $cardLink, 'person' => $bd->getAllClients(), 'menu' => $this->action_get_navbar()];
            $this->render("liste", $data);
        }
    }

    /**
     * Renvoie la liste de tous les prestataires\n
     * La vérification de l'identifiant de Session permet de s'assurer que la personne est connectée en faisant partie de la base de données
     * @return void
     */
    public function action_prestataires()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['id'])) {
            $bd = Model::getModel();
            $buttonLink = '?controller=gestionnaire&action=ajout_prestataire_form';
            $cardLink = "?controller=gestionnaire&action=infos_personne";
            $data = ['title' => 'Prestataires', 'cardLink' => $cardLink, "buttonLink" => $buttonLink, "person" => $bd->getAllPrestataires(), 'menu' => $this->action_get_navbar()];
            $this->render("liste", $data);
        }
    }

    /**
     * Renvoie la liste de tous les commerciaux\n
     * La vérification de l'identifiant de Session permet de s'assurer que la personne est connectée en faisant partie de la base de données
     * @return void
     */
    public function action_commerciaux()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['id'])) {
            $bd = Model::getModel();
            $buttonLink = '?controller=gestionnaire&action=ajout_commercial_form';
            $cardLink = "?controller=gestionnaire&action=infos_personne";
            $data = ['title' => 'Commerciaux', 'cardLink' => $cardLink, 'buttonLink' => $buttonLink, "person" => $bd->getCommerciaux(), 'menu' => $this->action_get_navbar()];
            $this->render("liste", $data);
        }
    }

    /**
     * Vérifie l'obtention des informations nécessaires pour renvoyer la vue liste avec les bonnes variables pour afficher la liste des bons de livraisons d'un prestataire en fonction de la mission
     * @return void
     */
    public function action_mission_bdl(){
        $bd = Model::getModel();
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if(isset($_GET['id']) && isset($_GET['id-prestataire'])){
            $cardLink = '?controller=gestionnaire&action=consulter_bdl';
            $data = ['title' => 'Bons de livraison', 'cardLink' => $cardLink, 'menu' => $this->action_get_navbar(), 'person' => $bd->getBdlsOfPrestataireByIdMission(e($_GET['id']), e($_GET['id-prestataire']))];
            $this->render('liste', $data);
        }
        $this->action_dashboard();
    }

    /**
     * Vérifie l'obtention des informations nécessaires à l'assignation d'un prestataire dans une mission
     * @return void
     */
    public function action_assigner_prestataire()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $bd = Model::getModel();
        if (isset($_POST['email'])) {
            $bd->assignerPrestataire(e($_POST['email']), e($_POST['mission']));
        }
        $this->action_dashboard();
    }


    /*--------------------------------------------------------------------------------------*/
    /*                                Formulaires d'ajout                                  */
    /*--------------------------------------------------------------------------------------*/

    /**
     * Renvoie la vue du formulaire pour l'ajout d'un interlocuteur
     * @return void
     */
    public function action_ajout_interlocuteur_form()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $data = ['menu' => $this->action_get_navbar()];
        $this->render('ajout_interlocuteur', $data);
    }

    /**
     * Renvoie la vue du formulaire pour l'ajout d'une composante
     * @return void
     */
    public function action_ajout_composante_form()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $data = ['menu' => $this->action_get_navbar()];
        $this->render('ajout_composante', $data);
    }

    /**
     * Renvoie la vue du formulaire pour l'ajout d'un prestataire
     * @return void
     */
    public function action_ajout_prestataire_form()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $data = ['menu' => $this->action_get_navbar()];
        $this->render('ajout_prestataire', $data);
    }

    /**
     * Renvoie la vue du formulaire pour l'ajout d'une mission
     * @return void
     */
    public function action_ajout_mission_form() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $bd = Model::getModel();
    
        // Récupérer les prestataires, sociétés et composantes depuis la base de données
        $prestataires = $bd->getAllPrestataires();
        $societes = $bd->getAllClients();
        $composantes = $bd->getAllComposantes();
    
        // Préparer les données à passer à la vue
        $data = [
            'menu' => $this->action_get_navbar(),
            'prestataires' => $prestataires,
            'societes' => $societes,
            'composantes' => $composantes
        ];
    
        // Rendre la vue avec les données
        $this->render('ajout_mission', $data);
    }

    /**
     * Renvoie la vue du formulaire pour l'ajout d'un client
     * @return void
     */
    public function action_ajout_client_form()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $data = ['menu' => $this->action_get_navbar()];
        $this->render('ajout_client', $data);
    }

    /**
     * Renvoie la vue du formulaire pour l'ajout d'un commercial
     * @return void
     */
    public function action_ajout_commercial_form()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $data = ['menu' => $this->action_get_navbar()];
        $this->render('ajout_commercial', $data);
    }

    /**
     * Vérifie l'obtention des informations nécessaires et que le commercial n'existe pas en tant que personne et commercial avant de l'ajouter
     * @return void
     */
    public function action_ajout_commercial()
    {
        $bd = Model::getModel();
        if (!$bd->checkPersonneExiste(e($_POST['email-commercial']))){
            $this->action_ajout_personne($_POST['nom'], $_POST['prenom'], $_POST['email-commercial']);
        }
        if (isset($_POST['email-commercial']) && $bd->checkPersonneExiste(e($_POST['email-commercial'])) && !$bd->checkCommercialExiste(e($_POST['email-commercial']))) {
            $bd->addCommercial(e($_POST['email-commercial']));
        }
        $this->action_default();
    }

    /**
     * Vérifie qu'il y a toutes les informations nécessaires pour l'ajout d'un(e) client/société
     * @return void
     */
    public function action_ajout_client()
    {
        $bd = Model::getModel();
        if (isset($_POST['client']) &&
            isset($_POST['tel']) &&
            isset($_POST['mission']) &&
            isset($_POST['type-bdl']) &&
            isset($_POST['date-mission']) &&
            isset($_POST['composante']) &&
            isset($_POST['numero-voie']) &&
            isset($_POST['type-voie']) &&
            isset($_POST['nom-voie']) &&
            isset($_POST['cp']) &&
            isset($_POST['ville']) &&
            isset($_POST['prenom-interlocuteur']) &&
            isset($_POST['nom-interlocuteur']) &&
            isset($_POST['email-interlocuteur']) &&
            isset($_POST['prenom-commercial']) &&
            isset($_POST['nom-commercial']) &&
            isset($_POST['email-commercial']) &&
            !$bd->checkSocieteExiste(e($_POST['client']))) {

            $bd->addClient(e($_POST['client']), e($_POST['tel']));
            $this->action_ajout_composante();
        }
        $this->action_ajout_client_form();
    }

    /**
     * Vérifie si la personne existe et la créée si ce n'est pas le cas
     * @param $nom
     * @param $prenom
     * @param $email
     * @return void
     */
    public function action_ajout_personne($nom, $prenom, $email)
    {
        $bd = Model::getModel();
        if (!$bd->checkPersonneExiste($email)) {
            $bd->createPersonne($nom, $prenom, $email, genererMdp());
        }
    }

    /**
     * Fonction qui créée une composante, son interlocuteur, commercial et les assigne a elle, et créée la mission.
     * @return void
     */
    public function action_ajout_composante()
    {
        $bd = Model::getModel();
        if (isset($_POST['composante']) &&
            isset($_POST['numero-voie']) &&
            isset($_POST['type-voie']) &&
            isset($_POST['nom-voie']) &&
            isset($_POST['cp']) &&
            isset($_POST['ville']) &&
            !$bd->checkComposanteExiste($_POST['composante'], $_POST['client'])) {
            $bd->addComposante($_POST['type-voie'],
                $_POST['cp'],
                $_POST['numero-voie'],
                $_POST['nom-voie'],
                $_POST['client'],
                $_POST['composante']);
            $this->action_ajout_interlocuteur_dans_composante();
            $this->action_ajout_commercial_dans_composante();
            $this->action_ajout_mission();
        }
        if(isset($_POST['tel'])){
            $this->action_ajout_client_form();
        }
        else{
            $this->action_ajout_composante_form();
        }
    }

    /**
     * Vérifie que la mission n'existe pas pour ensuite la créer
     * @return void
     */
    public function action_ajout_mission()
    {
        $bd = Model::getModel();
        
        // Vérifiez si les champs requis sont définis dans le tableau $_POST
        if (isset($_POST['mission'], $_POST['composante'], $_POST['prestataire'])) {
            $mission = e($_POST['mission']);
            $composante = e($_POST['composante']);
            $prestataire = e($_POST['prestataire']);
    
            if (!$bd->checkMissionExiste($mission, $composante)) {
                // Assurez-vous de passer $prestataire à addMission
                $bd->addMission(
                    e($_POST['type-bdl']),
                    $mission,
                    e($_POST['date-mission']),
                    $composante,
                    $prestataire
                );
            }
        }
    
        $this->action_default();
    }
    
    /**
     * Vérifie l'obtention de toutes les informations d'un prestataire pour ensuite créer la personne et l'ajouter en tant que prestataire
     * @return void
     */
    public function action_ajout_prestataire(){
        $bd = Model::getModel();
        if(isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['email-prestataire'])){
            $this->action_ajout_personne(e($_POST['nom']), e($_POST['prenom']), e($_POST['email-prestataire']));
            $bd->addPrestataire(e($_POST['email-prestataire']));
        }
    }

    /**
     * Vérifie l'obtention de toutes les informations nécessaires pour l'ajout d'un interlocuteur dans une composante
     * @return void
     */
    public function action_ajout_interlocuteur_dans_composante()
    {
        $bd = Model::getModel();
        if (isset($_GET['id-composante']) && isset($_POST['email-interlocuteur']) && isset($_POST['nom-interlocuteur']) && isset($_POST['prenom-interlocuteur'])) {
            if (!$bd->checkInterlocuteurExiste(e($_POST['email-interlocuteur']))) {
                $this->action_ajout_personne(e($_POST['nom-interlocuteur']), e($_POST['prenom-interlocuteur']), e($_POST['email-interlocuteur']));
                $bd->addInterlocuteur(e($_POST['email-interlocuteur']));
            }
            $bd->assignerInterlocuteurComposanteByIdComposante(e($_GET['id-composante']), e($_POST['email-interlocuteur']));
            $this->action_composantes();
        }
        if (isset($_GET['id-client']) && isset($_POST['email-interlocuteur']) && isset($_POST['nom-interlocuteur']) && isset($_POST['prenom-interlocuteur']) && isset($_POST['composante'])) {
            if (!$bd->checkInterlocuteurExiste(e($_POST['email-interlocuteur']))) {
                $this->action_ajout_personne(e($_POST['nom-interlocuteur']), e($_POST['prenom-interlocuteur']), e($_POST['email-interlocuteur']));
                $bd->addInterlocuteur(e($_POST['email-interlocuteur']));
            }
            $bd->assignerInterlocuteurComposanteByIdClient(e($_GET['id-client']), e($_POST['email-interlocuteur']), e($_POST['composante']));
            $this->action_clients();
        }
        if (isset($_POST['client']) && isset($_POST['composante'])){
            $id = $bd->getIdComposante(e($_POST['composante']), e($_POST['client']));
            $bd->assignerInterlocuteurComposanteByIdComposante($id['id_composante'], e($_POST['email-interlocuteur']));
        }
    }

    /**
     * Vérifie l'obtention des informations nécessaires pour l'ajout d'un prestataire dans une misison
     * @return void
     */
    public function action_ajout_prestataire_dans_mission()
    {
        $bd = Model::getModel();
        if (isset($_POST['mission']) && isset($_POST['email-prestataire']) && $_GET['id'] && $bd->checkPrestataireExiste(e($_POST['email-prestataire']))) {
            $bd->assignerPrestataire(e($_POST['email-prestataire']), e($_POST['mission']), e($_GET['id']));
        }
        $this->action_ajout_prestataire_form();
    }

    /**
     * Vérifie l'obtention des informations nécessaires pour l'ajout d'un commercial dans une composante
     * @return void
     */
    public function action_ajout_commercial_dans_composante()
    {
        $bd = Model::getModel();
        if (isset($_POST['composante']) && isset($_POST['email-commercial']) && isset($_POST['client'])) {
            $this->action_ajout_commercial();
            $bd->assignerCommercial(e($_POST['email-commercial']), e($_POST['composante']), e($_POST['client']));
        } elseif (isset($_POST['email-commercial']) && isset($_GET['id-composante'])) {
            $this->action_ajout_commercial();
            $bd->assignerCommercialByIdComposante(e($_POST['email-commercial']), e($_GET['id-composante']));
            $this->action_ajout_commercial_form();
        }
    }

    /**
     * Vérifie l'obtention d'un id dans l'url qui fait référence à la composante et renvoie la vue qui affiche les informations de la composante
     * @return void
     */
    public function action_infos_composante()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_GET['id'])) {
            $bd = Model::getModel();
            $infos = $bd->getInfosComposante(e($_GET['id']));
            $prestataires = $bd->getPrestatairesComposante(e($_GET['id']));
            $commerciaux = $bd->getCommerciauxComposante(e($_GET['id']));
            $interlocuteurs = $bd->getInterlocuteursComposante(e($_GET['id']));
            $bdl = $bd->getBdlComposante(e($_GET['id']));
            $cardLink = '?controller=gestionnaire';
            $data = ['infos' => $infos,
                'prestataires' => $prestataires,
                'commerciaux' => $commerciaux,
                'interlocuteurs' => $interlocuteurs,
                'bdl' => $bdl,
                'cardLink' => $cardLink,
                'menu' => $this->action_get_navbar()];
            $this->render('infos_composante', $data);
        }
    }

    /**
     * Vérifie qu'il existe dans l'url l'id qui fait référence au client et renvoie la vue qui affiche les informations sur le client
     * @return void
     */
    public function action_infos_client()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_GET['id'])) {
            $bd = Model::getModel();
            $infos = $bd->getInfosSociete(e($_GET['id']));
            $composantes = $bd->getComposantesSociete(e($_GET['id']));
            $interlocuteurs = $bd->getInterlocuteursSociete(e($_GET['id']));
            $data = ['infos' => $infos,
                'composantes' => $composantes,
                'interlocuteurs' => $interlocuteurs,
                'menu' => $this->action_get_navbar()];
            $this->render('infos_client', $data);
        }
    }

    /**
     * Vérifie qu'il existe dans l'url l'id qui fait référence au bon de livraison et renvoie la vue qui permet de consulter le bon de livraison
     * @return void
     */
    public function action_consulter_bdl(){
        $bd = Model::getModel();
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_GET['id'])) {
            $typeBdl = $bd->getBdlTypeAndMonth(e($_GET['id']));
            if($typeBdl['type_bdl'] == 'Heure'){
                $activites = $bd->getAllNbHeureActivite(e($_GET['id']));
            }
            if($typeBdl['type_bdl'] == 'Demi-journée'){
                $activites = $bd->getAllDemiJourActivite(e($_GET['id']));
            }
            if($typeBdl['type_bdl'] == 'Journée'){
                $activites = $bd->getAllJourActivite(e($_GET['id']));
            }

            $data = ['menu' => $this->action_get_navbar(), 'bdl' => $typeBdl, 'activites' => $activites];
            $this->render("consulte_bdl", $data);
        } else {
            echo 'Une erreur est survenue lors du chargement de ce bon de livraison';
        }
    }
}

?>
