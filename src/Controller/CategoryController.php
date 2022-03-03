<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryController extends AbstractController
{
    
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
     */
    public function edit(int $id, CategoryRepository $categoryRepository, Request $request, EntityManagerInterface $em) {
        $category = $categoryRepository->find($id);

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