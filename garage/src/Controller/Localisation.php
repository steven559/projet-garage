<?php
/**
 * Created by PhpStorm.
 * User: Administrateur
 * Date: 29/04/2019
 * Time: 16:32
 */

namespace App\Controller;


use App\Entity\Contact;
use App\Entity\Horaire;
use App\Form\ContactType;
use App\Form\HoraireType;
use App\Repository\HoraireRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Localisation extends AbstractController
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/localisation", name="localisation", methods={"GET","POST"})
     */
public function index(Request $request , HoraireRepository $horaireRepository){

    $contact = new Contact();
    $form =$this->createForm(ContactType::class,$contact);
    $form->handleRequest($request);
    if($form->isSubmitted() && $form->isValid()) {
        $user = $contact->getEmail();

        $transport = (new \Swift_SmtpTransport('smtp.gmail.com',465,'ssl'))
            ->setUsername('garagepilet@gmail.com')
            ->setPassword('b@bmarley789');

        $mailer = (new\Swift_Mailer($transport));

        $message = (new\Swift_Message($user))
            ->setSubject('Contact')
            ->setFrom($user)
            ->setTo('garagepilet@gmail.com')
            ->setBody(
                $this->renderView(
                    'localisation/emailcontact.html.twig',[
                        'contact' =>$contact,
                        'user' =>$user
                    ]
                ),
                'text/html'
            );
        $mailer->send($message);

        $this->redirectToRoute('content_index');

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($contact);
        $entityManager->flush();


    }
    return $this->render('localisation/index.html.twig',[
        'form' =>$form->createView(),
        'horaire' => $horaireRepository->findAll()
    ]);



}


    /**
     * @Route("/{id}/editHoraire", name="horaire_edit", methods={"GET","POST"})
     */
    public function editHoraire(Request $request, Horaire $horaire): Response
    {
        $form = $this->createForm(HoraireType::class, $horaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('localisation', [
                'id' => $horaire->getId(),
            ]);
        }
        return $this->render('content/editHoraire.html.twig', [
            'horaire' => $horaire,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/horaire/{id}", name="horaire_delete", methods={"DELETE"})
     */
    public function deleteHoraire(Request $request, Horaire $horaire): Response
    {
        if ($this->isCsrfTokenValid('delete' . $horaire->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($horaire);
            $entityManager->flush();
        }
        return $this->redirectToRoute('localisation');

    }

    /**
     * @Route("/newHoraire" , name="horaire_new" ,methods={"GET","POST"})
     */
    public function newHorraire(Request $request)
    {
        $horraire = new Horaire();
        $form = $this->createForm(HoraireType::class, $horraire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($horraire);
            $entityManager->flush();

            return $this->redirectToRoute('localisation');
        }
        return $this->render('content/newHoraire.html.twig', [
            'horaire' => $horraire,
            'form' => $form->createView(),
        ]);

    }








}