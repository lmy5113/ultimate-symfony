<?php

namespace App\EventDispatcher;

use App\Event\ProductViewEvent;
use App\Event\PurchaseSuccessEvent;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

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
            'product.view' => 'sendProductViewEmail'
        ];
    }

    public function sendProductViewEmail(ProductViewEvent $productViewEvent)
    {
        $email = new TemplatedEmail();
        $email->from(new Address("contact@mail.com", "Infos de la boutique"))
            ->to("admin@mail.com")
            ->htmlTemplate('emails/product_view.html.twig')
            ->context([
                'product' => $productViewEvent->getProduct()
            ])
            ->subject("Visite du produit {$productViewEvent->getProduct()->getId()}");

        // $this->mailer->send($email);
    }
}
