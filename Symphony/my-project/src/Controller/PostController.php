<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use App\Entity\Post;

class PostController extends AbstractController
{
    /**
     * @Route("/post", name="post")
     */
    public function index()
    {
        

        $list=$this->getDoctrine()
            ->getRepository(Post::class)
            ->findAll();
        if (!$list) {
            throw $this->createNotFoundException(
                'No product found'
            );
        } 
        

        return $this->render('post/index.html.twig', [
            'controller_name' => 'PostController',
             'list' => $list
        ]);
    }

    /**
     * @route("/post/create", name="postCreate")
     */

    public function store(Request $request){
        $post = new Post();
        $post-> setTitle('Article '.random_int(0,100));
        $post-> setContent('Lorem LOREMLorem LOREMLorem LOREMLorem LOREM');
        $post-> setAuthor('Randauthor');
        $post-> setCreatedAT(new \Datetime('today'));

        $form = $this->createFormBuilder($post)
        ->add('title', TextType::class)
        ->add('content', TextareaType::class)
        ->add('author', TextType::class)
        ->add('save',SubmitType::class, array('label' => 'Send Post'))
        ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $post = $form->getData();

             $entityManager = $this->getDoctrine()->getManager();
             $entityManager->persist($post);
             $entityManager->flush();

            return $this->redirectToRoute('post');
        }

        return $this->render('post/create.html.twig', array('form'=>$form->createView()));
    }
}

?>
