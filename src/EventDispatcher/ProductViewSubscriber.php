<?php

namespace App\EventDispatcher;

use Psr\Log\LoggerInterface;
use App\Event\ProductViewEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ProductViewSubscriber implements EventSubscriberInterface
{
    protected $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public static function getSubscribedEvents()
    {
        return [
            'product.view' => 'sendEmailProductView'
        ];
    }

    public function sendEmailProductView(ProductViewEvent $productViewEvent)
    {
        $this->logger->info("Le produit " . $productViewEvent->getProduct()->getName() . " est consulté");
    }
}
