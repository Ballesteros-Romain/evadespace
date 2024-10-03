<?php

namespace App\EventListener;

use App\Entity\Reservation;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;

final class ReservationListener
{
        private MailerInterface $mailer;

        public function __construct(MailerInterface $mailer){
            $this->mailer = $mailer;
        }
    #[AsEventListener(event: BeforeEntityPersistedEvent::class)]
    public function onReservation($event): void
    {
        $entity = $event->getEntityInstance();

        if(!$entity instanceof Reservation){
            return;
        }

        $user = $event->getEntityInstance();
        $start_date = $user->getStartDate();
        $end_date = $user->getEndDate();

        $email = (new TemplatedEmail())
        ->from('evadespace@contact.com')
        // ->from(new Address('evadespace@contact.com', 'contact evad\'espace'))
        ->to($user->getEmail())
        ->subject('Il y a une nouvelle reservation')
        ->htmlTemplate('_partials/_mail.html.twig')
        ->context([
                'user' => $user,
                'start_date' => $start_date,
                'end_date' => $end_date
            ]);
            $this->mailer->send($email);

    }
}