<?php

namespace App\Controller\Purchase;

use App\Entity\Purchase;
use App\Cart\CartService;
use App\Event\PurchaseSuccessEvent;
use App\Repository\PurchaseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class PurchasePaymentSuccessController extends AbstractController
{
    /**
     * @Route("/purchase/terminate/{id}", name="purchase_payment_success")
     * @IsGranted("ROLE_USER");
     */

    public function success($id, PurchaseRepository $purchaseRepository, EntityManagerInterface $em, CartService $cartService, EventDispatcherInterface $dispatcher)
    {
        //1 Je récup la cmd
        $purchase = $purchaseRepository->find($id);

        if (
            !$purchase ||
            ($purchase && $purchase->getUser() !== $this->getUser()) ||
            ($purchase && $purchase->getStatus() === Purchase::STATUS_PAID)
        ) {
            $this->addFlash("warning", "La commande n'existe pas :");
            return $this->redirectToRoute('purchase_index');
        }
        //2 JE LA MET EN PAID

        $purchase->setStatus(Purchase::STATUS_PAID);
        $em->flush();

        //3 Vider le pannier
        $cartService->empty();

        //Lancer un evenement qui permet aux autres dev de réagir à la prise d'une commande.

        $purchaseEvent = new PurchaseSuccessEvent($purchase);

        $dispatcher->dispatch($purchaseEvent, 'purchase.success');


        //4 Je redirige 
        $this->addFlash('success', 'La commande à été payée et confirmée et payée');
        return $this->redirectToRoute('purchase_index');
    }
}
