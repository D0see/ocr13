<?php

namespace App\Service;

use App\Entity\Order;
use App\Entity\User;
use App\Repository\OrderStatusRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use ErrorHelper;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class UserService {
    public function __construct(
        private readonly UserPasswordHasherInterface $userPasswordHasher,
        private readonly EntityManagerInterface $entityManager,
        private readonly ValidatorInterface $validator,
        private readonly UserRepository $userRepository,
        private readonly OrderStatusRepository $orderStatusRepository
    ){}

    public function createUser(
        string $mail,
        string $password,
        string $firstName,
        string $lastName,
    ): User {

        $userSharingMail = $this->userRepository->findOneBy(['mail' => $mail]);

        if ($userSharingMail !== null) {
            throw new HttpException(statusCode: Response::HTTP_CONFLICT, message: "this username is already taken");
        }

        $user = (new User())
        ->setMail($mail)
        ->setFirstName($firstName)
        ->setLastName($lastName)
        ->setRoles(['ROLE_USER']);

        $order = (new Order())
        ->setUser(($user))
        ->setStatus($this->orderStatusRepository->findOneBy(['label' => 'PENDING']));

        $errors = $this->validator->validate($user);

        if (count($errors) > 0) {
            throw new HttpException(
                statusCode: Response::HTTP_INTERNAL_SERVER_ERROR, 
                message: ErrorHelper::spreadContraintViolationsMessages($errors)
            );
        }

        $user->setPassword($this->userPasswordHasher->hashPassword($user, $password));

        $this->entityManager->persist($user);
        $this->entityManager->persist($order);

        $this->entityManager->flush();
        
        return $user;
    }
}