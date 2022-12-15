<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\CartsProducts;
use App\Entity\Product;
use App\Entity\User;
use App\Form\AddCartType;
use App\Repository\CartsProductsRepository;
use App\Repository\ProductRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContentController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('content/home.html.twig', [
            'controller_name' => 'ContentController',
        ]);
    }

    #[Route('/products', name: 'app_products', methods: ['GET'])]
    public function indexProducts(ProductRepository $productRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $data = $productRepository->findAll();
        $product = $paginator->paginate(
            $data,
            $request->query->getInt('page', 1),
            6
        );
        return $this->render('content/product/index.html.twig', [
            'products' => $product,
        ]);
    }

    #[Route('/product/{id}', name: 'app_product_read', methods: ['GET', 'POST'])]
    public function show(Request $request, Product $product, CartsProductsRepository $cpRepository): Response
    {
        $status = NULL;
        $user = $this->getUser();
        $cart = $user->getCart();
        $cp = $cart->getCartsProducts()->toArray();

        $cartProduct = new CartsProducts();
        $cartProduct->setCart($cart);
        $form = $this->createForm(AddCartType::class, $cartProduct);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $cartProduct->setProduct($product);
            $quantity = $form->getData()->getQuantity();
            $cartProduct->setQuantity($quantity);
            if (isset($cp)) {
                foreach ($cp as $cpProduct) {
                    if ($cpProduct->getProduct() == $cartProduct->getProduct() && $cpProduct->getCart() == $cartProduct->getCart()) {
                        $quantity = $quantity + $cpProduct->getQuantity();
                        $cpProduct->setQuantity($quantity);
                        $cpRepository->save($cpProduct, true);
                        $status = true;
                    }
                }
            }
            if (empty($status)) {
                $cpRepository->save($cartProduct, true);
            }
            return $this->redirectToRoute('app_cart');
        }
        return $this->render('content/product/read.html.twig', [
            'product' => $product,
            'form' => $form->createView()
        ]);
    }
}
