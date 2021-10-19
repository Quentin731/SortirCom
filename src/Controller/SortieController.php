<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Form\CreateSortieType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SortieController extends AbstractController
{

    private $entityManager;

    /**
     * ProductController constructor.
     * @param $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("/create_sortie", name="create_sortie")
     */
    public function index(Request $request): Response
    {
        $sortie = new Sortie();
        $form = $this->createForm(CreateSortieType::class, $sortie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($sortie);
            $entityManager->flush();
        }

        return $this->render('sortie/create-index.html.twig', [
            'createSortie' => $form->createView(),
        ]);
    }

    /**
     * @Route("/show_sortie/{id}", name="show_sortie")
     */
    public function show($id) : Response
    {
        $sortie = $this->entityManager->getRepository(Sortie::class)->find($id);

        return $this->render('sortie/show-index.html.twig', [
            'sortie' => $sortie
        ]);
    }
}
