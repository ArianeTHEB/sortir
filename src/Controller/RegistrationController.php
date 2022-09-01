<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Form\RegistrationFormType;
use App\Repository\ParticipantRepository;
use App\Security\ParticipantAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
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

            //POUR UPLOAD UNE PHOTO
            // Récupèrer les informations du formulaire dans un objet $file
           // $file = $form->get('photo')->getData();

           // if ($file)
           // {
                // On renomme le fichier, selon une convention propre au projet
                // Par exemple nom de l'entité + son id + extension soit 'entite-1.jpg'

           //     $newFilename = $user->getNom()."-".$user->getId().".".$file->guessExtension();
           // }
           // $file->move($this->getParameter('upload_champ_entite_dir'), $newFilename);
           // $user->setChamp($newFilename);

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
}
