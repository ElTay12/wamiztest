<?php

namespace App\Controller;

use App\Form\MailingType;
use App\Repository\MailingRepository;
use App\Entity\Mailing;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use \Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends AbstractController
{
    public function __construct(MailingRepository $repository, ObjectManager $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }

    public function index(Request $request): Response
    {
        // Init form Sign in Newsletter
        $mailing = new Mailing();
        $form = $this->createForm(MailingType::class, $mailing);
        $form->handleRequest($request);

        // If Submit newsletter
        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($mailing);
            $this->em->flush();

            $this->addFlash('success', 'Your email has been added');

            return $this->redirectToRoute('index');
        }

        // Check if error on form to show modal on view
        $errors = ( count($form->getErrors(true)) > 0 ) ? true : false;

        return $this->render('pages/index.html.twig', [
            'form' => $form->createView(),
            'errors' => $errors
        ]);
    }

    public function admin(): Response
    {
        return $this->render('pages/admin/index.html.twig');
    }
}