<?php 

namespace App\EventDispatcher;

use App\Event\ProductViewEvent;
use App\Event\PurchaseSuccessEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ProductViewSubscriber implements EventSubscriberInterface {
    protected $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
    public static function getSubscribedEvents()
    {
        return [
            'product.view' => 'sendProductViewEmail'
        ];    
    }

    public function sendProductViewEmail(ProductViewEvent $productViewEvent) {
        $this->logger->info("product {$productViewEvent->getProduct()->getId()} viewed");
    }

}