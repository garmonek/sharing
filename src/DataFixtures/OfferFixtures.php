<?php /** @noinspection ALL */

namespace App\DataFixtures;

use App\DataFixtures\Image\ImageResource;
use App\Entity\City;
use App\Entity\District;
use App\Entity\Image;
use App\Entity\Offer;
use App\Entity\Tag;
use App\Entity\User;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\User\UserInterface;

/**
* User Fixtures class
*/
class OfferFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    /**
     * Load data.
     *
     * @param ObjectManager $manager
     */
    public function loadData(ObjectManager $manager): void
    {
        /** @var City $krk */
        $krk = $this->manager->getRepository(City::class)->findOneBy(['name' => 'kraków']);
        $rz = $this->manager->getRepository(City::class)->findOneBy(['name' => 'rzeszów']);

        ['borowikiTags' => $borowikiTags, 'klapkiTags' => $klapkiTags, 'other' => $otherTags ] =
            array_reduce(
                $this->manager->getRepository(Tag::class)->findAll(),
                function (array $carry, Tag $tag) {
                    if (in_array($tag->getName(), TagFixtures::BOROWIKI_TAGS)) {
                        $carry['borowikiTags'][] = $tag;

                        return $carry;
                    }

                    if (in_array($tag->getName(), TagFixtures::KLAPKI_TAGS)) {
                        $carry['klapkiTags'][] = $tag;

                        return $carry;
                    }

                    $carry['other'][] = $tag;

                    return $carry;
                },
                []
            );

        $admin = $this->manager->getRepository(User::class)->findOneBy(['username' => 'admin']);
        $notAdmin = $this->manager->getRepository(User::class)->findOneBy(['username' => 'user']);

        $offer = $this->createOffer('borowiki', $admin, $krk, $borowikiTags, $klapkiTags, 'borowik');
        $this->manager->persist($offer);

        $offer = $this->createOffer('klapkiKRK', $notAdmin, $krk, $klapkiTags, $borowikiTags, 'klapki');
        $this->manager->persist($offer);

        $almoustBorowikiTags = [$borowikiTags[0], $otherTags[0], $otherTags[1]];
        $offer = $this->createOffer('klapki2Krk', $notAdmin, $rz, $klapkiTags, $almoustBorowikiTags, 'klapki');
        $this->manager->persist($offer);

        $offer = $this->createOffer('borowiki2krk', $admin, $krk, $almoustBorowikiTags, $klapkiTags, 'borowik');
        $this->manager->persist($offer);


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
            TagFixtures::class,
            UserFixtures::class,
        ];
    }

    /**
     * @param string        $name
     * @param UserInterface $user
     * @param City          $krk
     * @param array         $tags
     * @param array         $exchangeTags
     * @param string        $image
     *
     * @return Offer
     */
    private function createOffer(string $name, UserInterface $user, City $krk, array $tags, array $exchangeTags, string $image = 'jelen'): Offer
    {
        $offer = new Offer();
        $offer->setUser($user);
        $offer->setDistrict($krk->getDistricts()->first());
        $offer->setName($name);
        foreach ($tags as $tag) {
            $offer->addTag($tag);
        }

        foreach ($exchangeTags as $tag) {
            $offer->addExchangeTag($tag);
        }

        $offer->setActive(true);
        $description = array_reduce($tags, function (string $out, Tag $tag) {
            $out .= $tag->getName().' ';

            return $out;
        }, '');
        $offer->setDescription($description);

        $imageResource = new ImageResource();
        $images = $imageResource->getImages($image);
        /** @var Image $image */
        foreach ($images as $image) {
            $image->setUser($user);
            $offer->addImage($image);
        }

        return $offer;
    }
}
