<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/blog")
 */
class BlogController extends Controller
{

  /**
   * @Route("/", defaults={"page": "1"}, name="blog_index")
   * */
  public function indexAction($page)
  {
    return new Response("TODO: blog index");
  }

}
