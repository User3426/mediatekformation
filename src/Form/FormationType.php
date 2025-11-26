<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Formation;
use App\Entity\Playlist;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/*
 * Formulaire pour la création et la modification d'une formation.
 *
 * Gère les champs : date de publication, titre, description, vidéo associée, playlist et catégories.
 *
 * @author Tristan
 */
class FormationType extends AbstractType
{
    /*
     * Construit le formulaire pour une formation.
     *
     * @param FormBuilderInterface $builder Le constructeur de formulaire
     * @param array $options Options supplémentaires pour le formulaire
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('publishedAt', DateType::class, [
                'widget' => 'single_text',
                'label' => 'publiée le'
            ])
            ->add('title')
            ->add('description')
            ->add('videoId')
            ->add('playlist', EntityType::class, [
                'class' => Playlist::class,
                'choice_label' => 'name',
            ])
            ->add('categories', EntityType::class, [
                'class' => Categorie::class,
                'choice_label' => 'name',
                'multiple' => true,
                'required' => false
            ])
            ->add('submit', SubmitType::class, [
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
            'data_class' => Formation::class,
        ]);
    }
}
