<?php

namespace App\Controllers;

use App\Config;
use App\Model\UserRegister;
use App\Models\Articles;
use App\Utility\Hash;
use App\Utility\Session;
use \Core\View;
use Exception;
use http\Env\Request;
use http\Exception\InvalidArgumentException;

/**
 * User controller
 */

class User extends \Core\Controller
{

    /**
     * Affiche la page de login
     */
    public function loginAction()
    {
        if(isset($_POST['submit'])){
            $f = $_POST;

            // TODO: Validation

            $loginResult = $this->login($f);

            // Si login OK, redirige vers le compte
            if($loginResult) {
                header('Location: /account');
            } else {
                // Affiche la page de login
                View::renderTemplate('User/login.html');
                throw new Exception('');
            }
        } else {
            // Affiche la page de login
            View::renderTemplate('User/login.html');
        }
    }

    /**
     * Page de création de compte
     */
    public function registerAction()
{
    $data = []; // Initialisation de la variable $data

    if (isset($_POST['submit'])) {
        $f = $_POST;

        if ($f['password'] !== $f['password-check']) {
            $data['error'] = "Les mots de passe ne correspondent pas.";
            View::renderTemplate('User/register.html', $data);
            throw new \Exception("Les mots de passe ne correspondent pas.");
            return; // Arrêter le processus ici si les mots de passe ne correspondent pas
        }

        // Vérifier si l'utilisateur existe déjà
        $existingUser = \App\Models\User::getUserByEmail($f['email']);
        if ($existingUser) {
            $data['error'] = "Un compte avec cette adresse e-mail existe déjà.";
            View::renderTemplate('User/register.html', $data);
            throw new \Exception("Un compte avec cette adresse e-mail existe déjà.");
            return; // Arrêter le processus ici si l'utilisateur existe déjà
        }

        // Validation et création du compte
        $userId = $this->register($f);

        if ($userId) {
            // Connexion automatique après la création du compte
            $user = \App\Models\User::getUserById($userId);
            $_SESSION['user'] = [
                'id' => $user['id'],
                'username' => $user['username'],
            ];
            header('Location: /account');
            return;
        }
    }

    View::renderTemplate('User/register.html', $data);
}


    /**
     * Affiche la page du compte
     */
    public function accountAction()
    {
        $articles = Articles::getByUser($_SESSION['user']['id']);

        View::renderTemplate('User/account.html', [
            'articles' => $articles
        ]);
    }

    /*
     * Fonction privée pour enregister un utilisateur
     */
    private function register($data)
    {
        try {
            // Generate a salt, which will be applied to the during the password
            // hashing process.
            $salt = Hash::generateSalt(32);

            $userID = \App\Models\User::createUser([
                "email" => $data['email'],
                "username" => $data['username'],
                "password" => Hash::generate($data['password'], $salt),
                "salt" => $salt
            ]);

            return $userID;

        } catch (Exception $ex) {
            // TODO : Set flash if error : utiliser la fonction en dessous
            /* Utility\Flash::danger($ex->getMessage());*/
        }
    }

    private function login($data) {
        try {
            if (!isset($data['email']) || !isset($data['password'])) {
                throw new Exception('Veuillez fournir une adresse e-mail et un mot de passe.');
            }
    
            $user = \App\Models\User::getByLogin($data['email']);
    
            if (!$user) {
                return false; // L'utilisateur n'existe pas
            }
    
            if (Hash::generate($data['password'], $user['salt']) !== $user['password']) {
                return false; // Mot de passe incorrect
            }
    
            // TODO: Create a remember me cookie if needed
    
            $_SESSION['user'] = array(
                'id' => $user['id'],
                'username' => $user['username'],
            );
    
            return true; // Connexion réussie
    
        } catch (Exception $ex) {
            // TODO: Gérer les erreurs et les messages d'erreur
            /* Utility\Flash::danger($ex->getMessage()); */
            return false; // Erreur lors de la tentative de connexion
        }
    }


    /**
     * Logout: Delete cookie and session. Returns true if everything is okay,
     * otherwise turns false.
     * @access public
     * @return boolean
     * @since 1.0.2
     */
    public function logoutAction() {

        /*
        if (isset($_COOKIE[$cookie])){
            // TODO: Delete the users remember me cookie if one has been stored.
            // https://github.com/andrewdyer/php-mvc-register-login/blob/development/www/app/Model/UserLogin.php#L148
        }*/
        // Destroy all data registered to the session.

        $_SESSION = array();

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        session_destroy();

        header ("Location: /");

        return true;
    }

}
