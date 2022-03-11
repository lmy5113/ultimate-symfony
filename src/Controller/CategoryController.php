<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CategoryController extends AbstractController
{
    protected $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository) {
        $this->categoryRepository = $categoryRepository;
    }

    public function renderMenuList() {
        
        return $this->render('category/_menu.html.twig', [
            'categories' => $this->categoryRepository->findAll()
        ]);
    }


    /**
     * @Route("/admin/category/create", name="category_create")
     */
    public function create(Request $request, SluggerInterface $slugger, EntityManagerInterface $em) {
        $form = $this->createForm(CategoryType::class); 
        $form->handleRequest($request); 
        if ($form->isSubmitted() && $form->isValid()) {
            $category = $form->getData();
            $category->setSlug($slugger->slug($category->getName()));
 
            $em->persist($category);
            $em->flush();
            return $this->redirectToRoute('product_category', ['slug' => $category->getSlug()]);
        }
        
        return $this->render('category/create.html.twig', ['formView' => $form->createView()]);
     }

    /**
     * @Route("/admin/category/{id}/edit", name="category_edit")
     * 
     */
    public function edit(int $id, CategoryRepository $categoryRepository, Request $request, EntityManagerInterface $em) {
       
        // $user = $this->getUser();
        //  if (!$user) {
        //      return $this->redirectToRoute('security_login');
        // }
        // if (!$this->isGranted("ROLE_ADMIN")) {
        //     throw new AccessDeniedException("Vous n'avez pas le droit d'accès.");
        // }
        
        // $this->denyAccessUnlessGranted("ROLE_ADMIN", null, "Vous n'avez pas le droit d'accéder à cette ressource.");

        $category = $categoryRepository->find($id);

        if (!$category) {
            throw new NotFoundHttpException("Cette catégorie n'existe pas.");
        }

        // $user = $this->getUser();

        // if (!$user) {
        //     return $this->redirectToRoute("security_login");
        // }

        // if ($user !== $category->getOwner()) {
        //     throw new AccessDeniedException("Vous n'êtes pas le propriétaire de cette catégorie.");
        // }

        // $security->isGranted('CAN_EDIT', $category);    

        // $this->denyAccessUnlessGranted('CAN_EDIT', $category);    

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('product_category', ['slug' => $category->getSlug()]);    
        }

        return $this->render('category/edit.html.twig', [
            'category' => $category,
            'formView' => $form->createView()
        ]);
    }

}