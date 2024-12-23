<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\EnergyDrink;
class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $energyDrink = new EnergyDrink();
        $energyDrink->setName('Red Bull Winter Edition');
        $energyDrink->setImage("/public/images/winteredition.jpg");
        $manager->persist($energyDrink);

        $energyDrink = new EnergyDrink();
        $energyDrink->setName('Red Bull Summer Edition');
        $energyDrink->setImage("/public/images/summeredition.jpg");
        $manager->persist($energyDrink);

        $manager->flush();
}

}
