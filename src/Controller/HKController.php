<?php
// src/Controller/HKController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use HKAPI\API as HK;

/**
 * @Route("/hk")
 */
class HKController extends AbstractFOSRestController
{
  private $hk = null;
  private function getHK()
  {
    if ($this->hk == null) {
      $this->hk = new HK($_ENV['HK_IP'], 10025, new \HKAPI\Devices\AVR());
    }
    return $this->hk->zone('Main Zone');
  }

  private function formatResponse(Request $req, $statusCode, $data = null)
  {
    $json = array('uri' => $req->getPathInfo());
    if ($data != null) $json = array_merge($json, $data);
    return $this->handleView($this->view($json, $statusCode));
  }

  /**
   * @Rest\Get("/source/{sourceName}")
   */
  public function setSource(Request $req, string $sourceName)
  {
    $this->getHK()->selectSource($sourceName);
    return $this->formatResponse($req, Response::HTTP_OK, array('message' => "Setting source $sourceName..."));
  }

  /**
   * @Rest\Get("/off")
   */
  public function powerOff(Request $req)
  {
    $this->getHK()->off('');
    return $this->formatResponse($req, Response::HTTP_OK, array('message' => "Powering off..."));
  }

  /**
   * @Rest\Get("/volume/{direction}")
   */
  public function setVolume(Request $req, string $direction)
  {
    if ($direction == 'up') {
      $this->getHK()->volumeUp('');
    } else if ($direction == 'down') {
      $this->getHK()->volumeDown('');
    }
    return $this->formatResponse($req, Response::HTTP_OK);
  }
}
