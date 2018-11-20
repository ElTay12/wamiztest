<?php

namespace App\Controller;

use App\Entity\Mailing;
use App\Repository\MailingRepository;

use App\Entity\MailingSearch;
use App\Form\MailingSearchType;

use App\Entity\Newsletter;
use App\Form\NewsletterType;
use App\Notification\NewsletterNotification;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use \Symfony\Component\HttpFoundation\Request;

class EmailingController extends AbstractController
{

    public function __construct(MailingRepository $repository, ObjectManager $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }

    public function index(Request $request, NewsletterNotification $notification): Response
    {

        $mailingSearch = new MailingSearch();
        $form = $this->createForm(MailingSearchType::class, $mailingSearch);
        $form->handleRequest($request);

        $newsletter = new Newsletter();
        $formNewsletter = $this->createForm(NewsletterType::class, $newsletter);
        $formNewsletter->handleRequest($request);

        if($formNewsletter->isSubmitted() && $formNewsletter->isValid()){

            $send_mails = $this->repository->findAllVisible();
            foreach ($send_mails as $key => $mailing){
                $notification->notify($newsletter, $mailing->getEmail());
            }

            $this->addFlash('success', 'Newsletter has been send ('.count($send_mails).')');
        }

        $mails = $this->repository->findAllVisible($mailingSearch);

        return $this->render('pages/admin/emailing/index.html.twig', [
            'mails' => $mails,
            'form' => $form->createView(),
            'form_newsletter' => $formNewsletter->createView()
        ]);
    }

    public function delete(Mailing $mailing){

        $mailing->setDeletedAt(new \DateTime());
        $this->em->persist($mailing);
        $this->em->flush();

        $this->addFlash('success', 'This email has been deleted !');

        return $this->redirectToRoute('admin_emailing');
    }

}