<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    /**
     * @Route("/test", name="test")
     */
    public function index(): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
      
        $user  =$entityManager->getRepository(User::class)->find($this->getUser()->getId());
         
        $user->setRoles(['ROLE_ADMIN']);
        
        $entityManager->flush();
         
       
       return $this->render('test/index.html.twig', [
             'controller_name' => 'TestController',
         ]);
    }
}
