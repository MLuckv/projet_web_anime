<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Anime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\ChoiceList\View\ChoiceView;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class AnimeSearch extends AbstractType implements DataTransformerInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager, UrlGeneratorInterface $urlGenerator) {
        $this->entityManager = $entityManager;
        $this->urlGenerator = $urlGenerator;
    }



    public function buildForm(FormBuilderInterface $builder, array $options):void
    {
        $builder->addModelTransformer($this);
    }

    public  function buildView(FormView $view, FormInterface $form, array $options):void
    {
        $choices =[];
        $anime = $form->getData();
        if($anime instanceof Anime){
            $choices = [new ChoiceView(
                $anime,
                (string)$anime->getId(),
                (string)$anime->getNom(),
            )];
        }
        $view->vars['choices'] = $choices;
        $view->vars['choice_translation_domain'] = false;
        $view->vars['placeholder_in_choices'] = false;
        $view->vars['placeholder'] = 'Selectionnez un anime ';
        $view->vars['expanded'] = false;
        $view->vars['multiple'] = false;
        $view->vars['preferred_choices'] = [];
        $view->vars['value'] = $anime ? (string)$anime->getId() : 0;
    }

    public function configureOptions(OptionsResolver $resolver):void
    {
        $resolver->setDefaults([
            'compound' => false,
            'attr' =>[
                'is' => 'select-anime',
                'data-remove' => $this->urlGenerator->generate('home_search'),
                'data-value' => 'id',
                'data-label' => 'nom'
            ]
        ]);

    }

    public function getBlockPrefix():string
    {
        return 'choice';
    }

    public function transform($anime):string
    {
        return null == $anime ? '' : (string) $anime->getId();
    }

    public function reverseTransform($animeId): ?Anime{
        return $this->entityManager->getRepository(Anime::class)->find($animeId);
    }

}
