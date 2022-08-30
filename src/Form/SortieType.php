<?php

namespace App\Form;

use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\Ville;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieType extends AbstractType

{

    public function buildForm(FormBuilderInterface $builder, array $options, Ville $ville= null): void
    {

        $builder
            ->add('nom')
            ->add('dateHeureDebut')
            ->add('dateLimiteInscription')
            ->add('nbInscriptionsMax')
            ->add('duree')
            ->add('infosSortie')
            ->add('villes', EntityType::class, [
                'mapped' => false,
                'class' => Ville::class,
                'choice_label' => 'nom',
                'placeholder' => 'ville',
                'label' => 'villes',
                'required' => false
            ])
            ->add('lieu', ChoiceType::class,[
                'placeholder' => '(choisir une ville)',
                'required' => false
            ])
    //        ->add('Valider', SubmitType::class)
 /*           ->add('etat')
           ->add('campus')
            ->add('organisateur')
            ->add('lieu')
            ->add('participants')*/
        ;

        $formModifier = function(FormInterface $form, Ville $ville = null){
            //ternaire si le champ ville est vide alors je renvoi une liste de lieux vide, sinon je renvoi les lieux
            //déja enregistrés
            $lieux = null === $ville ? [] : $ville->getLieus();
            dump($lieux);


            $form -> add('lieu',EntityType::class,[
                'class' => Lieu::class,
                'choices' => $lieux,
                'choice_label' => 'nom',
                'placeholder' => 'Lieu (choisir une ville)',
                'label' => 'Lieu',
                'required' => false
            ])
                ->add('dateLimiteInscription')
                ->add('dateHeureDebut')
                ->add('duree');
        };
        $builder->get('villes')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($formModifier){

                $villes= $event->getForm()->getData();
                dump($villes);
                $formModifier($event->getForm()->getParent(), $villes);

            }
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
