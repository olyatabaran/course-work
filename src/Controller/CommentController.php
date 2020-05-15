<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{

    public function index()
    {

        return $this->render('comment/index.html.twig', [
            'controller_name' => 'CommentController',
        ]);
    }
}