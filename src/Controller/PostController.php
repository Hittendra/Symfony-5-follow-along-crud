<?php

namespace App\Controller;

use App\Entity\Post;
// use App\Entity\Category;
// use App\Form\Category;
use App\Form\PostType;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
// use Symfony\Bridge\Doctrine\Form\Type\EntityType;


/**
     * @Route("/post", name="post.")
     */
class PostController extends AbstractController
{
    /**
     * @Route("/", name="index")
     * @param PostRepository $postRepository
     * @return Response
     */
    public function index(PostRepository $postRepository)
    {
        $posts = $postRepository->findAll();
        // dump($posts);

        return $this->render('post/index.html.twig', [
            'posts' => $posts
        ]);
    }

    /**
     * @Route("/create", name="create")
     * @param Request $request
     * @return Response
     */


    public function create(Request $request) {
        //create a new post with title
        $post = new Post();

        // $post->setTitle('This is going to be a title');

        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            $em = $this->getDoctrine()->getManager();
            // dd($post);
            // dd($request->files);
            /** @var UploadedFile $file */
            $file = $request->files->get('post')['attachment'];

            if ($file) {
                $filename = md5(uniqid()). '.' . $file->guessClientExtension();

                $file->move(
                    $this->getParameter('uploads_dir'),
                    $filename
                );

                $post->setImage($filename);

                $em->persist($post);
                $em->flush();
            }
          

           
            
            return $this->redirect($this->generateUrl('post.index'));

        }



        //entity manager
        // $em = $this->getDoctrine()->getManager();


        // actually executes the queries (i.e. the INSERT query)
        // $em->persist($post);
        // $em->flush();


        //  //return a response
        // return new Response('Saved new match with id '.$post->getId());

        return $this->render('post/create.html.twig', [
            'form' => $form->createView()
        ]);
       
    }
     
    /**
     *  @Route("/show/{id}", name="show")
     * @param Post $post
     *  @return Response
     */

    
    public function show(Post $post) {
        // dd($post);
        
        // create the show 
        return $this->render('post/show.html.twig', ['post' => $post]);
    }


    /**
     *  @Route("/delete/{id}", name="delete")
     */

    public function remove(Post $post) {

          $em = $this->getDoctrine()->getManager();
          
          $em->remove($post);
          $em->flush();

          $this->addFlash('success', 'Post was removed');


          return $this->redirect($this->generateUrl('post.index'));

    }

}
