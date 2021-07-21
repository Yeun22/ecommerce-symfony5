<?php

namespace App\EventDispatcher;

use App\Entity\User;
use Psr\Log\LoggerInterface;
use App\Event\PurchaseSuccessEvent;
use Symfony\Component\Mime\Address;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PurchaseSuccessEmailSubscriber implements EventSubscriberInterface
{
    protected $logger;
    protected $mailler;
    protected $security;

    public function __construct(LoggerInterface $logger, MailerInterface $mailler, Security $security)
    {
        $this->logger = $logger;
        $this->mailler = $mailler;
        $this->security = $security;
    }

    public static function getSubscribedEvents()
    {
        return [
            'purchase.success' => "sendSuccessEmail"
        ];
    }

    public function sendSuccessEmail(PurchaseSuccessEvent $purchaseSuccessEvent)
    {

        //1 Récupéré l'user en ligne
        //Security
        /** @var User */
        $currentUser = $this->security->getUser();


        //2 récup la cmde
        //PurchaseSuccessEvent
        $purchase = $purchaseSuccessEvent->getPurchase();

        //3 Ecrire le mail
        $email = new TemplatedEmail();
        $email->to(new Address($currentUser->getEmail(), $currentUser->getFullName()))
            ->from("contact@mail.com")
            ->subject("Bravo, votre commande n° ({$purchase->getId()}) a bien été confirmée ")
            ->htmlTemplate('emails/purchase_success.html.twig')
            ->context([
                'purchase' => $purchase,
                'user' => $currentUser
            ]);

        //4 Envoyer l'email
        $this->mailler->send($email);


        return $this->logger->info("Email envoyé pour la commande n°" .
            $purchaseSuccessEvent->getPurchase()->getId());
    }
}
