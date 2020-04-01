<?php

namespace App\DataFixtures;

use App\Entity\City;
use Doctrine\Common\Persistence\ObjectManager;

/**
* Product Fixtures class
*/
class CityFixtures extends AbstractBaseFixtures
{

    /**
     * Load data.
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function loadData(ObjectManager $manager): void
    {
        $cities = ['kraków','rzeszów'];

        foreach ($cities as $name) {
            $city = new City();
            $city->setName($name);

            $this->manager->persist($city);
        }

        $this->manager->flush();
    }
}
