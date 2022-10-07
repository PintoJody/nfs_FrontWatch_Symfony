<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class HomeController extends AbstractController
{
    private $client;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->client = $httpClient;
    }

    #[Route('/', name: 'app_home', methods:['GET'])]
    public function home(): Response
    {
        $response = $this->client->request(
            'GET',
            $_ENV['WATCH_API_URL'].'/show'
        );
        $datas = $response->toArray();

        return $this->render('home/index.html.twig', [
            'datas' => $datas,
        ]);
    }

    #[Route('/watch/show/{id}', name: 'app_watch_show', methods:['GET'])]
    public function show(Request $request): Response
    {
        $response = $this->client->request(
            'GET',
            $_ENV['WATCH_API_URL'].'/show/'.$request->get('id')
        );
        $datas = $response->toArray();

        dump($datas);

        return $this->render('watch/show.html.twig', [
            'datas' => $datas,
        ]);
    }
}
