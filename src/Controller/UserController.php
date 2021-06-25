<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Form\UserForm;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use App\Form\ProfileForm;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;

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

            // ... сделайте любую другую работу - вроде отправки письма и др
            // может, установите "флеш" сообщение об успешном выполнении для пользователя

            return $this->redirectToRoute('index');
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
}
