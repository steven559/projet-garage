<?php

namespace App\Controller;

use App\Entity\User;
use App\Utils\Token;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Swift_SmtpTransport;


class UserController extends AbstractController
{
    /**
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return Response
     * @Route("/register", name="register")
     */
    public function inscrit(Request $request, UserPasswordEncoderInterface $passwordEncoder, ObjectManager $manager): Response
    {
        $user = new User();
        $form = $this->createFormBuilder($user)
            ->add('email', TextType::class)
            ->add('password', PasswordType::class)
            ->add('nom', TextType::class)
            ->add('prenom', TextType::class)
            ->add('numero', TextType::class)
            ->getForm();

        if ($this->isGranted('ROLE_ADMIN')) {
            $form = $this->createFormBuilder($user)
                ->add('email', TextType::class)
                ->add('password', PasswordType::class)
                ->add('nom', TextType::class)
                ->add('prenom', TextType::class)
                ->add('numero', TextType::class)
                ->add('role', ChoiceType::class, [
                    'label' => 'Attribution de rôle: ',
                    'choices' => [
                        'Administrateur' => 'ROLE_ADMIN']
                ])
                ->getForm();
        }

        //->createForm(UserType::class, $user);
        $form->handleRequest($request);
        //on encode le password si il est ok
        if ($form->isSubmitted() && $form->isValid()) {
            $tokenGenerator = new Token();
            $token = $tokenGenerator->generateToken();
            $user->setIncriptionToken($token);
            //prepare le set active
            if ($this->isGranted('ROLE_ADMIN')) {
                $user->setRole('ROLE_ADMIN');


            } else {
                $user->setRole('ROLE_USER');
            }
            $user->setPassword(


                $form->get('password')->getData()

            );

            $user->setRole('ROLE_USER');
            $user->setActive(0);


            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            $mail = $user->getEmail();
            $token = $user->getIncriptionToken();
            $transport = (new \Swift_SmtpTransport('smtp.gmail.com', 465, 'ssl'))
                ->setUsername('garagepilet@gmail.com')
                ->setPassword('b@bmarley789');

            $mailer = new \Swift_Mailer($transport);

            $message = (new \Swift_Message('Garage Pilet'))
                ->setSubject('Confirmation de votre inscription ')
                ->setFrom('garagepilet@gmail.com')
                ->setTo($mail)
                ->setBody(
                    $this->renderView(
                        'content/registration_token.html.twig', [
                        'RegisterToken' => $token

                    ]),
                    'text/html'
                );
            $mailer->send($message);

          return $this->redirectToRoute('login');
        }
        return $this->render('user/inscrit.html.twig', [
            'registrationForm' => $form->createView(),
        ]);

    }

    /**
     * @param Request $request
     * @param ObjectManager $manager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Route("/registration",name="inscrit",methods={"GET"})
     */
    public function checkRegister(Request $request, ObjectManager $manager)
    {

        $token = $request->get('incriptionToken');
        $user = $this->getDoctrine()->getRepository(User::class);
        $resultat = $user->findOneBy(['incriptionToken' => $token]);
        dump($resultat);
        if ($resultat) {

                $resultat->setActive(1);


                $manager->flush();

                return $this->redirectToRoute('content_index');

        } else {

            return $this->render('connect/index.html.twig');

        }

    }

}
