<?php
// src/AppBundle/Controller/ClientsController.php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Client;

class ClientsController extends Controller
{
    // private $client_data = [
    //     [  'id' => 0 , 
    //         'title' => 'mr', 
    //         'name' => 'Roy', 
    //         'last_name' => 'Adams', 
    //         'address' => '2872 Marquette Street',
    //         'zip_code' => '10312',
    //         'city' => 'New York City',
    //         'state' => 'NY',
    //         'email' => 'radams1v@example.com' 
    //     ],
    //     [  'id' => 1 , 
    //         'title' => 'mrs', 
    //         'name' => 'Bonnie', 
    //         'last_name' => 'Clark', 
    //         'address' => '4 Porter Avenue',
    //         'zip_code' => '80028',
    //         'city' => 'Louisville',
    //         'state' => 'CO',
    //         'email' => 'bclark6@example.com' 
    //     ],
    //     [  'id' => 2 , 
    //         'title' => 'ms', 
    //         'name' => 'Carol', 
    //         'last_name' => 'Shaw', 
    //         'address' => '650 Grover Alley',
    //         'zip_code' => '30305',
    //         'city' => 'Atlanta',
    //         'state' => 'GA',
    //         'email' => 'cshaw@example.com' 
    //     ]
    // ];

    private $titles = ['mr', 'ms', 'mrs', 'dr', 'mx'];

    /**
     * @Route("/guests", name="index_clients")
     */
    public function showIndex()
    {
        $data = [];
        // $data['clients'] = $this->client_data;
        $clients = $this->getDoctrine()
                        ->getRepository('AppBundle:Client')
                        ->findAll();
        $data['clients'] = $clients;
        return $this->render("clients/index.html.twig", $data);
    }

    /**
     * @Route("/guests/modify/{id_clients}", name="modify_client")
     */
    public function showDetails(Request $request, $id_clients)
    {
        $data = [];
        $data['mode'] = 'modify';
        // $data['clients'] = $this->client_data;
        $client_repo = $this->getDoctrine()
                            ->getRepository('AppBundle:Client');
        $data['form'] = [];
        $data['titles'] = $this->titles;

        // $client_data = $this->client_data[$id_clients];
        // $data['form'] = $client_data;

        $form = $this->createFormBuilder()
                    ->add('name')
                    ->add('last_name')
                    ->add('title')
                    ->add('address')
                    ->add('zip_code')
                    ->add('city')
                    ->add('state')
                    ->add('email')
                    ->getForm()
        ;
        
        $form->handleRequest( $request );

        if($form->isSubmitted())
        {
            $form_data = $form->getData();
            $data['form'] = [];
            $data['form'] = $form_data;
            $client = $client_repo->find($id_clients);

            $client->setTitle($form_data['title']);
            $client->setName($form_data['name']);
            $client->setLastName($form_data['last_name']);
            $client->setAddress($form_data['address']);
            $client->setZipCode($form_data['zip_code']);
            $client->setCity($form_data['city']);
            $client->setState($form_data['state']);
            $client->setEmail($form_data['email']);

            $em = $this->getDoctrine()
                        ->getManager();
            $em->flush();

            return $this->redirectToRoute('index_clients');
        }
        else{
            // $client_data = $this->client_data[$id_clients];
            $client = $client_repo->find($id_clients);
            $client_data['id'] = $client->getId();
            $client_data['title'] = $client->getTitle();
            $client_data['name'] = $client->getName();
            $client_data['last_name'] = $client->getLastName();
            $client_data['address'] = $client->getAddress();
            $client_data['zip_code'] = $client->getZipCode();
            $client_data['city'] = $client->getCity();
            $client_data['state'] = $client->getState();
            $client_data['email'] = $client->getEmail();

            $client_data['titles'] = $this->titles;
            $data['form'] = $client_data;
        }

        return $this->render("clients/form.html.twig", $data);
    }

    /**
     * @Route("/guests/new", name="new_client")
     */
    public function showNew(Request $request)
    {
        $data = [];
        $data['mode'] = 'new_clients';
        // $data['clients'] = $this->client_data;
        $data['titles'] = $this->titles;
        $data['form'] = [];
        $data['form']['title'] = '';

        $form = $this->createFormBuilder()
                    ->add('name')
                    ->add('last_name')
                    ->add('title')
                    ->add('address')
                    ->add('zip_code')
                    ->add('city')
                    ->add('state')
                    ->add('email')
                    ->getForm()
        ;
        
        $form->handleRequest( $request );

        if($form->isSubmitted())
        {
            $form_data = $form->getData();
            $data['form'] = [];
            $data['form'] = $form_data;

            $em = $this->getDoctrine()->getManager();
            $client = new Client();
            $client->setTitle($form_data['title']);
            $client->setName($form_data['name']);
            $client->setLastName($form_data['last_name']);
            $client->setAddress($form_data['address']);
            $client->setZipCode($form_data['zip_code']);
            $client->setCity($form_data['city']);
            $client->setState($form_data['state']);
            $client->setEmail($form_data['email']);

            $em->persist($client);
            $em->flush();

            return $this->redirectToRoute('index_clients');
        }

        return $this->render("clients/form.html.twig", $data);
    }

}