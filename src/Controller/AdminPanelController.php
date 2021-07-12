<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class AdminPanelController extends AbstractController
{
    /**
    * @Route("/admin", name="admin_panel")
    */

    public function show(UserRepository $userRepository): Response
    {
            $users = $userRepository
            ->findAll();

        return $this->render('admin.html.twig',[
            'users' => $users
        ]);
    }

    /**
    * @Route("/admin/{page}/delete", name="user_delete", requirements={"page"="\d+"})
    */
    public function deleteUser(int $page, Request $request) {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->find($page);
        $em->remove($user);
        $em->flush();
        $this->addFlash('success', 'User is delete!');

        return $this->redirectToRoute('admin_panel');
    }

    /**
    * @Route("/admin/{page}/setadmin", name="user_role", requirements={"page"="\d+"})
    */
    public function setAdminUser(int $page, Request $request) {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->find($page);
        if(in_array('ROLE_ADMIN',$user->getRoles())){
            $user->setRoles(['ROLE_USER']);
        } else {
            $user->setRoles(['ROLE_ADMIN']);
        }
        $em->persist($user);
        $em->flush();
        $this->addFlash('success', 'Role user is changed!');

        return $this->redirectToRoute('admin_panel');
    }

    /**
    * @Route("/admin/{page}/blocked", name="user_block", requirements={"page"="\d+"})
    */
    public function blockedUser(int $page, Request $request) {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->find($page);
        if($user->getBlocked()){
            $user->setBlocked(false);
        } else {
            $user->setBlocked(true);
        }
        $em->persist($user);
        $em->flush();
        $this->addFlash('success', 'Role user is changed!');

        return $this->redirectToRoute('admin_panel');
    }
}