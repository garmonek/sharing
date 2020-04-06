<?php

namespace App\DataFixtures;

use App\Entity\Tag;
use Doctrine\Common\Persistence\ObjectManager;

/**
* User Fixtures class
*/
class TagFixtures extends AbstractBaseFixtures
{
    public const BOROWIKI_TAGS = ['jedzenie', 'grzyby', 'wegetariańskie jedzenie'];
    public const KLAPKI_TAGS = ['buty', 'obuwie', 'obówie męskie', 'klapki'];

    /**
     * Load data.
     *
     * @param ObjectManager $manager
     */
    public function loadData(ObjectManager $manager): void
    {
        $tags = array_merge(self::BOROWIKI_TAGS, self::KLAPKI_TAGS);

        foreach ($tags as $name) {
            $tag = new Tag();
            $tag->setName($name);
            $this->manager->persist($tag);
        }

        for ($i = 0; $i < 20; $i++) {
            $tag = new Tag();
            $tag->setName($this->faker->unique()->word());
            $this->manager->persist($tag);
        }

        $this->manager->flush();
    }
}
