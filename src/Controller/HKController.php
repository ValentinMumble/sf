<?php

declare(strict_types=1);

namespace App\Controller;

use HKAPI\API as HK;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/hk")
 */
class HKController
{
    /** @var Zone $hk */
    private $hk = null;

    /**
     * @Route("/source/{sourceName}", methods={"GET"})
     */
    public function setSource(Request $request, string $sourceName): JsonResponse
    {
        $this->initializeHK();

        $this->hk->selectSource($sourceName);

        return new JsonResponse("Setting source $sourceName...");
    }

    /**
     * @Route("/off", methods={"GET"})
     */
    public function powerOff(): JsonResponse
    {
        $this->initializeHK();

        $this->hk->off();

        return new JsonResponse('Powering off...');
    }

    /**
     * @Route("/timer", methods={"GET"})
     */
    public function timer(): JsonResponse
    {
        $this->initializeHK();

        $this->hk->sleep();

        return new JsonResponse('Setting sleep timer...');
    }

    /**
     * @Route("/dim", methods={"GET"})
     */
    public function dim(): JsonResponse
    {
        $this->initializeHK();

        $this->hk->dim();

        return new JsonResponse('Dimming HUD...');
    }

    /**
     * @Route("/volume/{direction}", methods={"GET"})
     */
    public function setVolume(Request $request, string $direction): Response
    {
        $this->initializeHK();

        if ($direction === 'up') {
            $this->hk->volumeUp();
            sleep(1);
            $this->hk->volumeUp();
        } else if ($direction === 'down') {
            $this->hk->volumeDown();
            sleep(1);
            $this->hk->volumeDown();
        } else {
            throw new BadRequestHttpException('Direction can only be up or down');
        }

        return new Response('', Response::HTTP_NO_CONTENT);
    }

    private function initializeHK(): void
    {
        if (null === $this->hk) {
            if (empty($_ENV['HK_IP'])) {
                throw new \Exception('HK: invalid IP, check .env');
            }

            try {
                $hk = new HK($_ENV['HK_IP'], 10025, new \HKAPI\Devices\AVR());
                $this->hk = $hk->zone('Main Zone');
            } catch (\Exception $exception) {
                throw new \Exception(sprintf('HK: %s', $exception->getMessage()));
            }
        }
    }
}
