<?php

namespace App\DataFixtures;

use App\Entity\CardSuit;
use App\Entity\CardValue;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CardFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $suits = ['Pique', 'Trèfle','Carreaux', 'Cœur'];
        foreach ($suits as $suit) {
            $cardSuit = new CardSuit();
            $cardSuit->setName($suit);
            $manager->persist($cardSuit);
        }

        $values = ['10','2', '3', 'AS','4', 'Valet','5', 'Dame', '8','Roi','6', '7', '9'];
        foreach ($values as $value) {
            $cardValue = new CardValue();
            $cardValue->setName($value);
            $manager->persist($cardValue);
        }

        $manager->flush();
    }
}
