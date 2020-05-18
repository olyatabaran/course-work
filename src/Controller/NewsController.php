<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Like;
use App\Entity\News;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class NewsController extends AbstractController
{
    /**
     * @Route("/news/pages/{page}", name="newsPages")
     */
    public function index($page)
    {

        $per_page = 5;

        $news = $this->getDoctrine()
            ->getRepository(News::class)
            ->printNews($page,$per_page);

        $likes = $this->getDoctrine()
            ->getRepository(Like::class)
            ->countLike();

        $comments = $this->getDoctrine()
            ->getRepository(Comment::class)
            ->countComments();

        $latestComments = $this->getDoctrine()
            ->getRepository(Comment::class)
            ->latestComments();


        $popularNews = $this->getDoctrine()
            ->getRepository(News::class)
            ->popularNews();


        $total_news = $this->getDoctrine()
            ->getRepository(News::class)
            ->countNews();


        $pages_count = ceil($total_news / $per_page);


        return $this->render('news/index.html.twig', [
            'total_news' => $total_news,
            'likes' => $likes,
            'comments' => $comments,
            'user' => $this->getUser(),
            'popularNews' => $popularNews,
            'latestComments' => $latestComments,
            'pages' => $pages_count,
            'news' => $news,

        ]);
    }


    /**
     * @Route("/news/{id}/like", name="likes")
     */
    public function newLike($id, Request $request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $entityManager = $this->getDoctrine()->getManager();


        $user = $this->getUser();


        $novelty = $this->getDoctrine()
            ->getRepository(News::class)
            ->find($id);


        $like = $this->getDoctrine()
            ->getRepository(Like::class)
            ->findOneBy([
                'user' => $user,
                'news' => $novelty
            ]);

        if (!$like) {

            $like = new Like();

            $like->setUser($user);
            $like->setNews($novelty);
            $entityManager->persist($like);
            $entityManager->flush();

        }

        return $this->redirect($request->headers->get('referer'));

    }

    /**
     * @Route("/news/{id}", name="newsDetails")
     */
    public function details($id)
    {

        $news = $this->getDoctrine()
            ->getRepository(News::class)
            ->find($id);

        $comments = $news->getComments();

        $popularNews = $this->getDoctrine()
            ->getRepository(News::class)
            ->popularNews();

        $latestComments = $this->getDoctrine()
            ->getRepository(Comment::class)
            ->latestComments();


        return $this->render('news/details.html.twig', [
            'newsComments' => $comments,
            'news' => $news,
            'user' => $this->getUser(),
            'popularNews' => $popularNews,
            'latestComments' => $latestComments,

        ]);
    }


    /**
     * @Route("/news/{id}/comment", name="comment")
     */
    public function newComment($id)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $entityManager = $this->getDoctrine()->getManager();
        /** @var $user User */
        $user = $this->getUser();

        $novelty = $this->getDoctrine()
            ->getRepository(News::class)
            ->find($id);

        $comment = new Comment();

        $comment->setUser($user);
        $comment->setNovelty($novelty);
        $comment->setContent($_POST['content']);
        $comment->setUpdatedAt(new \DateTime());
        $comment->setCreatedAt(new \DateTime());

        $entityManager->persist($comment);
        $entityManager->flush();
        return $this->redirectToRoute('newsDetails', ['id' => $id]);
    }


}
