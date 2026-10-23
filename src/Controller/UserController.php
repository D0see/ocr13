<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

#[Route('/user')]
final class UserController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly TokenStorageInterface $tokenStorage,
    ) {
    }

    #[Route('/my-account', name: 'user_my_account', methods: ['GET'])]
    public function myAccount(): Response
    {
        return $this->render('user/my_account.html.twig');
    }

    #[Route('/toggle-api-access', name: 'user_toggle_api_access', methods: ['POST'])]
    public function toggleApiAccess(): Response
    {
        $user = $this->getActiveUser();
        $roles = $user->getRoles();

        if (in_array('ROLE_API', $roles)) {
            $roles[] = 'ROLE_API';
        } else {
            $key = array_search('ROLE_API', $roles);
            array_splice($roles, $key, 1);
        }

        $user->setRoles($roles);
        $this->entityManager->flush();

        return $this->redirectToRoute('user_my_account');
    }

    #[Route('/delete-my-account', name: 'user_delete_my_account', methods: ['POST'])]
    public function deleteMyAccount(Request $request): Response
    {
        $user = $this->getActiveUser();

        foreach ($user->getOrders() as $order) {
            foreach ($order->getOrderProducts() as $orderProduct) {
                $this->entityManager->remove($orderProduct);
            }

            $this->entityManager->remove($order);
        }

        $this->entityManager->remove($user);
        $this->entityManager->flush();

        $this->tokenStorage->setToken(null);
        if ($request->hasSession()) {
            $request->getSession()->invalidate();
        }

        return $this->redirectToRoute('app_login');
    }

    private function getActiveUser(): User
    {
        $user = $this->getUser();

        if (!$user instanceof User) {
            throw new AccessDeniedException('An authenticated user is required.');
        }

        return $user;
    }
}
