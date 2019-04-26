<?php

namespace App\Controller;

use App\Entity\Vente;
use App\Form\VenteType;
use App\Repository\VenteRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @Route("/vente")
 */
class VenteController extends AbstractController
{
    /**
     * @Route("/", name="vente_index", methods={"GET"})
     */
    public function index(VenteRepository $venteRepository, PaginatorInterface $paginator,Request $request): Response
    {

        $image = $paginator->paginate(
            $venteRepository->findAll(), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            3 /*limit per page*/
        );


        return $this->render('vente/index.html.twig', [
            'ventes' => $image,

        ]);
    }



    /**
     * @Route("/new", name="vente_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $vente = new Vente();
        $form = $this->createForm(VenteType::class, $vente);
        $form->handleRequest($request);
        $dossier_image = $this->getParameter('uploads_directory');

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $request->files->get('vente')['image'];
            if($file){
                $new_image = md5(uniqid()) . '.' . $file->guessExtension();
                $file->move(
                    $dossier_image,
                    $new_image
                );
                $vente->setImage($new_image);
            }
            $vente = $form->getData();


            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($vente);
            $entityManager->flush();

            return $this->redirectToRoute('vente_index');
        }

        return $this->render('vente/new.html.twig', [
            'vente' => $vente,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="vente_show", methods={"GET"})
     */
    public function show(Vente $vente): Response
    {
        return $this->render('vente/show.html.twig', [
            'vente' => $vente,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="vente_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Vente $vente): Response
    {
        $form = $this->createForm(VenteType::class, $vente);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('vente_index', [
                'id' => $vente->getId(),
            ]);
        }

        return $this->render('vente/edit.html.twig', [
            'vente' => $vente,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="vente_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Vente $vente): Response
    {
        if ($this->isCsrfTokenValid('delete'.$vente->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($vente);
            $entityManager->flush();
        }

        return $this->redirectToRoute('vente_index');
    }
}
