<?php

namespace App\Controller;

use App\Entity\RendezVous;
use App\Form\RendezVousType;
use App\Repository\RendezVousRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/rendez/vous")
 */
class RendezVousController extends AbstractController
{

    //permet de faire la demande de rendez-vous
    /**
     * @Route("/user", name="rendez_vous_index", methods={"GET"})
     */
    public function index(RendezVousRepository $rendezVousRepository): Response
    {
        $user = $this->getUser();

        return $this->render('rendez_vous/index.html.twig', [
            //mettre la condition
            'rendez_vouses' => $rendezVousRepository->findBy(["User"=>$user]),
        ]);
    }


    //permet de recevoir tout les rendez-vous
    /**
     * @param RendezVousRepository $rendezVousRepository
     * @return Response
     * @Route("/admin", name="rendez_vous_admin", methods={"GET"})
     */
    public function indexAdmin(RendezVousRepository $rendezVousRepository): Response
    {
        return $this->render('rendez_vous/admin.html.twig', [

            'rendez_vouses' => $rendezVousRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="rendez_vous_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $rendezVous = new RendezVous();
        $form = $this->createForm(RendezVousType::class, $rendezVous);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $user_email = $user->getEmail();
            $rendezVous->setUser($this->getUser());
            // Envoie email
         /*   $form = $this->createForm(EmailType::class,$email);
            $message = (new \Swift_Message('Hello Email'))
                ->setFrom($form)
                ->setTo($form)
                ->setBody(
                    $this->renderView(
                    // templates/emails/registration.html.twig
                        'rendez_vous/admin.html.twig',
                        ['name' => $email]
                    ),
                    'text/html'
                );

            $mailer->send($message);*/


            // recup user connecte
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($rendezVous);
            $entityManager->flush();

            return $this->redirectToRoute('rendez_vous_index');
        }

        return $this->render('rendez_vous/new.html.twig', [
            'rendez_vous' => $rendezVous,
            'form' => $form->createView(),
        ]);
    }

 /*   public function indexMail($email, \Swift_Mailer $mailer)
    {
        $form = $this->createForm(EmailType::class,$email);
        $message = (new \Swift_Message($email))
            ->setFrom('')
            ->setTo('')
            ->setBody(
                $this->renderView(
                // templates/emails/registration.html.twig
                    'rendez_vous/admin.html.twig',
                    ['name' => $email]
                ),
                'text/html'
            );

        $mailer->send($message);

        return $this->render('rendez_vous/index.html.twig');
    }*/

    /**
     * @Route("/{id}", name="rendez_vous_show", methods={"GET"})
     */
    public function show(RendezVous $rendezVous): Response
    {
        return $this->render('rendez_vous/show.html.twig', [
            'rendez_vous' => $rendezVous,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="rendez_vous_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, RendezVous $rendezVous): Response
    {
        $form = $this->createForm(RendezVousType::class, $rendezVous);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('rendez_vous_index', [
                'id' => $rendezVous->getId(),
            ]);
        }

        return $this->render('rendez_vous/edit.html.twig', [
            'rendez_vous' => $rendezVous,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="rendez_vous_delete", methods={"DELETE"})
     */
    public function delete(Request $request, RendezVous $rendezVous): Response
    {
        if ($this->isCsrfTokenValid('delete'.$rendezVous->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($rendezVous);
            $entityManager->flush();
        }

        return $this->redirectToRoute('rendez_vous_index');
    }
}
