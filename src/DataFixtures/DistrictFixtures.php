<?php /** @noinspection ALL */

namespace App\DataFixtures;

use App\Entity\City;
use App\Entity\District;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
* User Fixtures class
*/
class DistrictFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    /**
     * Load data.
     *
     * @param ObjectManager $manager
     */
    public function loadData(ObjectManager $manager): void
    {
        $krk = $this->manager->getRepository(City::class)->findOneBy(['name' => 'kraków']);
        $krkDistr = $this->getKrkDistricts();
        $this->persistDistrict($krkDistr, $krk);

        $rz = $this->manager->getRepository(City::class)->findOneBy(['name' => 'rzeszów']);
        $rzDistr = $this->getRzDistricts();
        $this->persistDistrict($rzDistr, $rz);

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
            CityFixtures::class,
        ];
    }

    /**
     * @return array
     */
    private function getKrkDistricts(): array
    {
        return [
            "stare miasto",
            "krowodrza",
            "bronowice",
            "zwierzyniec",
            "dębniki",
            "swoszowice",
            "podgórze duchackie",
            "bieżanów",
            "prokocim",
            "podgórze",
            "czyżyny",
            "mistrzejowice",
            "bieńczyce",
            "wzgórza krzesławickie",
            "nowa huta",
            "grzegórzki",
            "prądnik czerwony",
            "prądnik biały",
            "łagiewniki",
            "borek fałęcki",
        ];
    }

    /**
     * @return array
     */
    private function getRzDistricts(): array
    {
        return [
            'drabinianka',
            'piastów',
            'wilkowyja',
            'święty roch',
            'pobitno',
            'zaszkole',
            'słcina górna',
            'staromieście',
            'nowe miasto',
            'zalesie',
            'staroniwa',
            'baranówka',
            'śródmieście',
        ];
    }

    /**
     * @param array $districtsNames
     * @param City  $city
     */
    private function persistDistrict(array $districtsNames, City $city): void
    {
        foreach ($districtsNames as $districtsName) {
            $district = new District();

            $district->setName($districtsName);
            $district->setCity($city);
            $this->manager->persist($district);
        }
    }
}
