<?php

namespace App\Controller;

use App\Entity\City;
use App\Entity\Trip;
use App\Form\CreateSortieType;
use App\Form\CancelationTripType;
use App\Repository\PlaceRepository;
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
     * @Route("/create_sortie", name="create_sortie")
     */
    public function index(Request $request): Response
    {
        $sortie = new Trip();
        $form = $this->createForm(CreateSortieType::class, $sortie);
        $form->handleRequest($request);

        $user = $this->getUser();
        if ($user->getGroups() == null and $user->getRoles() == ['ROLE_USER']) {
            return $this->redirectToRoute('home', array("error" => "impossible de créer une sortie, vous n'êtes dans aucun groupe"));
        }

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
        if ($sortie->getIsAviable() == false) {
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
        $places = $placeRepository->findBy(["city" => $city]);

//        dd($this->json($places, Response::HTTP_OK, [], ['groups' => ['place', 'city']]));

        return $this->json($places, Response::HTTP_OK, [], ['groups' => ['place', 'city']]);
    }

    /**
     * @Route("/trip/{id}/cancelation", name="cancelation")
     */
    public function cancelationTrip(Request $request, $id): Response
    {
        $trip = $this->entityManager->getRepository(Trip::class)->find($id);
        $form = $this->createForm(CancelationTripType::class, $trip);
        $form->handleRequest($request);

        $dateNow = date("Y-m-d H:i:s");

        if ($form->isSubmitted() && $form->isValid() && $trip->getTripStartDate() > $dateNow) {
            $trip->setState(1);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($trip);
            $entityManager->flush();
            return $this->redirectToRoute('home');
        }
        return $this->render('sortie/cancel-index.html.twig', [
            'form' => $form->createView(),
            'trip' => $trip
        ]);
    }
}
