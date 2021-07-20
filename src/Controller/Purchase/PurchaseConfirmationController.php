<?php

namespace App\Controller\Purchase;

use App\Cart\CartService;
use App\Entity\Purchase;
use App\Form\CartConfirmationType;
use App\Purchase\PurchasePersister;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PurchaseConfirmationController extends AbstractController
{
    protected $cartService;
    protected $em;
    protected $persister;

    public function __construct(CartService $cartService, EntityManagerInterface $em, PurchasePersister $persister)
    {
        $this->cartService = $cartService;
        $this->em = $em;
        $this->persister = $persister;
    }
    /**
     * @Route("/purchase/confirm", name="purchase_confirm")
     * @IsGranted("ROLE_USER", message="Vous devez être connecté pour confirmer une commande")
     */
    public function confirm(Request $request)
    {
        //1 Nous voulons lire les données du form 
        $form = $this->createForm(CartConfirmationType::class);
        $form->handleRequest($request);

        //2 Formulaire non fourni je dégage
        if (!$form->isSubmitted()) {
            $this->addFlash('warning', 'Vous devez remplir le formulaire de confirmation');
            $this->redirectToRoute('cart_show');
        }

        //4 S'il n'y a pas de produits dans le pannier : On dégage
        $cartItems = $this->cartService->getDetailedCartItems();

        if (count($cartItems) === 0) {
            $this->addFlash('warning', 'Vous ne pouvez confirmer une commande avec un pannier vide');
            $this->redirectToRoute('cart_show');
        }


        //5 Nous allons créer une purchase
        /** @var Purchase */
        $purchase = $form->getData();

        //8 Enregistrer la cmd

        $this->persister->storePurchase($purchase);


        return $this->redirectToRoute("purchase_payment_form", [
            'id' => $purchase->getId()
        ]);
    }
}
