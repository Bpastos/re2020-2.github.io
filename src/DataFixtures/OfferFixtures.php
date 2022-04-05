<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Offer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class OfferFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($o = 1; $o <= 10; ++$o) {
            $offer = new Offer();
            $offer
                ->setName("Offer n°${o}")
                ->setDescription('texte')
                ->setPrice(10000);
            $manager->persist($offer);
        }
        $manager->flush();
    }
}
