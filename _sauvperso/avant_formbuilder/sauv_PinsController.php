<?php

namespace App\Controller;

use App\Entity\Pin;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PinsController extends AbstractController
{

    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em=$em;
    }

    public function index(): Response
    {
      $repo = $this->em->getRepository('App\Entity\Pin');
      $pins = $repo->findAll();

        return $this->render('pins/index.html.twig', ['pins' => $pins]);
    }

    public function create(Request $request): Response
    {
        if ($request->isMethod('POST')){
            $data = $request->request->all();
            
            if ($this->isCsrfTokenValid('pins_create',$data['_token'])) {

            $pin=new Pin;
            $pin->setTitle($data['title']);
            $pin->setDescription($data['description']);

            $this->em->persist($pin);
            $this->em->flush();
          };

      //       return $this->redirect($this->generateUrl('app_home'));
            
             return $this->redirectToRoute('app_home');
        
        }
        return $this->render('pins/create.html.twig');
    }

}