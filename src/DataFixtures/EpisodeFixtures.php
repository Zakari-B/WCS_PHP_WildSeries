<?php

namespace App\DataFixtures;

use App\Entity\Episode;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\String\Slugger\SluggerInterface;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    private SluggerInterface $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }
    
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        for ($i = 1; $i <= (count(CategoryFixtures::CATEGORIES) * ProgramFixtures::NB_PROGRAMS); $i++) {
            for ($j = 1; $j <= SeasonFixtures::NB_SEASONS; $j++) {
                for ($k = 1; $k <= 10; $k++) {
                    $episode = new Episode();
                    $episode->setSeason($this->getReference($i . '_season_' . $j));
                    $episode->setTitle('Episode ' . $k);
                    $this->addReference($i . '_season_' . $j . '_episode_' . $k, $episode);
                    $episode->setNumber($k);
                    $episode->setSynopsis($faker->paragraphs(2, true));
                    $episode->setDuration($faker->numberBetween(40, 50));
                    $episode->setSlug($this->slugger->slug($episode->getTitle()));
                    $manager->persist($episode);
                }
            }
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            SeasonFixtures::class,
        ];
    }
}
