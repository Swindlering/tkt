<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ArticleFixtures extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        // add 20 articles
        for ($count = 0; $count < 20; $count++) {
            $article = new Article();
            $article->setTitle($this->generateShuffledStrings(30));
            $article->setSubTitle($this->generateShuffledStrings(45));
            $article->setContent($this->generateShuffledStrings(230));
            // link it to the user
            $users = [
                $this->getReference(UserFixtures::USER_REFERENCE_ADMIN),
                $this->getReference(UserFixtures::USER_REFERENCE_CONTACT)
            ];
            $article->setUser(
                $users[array_rand($users, 1)]
            );
            $manager->persist($article);
        }
        $manager->flush();
    }

    /***
     * Random Strings Generate
     */
    private function generateShuffledStrings($nbrChar)
    {
        $permitted_chars = '0123456789 abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $permitted_chars_length = strlen($permitted_chars);
        $random_string = '';
        for ($i = 0; $i < $nbrChar; $i++) {
            $random_character = $permitted_chars[mt_rand(0, $permitted_chars_length - 1)];
            $random_string .= $random_character;
        }

        return $random_string;
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
        ];
    }
}
