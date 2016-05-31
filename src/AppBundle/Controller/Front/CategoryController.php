<?php

namespace AppBundle\Controller\Front;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Category;
use Symfony\Component\HttpFoundation\Response;

/**
 * Category controller.
 *
 * @Route("/category")
 */
class CategoryController extends Controller
{
    /**
     * Lists all Category entities.
     *
     * @Route("/", name="category_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $categories = $em->getRepository('AppBundle:Category')->findAll();

        return $this->render('AppBundle:Front:Category/index.html.twig', array(
            'categories' => $categories,
        ));
    }

    /**
     * Finds and displays a Category entity.
     *
     * @Route("/{slug}", name="category_show")
     * @Method("GET")
     *
     * @param Category $category
     * @return Response
     */
    public function showAction(Category $category)
    {
        return $this->render('AppBundle:Front:Category/show.html.twig', array(
            'category' => $category,
        ));
    }
}
