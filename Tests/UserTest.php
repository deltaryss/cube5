<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use App\Controllers\User;

class UserTest extends TestCase
{
    public function testRegisterActionWithPasswordMismatch()
    {
        $user = new User([]);

        //set data to simulate a form submission
        $formData = [
            'email' => 'testMismatch@test.com',
            'username' => 'testMismatch',
            'password' => 'password',
            'password-check' => 'password2'
        ];

        //set the POST superglobal
        $_POST = $formData;

        $_POST['submit'] = true;

        //wait for "Les mots de passe ne correspondent pas." exception
        $this->expectException(\Exception::class);

        //call the method
        $user->registerAction();

        //reset the POST superglobal
        $_POST = [];
    }
    public function testRegisterActionWithExistingUser()
    {
        $user = new User([]);

        //set data to simulate a form submission
        $formData = [
            'email' => 'admin@admin.fr',
            'username' => 'Azerty',
            'password' => 'password',
            'password-check' => 'password'
        ];

        //set the POST superglobal
        $_POST = $formData;
        $_POST['submit'] = true;

        //wait for "L'utilisateur existe déjà." exception
        $this->expectException(\Exception::class);

        //call the method
        $user->registerAction();

        //reset the POST superglobal
        $_POST = [];
    }
    public function testRegisterActionWithValidData()
    {
        $user = new User([]);

        //set data to simulate a form submission
        $formData = [
            'email' => 'testRegister@test.com',
            'username' => 'testRegister',
            'password' => 'password',
            'password-check' => 'password'
        ];

        //set the POST superglobal
        $_POST = $formData;
        $_POST['submit'] = true;

        //call the method
        $user->registerAction();

        //reset the POST superglobal
        $_POST = [];

        //check if the user exists
        $this->assertNotNull(\App\Models\User::getUserByEmail($formData['email']));

        //delete the user
        \App\Models\User::deleteUserByEmail($formData['email']);
    }

    public function testLoginActionWithValidData()
    {
        $user = new User([]);

        //set data to simulate a form submission
        $formData = [
            'email' => 'test@test.com',
            'password' => 'test'
        ];

        //set the POST superglobal
        $_POST = $formData;
        $_POST['submit'] = true;

        //call the method
        $user->loginAction();

        //reset the POST superglobal
        $_POST = [];

        //check if the user is logged in
        $this->assertNotNull($_SESSION['user']);
    }
    public function testLoginActionWithInvalidData()
    {
        $user = new User([]);

        //set data to simulate a form submission
        $formData = [
            'email' => 'test@notecist.oreo',
            'password' => 'test',
        ];

        //set the POST superglobal
        $_POST = $formData;
        $_POST['submit'] = true;

        //wait for "wrong login info" exception
        $this->expectException(\Exception::class);

        //call the method
        $user->loginAction();
    
        //reset the POST superglobal
        $_POST = [];
    }
}
