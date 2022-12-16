<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\CartsProducts;
use App\Entity\User;
use App\Form\ProfilType;
use App\Form\RegistrationFormType;
use App\Repository\CartsProductsRepository;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use App\Security\UserAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Stripe\StripeClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class UserController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, UserAuthenticator $authenticator, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $cart = new Cart();
            $user->setCart($cart);
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }

        return $this->render('security/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/profil', name: 'app_profil')]
    public function profil(ProductRepository $productRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $data = $productRepository->findBy(['seller' => $this->getUser()]);
        $product = $paginator->paginate(
            $data,
            $request->query->getInt('page', 1),
            6
        );
        return $this->render('security/profil.html.twig', [
            'products' => $product
        ]);
    }

    #[Route('/votre-panier', name: 'app_cart')]
    public function cart(): Response
    {
        $user = $this->getUser();
        $cart = $user->getCart();
        $cartProducts = $cart->getCartsProducts()->toArray();

        $quantities = [];
        foreach ($cartProducts as $product) {
            array_push($quantities, $product->getQuantity() * $product->getProduct()->getPrice());
        }
        $totalPrice = array_sum($quantities);
        return $this->render('security/profil/cart.html.twig', [
            'cartProducts' => $cartProducts,
            'total' => $totalPrice
        ]);
    }

    #[Route('/cart/{id}/delete', name: 'app_cart_delete')]
    public function deleteCart(CartsProducts $cartsProducts, CartsProductsRepository $cartsProductsRepository)
    {
        $cartsProductsRepository->remove($cartsProducts, true);

        return $this->redirectToRoute('app_cart');
    }

    #[Route('/profil/edit', name: 'app_profil_edit')]
    public function editProfil(Request $request, UserRepository $userRepository): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(ProfilType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->save($user, true);

            return $this->redirectToRoute('app_profil', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('security/editProfil.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/profil/checkout={total}', name: 'app_profil_checkout')]
    public function cartCheckout($total)
    {
        // $currentUser = $this->getUser()->getId();

        // if ($currentUser !== $user->getId()) {
        //     return $this->redirectToRoute('app_profil');
        // }
        if ($_POST) {
            $stripe = new \Stripe\StripeClient($this->getParameter('stripe_sk'));
            $stripe->paymentIntents->create(
                [
                    'amount' => $total,
                    'currency' => 'eur',
                    'automatic_payment_methods' => ['enabled' => true],
                ]
            );
        }

        return $this->render('security/checkout.html.twig');
    }
}
