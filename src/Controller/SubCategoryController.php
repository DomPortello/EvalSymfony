<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\SubCategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;


class SubCategoryController extends AbstractController
{


    public function __construct(
        private SubCategoryRepository $subCategoryRepository)
    {
    }

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    #[Route('/{category}/{subCategory}', name: 'subCategory_index')]
    public function index(string $category, string $subCategory): Response
    {
        $subCategory = $this->subCategoryRepository->findAllWithRelationsForOne($subCategory);

        return $this->render('sub_category/index.html.twig', [
            'controller_name' => 'SubCategoryController',
            'subCategories' => $subCategory
        ]);
    }
}
