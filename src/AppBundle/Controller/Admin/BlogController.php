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
   * @Route("/", name="admin_post_index")
   * */
  public function indexAction()
  {
    return new Response("TODO: admin index / admin post index");
  }

  /**
   * @Route("/{id}/edit", requirements={"id": "\d+"}, name="admin_post_edit")
   * */
  public function editAction(Post $post, Request $request)
  {
    return new Response("TODO: admin post edit");
  }
}
