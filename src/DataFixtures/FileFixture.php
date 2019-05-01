<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\File;
use Doctrine\Common\Persistence\ObjectManager;

class FileFixture extends BaseFixture
{
    // Listing of categories to be created
    // Names must correspond with categories from http://lorempixel.com
    private static $categoryNames = [
        'abstract',
        'animals',
        'business',
        'cats',
        'city',
        'food',
        'people',
        'nature',
        'sports',
        'technics',
        'transport',
    ];

    /**
     * Fill database with dummy data
     * @param ObjectManager $manager
     */
    public function loadData(ObjectManager $manager): void
    {
        // Create categories and create images in all categories
        foreach (self::$categoryNames as $categoryName)
        {
            // get a random number that is biased towards the lower of two given numbers
            $number = $this->faker->biasedNumberBetween(10, 25, function($x) { return 1 - sqrt($x); });

            $this->createManyWithAssociatedClass(File::class, Category::class, $number,
                function (File $file, Category $category, $count) use ($manager, $categoryName)
                {
                    $file->setFilePath($this->faker->image('public/images/gallery',
                        $width = 640, $height = 480, $categoryName, false))
                        ->setFileName($this->faker->word . ' ' . $categoryName)
                        ->setDescription($this->faker->sentence($nbWords = 12, $variableNbWords = true));
                    $category->setCategoryName($categoryName);
                    // add an association from current Category object to current File object
                    $file->setCategories($category);
                });
        }
        $manager->flush();
    }
}