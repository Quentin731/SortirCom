<?php

namespace App\Controller;

use App\Entity\Trip;
use App\Form\CreateSortieType;
use DateInterval;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TripController extends AbstractController
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
     * @Route("/trip/create", name="create_sortie")
     */
    public function index(Request $request): Response
    {
        $sortie = new Trip();
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
     * @Route("/trip/{id}", name="show_sortie")
     */
    public function show($id): Response
    {
        $sortie = $this->entityManager->getRepository(Trip::class)->find($id);
        if ($sortie == null) {
            return $this->redirectToRoute('home', array("error" => "impossible, la sortie n'existe pas"));
        }
        if ($sortie->getIsAviable() == false){
            return $this->redirectToRoute('home', array("error" => "impossible, la sortie est terminée depuis 30 jours"));
        }

        return $this->render('sortie/show-index.html.twig', [
            'sortie' => $sortie
        ]);
    }

    /**
     * @Route("/trip/{id}/registration", name="registration")
     */
    public function registration($id): Response
    {
        $user = $this->getUser();
        $trip = $this->entityManager->getRepository(Trip::class)->find($id);
        if ($trip == null || $user == null) {
            return $this->redirectToRoute('home', array("error" => "impossible"));
        }
        $error = $trip->addUserWithValidation($user);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($trip);
        $entityManager->flush();

        return $this->redirectToRoute('home', array("error" => $error));
    }

    /**
     * @Route("/trip/{id}/abandonment", name="abandonment")
     */
    public function abandonment($id): Response
    {
        $user = $this->getUser();
        $trip = $this->entityManager->getRepository(Trip::class)->find($id);
        if ($trip == null || $user == null) {
            return $this->redirectToRoute('home', array("error" => "impossible"));
        }
        $error = $trip->removeUserWithValidation($user);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($trip);
        $entityManager->flush();

        return $this->redirectToRoute('home', array("error" => $error));
    }

    /**
     * @Route("placeList/{id}", name="placeList")
     */
    public function getJsonPlaceList(City $city, PlaceRepository $placeRepository)
    {
        return $this->json($placeRepository->findBy(["city" => $city]));
    }
}
