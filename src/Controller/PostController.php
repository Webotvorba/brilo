<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PostController extends AbstractController
{

    #[Route('/{page}', name: 'post.index', requirements: ['page' => '\d+'], defaults: ['page' => 1], methods: ['GET'])]
    public function index(PostRepository $postRepository, PaginatorInterface $paginator, int $page, Request $request): Response
    {
        $queryBuilder = $postRepository->createQueryBuilder('p')
                                       ->orderBy('p.id', 'DESC');

        $pagination = $paginator->paginate(
            $queryBuilder,
            $page,
            24
        );

        if ($page > $pagination->getPageCount()) {
            throw $this->createNotFoundException('The page does not exist.');
        }

        return $this->render('post/index.html.twig', [
            'pagination' => $pagination,
            'title' => 'Zoznam príspevkov'
        ]);
    }

    #[Route('/post/{id}', name: 'post.show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(Post $post, Request $request): Response
    {

        return $this->render('post/show.html.twig', [
            'title' => '',
            'post'  => $post,
            'author' => $post->getAuthor(),
            'comments' => $post->getComments()
        ]);
    }
}
