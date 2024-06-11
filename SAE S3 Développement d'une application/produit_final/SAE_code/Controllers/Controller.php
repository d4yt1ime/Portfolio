<?php

abstract class Controller
{
    /**
     * Constructeur. Lance l'action correspondante
     */
    public function __construct()
    {

        //On détermine s'il existe dans l'url un paramètre action correspondant à une action du contrôleur
        if (isset($_GET['action']) and method_exists($this, "action_" . $_GET["action"])) {
            //Si c'est le cas, on appelle cette action
            $action = "action_" . $_GET["action"];
            $this->$action();
        } else {
            //Sinon, on appelle l'action par défaut
            $this->action_default();
        }
    }

    /**
     * Action par défaut du contrôleur (à définir dans les classes filles)
     */
    abstract public function action_default();

    /**
     * Affiche la vue
     * @param string $vue nom de la vue
     * @param array $data tableau contenant les données à passer à la vue
     * @return null
     */
    protected function render($vue, $data = [])
    {

        //On extrait les données à afficher
        extract($data);

        /* 
        On test si le parametre recherché est déjà dans la session sinon on le crée et le met en statut deconnecté
        On test ensuite l'état de la connexion, si l'état est à false, on ecrase la vue pour forcer celle de "connexion"
        Cette fonction est appellée dans tout les affichages donc quelque soit ce qu'on essaye d'accèder on retombera sur la page de connexion si on n'est pas connecté
        Si l'utilisateur est connecté, il n'y a aucun changement à ce qu'il essaye de charger 
        */
     
        if(! isset($_SESSION["connecte"])){
            $_SESSION["connecte"]=false;
        }
        if(! $_SESSION["connecte"]){
            $vue = "connexion";
        }
        //On teste si la vue existe
        $file_name = "Views/view_" . $vue . '.php';
        if (file_exists($file_name)) {
            //Si oui, on l'affiche
            include $file_name;
        } else {
            //Sinon, on affiche la page d'->action_error
            $this->action_error("La vue n'existe pas !");
        }
        // Pour terminer le script
        die();
    }

    /**
     * Méthode affichant une page d'erreur
     * @param string $message Message d'erreur à afficher
     * @return null
     */
    protected function action_error($message = '')
    {
        $data = [
            'title' => "Error",
            'message' => $message,
        ];
        $this->render("message", $data);
    }
}
