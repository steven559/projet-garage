<?php

namespace App\Controller;

use App\Entity\RendezVous;
use App\Entity\AdminReponse;
use App\Form\AdminReponseType;
use App\Form\RendezVousType;
use App\Repository\AdminReponseRepository;
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
     * @param RendezVousRepository $rendezVousRepository
     * @param AdminReponseRepository $adminReponseRepository
     * @return Response
     */
    public function index(RendezVousRepository $rendezVousRepository, AdminReponseRepository $adminReponseRepository): Response
    {
        $user = $this->getUser();

        return $this->render('rendez_vous/index.html.twig', [
            //Permet de bloquer le contenue a l'utililaseur uniquement

            'rendez_vouses' => $rendezVousRepository->findBy(["User"=>$user]),
            'AdminReponses'=> $adminReponseRepository->findBy(['user_id'=>$user]),
        ]);
    }



    /**
     * @param RendezVousRepository $rendezVousRepository
     * @return Response
     * @Route("/admin", name="rendez_vous_admin", methods={"GET"})
     */
    public function indexAdmin(RendezVousRepository $rendezVousRepository, AdminReponseRepository $adminReponseRepository): Response
    {
        return $this->render('rendez_vous/index.html.twig', [
            //permet de d'afficher tout les rendez-vous pour l'admin

            'rendez_vouses' => $rendezVousRepository->findAll(),
            'AdminReponses'=> $adminReponseRepository->findAll()
        ]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Route("/admin/new/{id}" ,name="rendez_vous_newAdmin", methods={"GET","POST"})
     */
    public function newResponse(Request $request, RendezVous $rendezVous): Response
    {
        //on créé le formulaire

        $AdminReponse = new AdminReponse();
        $form = $this->createForm(AdminReponseType::class,$AdminReponse);
        $form->handleRequest($request);

        //on verifie si il est valide
        if($form->isSubmitted() && $form->isValid()){

           //recuperé l'utilisateur connecter
            $user = $this->getUser();
            $user_email = $user->getEmail();
          /*  $AdminReponse->setCreatedAt(new\DateTime(time()));*/
            $AdminReponse->setUserId($rendezVous->getUser());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($AdminReponse);
            $entityManager->flush();

$this->addFlash('success', 'Message Envoyez ');

            return $this->redirectToRoute('rendez_vous_index');
        }
        return $this->render('rendez_vous/newAdmin.html.twig', [
            'reponse' => $AdminReponse,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/new", name="rendez_vous_new", methods={"GET","POST"})
     */
    public function new(Request $request, RendezVousRepository $rendezVousRepository): Response
    {
        $rendezVous = new RendezVous();
        $form = $this->createForm(RendezVousType::class, $rendezVous);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $user_email = $user->getEmail();
            $rendezVous->setUser($this->getUser());
            // Envoie email
            //configurer le swift avec l'email souhaiter

            $transport = (new \Swift_SmtpTransport('smtp.gmail.com',465,'ssl'))
                ->setUsername('garagepilet@gmail.com')
                ->setPassword('b@bmarley789');

            $mailer = new\Swift_Mailer($transport);
         //configuré l'affichage du mail
            //l'envoie le receveur
            $message = (new\Swift_Message('Garage Pilet'))
                ->setSubject('Message de rendez-vous')
                ->setFrom('garagepilet@gmail.com') //renplacer ar user-email
                ->setTo('garagepilet@gmail.com')
                ->setBody(
                    $this->renderView(
                        'content/rendez_vous.html.twig',[
                            'rendezvouses' => $rendezVous,
                            'User' => $user

                        ]
                    ),
                    'text/html'
                );
            //on envoie
            $mailer->send($message);

            $this->redirectToRoute('content_index');



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
     * @param AdminReponse $adminReponse
     * @return Response
     * @Route("/show/{id}",name="reponse_rendez_vous",methods={"GET"})
     */
    public function showAdmin(AdminReponse $adminReponse): Response
    {
        return $this->render('rendez_vous/showAdmin.html.twig',[
            'AdminReponse'=> $adminReponse
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
