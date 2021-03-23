<?php

namespace App\DataFixtures;

use App\Factory\CategoryFactory;
use App\Factory\CommentFactory;
use App\Factory\PostFactory;
use App\Factory\PostLikeFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {   // Création de 7 users
        UserFactory::new()->createMany(7);

        // Création d'un administrateur
        UserFactory::new()->create([
            'firstname' => 'Marie',
            'lastname' => 'C',
            'roles' => ['ROLE_ADMIN'],
            'password' => 'admin',
            'email' => 'admin@admin.com'
        ]);

       // Création de 3 catégories grâce à la CategoryFactory, l'usine à fabriquer des catégories
        CategoryFactory::new()->createMany(3);

       // Création de 10 articles grâce à la PostFactory, l'usine à fabriquer des articles
        PostFactory::new()->createMany(10);

       // Création de 10 commentaires
        CommentFactory::new()->createMany(10);

       //Création de Likes
        PostLikeFactory::new()->createMany(40);

        $manager->flush();
    }
}
