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
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

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
        $entityManager = $this->getDoctrine()->getManager();
        $posts = $entityManager->getRepository(Post::class)->findBy([], ['publishedAt' => 'DESC']);
        return $this->render('admin/blog/index.html.twig', ['posts' => $posts]);
    }

    /**
     * Creates a new Post entity.
     *
     * @Route("/new", name="admin_post_new")
     * @Method({"GET", "POST"})
     *
     * NOTE: the Method annotation is optional, but it's a recommended practice
     * to constraint the HTTP methods each controller responds to (by default
     * it responds to all methods).
     */
    public function newAction(Request $request)
    {   
        $post = new Post();
        $post->setAuthorEmail($this->getUser()->getEmail());

        // See http://symfony.com/doc/current/book/forms.html#submitting-forms-with-multiple-buttons
        $form = $this->createForm(PostType::class, $post)
            ->add('saveAndCreateNew', SubmitType::class);

        $form->handleRequest($request);

        // the isSubmitted() method is completely optional because the other
        // isValid() method already checks whether the form is submitted.
        // However, we explicitly add it to improve code readability.
        // See http://symfony.com/doc/current/best_practices/forms.html#handling-form-submits
        if ($form->isSubmitted() && $form->isValid()) {
            $post->setSlug($this->get('slugger')->slugify($post->getTitle()));

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($post);
            $entityManager->flush();

            // Flash messages are used to notify the user about the result of the
            // actions. They are deleted automatically from the session as soon
            // as they are accessed.
            // See http://symfony.com/doc/current/book/controller.html#flash-messages
            $this->addFlash('success', 'post.created_successfully');

            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('admin_post_new');
            }   

            return $this->redirectToRoute('admin_post_index');
        }   

        return $this->render('admin/blog/new.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]); 
    }

    /**
     * Finds and displays a Post entity.
     *
     * @Route("/{id}", requirements={"id": "\d+"}, name="admin_post_show")
     * @Method("GET")
     */
    public function showAction(Post $post)
    {
        // This security check can also be performed:
        //   1. Using an annotation: @Security("post.isAuthor(user)")
        //   2. Using a "voter" (see http://symfony.com/doc/current/cookbook/security/voters_data_permission.html)
        if (null === $this->getUser() || !$post->isAuthor($this->getUser())) {
            throw $this->createAccessDeniedException('Posts can only be shown to their authors.');
        }   

        return $this->render('admin/blog/show.html.twig', [
            'post' => $post,
        ]); 
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
