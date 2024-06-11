<?php

class Controller_login extends Controller
{

    /**
     * @inheritDoc
     */
    public function action_default()
    {
        $this->action_login_form();
    }
    /**
 * Action pour afficher le formulaire de connexion.\n
 * 
 * Cette fonction rend la vue "login" pour permettre à l'utilisateur de se connecter.
 * 
 * @return void
 */
    public function action_login_form()
    {
        $this->render("login");
    }

    /**
     * Vérifie que le mot de passe correspond au mail
     * @return void
     */
    public function action_check_pswd()
    {
        $db = Model::getModel();
        if (isset($_POST['mail']) && isset($_POST['password'])) {
            if (!preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $_POST['mail'])) {
                $msg = "Ce n'est pas un email correcte !";
            } else {
                $msg = "L'identifiant ou le mot de passe est incorrect !";

                if ($db->checkMailPassword(e($_POST['mail']), e($_POST['password']))) {
                    $role = $db->hasSeveralRoles();
                    if (isset($role['roles'])) {
                        $msg = $role;
                    } else {
                        $_SESSION['role'] = $role;
                        header("Location: index.php?controller=login&action=accueil");
                        return;
                    }
                }
            }

            $data = ['response' => $msg];
            $this->render('login', $data);
        }
    }

    /**
     * Renvoie vers la page d'accueil
     * @return void
     */
    public function action_accueil()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['id'])) {
            $data = ['menu' => [['link' => '?controller='.$_SESSION['role'].'&action=clients', 'name' => 'Société'],
            ['link' => '?controller='.$_SESSION['role'].'&action=composantes', 'name' => 'Composantes'],
            ['link' => '?controller='.$_SESSION['role'].'&action=missions', 'name' => 'Missions'],
            ['link' => '?controller='.$_SESSION['role'].'&action=prestataires', 'name' => 'Prestataires'],
            ['link' => '?controller='.$_SESSION['role'].'&action=commerciaux', 'name' => 'Commerciaux'],
            ['link' => '?controller='.$_SESSION['role'].'&action=gestionnaires', 'name' => 'Gestionnaires']]];
            return $this->render('accueil', $data);
        } else {
            echo 'Une erreur est survenue lors du chargement du tableau de bord';
        }
    }

    /**
     * Cette fonction va être appelée eu fur et à mesure que l'utilisateur tape son email afin de lui indiquer si son email existe\n
     * Elle vérifie si l'email existe dans la base de donnée, renvoie true si oui, false sinon
     * @return bool
     */
    public function action_check_mail()
    {
        $mailExisting = false;

        if (isset($_POST['mail'])) {
            $mail = e($_POST['mail']);
            //à chiffrer
            $bd = Model::getModel();
            $mailExisting = $bd->mailExists($mail);
        }

        return $mailExisting;
    }

}

