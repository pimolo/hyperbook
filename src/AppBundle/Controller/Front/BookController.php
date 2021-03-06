<?php

namespace AppBundle\Controller\Front;

use AppBundle\Entity\Category;
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
 * @Route("/book")
 */
class BookController extends Controller
{
    /**
     * Lists all Book entities.
     *
     * @Route("/", name="book_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $books = $em->getRepository('AppBundle:Book')->findAll();

        return $this->render('AppBundle:Front:Book/index.html.twig', array(
            'books' => $books,
        ));
    }

    /**
     * @Route("/top5", name="book_index_top5")
     * @Method("GET")
     *
     * @return Response
     */
    public function topAction()
    {
        $books = $this->getDoctrine()->getRepository('AppBundle:Book')->findBy([], ['downloads' => 'DESC'], 5);

        return $this->render('AppBundle:Front:Book/index.html.twig', ['books' => $books]);
    }

    /**
     * Finds and displays a Book entity.
     *
     * @Route("/{slug}", name="book_show")
     * @Method("GET")
     *
     * @param Book $book
     * @return Response
     */
    public function showAction(Book $book)
    {
        return $this->render('AppBundle:Front:Book/show.html.twig', array(
            'book' => $book,
        ));
    }

    /**
     * @Route("/category/{slug}", name="book_index_category")
     * @Method("GET")
     *
     * @param Category $category
     * @return Response
     */
    public function listByCategory(Category $category)
    {
        return $this->render('AppBundle:Front:Book/index.html.twig', array(
            'books' => $category->getBooks(),
        ));
    }

    /**
     * @Route("/download/{slug}", name="book_download")
     * @Method("GET")
     *
     * @param Book $book
     * @return Response
     */
    public function downloadAction(Book $book)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $book->setDownloads($book->getDownloads() + 1);
        $this->getDoctrine()->getManager()->flush();

        $downloadHandler = $this->get('vich_uploader.download_handler');

        return $downloadHandler->downloadObject($book, 'bookFile');
    }
}
