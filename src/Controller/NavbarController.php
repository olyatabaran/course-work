<?php

namespace App\Controller;

use App\Entity\Department;
use App\Entity\News;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class NavbarController extends AbstractController
{

    public function index()
    {
        $departments = $this->getDoctrine()
            ->getRepository(Department::class)
            ->findAll();

        return $this->render('navbar/index.html.twig', [
            'departments' => $departments,
        ]);
    }
}
