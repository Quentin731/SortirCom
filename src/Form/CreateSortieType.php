<?php

    namespace App\Form;

    use App\Entity\Group;
    use App\Entity\User;
    use App\Entity\Place;
    use App\Entity\Trip;
    use App\Entity\City;
    use Doctrine\ORM\EntityRepository;
    use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
    use Symfony\Component\Form\Extension\Core\Type\DateType;
    use Symfony\Component\Form\Extension\Core\Type\IntegerType;
    use Symfony\Bridge\Doctrine\Form\Type\EntityType;
    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\Extension\Core\Type\TextType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolver;
    use Symfony\Component\Security\Core\Security;

    class CreateSortieType extends AbstractType
    {
        private $security;

        public function __construct(Security $security)
        {
            $this->security = $security;
        }

        public function buildForm(FormBuilderInterface $builder, array $options): void
        {
            $builder
                ->add('tripName', TextType::class, ['label' => 'Nom de la sortie:'])
                ->add('tripStartDate', DateTimeType::class, [
                    'label' => 'Date et heure de la sortie:',
                    'widget' => 'single_text',
                ])
                ->add('deadlineRegistrationDate', DateType::class, [
                    'label' => 'Date limite d\'inscription:',
                    'widget' => 'single_text',
                ])
                ->add('capacity', IntegerType::class, ['label' => 'Nombre de places:'])
                ->add('duration', IntegerType::class, ['label' => 'Durée (en minutes):'])
                ->add('description', TextType::class, ['label' => 'Description et infos:'])
                ->add('place', EntityType::class, [
                    'class' => Place::class,
                    'choice_label' => 'city.cityName',
                    'label' => 'Ville',
                    "attr" => [
                        "id" => "city"
                    ]
                ])
                ->add('groups', EntityType::class, [
                    'choice_label' => 'label',
                    'class' => Group::class,
                    'label' => 'Groupes :',
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('u')
                            ->join('u.users', 'users')
                            ->andWhere(sprintf('users.%s = :%s','id', 'id'))
                            ->setParameter('id', $this->getUserId());


                    },
                    'multiple' => true
                ])
            ;
        }

        public function getUserId(){
            return $this->security->getUser()->getId();
        }

        public function configureOptions(OptionsResolver $resolver): void
        {
            $resolver->setDefaults([
                'data_class' => Trip::class,
            ]);
        }
    }
