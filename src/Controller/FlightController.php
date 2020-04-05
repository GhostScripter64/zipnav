<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Airport;
use App\Entity\Flight;
use App\Form\FlightType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

/**
 * @Route("/flight")
 */
class FlightController extends AbstractController {

    /**
     * @Route(path="/", name="flight_list", methods={"GET"})
     */
    public function index()
    {
        $flights = $this->getDoctrine()->getRepository(Flight::class)->findAll();

        return $this->render('flight/index.html.twig', array('flights' => $flights));
    }

    /**
     * @Route(path="/new", name="flight_new", methods={"GET", "POST"})
     */
    public function new(Request $request)
    {
      //Create New Flight
      $flight = new Flight($this->getDoctrine()->getManager());
      $form = $this->createForm(FlightType::class, $flight);

      $form->handleRequest($request);
      if ($form->isSubmitted() && $form->isValid()) {
        $flight = $form->getData();

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($flight);
        $entityManager->flush();

        return $this->redirectToRoute("flight_list");

      }
      return $this->render('flight/new.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route(path="/edit/{id}", name="flight_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, $id)
    {
      //Fetch flight by ID
      $flight = $this->getDoctrine()->getRepository(Flight::class)->find($id);
      $form = $this->createForm(FlightType::class, $flight);

      $form->handleRequest($request);
      if ($form->isSubmitted() && $form->isValid()) {
        $flight = $form->getData();

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($flight);
        $entityManager->flush();

        return $this->redirectToRoute("flight_list");

      }
      return $this->render('flight/edit.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route(path="/delete/{id}", name="flight_delete", methods={"DELETE"})
     */
    public function delete(Request $request, $id)
    {
      $flight = $this->getDoctrine()->getRepository(Flight::class)->find($id);

      $entityManager = $this->getDoctrine()->getManager();
      $entityManager->remove($flight);
      $entityManager->flush();

      return $this->redirectToRoute('flight_list');
    }

    /**
     * @Route(path="/{id}", name="flight_show", methods={"GET"})
     */
    public function show($id)
    {
      $flight = $this->getDoctrine()->getRepository(Flight::class)->find($id);
      return $this->render('flight/show.html.twig', array('flight' => $flight));
    }
}
