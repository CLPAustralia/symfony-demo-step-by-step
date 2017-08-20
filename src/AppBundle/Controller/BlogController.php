<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Post;
use AppBundle\Entity\Comment;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Psr\Log\LoggerInterface;

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
}
