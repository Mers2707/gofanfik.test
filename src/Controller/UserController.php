<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Form\UserForm;
use App\Form\ProfileForm;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class UserController extends AbstractController
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }
    /**
     * @Route("/registration", name="registration_user")
     */
    public function registerAction(Request $request)
    {
        // 1) постройте форму
        $user = new User();
        $form = $this->createForm(UserForm::class, $user);

        // 2) обработайте отправку (произойдёт только в POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($this->passwordHasher->hashPassword($user,$user->getPassword()));
            $user->setCreated();
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Registration complete! Please sign in.');
            return $this->redirectToRoute('app_login');
        }

        return $this->render(
            'user/signup.html.twig',
            array('form' => $form->createView())
        );
    }

    /**
    * @Route("/users", name="users_show")
    */
    public function show(UserRepository $userRepository): Response
    {
        $users = $userRepository
            ->findAll();

        if (!$users) {
            throw $this->createNotFoundException(
                'No article found'
            );
        }
        return $this->render('user/showUsers.html.twig',[
            'users' => $users
        ]);

        // or render a template
        // in the template, print things with {{ product.name }}
        // return $this->render('product/show.html.twig', ['product' => $product]);
    }

    /**
    * @Route("/profile", name="profile_user")
    */
    public function profile(Request $request, UserRepository $userRepository)
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $form = $this->createForm(ProfileForm::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $users = $userRepository
            ->upgradePassword($user,$this->passwordHasher->hashPassword($user,$user->getPassword()));

            $this->addFlash('success', 'Password is changed!');
            return $this->redirectToRoute('profile_user');
        }

        return $this->render(
            'user/profile.html.twig',
            array('form' => $form->createView())
        );
    }
}
