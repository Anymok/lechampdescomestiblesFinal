<?php

namespace App\DataFixtures;

use App\Entity\Plants;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class PlantFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
       $faker = Factory::create('fr_FR');

       for($i = 0; $i < 100; $i++)
       {
           $plants = new Plants();
           $plants
                ->setTitle($faker->words(3, true))
                ->setDescription($faker->sentence(3, true))
                ->setQuantity($faker->numberBetween(1, 50))
                ->setType($faker->words(1, true))
                ->setColor($faker->words(1, true))
                ->setPrice($faker->numberBetween(0.50, 20));
                $manager->persist($plants);
        }





        $manager->flush();
    }
}
