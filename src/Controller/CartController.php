<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use App\Cart\CartService;
use App\Form\CartConfirmationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    protected $productRepository;
    protected $cartService;
    public function __construct(ProductRepository $productRepository, CartService $cartService)
    {
        $this->productRepository = $productRepository;
        $this->cartService = $cartService;
    }

    /**
     * @Route("/cart/add/{id}", name="cart_add", requirements={"id": "\d+"})
     */
    public function add(int $id, Request $request): Response
    {
        $product = $this->productRepository->find($id);
        if (!$product) {
            throw $this->createNotFoundException("Le produit {$id} n'existe pas.");    
        }

        $this->cartService->add($id);

        $this->addFlash('success', "Le produit a bien été ajouté au panier");
        if ($request->query->get('returnToCart')) {
            return $this->redirectToRoute('cart_show');
        } 

        return $this->redirectToRoute('product_show', [
            'category_slug' => $product->getCategory()->getSlug(),
            'slug' => $product->getSlug()
        ]);
    }

    /**
     * @Route("/cart", name="cart_show")
     */
    public function show() {
        $form = $this->createForm(CartConfirmationType::class);
        return $this->render('cart/index.html.twig', [
            'items' => $this->cartService->getDetailedCartItems(),
            'total' => $this->cartService->getTotal(),
            'confirmationForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/cart/delete/{id}", name="cart_delete", requirements={"id": "\d+"})
     */
    public function delete(int $id) {
        $product = $this->productRepository->find($id);

        if (!$product) {
            throw $this->createNotFoundException("Le produit {$id} n'existe pas");
        }

        $this->cartService->remove($id);

        $this->addFlash("success", "Le produit {$id} a été bien supprimé");

        return $this->redirectToRoute("cart_show");
    }

    /**
     * @Route("/cart/decrement/{id}", name="cart_decrement", requirements={"id": "\d+"})
     */
    public function decrement(int $id) {
        $product = $this->productRepository->find($id);

        if (!$product) {
            throw $this->createNotFoundException("Le produit {$id} n'existe pas");
        } 
        
        $this->cartService->decrement($id);

        $this->addFlash("success", "Le produit {$id} a été décrémenté");

         return $this->redirectToRoute("cart_show");
    }
}
