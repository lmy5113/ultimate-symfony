<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    protected $encoder;
    
    public function __construct(SluggerInterface $slugger, UserPasswordEncoderInterface $userPasswordEncoderInterface)
    {
        $this->slugger = $slugger;
        $this->encoder = $userPasswordEncoderInterface;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $faker->addProvider(new \Liior\Faker\Prices($faker));
        $faker->addProvider(new \Bezhanov\Faker\Provider\Commerce($faker));
        $faker->addProvider(new \Bluemmb\Faker\PicsumPhotosProvider($faker));
        
        $admin = new User();
        $hash = $this->encoder->encodePassword($admin, 'password');
        $admin->setEmail("admin@gmail.com")
            ->setPassword($hash)
            ->setFullName("Admin")
            ->setRoles(["ROLE_ADMIN"]);

        $manager->persist($admin);

        for ($u = 0; $u < 5; $u++) {
            $user = new User();
            $hash = $this->encoder->encodePassword($user, 'password');
            $user->setEmail("user{$u}@gmail.com")
                ->setFullName($faker->name())
                ->setPassword($hash);

            $manager->persist($user);
        }

        for ($c = 0; $c < 3; $c++) {
            $category = new Category();
            $category->setName($faker->department())
                ->setSlug(strtolower($this->slugger->slug($category->getName())));
            $manager->persist($category);

            for ($p = 0; $p < mt_rand(15, 20); $p++) {
                $product = new Product();
                $productName = $faker->productName();
                $product->setName($productName)
                    ->setPrice($faker->price(4000, 20000))
                    ->setCategory($category)
                    ->setShortDescription($faker->paragraph())
                    ->setMainPicture($faker->imageUrl(400, 400, true))
                    ->setSlug(strtolower($this->slugger->slug($productName)));

                $manager->persist($product);
            }
        }

        $manager->flush();
    }
}
