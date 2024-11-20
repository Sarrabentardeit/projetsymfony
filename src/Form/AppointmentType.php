<?php
namespace App\Form;

use App\Entity\Appointment;
use App\Entity\Doctor;
use App\Entity\Patient;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AppointmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateTime', DateTimeType::class, [
                'widget' => 'single_text', // Utilise un sélecteur de date/heure moderne
                'label' => 'Date et heure du rendez-vous',
            ])
            ->add('doctor', EntityType::class, [
                'class' => Doctor::class,
                'choice_label' => 'username', // Affiche le nom du médecin
                'label' => 'Médecin',
            ])
            ->add('patient', EntityType::class, [
                'class' => Patient::class,
                'choice_label' => 'username', // Affiche le nom du patient
                'label' => 'Patient',
            ])
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'Confirmé' => 'confirmé',
                    'En attente' => 'en_attente',
                    'Annulé' => 'annulé',
                ],
                'label' => 'Statut du rendez-vous',
            ])
            ->add('notes', TextareaType::class, [
                'required' => false,
                'label' => 'Notes supplémentaires',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Appointment::class,
        ]);
    }
}
