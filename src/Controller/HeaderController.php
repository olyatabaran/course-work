<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class HeaderController extends AbstractController
{

    public function index()
    {
        return $this->render('header/index.html.twig');
    }
}
