<?php
namespace App\Notification;

use App\Entity\Newsletter;
use Twig\Environment;

class NewsletterNotification {

    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var Environment
     */
    private $renderer;

    public function __construct(\Swift_Mailer $mailer, Environment $renderer)
    {
        $this->mailer = $mailer;
        $this->renderer = $renderer;
    }

    public function notify(Newsletter $newsletter, $mail)
    {
        $message = (new \Swift_Message('Newsletter :' . $newsletter->getTitle()))
            ->setFrom('noreply@wamiztest.com')
            ->setTo($mail)
            ->setBody($this->renderer->render('emails/newsletter.html.twig', [
                'newsletter' => $newsletter
            ]), 'text/html');

        $this->mailer->send($message);

    }
}