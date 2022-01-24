<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\SubCategoryRepository;
use App\Repository\ThreadRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;


class HomeController extends AbstractController
{


    public function __construct(
        private CategoryRepository $categoryRepository,
    )
    {
    }

    #[Route('/', name: 'home_index')]
    public function index(SluggerInterface $slugger): Response
    {
        $categories = $this->categoryRepository->findAllWithRelations();

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'categories' => $categories
        ]);
    }
}
