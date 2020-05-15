<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Like;
use App\Entity\News;
use App\Entity\TextBlock;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {

        $likes = $this->getDoctrine()
            ->getRepository(Like::class);

        $comments = $this->getDoctrine()
            ->getRepository(Comment::class)
            ->countComments();


        $news = $this->getDoctrine()
            ->getRepository(News::class)
            ->findBy([], null, 3);

        $users = $this->getDoctrine()
            ->getRepository(User::class)
            ->findBy([], null, 12);


        return $this->render('home/index.html.twig', [
            'news' => $news,
            'users' => $users,
            'likes' => $likes,
            'comments' => $comments,
        ]);
    }


    /**
     * @Route("/about", name="about")
     */
    public function about()
    {

        $array = [];
        $aboutUniversity = $this->getDoctrine()
            ->getRepository(TextBlock::class)
            ->findBy(['key_name' => ['aboutRight', 'aboutLeft']]);

        foreach ($aboutUniversity as $about){
            $array[$about->getKeyName()] = $about;
        }

        $newsArticle = $this->getDoctrine()
            ->getRepository(News::class)
            ->countNews();

        $users = $this->getDoctrine()
            ->getRepository(User::class)
            ->countUsers();


        $teachers = $this->getDoctrine()
            ->getRepository(User::class)
            ->findBy(['keyName' => ['teacher']]);


        return $this->render('about/index.html.twig', [
            'aboutUniversity' => $array,
            'newsArticle' =>  $newsArticle,
            'users' => $users,
            'teachers' => $teachers,
        ]);
    }


    /**
     * @Route("/connect", name="connect")
     */
    public function connect()
    {
        $array = [];

        $text_blocks = $this->getDoctrine()
            ->getRepository(TextBlock::class)
            ->findBy(['key_name' => ['address', 'email', 'phone']]);

        foreach ($text_blocks as $text_block){
            $array[$text_block->getKeyName()] = $text_block;
        }

        return $this->render('connect/index.html.twig', [
            'controller_name' => 'HomeController',
            'text_blocks' => $array,
        ]);
    }

}
