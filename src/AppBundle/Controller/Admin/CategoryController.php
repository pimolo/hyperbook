<?php

namespace AppBundle\Controller\Admin;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Category;
use AppBundle\Form\CategoryType;
use Symfony\Component\HttpFoundation\Response;

/**
 * Category controller.
 *
 * @Route("/admin/category")
 */
class CategoryController extends Controller
{
    /**
     * Lists all Category entities.
     *
     * @Route("/", name="admin_category_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $categories = $em->getRepository('AppBundle:Category')->findAll();

        return $this->render('AppBundle:Admin:Category/index.html.twig', array(
            'categories' => $categories,
        ));
    }

    /**
     * Creates a new Category entity.
     *
     * @Route("/new", name="admin_category_new")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @return Response
     */
    public function newAction(Request $request)
    {
        $category = new Category();
        $form = $this->createForm('AppBundle\Form\CategoryType', $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute('category_show', array('slug' => $category->getSlug()));
        }

        return $this->render('AppBundle:Admin:Category/new.html.twig', array(
            'category' => $category,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Category entity.
     *
     * @Route("/{slug}", name="admin_category_show")
     * @Method("GET")

     * @param Category $category
     * @return Response
     */
    public function showAction(Category $category)
    {
        $deleteForm = $this->createDeleteForm($category);

        return $this->render('AppBundle:Admin:Category/show.html.twig', array(
            'category' => $category,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Category entity.
     *
     * @Route("/{slug}/edit", name="admin_category_edit")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @param Category $category
     * @return Response
     */
    public function editAction(Request $request, Category $category)
    {
        $deleteForm = $this->createDeleteForm($category);
        $editForm = $this->createForm('AppBundle\Form\CategoryType', $category);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute('category_edit', array('slug' => $category->getSlug()));
        }

        return $this->render('AppBundle:Admin:Category/edit.html.twig', array(
            'category' => $category,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Category entity.
     *
     * @Route("/{slug}", name="admin_category_delete")
     * @Method("DELETE")
     *
     * @param Request $request
     * @param Category $category
     * @return Response
     */
    public function deleteAction(Request $request, Category $category)
    {
        $form = $this->createDeleteForm($category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($category);
            $em->flush();
        }

        return $this->redirectToRoute('category_index');
    }

    /**
     * Creates a form to delete a Category entity.
     *
     * @param Category $category The Category entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Category $category)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('category_delete', array('slug' => $category->getSlug())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
