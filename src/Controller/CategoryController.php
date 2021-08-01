<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @Route("/category", name="category")
     */
    public function index(Request $request,EntityManagerInterface $manager): Response
    {
        $category = new Category();


        $form = $this->createForm(CategoryType::class, $category);

       
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid() )
        {
            $category = $form->getData();
            $manager->persist($category);
            $manager->flush();

            $this->addFlash(
                'success',
                'Votre article a été enregistré avec succès'
            );
            
          //  return $this->redirect($this->generateUrl("blog-edit", array("id"=>$id))); 
        

        }
        $entityManager = $this->getDoctrine()->getManager();
        $category = $entityManager->getRepository(Category::class)->findAll();

        
        return $this->render('category/index.html.twig', [
            'category'=>$category,  
            'form'=>$form->createView(),
        ]);
    }

    /**
     * @Route("/category/delete/{id}", name="category-delete")
     */
    public function delete($id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $category = $entityManager->getRepository(Category::class)->find($id);
        $entityManager->remove($category);
        $entityManager->flush();
        return $this->redirect($this->generateUrl("category"));

      //  return new Response("Article removed");
    }

    /**
     * @Route("/category/add", name="category-add")
     */
    public function add(Request $request,EntityManagerInterface $manager)
    {
  
       $form = $this->createForm(CategoryType::class, $category);

       
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid() )
        {
            $category = $form->getData();
            $manager->persist($category);
            $manager->flush();

            $this->addFlash(
                'success',
                'Votre article a été enregistré avec succès'
            );
            
            return $this->redirect($this->generateUrl("blog-edit", array("id"=>$id))); 
        

        }
        else {
     
        }
        return $this->render('blog/add.html.twig',[
            'form'=>$form->createView(),
        ]);

    }
     /**
     * @Route("/category/edit/{id}",name="category-edit")
     */
    public function edit(Category $category = null,Request $request,EntityManagerInterface $manager)
    {
       
       $form = $this->createForm(CategoryType::class, $category);
      $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid() )
        {
  
            $category = $form->getData();
            $manager->persist($category);
            $manager->flush();
            $this->addFlash(
                'success',
                'Votre category a été enregistré avec succès'
            );
            
            return $this->redirect($this->generateUrl("category" )); 
        }
        

      
        return $this->render('category/edit.html.twig',[
            'form'=>$form->createView(),
            'category' => $category
        ]);

    }

}
