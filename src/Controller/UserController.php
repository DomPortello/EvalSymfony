<?php

namespace App\Controller;

use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{


    public function __construct(
        private PaginatorInterface $paginator,
        private PostRepository $postRepository
    )
    {
    }

    #[Route('/user', name: 'user_detail')]
    public function index(Request $request): Response
    {
        $id = $this->getUser()->getId();
        $qbPosts = $this->postRepository->getAllPostsFromAnUser($id);

        $pagination = $this->paginator->paginate($qbPosts, $request->query->getInt('page', 1),2);

        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
            'posts' => $pagination
        ]);
    }
}
