<?php

namespace App\EventDispatcher;

use App\Entity\User;
use App\Event\PurchaseSuccessEvent;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class PurchaseSuccessEmailSubscriber implements EventSubscriberInterface
{
    protected $mailer;
    protected $security;

    public function __construct(MailerInterface $mailer, Security $security) 
    {
        $this->mailer = $mailer;
        $this->security = $security;
    }

    public static function getSubscribedEvents()
    {
        return [
            'purchase.success' => 'sendSuccessEmail'
        ];
    }

    public function sendSuccessEmail(PurchaseSuccessEvent $purchaseSuccessEvent)
    {
        /** @var User */
        $currentUser = $this->security->getUser();
        $purchase = $purchaseSuccessEvent->getPurchase();
        $email = new TemplatedEmail();
        $email->to(new Address($currentUser->getEmail(), $currentUser->getFullName()))
              ->from("contact@admin.com")
              ->subject("Votre commande {$purchase->getId()} a bien été confirmée")
              ->htmlTemplate("emails/purchase_success.html.twig")
              ->context([
                  'purchase' => $purchase,
                  'user' => $currentUser
              ]);

              
        $this->mailer->send($email);

    }
}
