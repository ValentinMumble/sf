<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/f")
 */
class FileController
{
    const ZONES = [
        'main' => '250',
        'home' => 'srdl',
        'lh765yu' => 'sr'
    ];

    const MIMES = [
        'mp4' => 'video/mp4',
        'mkv' => 'video/x-matrovska',
        'png' => 'image/png',
        'jpg' => 'image/jpg'
    ];

    const VALIDITY_S = 60 * 30;

    /**
     * @Route("/{zone}/{file64}", methods={"GET"})
     */
    public function serveFile(Request $request, string $zone, string $file64): Response
    {
        $requestTime = $request->query->get('t');

        if ('' == $requestTime || $requestTime + self::VALIDITY_S < time()) {
            return new RedirectResponse($_ENV['REDIRECT_URL']);
        }

        $path = sprintf('/%s/%s', self::ZONES[$zone], base64_decode($file64));

        $response = new Response();
        $response->headers->set('Content-Type', self::MIMES[pathinfo($path)['extension']] ?? 'video/mp4');
        $response->headers->set('X-Accel-Redirect', $path);

        return $response;
    }
}
