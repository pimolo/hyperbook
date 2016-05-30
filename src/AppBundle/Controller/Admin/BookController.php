<?php

namespace AppBundle\Controller\Admin;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Book;
use AppBundle\Form\BookType;
use Symfony\Component\HttpFoundation\Response;

/**
 * Book controller.
 *
 * @Route("/admin/book")
 */
class BookController extends Controller
{
    /**
     * Lists all Book entities.
     *
     * @Route("/", name="admin_book_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $books = $em->getRepository('AppBundle:Book')->findAll();

        return $this->render('AppBundle:Admin:Book/index.html.twig', array(
            'books' => $books,
        ));
    }

    /**
     * Creates a new Book entity.
     *
     * @Route("/new", name="admin_book_new")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @return Response
     */
    public function newAction(Request $request)
    {
        $book = new Book();
        $form = $this->createForm('AppBundle\Form\BookType', $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($book);
            $em->flush();

            return $this->redirectToRoute('admin_book_show', array('slug' => $book->getSlug()));
        }

        return $this->render('AppBundle:Admin:Book/new.html.twig', array(
            'book' => $book,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Book entity.
     *
     * @Route("/{slug}", name="admin_book_show")
     * @Method("GET")
     *
     * @param Book $book
     * @return Response
     */
    public function showAction(Book $book)
    {
        $deleteForm = $this->createDeleteForm($book);

        return $this->render('AppBundle:Admin:Book/show.html.twig', array(
            'book' => $book,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Book entity.
     *
     * @Route("/{slug}/edit", name="admin_book_edit")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @param Book $book
     * @return Response
     */
    public function editAction(Request $request, Book $book)
    {
        $deleteForm = $this->createDeleteForm($book);
        $editForm = $this->createForm('AppBundle\Form\BookType', $book);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($book);
            $em->flush();

            return $this->redirectToRoute('admin_book_edit', array('slug' => $book->getSlug()));
        }

        return $this->render('AppBundle:Admin:Book/edit.html.twig', array(
            'book' => $book,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Book entity.
     *
     * @Route("/{slug}", name="admin_book_delete")
     * @Method("DELETE")
     *
     * @param Request $request
     * @param Book $book
     * @return Response
     */
    public function deleteAction(Request $request, Book $book)
    {
        $form = $this->createDeleteForm($book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($book);
            $em->flush();
        }

        return $this->redirectToRoute('admin_book_index');
    }

    /**
     * Creates a form to delete a Book entity.
     *
     * @param Book $book The Book entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Book $book)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_book_delete', array('slug' => $book->getSlug())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
