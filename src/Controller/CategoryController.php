<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\String\Slugger\SluggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CategoryController extends AbstractController
{

    // protected $categoryRepository;
    // public function __construct(CategoryRepository $categoryRepository)
    // {
    //     $this->categoryRepository = $categoryRepository;
    // }

    // public function renderMenuList()
    // {
    //     // 1 Aller chercher les categories
    //     $categories = $this->categoryRepository->findAll();
    //     // 2 Renvoyer le rendu HTML
    //     return $this->render('category/_menu.html.twig', [
    //         'categories' => $categories
    //     ]);
    // }
    /**
     * @Route("/admin/category/create", name="category_create")
     */
    public function create(Request $request, EntityManagerInterface $em, SluggerInterface $slugger)
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $category->setSlug(strtolower($slugger->slug($category->getName())));
            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute('homepage', []);
        }
        $formView = $form->createView();

        return $this->render(
            'category/create.html.twig',
            [
                'formView' => $formView
            ]
        );
    }
    /**
     * @Route("/admin/category/{id}/edit", name="category_edit")
     */
    public function edit($id, Request $request, CategoryRepository $categoryRepository, EntityManagerInterface $em, SluggerInterface $slugger, Security $security): Response
    {

        // $this->denyAccessUnlessGranted("ROLE_ADMIN", null, "Vous n'avez pas le droit d'acceder à cette page");



        $category = $categoryRepository->find($id);
        if (!$category) {
            throw new NotFoundHttpException("Cette categorie n'existe pas");
        }

        // $security->isGranted('CAN_EDIT', $category);
        // $this->denyAccessUnlessGranted("CAN_EDIT", $category, "Vous n'êtes pas le proprio de cette category");


        // $user = $this->getUser();
        // if (!$user) {
        //     return $this->redirectToRoute("security_login");
        // }
        // if ($user !== $category->getOwner()) {
        //     throw new AccessDeniedHttpException(("Vous n'êtes pas le proprietaire de cette catégorie"));
        // }
        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $category->setSlug(strtolower($slugger->slug($category->getName())));
            $em->flush();

            return $this->redirectToRoute('homepage', []);
        }
        $formView = $form->createView();

        return $this->render(
            'category/edit.html.twig',
            [
                'formView' => $formView,
                'category' => $category
            ]
        );
    }
}
