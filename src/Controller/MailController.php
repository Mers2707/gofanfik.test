<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailerController extends AbstractController
{
    #/**
    # * @Route("/email/{email}, name="confirm_mail")
    # */
    public function sendEmail($email, MailerInterface $mailer): void
    {
        $email = (new Email())
            ->from('hello@example.com')
            ->to($email)
            ->subject('Confirm you registration!')
            ->text('Confirm you registration!')
            ->html('<p>Follow the <a href="">link</a> to continue registration</p>');

        $mailer->send($email);
    }
}