<?php

namespace App\Controller;

use App\Entity\Pin;
use App\Repository\PinRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

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

    public function show(Pin $pin): Response
    {
        
        return $this->render('pins/show.html.twig', compact('pin'));

    }

    public function create(Request $request): Response
    {
       $pin = new Pin;
        
       $form = $this->createFormBuilder($pin)
       ->add('title', null, ['attr'=> ['autofocus' => true]])
       ->add('description', null)
       ->getForm()
       ;
    
       $form->handleRequest($request);

       if ($form->isSubmitted() && $form->isValid()) {
        
        $this->em->persist($pin);
        $this->em->flush();

        return $this->redirectToRoute('app_pins_show',['id' => $pin->getId()]);

       }

       return $this->render('pins/create.html.twig', [
        'monFormulaire' => $form->createView()
       ]);
    }

}   