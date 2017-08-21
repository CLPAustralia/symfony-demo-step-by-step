<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Post;
use AppBundle\Entity\Comment;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Psr\Log\LoggerInterface;
use AppBundle\Form\CommentType;

/**
 * @Route("/blog")
 */
class BlogController extends Controller
{

  /**
   * @Route("/", defaults={"page": "1"}, name="blog_index")
   * @Route("/page/{page}", requirements={"page": "[1-9]\d*"}, name="blog_index_paginated")
   * */
  public function indexAction($page)
  {
    $posts = $this->getDoctrine()->getRepository(Post::class)->findLatest($page);
    return $this->render('blog/index.html.twig', ['posts' => $posts] );
  }

  /**
   * @Route("/posts/{slug}", name="blog_post")
   * */
  public function postShowAction(Post $post)
  {
    return $this->render('blog/post_show.html.twig', ['post' => $post]);
  }

    /** 
     * @Route("/comment/{postSlug}/new", name="comment_new")
     * @Method("POST")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @ParamConverter("post", options={"mapping": {"postSlug": "slug"}})
     *
     * NOTE: The ParamConverter mapping is required because the route parameter
     * (postSlug) doesn't match any of the Doctrine entity properties (slug).
     * See http://symfony.com/doc/current/bundles/SensioFrameworkExtraBundle/annotations/converters.html#doctrine-converter
     */
    public function commentNewAction(Request $request, Post $post)
    {   
        $form = $this->createForm(CommentType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Comment $comment */
            $comment = $form->getData();
            $comment->setAuthorEmail($this->getUser()->getEmail());
            $comment->setPost($post);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('blog_post', ['slug' => $post->getSlug()]);
        }

        return $this->render('blog/comment_form_error.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }

  /**
   * 
   * */
  public function commentFormAction(Post $post)
  {
      $form = $this->createForm(CommentType::class);

      return $this->render('blog/_comment_form.html.twig', [
          'post' => $post,
          'form' => $form->createView(),
      ]); 
  }
}
