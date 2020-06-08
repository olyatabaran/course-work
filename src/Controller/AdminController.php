<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Department;
use App\Entity\News;
use App\Entity\TextBlock;
use App\Entity\User;
use App\Form\Type\CommentType;
use App\Form\Type\DepartmentType;
use App\Form\Type\NewsType;
use App\Form\Type\TextBlockType;
use App\Form\Type\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */


    public function entry()
    {

        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'User tried to access a page without having ROLE_ADMIN');
        return $this->render('admin_entry_point/index.html.twig');
    }

    /**
     * @Route("/admin/comment", name="admin_comment")
     */
    public function index()
    {

        $comments = $this->getDoctrine()
            ->getRepository(Comment::class)
            ->findAll();

        return $this->render('admin_comment/index.html.twig', [
            'comments' => $comments,
        ]);
    }

    /**
     * @Route("/admin/comment/delete/{id}", name="admin_comment_delete")
     */
    public function deleteComment($id)
    {
        $comments = $this->getDoctrine()
            ->getRepository(Comment::class)
            ->find($id);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($comments);
        $entityManager->flush();
        return $this->redirectToRoute('admin_comment');
    }

    /**
     * @Route("/admin/comment/update/{id}", name="admin_comment_update")
     */
    public function updateComment($id, Request $request)
    {

        $comment = $this->getDoctrine()
            ->getRepository(Comment::class)
            ->find($id);


        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $comment = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();
            return $this->redirectToRoute('admin_comment');
        }

        return $this->render('admin_comment/update.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/department", name="admin_department")
     */
    public function allDepartmets()
    {

        $departments = $this->getDoctrine()
            ->getRepository(Department::class)
            ->findAll();

        return $this->render('admin_department/index.html.twig', [
            'departments' => $departments,
        ]);
    }


    /**
     * @Route("/admin/department/delete/{id}", name="admin_department_delete")
     */
    public function deleteDepartment($id)
    {
        $department = $this->getDoctrine()
            ->getRepository(Department::class)
            ->find($id);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($department);
        $entityManager->flush();
        return $this->redirectToRoute('admin_department');

    }

    /**
     * @Route("/admin/department/update/{id}", name="admin_department_update")
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function updateDepartment($id, Request $request, SluggerInterface $slugger)
    {

        $department = $this->getDoctrine()
            ->getRepository(Department::class)
            ->find($id);

        $form = $this->createForm(DepartmentType::class, $department);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $brochureFile = $form->get('image')->getData();

            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $brochureFile->guessExtension();
                try {
                    $brochureFile->move(
                        $this->getParameter('department_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                $department->setImage($newFilename);

                $department = $form->getData();
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($department);
                $entityManager->flush();

                return $this->redirectToRoute('admin_department');
            }

        }
        return $this->render('admin_department/update.html.twig', [
            'form' => $form->createView(),
        ]);

    }

    /**
     * @Route("/admin/department/new", name="admin_department_new")
     *
     */
    public function newDepartment(Request $request, SluggerInterface $slugger)
    {
        $department = new Department();

        $form = $this->createForm(DepartmentType::class, $department);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $brochureFile = $form->get('image')->getData();

            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $brochureFile->guessExtension();
                try {
                    $brochureFile->move(
                        $this->getParameter('department_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                $department->setImage($newFilename);

                $department = $form->getData();
                $department->setCreatedAt(new \DateTime());
                $department->setUpdatedAt(new \DateTime());
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($department);
                $entityManager->flush();

                return $this->redirectToRoute('admin_department');
            }

        }

        return $this->render('admin_department/new.html.twig', [
            'form' => $form->createView(),
        ]);

    }

    /**
     * @Route("/admin/news", name="admin_news")
     */
    public function allNews()
    {

        $news = $this->getDoctrine()
            ->getRepository(News::class)
            ->findAll();

        return $this->render('admin_news/index.html.twig', [
            'news' => $news,
        ]);
    }

    /**
     * @Route("/admin/news/delete/{id}", name="admin_news_delete")
     */
    public function deleteNews($id)
    {
        $novelty = $this->getDoctrine()
            ->getRepository(News::class)
            ->find($id);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($novelty);
        $entityManager->flush();
        return $this->redirectToRoute('admin_news');

    }


    /**
     * @Route("/admin/news/update/{id}", name="admin_news_update")
     */
    public function updateNews($id, Request $request, SluggerInterface $slugger)
    {
        $novelty = $this->getDoctrine()
            ->getRepository(News::class)
            ->find($id);

        $form = $this->createForm(NewsType::class, $novelty);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $brochureFile = $form->get('image')->getData();

            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $brochureFile->guessExtension();
                try {
                    $brochureFile->move(
                        $this->getParameter('department_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                $novelty->setImage($newFilename);

                $novelty = $form->getData();
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($novelty);
                $entityManager->flush();
                return $this->redirectToRoute('admin_news');
            }

        }
        return $this->render('admin_news/update.html.twig', [
            'form' => $form->createView(),
        ]);

    }

    /**
     * @Route("/admin/news/new", name="admin_news_new")
     */
    public function newNews(Request $request, SluggerInterface $slugger)
    {
        $novelty = new News();
        $form = $this->createForm(NewsType::class, $novelty);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $brochureFile = $form->get('image')->getData();

            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $brochureFile->guessExtension();
                try {
                    $brochureFile->move(
                        $this->getParameter('department_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                $novelty->setImage($newFilename);
                $novelty = $form->getData();
                $novelty->setCreatedAt(new \DateTime());
                $novelty->setUpdatedAt(new \DateTime());
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($novelty);
                $entityManager->flush();
                return $this->redirectToRoute('admin_news');
            }

        }
        return $this->render('admin_news/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/admin/users", name="admin_users")
     */
    public function allUsers()
    {

        $users = $this->getDoctrine()
            ->getRepository(User::class)
            ->findAll();

        return $this->render('admin_user/index.html.twig', [
            'users' => $users,
        ]);
    }


    /**
     * @Route("/admin/users/delete/{id}", name="admin_users_delete")
     */
    public function deleteUsers($id)
    {
        $novelty = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($id);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($novelty);
        $entityManager->flush();
        return $this->redirectToRoute('admin_users');

    }

    /**
     * @Route("/admin/users/update/{id}", name="admin_users_update")
     */
    public function updateUsers($id, Request $request)
    {
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($id);

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirectToRoute('admin_users');
        }

        return $this->render('admin_user/update.html.twig', [
            'form' => $form->createView(),
        ]);

    }

    /**
     * @Route("/admin/text-block", name="admin_text-block")
     */
    public function allText()
    {

        $text_blocks = $this->getDoctrine()
            ->getRepository(TextBlock::class)
            ->findAll();

        return $this->render('admin_text_block/index.html.twig', [
            'text_blocks' => $text_blocks,
        ]);
    }


    /**
     * @Route("/admin/text-block/delete/{id}", name="admin_text-block_delete")
     */
    public function deleteTextBlock($id)
    {
        $text_block = $this->getDoctrine()
            ->getRepository(TextBlock::class)
            ->find($id);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($text_block);
        $entityManager->flush();
        return $this->redirectToRoute('admin_text-block');

    }

    /**
     * @Route("/admin/text-block/update/{id}", name="admin_text-block_update")
     */
    public
    function updateTextBlock($id, Request $request)
    {
        $text_block = $this->getDoctrine()
            ->getRepository(TextBlock::class)
            ->find($id);


        $form = $this->createForm(TextBlockType::class, $text_block);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $text_block = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($text_block);
            $entityManager->flush();
            return $this->redirectToRoute('admin_text-block');
        }

        return $this->render('admin_text_block/update.html.twig', [
            'form' => $form->createView(),
        ]);

    }

    /**
     * @Route("/admin/text-block/new", name="admin_text-block_new")
     */
    public
    function newTextBlock(Request $request)
    {
        $text_block = new TextBlock();

        $form = $this->createForm(TextBlockType::class, $text_block);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $text_block = $form->getData();
            $text_block->setCreatedAt(new \DateTime());
            $text_block->setUpdatedAt(new \DateTime());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($text_block);
            $entityManager->flush();
            return $this->redirectToRoute('admin_text-block');
        }

        return $this->render('admin_text_block/new.html.twig', [
            'form' => $form->createView(),
        ]);

    }


}
