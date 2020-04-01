<?php

namespace App\DataFixtures;

use App\Entity\City;
use App\Entity\District;
use App\Entity\Tag;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
* User Fixtures class
*/
class OfferFixtures extends AbstractBaseFixtures
{
    /**
     * Load data.
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function loadData(ObjectManager $manager): void
    {
        $tags = [];
        $matchingTags1 = [
            'jedzenie',
            'grzyby',
            'wegetariańskie jedzenie',
        ];

        foreach ($tags as $name) {
            $tag = new Tag();

            $tag->setName($name);
            $this->manager->persist($tag);
        }

        $this->manager->flush();
    }

    /**
     * @param array $districtsNames
     * @param object|null $city
     */
    private function persistDistrict(array $districtsNames, ?object $city): void
    {
        foreach ($districtsNames as $districtsName) {
            $district = new District();

            $district->setName($districtsName);
            $district->setCity($city);
            $this->manager->persist($district);
        }
    }

}
