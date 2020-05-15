<?php

namespace App\Controller;

use App\Entity\Department;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DepartmentController extends AbstractController
{

    /**
     * @Route("/department/{id}", name="department")
     */
    public function index($id)
    {

        $department = $this->getDoctrine()
            ->getRepository(Department::class)
            ->find($id);

        $users = $this->getDoctrine()
            ->getRepository(User::class)
            ->findBy([], null, 6);

        return $this->render('department/index.html.twig', [
            'department' => $department,
            'users' => $users,
        ]);
    }
}
