<?php

namespace App\DataFixtures;

use App\Entity\City;
use App\Entity\District;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
* User Fixtures class
*/
class OfferFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    /**
     * Load data.
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function loadData(ObjectManager $manager): void
    {
        $krk = $this->manager->getRepository(City::class)->findOneBy(['name'=> 'kraków']);
        $rz = $this->manager->getRepository(City::class)->findOneBy(['name'=> 'rzeszów']);

        $matchingTags1 = [
            'jedzenie',
            'grzyby',
            'wegetariańskie jedzenie'
        ];

        $exchangeTags1 = [];

        //todo zbiór randomowych obrazków, zbiór tagów
        //wziąść ze zbioru tagów po 3 różne na ofertę
        //wziąść z obrazków randomowo od 3 do 1 obrazka
        //użyć 3 pierwszych tagów do 2 ofert w odwrotności exchange tags tags
        //żeby pasowały

        //obrazki pododawać randomowo
        //description zrobić z tagów
        //name dać pasująca oferta 1
        //częściowo pasująca użyć części z tagów
        //niepasujące użyć wszystkiego oprócz tych tagów

        //todo dodać 2 perfekcyjnie pasujące oferty -obrazek żaba
        //dodać 3 czesciowo pasujące - obrazek kasza
        //dodać 4 nie pasujące - obrazek waleń
        //dla obu dystryktów


        $this->manager->flush();
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on
     *
     * @return array
     */
    public function getDependencies()
    {
        return [
            CityFixtures::class
        ];
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
