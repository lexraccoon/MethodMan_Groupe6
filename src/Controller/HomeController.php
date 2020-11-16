<?php

namespace App\Controller;

use App\Entity\HTTPRequest;
use App\Form\HTTPRequestType;
use App\Repository\HTTPRequestRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class HomeController extends AbstractController
{
    /* ajoute la requête entrée de le formulaire dans la bdd */

    private function addHTTPRequest(Request $request)
    {
        $http_request = new HTTPRequest();

        $form = $this->createForm(HTTPRequestType::class, $http_request);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($http_request);
            $entityManager->flush();
        }

        return $form->createView();
    }

    /* permet de récupérer les résultats de la requête entrée dans le formulaire puis de l'afficher dans l'index */

    private function HTTPClient(HttpClientInterface $client, Request $request)
    {
        $http_request = new HTTPRequest();

        $form = $this->createForm(HTTPRequestType::class, $http_request);
        $form->handleRequest($request);

        $response = "";

        if($form->isSubmitted() && $form->isValid()) {
            $method = $http_request->getMethod();
            $url = $http_request->getUrl();
            $token = $http_request->getToken();

            $response = $client->request($method, $url, [
                'headers' => [
                    'token' => $token,
                ],
            ]);
            return $response->toArray();
        }
        return $response;
    }

    /* fonction pour pouvoir afficher les resultats seulement si cette variable est en true que quand le form est validé et submit */

    private function getButtonValidate(Request $request)
    {
        $http_request = new HTTPRequest();

        $form = $this->createForm(HTTPRequestType::class, $http_request);
        $form->handleRequest($request);

        $buttonValidate = False;

        if($form->isSubmitted() && $form->isValid()) {
            $buttonValidate = True;

            return $buttonValidate;
        }
        return $buttonValidate;
    }

    /* fonction qui permet de recupérer toutes les requêtes dans la bdd puis boucler dessus dans l'index et afficher la méthode, l'url et le token */
    private function getHTTPRequest()
    {
        return $this->getDoctrine()
            ->getRepository(HTTPRequest::class)
            ->getAllHTTPRequest();
    }

    /* fonction qui permet de renvoyer la valeur du token pour après gérer si sa valeur est nulle ou non dans l'index.html.twig */

    private function getToken(Request $request)
    {
        $http_request = new HTTPRequest();
        $form = $this->createForm(HTTPRequestType::class, $http_request);
        $form->handleRequest($request);

        $token = null;

        if($form->isSubmitted() && $form->isValid())
        {
            $token = $http_request->getToken();
            return $token;
        }
        return $token;
    }

    /**
     * @Route("/home", name="home")
     * @param Request $request
     * @param HttpClientInterface $client
     * @return Response
     */

    /* fonction qui retourne toutes nos fonctions dans la vue index dans /templates/home */

    public function index(HttpClientInterface $client, Request $request)
    {
        return $this->render('home/index.html.twig', [
            'form' => $this->addHTTPRequest($request),
            'http_requests' => $this->getHTTPRequest(),
            'results' => $this->HTTPClient($client, $request),
            'buttonValidate' => $this->getButtonValidate($request),
            'token' => $this->getToken($request),
        ]);
    }

    /* fonction pour supprimer une requête (ne fonctionne pas car toutes les requêtes se suppriment) */

    /*public function getDeleteHTTPRequest($id): Response
    {
        $query = $this->getDoctrine()
            ->getRepository(HTTPRequest::class)
            ->deleteHTTPRequest($id);

        return new Response('');
    }*/




}
