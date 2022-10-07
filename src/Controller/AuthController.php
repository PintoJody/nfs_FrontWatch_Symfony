<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AuthController extends AbstractController
{
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    #[Route('/auth/registration', name: 'app_auth_registration')]
    public function register(Request $request, HttpClientInterface $httpClient): Response
    {
        $email = $request->query->get('email');
        $password = $request->query->get('password');

        if(!empty($email) && !empty($password)){
            $httpClient->request(
                'POST',
                $_ENV['AUTH_API_URL'].'/registration',
                [
                    'body' => [
                        'email' => $email,
                        'password' => $password,
                    ],
                ]
            );
            return $this->redirectToRoute('app_home');
        }

        return $this->render('auth/registration.html.twig', [
            'controller_name' => 'AuthController',
        ]);
    }

    #[Route('/auth/login', name: 'app_auth_login')]
    public function login(Request $request, HttpClientInterface $httpClient): Response
    {
        $email = $request->get('email');
        $password = $request->get('password');

        if(!empty($email) && !empty($password)){

            $result = $httpClient->request(
                'POST',
                $_ENV['AUTH_API_URL'].'/login',
                [
                    'headers' => ['content-type:application/json'],
                    'body' => json_encode([
                        'email' => $email,
                        'password' => $password
                    ]),
                ]
            );

            if($result->getStatusCode() === 200){
                //Autenticate user with token 
                //

                return $this->redirectToRoute('app_home');
            }else{
                return $this->render('auth/login.html.twig', [
                    'error' => 'Aucun compte associé'
                ]);
            }

        } 

        return $this->render('auth/login.html.twig');
    }
}
