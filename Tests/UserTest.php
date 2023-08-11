<?php

use PHPUnit\Framework\TestCase;
use App\Controllers\User;
use App\Models\User as UserModel;
use App\Utility\Hash;

class UserTest extends TestCase
{
    public function testRegister()
    {
        // Simuler les données du formulaire d'inscription
        $formData = [
            'email' => 'test@example.com',
            'username' => 'testuser',
            'password' => 'testpassword',
            'password-check' => 'testpassword',
        ];

        // Appeler la méthode de création de compte
        $userController = new User();
        $userId = $userController->register($formData);

        // Vérifier si l'utilisateur a été créé
        $user = UserModel::getUserById($userId);
        $this->assertEquals($user['email'], $formData['email']);
        $this->assertEquals($user['username'], $formData['username']);
    }

    // public function testLogin()
    // {
    //     // Créer un utilisateur pour le test
    //     $user = [
    //         'email' => 'test@example.com',
    //         'username' => 'testuser',
    //         'password' => Hash::generate('testpassword', 'salt'),
    //         'salt' => 'salt',
    //     ];
    //     UserModel::createUser($user);

    //     // Simuler les données du formulaire de connexion
    //     $loginData = [
    //         'email' => 'test@example.com',
    //         'password' => 'testpassword',
    //     ];

    //     // Appeler la méthode de connexion
    //     $userController = new User();
    //     $loggedIn = $userController->login($loginData);

    //     // Vérifier si la connexion est réussie
    //     $this->assertTrue($loggedIn);
    // }
}
