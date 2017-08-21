<?php

namespace AppBundle\Controller\Admin;

use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Post;
use AppBundle\Form\PostType;

/**
 * @Route("/admin/post")
 * */
class BlogController extends Controller
{

    /**
     * @Route("/", name="admin_index")
     * @Route("/", name="admin_post_index")
     */
    public function indexAction(LoggerInterface $logger)
    {
        $logger->info("### Admin Blog Index");
        return new Response("TODO: admin index / admin post index");
    }

    /**
    * @Route("/{id}/edit", requirements={"id": "\d+"}, name="admin_post_edit")
    */
    public function editAction(LoggerInterface $logger, Post $post, Request $request)
    {
        $logger->info("### Admin Blog Edit");
        if (null === $this->getUser() || !$post->isAuthor($this->getUser())) {
            throw $this->createAccessDeniedException('Posts can only be edited by their authors.');
        }

        $entityManager = $this->getDoctrine()->getManager();

        $editForm = $this->createForm(PostType::class, $post);

        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $post->setSlug($this->get('slugger')->slugify($post->getTitle()));
            $entityManager->flush();

            $this->addFlash('success', 'post.updated_successfully');

            return $this->redirectToRoute('admin_post_edit', ['id' => $post->getId()]);
        }   

        return $this->render('admin/blog/edit.html.twig', [
            'post' => $post,
            'edit_form' => $editForm->createView(),
        ]); 
    }


}
