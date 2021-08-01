<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OperationController extends AbstractController
{
    /**
     * @Route("/operation", name="operation")
     */
    public function index(): Response
    {
        return $this->render('operation/index.html.twig', [
            'controller_name' => 'OperationController',
        ]);
    }
    /**
     * @Route("/operation-add/{nb1}/{nb2}", name="operation-add")
     */
    public function add($nb1,$nb2): Response
    {
        return $this->render('operation/add.html.twig', [
            'controller_name' => 'OperationController',
            'nb1'=>$nb1,
            'nb2'=>$nb2,
        ]);
    }
    /**
     * @Route("/operation-divide/{nb1}/{nb2}", name="operation-divide")
     */
    public function divide($nb1,$nb2): Response
    {
        return $this->render('operation/divide.html.twig', [
            'controller_name' => 'OperationController',
            'nb1'=>$nb1,
            'nb2'=>$nb2,
        ]);
    }
     /**
     * @Route("/operation-multiply/{nb1}/{nb2}", name="operation-multiply")
     */
    public function multiply($nb1,$nb2): Response
    {
        return $this->render('operation/multiply.html.twig', [
            'controller_name' => 'OperationController',
            'nb1'=>$nb1,
            'nb2'=>$nb2,
        ]);
    }
     /**
     * @Route("/operation-substract/{nb1}/{nb2}", name="operation-substract")
     */
    public function substract($nb1,$nb2): Response
    {
        return $this->render('operation/substract.html.twig', [
            'controller_name' => 'OperationController',
            'nb1'=>$nb1,
            'nb2'=>$nb2,
        ]);
    }
    
}
