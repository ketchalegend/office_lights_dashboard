<?php

namespace App\Controller;

use App\Service\DateManager;
use App\Service\Fetcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function index(Request $request, Fetcher $fetcher, DateManager $dateManager)
    {
        $result = $fetcher->getLocation($request->getClientIp());
        $date = $dateManager->getDateFromTimezone($result['geoplugin_timezone']);

        dump($date);

        return $this->render('dashboard/dashboard.html.twig', [
            'date' => $date,
            'result' => $result
        ]);
    }
}
