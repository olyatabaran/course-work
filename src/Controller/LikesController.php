<?php

namespace App\Controller;

use App\Entity\Like;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class LikesController extends AbstractController
{

    public function index()
    {

        return $this->render('news/index.html.twig', [

        ]);
    }
}
