<?php

namespace App\Controller\Purchase;

use App\Entity\Purchase;
use App\Cart\CartService;
use App\Repository\PurchaseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PurchasePaymentSuccessController extends AbstractController {
    
    /**
     * @Route("/purchase/terminate/{id}", name="purchase_payment_success")
     * @IsGranted("ROLE_USER")
     */
    public function success(int $id, PurchaseRepository $purchaseRepository, EntityManagerInterface $em, CartService $cartService) {
        $purchase = $purchaseRepository->find($id);

        if (!$purchase || $purchase->getUser() != $this->getUser() || $purchase->getStatus() == Purchase::STATUS_PAID) {
            $this->addFlash("Warning", "La commande n'existe pas");
            return $this->redirectToRoute("purchase_index");
        }

        $purchase->setStatus(Purchase::STATUS_PAID);

        $em->persist($purchase);
        $em->flush();
        $cartService->empty();

        $this->addFlash('success', "La commande a été payé");

        return $this->redirectToRoute("purchase_index");
    }
}