<?php

namespace AppBundle\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/admin/post")
 * */
class BlogController
{

  /**
   * @Route("/", name="admin_index")
   * */
  public function indexAction()
  {
    return new Response("TODO: admin index");
  }

}
