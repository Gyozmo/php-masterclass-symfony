<?php

namespace App\Controller;

use App\Entity\Posts;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class PostController extends AbstractController
{
    /**
     * @Route("/post", name="post")
     */
    public function index()
    {

        $posts = $this->getDoctrine() //envoier les données de POSTS
                     ->getRepository(Posts::class)
                     ->findAll();
        
        return $this->render('post/index.html.twig', array('posts'=>$posts));

    }

    /**
     * @route("/post/create", name="createPost")
     */

    public function store(request $request)
    {
        $post = new Posts();  // on creer un nouveau Post
        $post->setTitle('article '.random_int(0,100));
        $post->setContent("lorem lorem lorem");
        $post->setAuthor("jean mich");
        $post->setCreatedAt(new \Datetime('now')); 

        $form = $this->createFormBuilder($post) // pour creer les inputs
        ->add('title', TextType::class)
        ->add('content', TextareaType::class)
        ->add('author', TextType::class)
        ->add('save', SubmitType::class)
        ->getForm(); //recuperer le formulaire generer

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){ //si formulaire submit et valide on réassigne post et on recupere les data
            $post = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager ->persist($post);
            $entityManager -> flush();

            return $this->redirectToRoute('post'); // on return et redirige la route
        }

        return $this->render('post/create.html.twig', array('form' => $form->createView()));

    }
    /**
     * @route ("/post/{id}", name="showPost")
     */
    public function show($id)//afficher un post
    {
        $post= $this->getDoctrine()
                    ->getRepository(posts::class)
                    ->find($id);

        return $this->render("post/show.html.twig", compact('post'));//compact renvoi renvoi un tableau                    
    }


    /**
     * @route ("/post/edit, name="editPost")
     */
    public function edit(request $request, $id)
    {
        
    }

}

?>