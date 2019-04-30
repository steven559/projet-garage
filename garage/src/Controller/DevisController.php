<?php

namespace App\Controller;

use App\Entity\AdminReponse;
use App\Entity\AdminReponseDevis;
use App\Entity\Devis;
use App\Form\AdminReponseDevisType;
use App\Form\DevisType;
use App\Repository\AdminReponseDevisRepository;
use App\Repository\DevisRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/devis")
 */
class DevisController extends AbstractController
{
    /**
     * @Route("/", name="devis_index", methods={"GET"})
     */
    public function index(DevisRepository $devisRepository,AdminReponseDevisRepository $adminReponseDevisRepository): Response
    {
        $user = $this->getUser();
        return $this->render('devis/index.html.twig', [
            'devis' => $devisRepository->findBy(["user"=>$user]),
            'AdminReponseDevi'=> $adminReponseDevisRepository->findBy(['user'=>$user]),
        ]);
    }

    /**
     * @param DevisRepository $devisRepository
     * @param AdminReponseDevisRepository $adminReponseDevisRepository
     * @return Response
     * @Route("/admin",name="devis_admin",methods={"GET"})
     */
    public function indexAdminDevis(DevisRepository $devisRepository,AdminReponseDevisRepository $adminReponseDevisRepository ){
        return $this->render('devis/index.html.twig',[
           //tout afficher les devis pour l'admin
            'devis' => $devisRepository->findAll(),
            'AdminReponseDevi' => $adminReponseDevisRepository->findAll()
        ]);
    }

    /**
     * @param Request $request
     * @param Devis $devis
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/admin/{id}",name="devis_newadmin",methods={"GET","POST"})
     */
    public function ReponseDevis(Request $request, Devis $devis){

        $AdminDevis = new AdminReponseDevis();
       $form = $this->createForm(AdminReponseDevisType::class ,$AdminDevis);
       $form->handleRequest($request);

       if ($form->isSubmitted() && $form->isValid()){
           $user = $this->getUser();
           $user_email = $user->getEmail();
           $AdminDevis->setUser($devis->getUser());

           $transport =(new \Swift_SmtpTransport('smtp.gmail.com',465,'ssl'))
               ->setUsername('garagepilet@gmail.com')
               ->setPassword('b@bmarley789');

           $mailer = new\Swift_Mailer($transport);

           $message = (new\Swift_Message('Garage Pilet'))
               ->setSubject('Reponse du Garage pilet')
               ->setFrom('garagepilet@gmail.com')
               ->setTo($user_email)
               ->setBody(
                   $this->renderView(
                       'devis/reponseDevis.html.twig' ,[
                           'AdminReponseDevi' =>$AdminDevis,
                           'user' => $user
                   ]
                   ),
                   'text/html'
               );
           $mailer->send($message);





           $entityManager = $this->getDoctrine()->getManager();
           $entityManager->persist($AdminDevis);
           $entityManager->flush();

          return $this->redirectToRoute('devis_index');
       }



           return $this->render('devis/newAdmin.html.twig',[
               'AdminReponseDevi' => $AdminDevis,
               'form' => $form->createView(),
           ]);


    }

    /**
     * @param AdminReponseDevis $adminReponseDevis
     * @return Response
     * @Route("/show/{id}",name="reponse_devis",methods={"GET"})
     */
    public function showDevisAdmin(AdminReponseDevis $adminReponseDevis): Response
    {
        return $this->render('devis/showDevisAdmin.html.twig',[
            'AdminReponseDevis' => $adminReponseDevis
        ]);
    }


    /**
     * @Route("/new", name="devis_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $devi = new Devis();

        $form = $this->createForm(DevisType::class, $devi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
           $user = $this->getUser();
           $user_email = $user->getEmail();

            $devi->setUser($this->getUser());
            //Envoie email
            $transport = (new \Swift_SmtpTransport('smtp.gmail.com',465,'ssl'))
                ->setUsername('garagepilet@gmail.com')
                ->setPassword('b@bmarley789');

            $mailer = new\Swift_Mailer($transport);

            $message = (new\Swift_Message('Garage Pilet'))
                ->setSubject('Demande de devis')
                ->setFrom( $user_email)
                ->setTo('garagepilet@gmail.com')
                ->setBody(
                    $this->renderView(
                        'devis/EmailDevis.html.twig',[
                            'devi' => $devi,
                            'User' => $user

                        ]
                    ),
                    'text/html'
                );
            $mailer->send($message);

            $this->redirectToRoute('devis_index');
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($devi);
            $entityManager->flush();

            return $this->redirectToRoute('devis_index');
        }

        return $this->render('devis/new.html.twig', [
            'devi' => $devi,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="devis_show", methods={"GET"})
     */
    public function show(Devis $devi): Response
    {
        return $this->render('devis/show.html.twig', [
            'devi' => $devi,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="devis_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Devis $devi): Response
    {
        $form = $this->createForm(DevisType::class, $devi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('devis_index', [
                'id' => $devi->getId(),
            ]);
        }

        return $this->render('devis/edit.html.twig', [
            'devi' => $devi,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="devis_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Devis $devi): Response
    {
        if ($this->isCsrfTokenValid('delete'.$devi->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($devi);
            $entityManager->flush();
        }

        return $this->redirectToRoute('devis_index');
    }
}
