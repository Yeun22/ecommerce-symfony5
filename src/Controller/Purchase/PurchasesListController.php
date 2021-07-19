<?php

namespace App\Controller\Purchase;

use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class  PurchasesListController extends AbstractController
{
    /**
     * @Route("/purchase", name="purchase_index")
     * @IsGranted("ROLE_USER", message="Vous devez être connecté pour accéder à vos commandes")
     */
    public function index()
    {
        // La personne est co ? --> Security
        /** @var User */
        $user = $this->getUser();

        if (!$user) {
            throw new AccessDeniedException("Vous devez être connecté pour accéder à vos commandes");
        }
        // Qui est co ? -->Security

        // On renvoie l'user connecté à twig pour afficher ses cmd -->Environnement de twig + Response
        return $this->render('purchase/index.html.twig', ['purchases' => $user->getPurchases()]);
    }
}
