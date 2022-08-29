<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Form\RegistrationFormType;
use App\Repository\ParticipantRepository;
use App\Security\ParticipantAuthenticator;
use ContainerI7xxGxm\getParticipantRepositoryService;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormBuilderInterface;
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
            $user = $form->getData();

            $this->addFlash('success', 'votre compte a bien été créé');
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
    public function profilAffichage(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, ParticipantAuthenticator $authenticator, EntityManagerInterface $entityManager): Response
    {
        $user = new Participant();
        $user->setNom(Participant::class);

        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            $this->addFlash('success', 'votre compte a bien été créé');
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










//LES COMMENTAIRES CI-DESSOUS SONT DIFFERENTS ESSAIS POUR L AFFICHAGE DU PROFIL : TOUS CES ESSAIS N OONT PAS ABOUTI
    // {
    // usually you'll want to make sure the user is authenticated first,
    // see "Authorization" below
    //$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

    // returns your User object, or null if the user is not authenticated
    // use inline documentation to tell your editor your exact User class
    //  /** @var \App\Entity\Participant $user */
    //$user = $this->getUser();

    // Call whatever methods you've added to your User class
    // For example, if you added a getFirstName() method, you can use that.
    //return $this->renderForm('main/profil.html.twig');
    // }


   // /**
     //* @Route("/profil", name="main_profil")
     //*/
   // public function profil(Request $request, EntityManagerInterface $entityManager)
    //{
      //  $user = new Participant();
        //$form = $this->createForm(Participant::class, $user);
        //$form->handleRequest($request);

        //if ($form->isSubmitted() && $form->isValid()) {
          //  $user = $form->getData();

            //$entityManager->persist($user);
            //$entityManager->flush();
        //}
        //return $this->render('main/profil.html.twig', [
          //  'registrationForm' => $form->createView(),
        //]);

    //}

//public function profil(Request $request, ParticipantRepository $participantRepository, EntityManagerInterface $entityManager)
//{
//    $this->getUser();
//return $this->render('main/profil.html.twig');
}
