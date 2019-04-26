<?php

namespace App\Controller;

use App\Entity\Content;
use App\Repository\VenteRepository;
use App\Entity\Horaire;
use App\Form\ContentType;
use App\Form\HoraireType;

use App\Repository\ContentRepository;

use App\Repository\HoraireRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/content")
 */
class ContentController extends AbstractController
{
    /**
     * @Route("/", name="content_index", methods={"GET"})
     */
    public function index(ContentRepository $contentRepository, HoraireRepository $horaireRepository , VenteRepository $venteRepository): Response
    {


      $lastvoitures= $venteRepository->articleAccueil();
        return $this->render('content/index.html.twig', [
            'lastvoitures'=> $lastvoitures,
            'contents' => $contentRepository->findAll(),
            'horaire' => $horaireRepository->findAll()
        ]);
    }

    /**
     * @Route("/mention",name="mention")
     */
    public function mention(){
        return $this->render('mention.html.twig');
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

            return $this->redirectToRoute('content_index');
        }
        return $this->render('content/newHoraire.html.twig', [
            'horaire' => $horraire,
            'form' => $form->createView(),
        ]);

    }

    /**
     * @Route("/new", name="content_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $content = new Content();
        $form = $this->createForm(ContentType::class, $content);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($content);
            $entityManager->flush();

            return $this->redirectToRoute('content_index');
        }

        return $this->render('content/new.html.twig', [
            'content' => $content,
            'form' => $form->createView(),
        ]);
    }



    /**
     * @Route("/afficher/{id}", name="content_show", methods={"GET"})
     */
    public function show(Content $content): Response
    {
        return $this->render('content/show.html.twig', [
            'content' => $content,
        ]);
    }

    /**
     * @Route("/afficherHoraire/{id}", name="horaire_show", methods={"GET"})
     */
    public function showHoraire(Horaire $horaire): Response
    {
        return $this->render('content/showHoraire.html.twig', [
            'horaire' => $horaire,
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

            return $this->redirectToRoute('content_index', [
                'id' => $horaire->getId(),
            ]);
        }
        return $this->render('content/editHoraire.html.twig', [
            'horaire' => $horaire,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="content_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Content $content): Response
    {
        $form = $this->createForm(ContentType::class, $content);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('content_index', [
                'id' => $content->getId(),
            ]);
        }

        return $this->render('content/edit.html.twig', [
            'content' => $content,
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
        return $this->redirectToRoute('content_index');

    }

    /**
     * @Route("/content{id}", name="content_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Content $content): Response
    {
        if ($this->isCsrfTokenValid('delete' . $content->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($content);
            $entityManager->flush();
        }

        return $this->redirectToRoute('content_index');
    }
}
