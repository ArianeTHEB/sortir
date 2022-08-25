<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Form\RegistrationFormType;
use App\Repository\ParticipantRepository;
use App\Security\ParticipantAuthenticator;
use ContainerI7xxGxm\getParticipantRepositoryService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;


class RegistrationController extends AbstractController
{
    /**
     * @Route("/registration/register", name="login")
     */
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, ParticipantAuthenticator $authenticator, EntityManagerInterface $entityManager): Response
    {
        $user = new Participant();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user=$form->getData();

            $this->addFlash('success','votre compte a bien été créé');
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('mot_de_passe')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();


            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/profil", name="main_profil")
     */
    public function profil(ParticipantRepository $participantRepository, EntityManagerInterface $entityManager): Response
    {
        //$this->getUser(ParticipantRepository::class);
        //$participant=$participantRepository;

       // $profil = $participantRepository->findAll();
        $monprofil= new Participant();
        //$monprofil ->setEmail(",ke,nfke");

        //dd($profil);
        return $this->render('main/profil.html.twig');
    }


}
