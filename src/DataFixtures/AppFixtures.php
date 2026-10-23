<?php

namespace App\DataFixtures;

use App\Entity\Order;
use App\Entity\OrderProduct;
use App\Entity\OrderStatus;
use App\Entity\Product;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private readonly UserPasswordHasherInterface $userPasswordHasher
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $user = (new User())
            ->setFirstName('Alex')
            ->setLastName('Morgan')
            ->setMail('alex.morgan@example.com')
            ->setRoles(['ROLE_API']);
        $user->setPassword($this->userPasswordHasher->hashPassword($user, 'password'));

        $status = (new OrderStatus())
            ->setLabel('PENDING');
        $status2 = (new OrderStatus())
            ->setLabel('COMPLETED');

        $products = [
            (new Product())
                ->setName('Coffee Beans')
                ->setShortDescription('Freshly roasted coffee beans.')
                ->setFullDescription('A 500 g bag of freshly roasted Arabica coffee beans.')
                ->setPrice(14.99)
                ->setPicturePath('/images/coffee-beans.jpg'),
            (new Product())
                ->setName('Ceramic Mug')
                ->setShortDescription('A handmade ceramic mug.')
                ->setFullDescription('A durable, handmade ceramic mug with a 350 ml capacity.')
                ->setPrice(12.50)
                ->setPicturePath('/images/ceramic-mug.jpg'),
        ];

        $order = (new Order())
            ->setStatus($status);
        $user->addOrder($order);

        foreach ($products as $index => $product) {
            $orderProduct = (new OrderProduct())
                ->setProduct($product)
                ->setQuantity($index === 0 ? 2 : 1)
                ->setLinkedOrder($order);
            $manager->persist($orderProduct);
            $manager->persist($product);
        }

        $manager->persist($user);
        $manager->persist($status);
        $manager->persist($status2);
        $manager->persist($order);
        $manager->flush();
    }
}