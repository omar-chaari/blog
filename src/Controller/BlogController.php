<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Form\ArticleEditType;
use App\Form\ArticleType;
use App\Form\CommentType;
use App\Form\EditImageType;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;



use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Loader\Configurator\twig;

use Symfony\Component\String\Slugger\SluggerInterface;

class BlogController extends AbstractController
{
    /**
     * @Route("/blog", name="blog-list")
     */
    public function index(PaginatorInterface $paginator,Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $article = $entityManager->getRepository(Article::class)->findAll();

        $pagination = $paginator->paginate(
            $article, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            5 /*limit per page*/
        );
    
        /*return $this->render("doctrine/index.html.twig",[
            'article'=>$article,
        ]);*/

        return $this->render('blog/index.html.twig', [
            'article'=>$pagination,        ]);
    }
    
    

    /**
     * @Route("/", name="list-front")
     */
    public function list(): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $article = $entityManager->getRepository(Article::class)->findListFront();

        

        return $this->render('blog/list.html.twig', [
            'article'=>$article,        ]);
    }
    
    /**
     * @Route("/blog/add", name="blog-add")
     */
    public function add(Article $article = null,Request $request,EntityManagerInterface $manager,SluggerInterface $slugger)
    {
        $fileUploader = new FileUploader($this->getParameter('image_directory'), $slugger);

        if(!$article)
        {
        $article = new Article();
        }
       $form = $this->createForm(ArticleType::class, $article);

       //dump($request->request->parameters["article"]);
       
        $form->handleRequest($request);
       
        if($form->isSubmitted() && $form->isValid() )
        {
            if(!$article->getId()){

               $article->setDatePub(new \DateTime());
               $article->setDateMaj(new \DateTime());
            }

    
            $img = $form->get('image')->getData(); // recupération du fichier
       
           
            if($img)
            
               { $imgFile = $fileUploader->upload($img);
            $article->setImage($imgFile);}

            $article = $form->getData();
            $manager->persist($article);
            $manager->flush();

            $this->addFlash(
                'success',
                'Votre article a été enregistré avec succès'
            );
            
        
            if(@$_POST["enregistrer"]!==null){
                return $this->redirect($this->generateUrl("blog-list"));    
            }
            else {
                return $this->redirect($this->generateUrl("blog-add"));
          
            } 


        }
        else {
           
        }
        return $this->render('blog/add.html.twig',[
            'form'=>$form->createView(),
            'editMode' => $article->getId() !== null
        ]);

    }

 /**
     * @Route("/blog/edit/{id}",name="blog-edit")
     */
    public function edit(Article $article = null,Request $request,EntityManagerInterface $manager,SluggerInterface $slugger)
    {
        $fileUploader = new FileUploader($this->getParameter('image_directory'), $slugger);

        /*if(!$article)
        {
        $article = new Article();
        }*/
       $form = $this->createForm(ArticleEditType::class, $article);
        $formImage = $this->createForm(EditImageType::class, $article);
      $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid() )
        {
       if(!$article->getId()){
               $article->setDatePub(new \DateTime());
               $article->setDateMaj(new \DateTime());
            }
            $article = $form->getData();
            $manager->persist($article);
            $manager->flush();
            $this->addFlash(
                'success',
                'Votre article a été enregistré avec succès'
            );
            
            return $this->redirect($this->generateUrl("blog-edit", array("id"=>$article->getId()))); 
        }
        

        $formImage->handleRequest($request);

        if($formImage->isSubmitted() && $formImage->isValid() )
        {
            $img = $formImage->get('image')->getData(); // recupération du fichier
           if($img)
                $imgFile = $fileUploader->upload($img);
            $article->setImage($imgFile);
         $article = $formImage->getData();
            $manager->flush();

            $this->addFlash(
                'success',
                'Votre article a été enregistré avec succès'
            );
          //  return $this->redirect($this->generateUrl("blog-edit", array("id"=>$article->getId()))); 
        }
        return $this->render('blog/edit.html.twig',[
            'form'=>$form->createView(),
            'formImage'=>$formImage->createView(),
            'editMode' => $article->getId() !== null,
            'article' => $article
        ]);

    }

    /**
     * @Route("/blog/show/{id}", name="blog-show")
     */
    public function show(Article $article=null ,Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
      //  $article = $entityManager->getRepository(Article::class)->findOneBy(['id'=>$id]);

        $comment = new Comment();


        $form = $this->createForm(CommentType::class, $comment);

       
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid() )
        {
            $comment = $form->getData();
            $comment->setArticle($article);
            $comment->setDatePub(new \DateTime());
            $entityManager->persist($comment);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Votre commentaire a été enregistré avec succès'
            );
            
          //return $this->redirect($this->generateUrl("blog-edit", array("id"=>$id))); 
        
          return $this->redirectToRoute("blog-show", ["id"=>$article->getId()]); 
        

        }
 
      //$list_comments= $entityManager->getRepository(Comment::class)->findAll();
      
        return $this->render('blog/show.html.twig', [
            'article'=>$article,
            'form'=>$form->createView(),  
             ]);
    }
    /**
     * @Route("/blog/delete/{id}", name="blog-delete")
     */
    public function delete($id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $article = $entityManager->getRepository(Article::class)->find($id);
       unlink('uploads/images/'.$article->getImage());

        foreach ($article->getComments() as $comment)
        {
        $entityManager->remove($comment);
        $entityManager->flush();
  
        }
        $entityManager->remove($article);
        $entityManager->flush();
        $this->addFlash(
            'success',
            'Votre article a été supprimé avec succès'
        );
        return $this->redirect($this->generateUrl("blog-list"));

      //  return new Response("Article removed");
    }

    /**
     * @Route("/test2", name="test2")
     */
    public function test2(): Response
    {
        //dump($this->getUser());
        //ajout du rôle dans l'entité User
      

      $entityManager = $this->getDoctrine()->getManager();
      
       $user  =$entityManager->getRepository(User::class)->find($this->getUser()->getId());
        
        $user->setRoles(['ROLE_ADMIN']);
       
            $entityManager->flush();
        
      
      return $this->render('test/index.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }
    
}
