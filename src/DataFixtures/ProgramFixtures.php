<?php

namespace App\DataFixtures;

use App\Entity\Program;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ProgramFixtures extends Fixture implements DependentFixtureInterface
{
    const COUNTRIES = [
        'France',
        'Germany',
        'United Kingdom',
        'United States',
        'Japan',
        'Russia',
    ];

    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 5; $i++) {
            foreach (CategoryFixtures::CATEGORIES as $key => $categoryName) {
                $program = new Program();
                $program->setTitle('Film ' . $key . $i);
                $program->setSynopsis('Un film populaire pour les amateurs du genre ' . $categoryName);
                $program->setCategory($this->getReference('category_' . $categoryName));
                $program->setCountry($this::COUNTRIES[$i]);
                $program->setYear(2000 + $i);
                $manager->persist($program);
                $manager->flush();
            }
        }
    }
    public function getDependencies()
    {
        // Tu retournes ici toutes les classes de fixtures dont ProgramFixtures dépend
        return [
            CategoryFixtures::class,
        ];
    }
}
