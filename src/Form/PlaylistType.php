<?php

namespace App\Form;

use App\Entity\Playlist;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/*
 * Formulaire pour la création et la modification d'une playlist.
 *
 * Gère les champs : nom et description.
 *
 * @author Tristan
 */
class PlaylistType extends AbstractType
{
    /*
     * Construit le formulaire pour une playlist.
     *
     * @param FormBuilderInterface $builder Le constructeur de formulaire
     * @param array $options Options supplémentaires pour le formulaire
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, [
                'label' => 'Nom'
            ])
            ->add('description')
            ->add('Submit', SubmitType::class, [
                'label' => 'Enregistrer'
            ])
        ;
    }

    /*
     * Configure les options par défaut du formulaire.
     *
     * @param OptionsResolver $resolver Résolveur d'options pour configurer les valeurs par défaut
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Playlist::class,
        ]);
    }
}
