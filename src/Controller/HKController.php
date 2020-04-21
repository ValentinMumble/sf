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

  private function initializeHK(): ?string
  {
    if (null === $this->hk) {
      try {
        $hk = new HK($_ENV['HK_IP'], 10025, new \HKAPI\Devices\AVR());
        $this->hk = $hk->zone('Main Zone');
      } catch (\Exception $exception) {
        return $exception->getMessage();
      }
    }
  }

  private function formatResponse(Request $req, $statusCode, $data = null)
  {
    $json = array('uri' => $req->getPathInfo());

    if ($data !== null) $json = array_merge($json, $data);

    return $this->handleView($this->view($json, $statusCode));
  }

  /**
   * @Rest\Get("/")
   */
  public function index(Request $req)
  {
    return $this->formatResponse($req, Response::HTTP_OK, array('message' => "Hello."));
  }

  /**
   * @Rest\Get("/source/{sourceName}")
   */
  public function setSource(Request $req, string $sourceName)
  {
    $error = $this->initializeHK();
    if ($error || null === $this->hk) {
      return $this->formatResponse($req, Response::HTTP_BAD_REQUEST, array('error' => "Failed to set $sourceName: $error"));
    }

    $this->hk->selectSource($sourceName);

    return $this->formatResponse($req, Response::HTTP_OK, array('message' => "Setting source $sourceName..."));
  }

  /**
   * @Rest\Get("/off")
   */
  public function powerOff(Request $req)
  {
    $error = $this->initializeHK();
    if ($error || null === $this->hk) {
      return $this->formatResponse($req, Response::HTTP_BAD_REQUEST, array('error' => "Failed to turn off: $error"));
    }

    $this->hk->off('');

    return $this->formatResponse($req, Response::HTTP_OK, array('message' => "Powering off..."));
  }

  /**
   * @Rest\Get("/volume/{direction}")
   */
  public function setVolume(Request $req, string $direction)
  {
    $error = $this->initializeHK();
    if ($error || null === $this->hk) {
      return $this->formatResponse($req, Response::HTTP_BAD_REQUEST, array('error' => "Failed to set volume $direction: $error"));
    }

    if ($direction === 'up') {
      $this->hk->volumeUp('');
    } else if ($direction === 'down') {
      $this->hk->volumeDown('');
    }

    return $this->formatResponse($req, Response::HTTP_OK);
  }
}
