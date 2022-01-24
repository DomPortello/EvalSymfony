<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\SubCategory;
use App\Form\PostType;
use App\Repository\PostRepository;
use App\Repository\SubCategoryRepository;
use App\Repository\ThreadRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;


class PostController extends AbstractController
{

    public function __construct(
        private PaginatorInterface     $paginator,
        private EntityManagerInterface $em,
        private PostRepository         $postRepository,
        private ThreadRepository       $threadRepository
    )
    {
    }

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    #[Route('/{category}/{subCategory}/{thread}', name: 'post_index')]
    public function index(Request $request, int $thread): Response
    {
        $thisThread = $this->threadRepository->findCurrentThreadWithRelations($thread);
        $qbPosts = $this->postRepository->getAllPostsFromAThread($thread);

        $pagination = $this->paginator->paginate($qbPosts, $request->query->getInt('page', 1),1);

        return $this->render('post/index.html.twig', [
            'controller_name' => 'PostController',
            'postPagination' => $pagination,
            'thread' => $thisThread
        ]);

    }

    #[Route('/{category}/{subCategory}/{thread}/create', name: 'post_create')]
    public function createPost(Request $request, string $thread): Response
    {
        $post = new Post();
        $newThread = $this->threadRepository->findOneBy(['id' => $thread]);
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $post->setThread($newThread);
            $post->setUser($this->getUser());
            $post->setCreatedAt(new \DateTime());
            $this->em->persist($post);
            $this->em->flush();
            return $this->redirectToRoute('post_index',
                [
                    'category' => $post->getThread()->getSubCategory()->getCategory()->getName(),
                    'subCategory' => $post->getThread()->getSubCategory()->getName(),
                    'thread' => $post->getThread()->getId(),
                ]
            );
        }
        return $this->render('post/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{category}/{subCategory}/{thread}/edit/{post}', name: 'post_edit')]
    public function editPost(Request $request, string $post): Response
    {
        $newPost = $this->postRepository->find($post);
        $form = $this->createForm(PostType::class, $newPost);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $newPost->setCreatedAt(new \DateTime());
            $this->em->persist($newPost);
            $this->em->flush();
            return $this->redirectToRoute('post_index',
                [
                    'category' => $newPost->getThread()->getSubCategory()->getCategory()->getName(),
                    'subCategory' => $newPost->getThread()->getSubCategory()->getName(),
                    'thread' => $newPost->getThread()->getId(),
                ]
            );
        }
        return $this->render('post/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{category}/{subCategory}/{thread}/delete/{post}', name: 'post_delete')]
    public function deletePost(string $post): Response
    {
        $newPost = $this->postRepository->find($post);

        $this->em->remove($newPost);
        $this->em->flush();
        return $this->redirectToRoute('post_index',
            [
                'category' => $newPost->getThread()->getSubCategory()->getCategory()->getName(),
                'subCategory' => $newPost->getThread()->getSubCategory()->getName(),
                'thread' => $newPost->getThread()->getId(),
            ]
        );
    }

    #[Route('/{category}/{subCategory}/{thread}/up/{post}', name: 'post_upVote')]
    public function upVotePost(Request $request, string $post): Response
    {
        $newPost = $this->postRepository->find($post);
        $newPost->setUpVote($newPost->getUpVote() + 1);
        $this->em->persist($newPost);
        $this->em->flush();
        return $this->redirectToRoute('post_index',
            [
                'category' => $newPost->getThread()->getSubCategory()->getCategory()->getName(),
                'subCategory' => $newPost->getThread()->getSubCategory()->getName(),
                'thread' => $newPost->getThread()->getId(),
            ]
        );
    }

    #[Route('/{category}/{subCategory}/{thread}/down/{post}', name: 'post_downVote')]
    public function downVotePost(Request $request, string $post): Response
    {
        $newPost = $this->postRepository->find($post);
        $newPost->setDownVote($newPost->getDownVote() + 1);
        $this->em->persist($newPost);
        $this->em->flush();
        return $this->redirectToRoute('post_index',
            [
                'category' => $newPost->getThread()->getSubCategory()->getCategory()->getName(),
                'subCategory' => $newPost->getThread()->getSubCategory()->getName(),
                'thread' => $newPost->getThread()->getId(),
            ]
        );
    }
}
