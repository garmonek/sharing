<?php

namespace App\DataFixtures;

use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Role;
use App\Entity\User;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

/**
* RoleFixtures class
*/
class RoleFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    /**
     * Load data.
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function loadData(ObjectManager $manager): void
    {
        $admin = $manager->getRepository(User::class)->findOneBy(['username' => 'admin']);

        $role = new Role();
        $role->setRole(User::ROLE_ADMIN);
        $role->setUser($admin);

        $this->manager->persist($role);

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
        UserFixtures::class,
        ];
    }
}
