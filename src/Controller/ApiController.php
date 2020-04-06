<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Airport;
use App\Entity\Flight;

/**
 * @Route("/api")
 */
class ApiController extends AbstractController
{
  /**
   * @Route(path="/flight", name="api_flight_list", methods={"GET"})
   */
  public function index(Request $request)
  {
      //$region = "Odden";//Debug only //what does Request object do?
      $region = $request->headers->get('X-SecondLife-Region');

      $departure = $this->getDoctrine()->getRepository(Airport::class)->findOneBy(['region' => $region]);

      if ($departure !== null) {
          $flights = $this->getDoctrine()->getRepository(Flight::class)->findBy(['from' => $departure]);//Find all FROM this region
          if (count($flights)) {
              $dest = [];
              foreach ($flights as $flight) {
                  $dest = array_merge($dest, [$this->getDoctrine()->getRepository(Airport::class)->findOneBy(["id" => $flight->getTo()])]);
              }
              return new Response(json_encode(array("destinations" => implode(',', $dest))));

          } else {
              return new Response(json_encode(array("error" => "no flights available from this airport")));
          }

      } else{
          return new Response(json_encode(array("error" => "departure airport is unknown | unable to get destinations")));
      }
  }

  /**
   * @Route(path="/flight/{to}", name="api_flight_kfm", methods={"GET"})
   */
  public function getFlight(Request $request, string $to)
  {
      //$region = "odden";//Debug only //what does Request object do?
      $region = $request->headers->get('X-SecondLife-Region');

      $from = $this->getDoctrine()->getRepository(Airport::class)->findOneBy(['region' => $region]);
      $response = gettype($from);
      if ($from !== null) {
          $flights = $this->getDoctrine()->getRepository(Flight::class)->findBy(['from' => $from]);//Find all FROM this region
          $to = $this->getDoctrine()->getRepository(Airport::class)->findOneBy(['name' => $to]);

          if ($to !== false) {
              $flight = $this->getDoctrine()->getRepository(Flight::class)->findOneBy([
                'from' => $from,
                'to' => $to
              ]);
              return new Response($flight->getJson());

          } else {
              return new Response(json_encode(array("error" => "destination airport not found")));
          }
      } else {
          return new Response(json_encode(array("error" => "departure airport is unknown | unable to get route")));
      }
  }
}
