<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Airport;
use App\Form\AirportType;

/**
 * @Route("/airport")
 */
class AirportController extends AbstractController
{
    /**
     * @Route("/", name="airport_list", methods={"GET"})
     */
    public function index()
    {
        //Find all airports
        $airports = $this->getDoctrine()->getRepository(Airport::class)->findAll();

        return $this->render('airport/index.html.twig', array('airports' => $airports));
    }

    /**
     * @Route("/new", name="airport_new", methods={"GET", "POST"})
     */
    public function new(Request $request)
    {
        //Find all airports
        $airport = new Airport();
        $form = $this->createForm(AirportType::class, $airport);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
          $airport = $form->getData();

          $entityManager = $this->getDoctrine()->getManager();
          $entityManager->persist($airport);
          $entityManager->flush();

          return $this->redirectToRoute("airport_list");

        }
        return $this->render('airport/new.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route(path="/edit/{id}", name="airport_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, $id)
    {
      //Fetch article by ID
      $airport = $this->getDoctrine()->getRepository(Airport::class)->find($id);
      $form = $this->createForm(AirportType::class, $airport);

      $form->handleRequest($request);
      if ($form->isSubmitted() && $form->isValid()) {
        $article = $form->getData();

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($airport);
        $entityManager->flush();

        return $this->redirectToRoute("airport_list");

      }
      return $this->render('airport/new.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/delete/{id}", name="airport_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Airport $airport): Response
    {
        if ($this->isCsrfTokenValid('delete'.$airport->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($airport);
            $entityManager->flush();
        }
        return $this->redirectToRoute('airport_list');
    }
}
