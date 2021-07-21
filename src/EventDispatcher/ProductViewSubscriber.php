<?php

namespace App\EventDispatcher;

use Psr\Log\LoggerInterface;
use App\Event\ProductViewEvent;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ProductViewSubscriber implements EventSubscriberInterface
{
    protected $logger;
    protected $mailer;

    public function __construct(LoggerInterface $logger, MailerInterface $mailer)
    {
        $this->logger = $logger;
        $this->mailer = $mailer;
    }

    public static function getSubscribedEvents()
    {
        return [
            'product.view' => 'sendEmailProductView'
        ];
    }

    public function sendEmailProductView(ProductViewEvent $productViewEvent)
    {

        // $email = new TemplatedEmail();
        // $email->from(new Address("contact@mail.com", "Info de la boutique"))
        //     ->to("admin@mail.com")
        //     ->text("Un visiteur est entrain de  voir la page du produit" . $productViewEvent->getProduct()->getName())
        //     ->subject("Visite du produit " . $productViewEvent->getProduct()->getName())
        //     ->htmlTemplate('emails/product_view.html.twig')
        //     ->context([
        //         'product' => $productViewEvent->getProduct()
        //     ]);
        // $this->mailer->send($email);
        $this->logger->info("Le produit " . $productViewEvent->getProduct()->getName() . " est consulté");
    }
}
