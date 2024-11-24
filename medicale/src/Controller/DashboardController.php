<?php


namespace App\Controller;

use App\Repository\AppointmentRepository;
use App\Repository\PrescriptionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\PatientRepository;
use App\Repository\MedicalRecordRepository;

class DashboardController extends AbstractController

{
    #[Route('/dashboard/patient', name: 'patient_dashboard')]
    public function patientDashboard(AppointmentRepository $appointmentRepository): Response
    {
        $user = $this->getUser(); // Récupérer l'utilisateur connecté

        if (!$user) {
            // Si aucun utilisateur n'est connecté, rediriger vers la page de connexion
            return $this->redirectToRoute('app_login');
        }

        // Filtrer les rendez-vous pour ce patient uniquement
        $appointments = $appointmentRepository->findBy(['patient' => $user]);

        return $this->render('dashboard/patient.html.twig', [
            'appointments' => $appointments, // Passer les rendez-vous du patient connecté
        ]);
    }

    #[Route('/dashboard/doctor', name: 'doctor_dashboard')]
    public function doctorDashboard(
        AppointmentRepository $appointmentRepository,
        PatientRepository $patientRepository,
        MedicalRecordRepository $medicalRecordRepository,
        PrescriptionRepository $prescriptionRepository
    ): Response {
        $user = $this->getUser();

        // Vérifiez que l'utilisateur est un médecin
        if (!$user) {
            throw $this->createAccessDeniedException('Vous devez être connecté.');
        }

        // Récupérer les rendez-vous du médecin connecté
        $appointments = $appointmentRepository->findBy(['doctor' => $user]);

        // Récupérer tous les patients
        $patients = $patientRepository->findAll();

        // Récupérer tous les dossiers médicaux
        $medicalRecords = $medicalRecordRepository->findAll();

        // Récupérer toutes les prescriptions associées aux dossiers médicaux
        $prescriptions = $prescriptionRepository->findAll();

        return $this->render('dashboard/doctor.html.twig', [
            'appointments' => $appointments,
            'patients' => $patients,
            'medical_records' => $medicalRecords,
            'prescriptions' => $prescriptions, // Ajout des prescriptions
        ]);
    }





    #[Route('/dashboard/admin', name: 'admin_dashboard')]
    public function adminDashboard(): Response
    {
        return $this->render('dashboard/admin.html.twig');
    }
}
