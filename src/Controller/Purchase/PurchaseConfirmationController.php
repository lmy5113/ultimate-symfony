<?php

namespace App\Controller\Purchase;

use DateTime;
use App\Entity\Purchase;
use App\Cart\CartService;
use App\Entity\PurchaseItem;
use App\Form\CartConfirmationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PurchaseConfirmationController extends AbstractController 
{
    protected $cartService;
    protected $em;

    public function __construct(CartService $cartService, EntityManagerInterface $em)
    {
        $this->cartService = $cartService;    
        $this->em = $em;
    }

    /**
     * @Route("/purchase/confirm", name="purchase_confirm")
     * @IsGranted("ROLE_USER", message="vous devez être connecté")
     */
    public function confirm(Request $request)
    {
        $form = $this->createForm(CartConfirmationType::class);
        $form->handleRequest($request);
        if (!$form->isSubmitted()) {
            $this->addFlash("warning", "Vous devez remplir le formulaire");
            return $this->redirectToRoute('cart_show');
        }

        $user = $this->getUser();

        $cartItems = $this->cartService->getDetailedCartItems();

        if (count($cartItems) == 0) {
            $this->addFlash('warning', "Votre panier est vide.");
            return $this->redirectToRoute('cart_show');
        }

        /** @var Purchase $purchase */
        $purchase = $form->getData();
        $purchase->setUser($user)
                 ->setPurchasedAt(new DateTime())
                 ->setTotal($this->cartService->getTotal());

        foreach ($this->cartService->getDetailedCartItems() as $cartItem) {
            $purchaseItem = new PurchaseItem();
            $purchaseItem->setPurchase($purchase)
                         ->setProduct($cartItem->product)
                         ->setProductName($cartItem->product->getName())
                         ->setQuantity($cartItem->qty)
                         ->setProductPrice($cartItem->product->getPrice())
                         ->setTotal($cartItem->getTotal());
            $this->em->persist($purchaseItem);         
        }

        $this->em->persist($purchase);
        
        $this->em->flush();
        $this->cartService->empty();

        $this->addFlash('success', "La commande a bien été enregistré");
        return $this->redirectToRoute('purchase_index');
    }
}