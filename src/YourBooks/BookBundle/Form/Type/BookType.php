<?php

namespace YourBooks\BookBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotNull;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null, array('label' => 'Titre de l\'oeuvre : '))
            ->add('summary', null, array('label' => 'écrivez votre résumé (2000 caractères max) : '))
            ->add('category', null, array('label' => 'Genre : ', 'required' => 'true'))
            ->add('terms','checkbox', array('mapped' => false,
                'constraints' => array(new NotNull()), 'label' => 'Conditions d\'utilisations : ', 'required' => 'true'))
            ->add('file', null, array('label' => 'Téléchargez votre manuscrit : '))
            ->add('author_rights','checkbox', array('mapped' => false,
                'constraints' => array(new NotNull()), 'label' => 'Je certifie l\'exactitude des informations ci-dessous ', 'required' => 'true'))
            ->add('rights','checkbox', array('mapped' => false,
                'constraints' => array(new NotNull()), 'label' => 'Ce manuscrit est libre de droit et ne fait l’objet d’aucun contrat d’édition', 'required' => 'true'))
            ->add('original','checkbox', array('mapped' => false,
                'constraints' => array(new NotNull()), 'label' => 'Ce manuscrit est une oeuvre originale de la pensée', 'required' => 'true'))
            ->add('laws','checkbox', array('mapped' => false,
                'constraints' => array(new NotNull()), 'label' => 'Ce manuscrit ne porte pas atteinte aux lois en vigueurs concernant la diffamation, le racisme,
            l’antisémitisme', 'required' => 'true'))
            ->add('french','checkbox', array('mapped' => false,
                'constraints' => array(new NotNull()), 'label' => 'Ce manuscrit est entier, rédigé en français.', 'required' => 'true'))
            ->add('one_file','checkbox', array('mapped' => false,
                'constraints' => array(new NotNull()), 'label' => 'Le fichier envoyé ne contient qu’un seul manuscrit.', 'required' => 'true'))
            ->add('only','checkbox', array('mapped' => false,
                'constraints' => array(new NotNull()), 'label' => 'Le fichier envoyé ne contient aucune autre mention que le nom, prénom, éventuellement
            pseudo, et le titre du manuscrit. Il ne contient aucune coordonnées, adresse,
            mail, lien, site, téléphone.', 'required' => 'true'))
            ->add('depot','checkbox', array('mapped' => false,
                'constraints' => array(new NotNull()), 'label' => 'Le fichier envoyé a fait l’objet d’un dépôt garantissant sa protection. L’auteur dispose d’une
            référence de dépôt valide qu’il s’engage à transmettre à Yourbooks en cas de
            demande.', 'required' => 'true'))
            ->add('liar','checkbox', array('mapped' => false,
                'constraints' => array(new NotNull()), 'label' => 'En cas de manquement ou de mensonge mon manuscrit sera supprimé et je ne
            pourrais prétendre à un remboursement', 'required' => 'true'))
        ;
    }

    public function getName()
    {
        return 'book_type';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'YourBooks\BookBundle\Entity\Book',
        ));

    }
}