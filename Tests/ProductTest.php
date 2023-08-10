<?php

use PHPUnit\Framework\TestCase;
use App\Controllers\Product;
use App\Models\Articles;
use App\Utility\Upload;

class ProductTest extends TestCase
{
    public function testCreateArticle()
    {
        // Simuler les données du formulaire de création d'article
        $formData = [
            'title' => 'Test Article',
            'description' => 'This is a test article',
            // ... autres données nécessaires pour la création
        ];

        // Simuler les données de l'utilisateur connecté
        $_SESSION['user'] = ['id' => 1]; // Simuler l'ID de l'utilisateur

        // Mock de la méthode Upload::uploadFile()
        $pictureName = 'test_picture.jpg';
        Upload::expects($this->once())
              ->method('uploadFile')
              ->willReturn($pictureName);

        // Appeler la méthode de création d'article
        $productController = new Product();
        $productController->indexAction($formData);

        // Vérifier si l'article a été créé
        $article = Articles::getByUser($_SESSION['user']['id']);
        $this->assertCount(1, $article);
        $this->assertEquals($formData['title'], $article[0]['title']);
        $this->assertEquals($pictureName, $article[0]['picture']); // Vérifier l'image
    }
}
