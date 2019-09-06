<?php
// src/Controller/HKController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;

/**
 * @Route("/hk")
 */
class HKController extends AbstractFOSRestController
{
  /**
   * @Rest\Get("/source/{sourceName}")
   * @return Response
   */
  public function setSource($sourceName)
  {
    $number = array('message' => 'coucou ' . $sourceName);
    return $this->handleView($this->view($number, Response::HTTP_OK));
  }
}
