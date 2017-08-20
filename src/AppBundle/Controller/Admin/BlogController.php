<?php

namespace AppBundle\Controller\Admin;

use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Post;

/**
 * @Route("/admin/post")
 * */
class BlogController
{

  /**
   * @Route("/", name="admin_index")
   * @Route("/", name="admin_post_index")
   * */
  public function indexAction(LoggerInterface $logger)
  {
    $logger->info("### Admin Blog Index");
    return new Response("TODO: admin index / admin post index");
  }

  /**
   * @Route("/{id}/edit", requirements={"id": "\d+"}, name="admin_post_edit")
   * */
  public function editAction(LoggerInterface $logger, Post $post, Request $request)
  {
    $logger->info("### Admin Blog Edit");
    return new Response("TODO: admin post edit");
  }
}
