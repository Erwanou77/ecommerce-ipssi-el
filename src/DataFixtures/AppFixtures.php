<?php

namespace App\DataFixtures;

use App\Entity\Brand;
use App\Entity\Category;
use App\Entity\Product;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(protected ManagerRegistry $managerRegistry, protected UserPasswordHasherInterface $userPasswordHasherInterface)
    {
    }
    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setFirstname('Gilbert');
        $user->setLastname('RedBull');
        $user->setEmail('gilbert@test.com');
        $user->setPassword($this->userPasswordHasherInterface->hashPassword($user, 'mdp1234'));
        $user->setRoles(["ROLE_USER"]);
        $ur = $this->managerRegistry->getRepository(User::class);
        $ur->save($user, true);

        $category = new Category();
        $category->setName('AleaNameCategory');
        $cr = $this->managerRegistry->getRepository(Category::class);
        $cr->save($category, true);

        $brand = new Brand();
        $brand->setName('AleaNameBrand');
        $br = $this->managerRegistry->getRepository(Brand::class);
        $br->save($brand, true);

        for ($i = 0; $i < 20; $i++) {
            $product = new Product();
            $product->setName('product ' . $i);
            $product->setExcerpt('Exercitation irure velit culpa deserunt aliquip.');
            $product->setDescription('Laborum ad mollit anim duis eu laborum minim nostrud. Mollit ea quis ex consequat laborum ad ea sunt consectetur commodo laboris consequat exercitation aliqua. Ex ex labore ut esse dolore qui. Mollit amet id id laborum nisi enim commodo mollit adipisicing dolore irure. Ex est voluptate laboris cillum pariatur labore culpa ad fugiat ipsum aliqua non quis.');
            $product->setImage('https://i.picsum.photos/id/415/800/600.jpg?hmac=vDE4_1ZCOkfLaRouF0AzF45LoTcdscIgb0stDNZ460k');
            $product->setQuantity(mt_rand(1, 15));
            $product->setSold(mt_rand(1, 5));
            $product->setPrice(mt_rand(10, 100));
            $product->setStatut(1);
            $product->setSeller($user);
            $product->setCategory($category);
            $product->setBrand($brand);
            $manager->persist($product);
        }
        $manager->flush();
    }
}
