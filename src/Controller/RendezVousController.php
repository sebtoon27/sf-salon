<?php

namespace App\Controller;

use DateTime;
use App\Entity\RendezVous;
use App\Form\RendezVousForm;
use App\Repository\RendezVousRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Prestation;

// Définition de la route principale pour ce contrôleur
#[Route('/rendez/vous')]
final class RendezVousController extends AbstractController
{
    // Affiche la liste de tous les rendez-vous
    #[Route(name: 'app_rendez_vous_index', methods: ['GET'])]
public function index(RendezVousRepository $rendezVousRepository, Security $security ): Response
{
    $user = $security->getUser();

    if ($this->isGranted('ROLE_ADMIN')) {
        $rendez_vouses = $rendezVousRepository->findAll();
    } else {
        $rendez_vouses = $rendezVousRepository->findBy(['client' => $user]);
    }

    return $this->render('rendez_vous/index.html.twig', [
        'rendez_vouses' => $rendez_vouses,
    ]);
}
 
// Crée un nouveau rendez-vous (formulaire + traitement)
#[Route('/new', name: 'app_rendez_vous_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $em, Security $security, RendezVousRepository $rendezVousRepository)
{
    $rendezVous = new RendezVous();

    // 1. Récupère la date passée en GET AVANT le handleRequest
    $dateStr = $request->query->get('date');
    $dateFromCalendar = false;
if ($dateStr) {
    $rendezVous->setDate(new \DateTimeImmutable($dateStr));
    $dateFromCalendar = true;
}

// Passe l'option au formulaire
$form = $this->createForm(RendezVousForm::class, $rendezVous, [
    'date_disabled' => $dateFromCalendar,
]);
$form->handleRequest($request);
    // Récupère tous les rendez-vous existants
    $rdvs = $rendezVousRepository->findAll();
    $slotsTaken = [];
    foreach ($rdvs as $rdv) {
        $start = $rdv->getDate();
        $prestations = $rdv->getPrestation();
        $duration = 0;
        foreach ($prestations as $prestation) {
            $duration += $prestation->getDuree(); // suppose que getDuree() retourne la durée en minutes
        }
        $end = (clone $start)->modify("+$duration minutes");
        $slotsTaken[] = [
            'start' => $start->format('Y-m-d\TH:i'),
            'end' => $end->format('Y-m-d\TH:i'),
        ];
    }

    // Si le formulaire est soumis et valide
    if ($form->isSubmitted() && $form->isValid()) {
       $date = $rendezVous->getDate();
       if ($date) {
    $dayOfWeek = (int)$date->format('w'); // 0 = dimanche, 1 = lundi, ..., 6 = samedi
    if ($dayOfWeek === 0 || $dayOfWeek === 1) {
        $this->addFlash('error', 'Impossible de prendre un rendez-vous le dimanche ou le lundi.');
        return $this->render('rendez_vous/new.html.twig', [
            'rendez_vous' => $rendezVous,
            'form' => $form,
            'slots_taken' => json_encode($slotsTaken),
        ]);
    }
 
}
if ($date) {
    $heure = (int)$date->format('H');
    $minute = (int)$date->format('i');
    $totalMinutes = $heure * 60 + $minute;
    // 12h00 = 720, 13h28 = 808
    if ($totalMinutes >= 720 && $totalMinutes < 808) {
        $this->addFlash('error', 'Impossible de prendre un rendez-vous entre 12h00 et 13h30.');
        return $this->render('rendez_vous/new.html.twig', [
            'rendez_vous' => $rendezVous,
            'form' => $form,
            'slots_taken' => json_encode($slotsTaken),
        ]);
    }
}
        $prestations = $rendezVous->getPrestation();
        $duration = 0;
        foreach ($prestations as $prestation) {
            $duration += $prestation->getDuree();
        }
        $end = (clone $date)->modify("+$duration minutes");

        // Vérifie le chevauchement
        $overlap = false;
        foreach ($rdvs as $rdv) {
            $rdvStart = $rdv->getDate();
            $rdvDuration = 0;
            foreach ($rdv->getPrestation() as $p) {
                $rdvDuration += $p->getDuree();
            }
            $rdvEnd = (clone $rdvStart)->modify("+$rdvDuration minutes");
            if ($date < $rdvEnd && $end > $rdvStart) {
                $overlap = true;
                break;
            }
            
        }

        if ($overlap) {
            $this->addFlash('error', 'Ce créneau chevauche un rendez-vous existant.');
        } else {
            $rendezVous->setClient($security->getUser());
            $em->persist($rendezVous);
            $em->flush();
            return $this->redirectToRoute('app_rendez_vous_index', [], Response::HTTP_SEE_OTHER);
        }
        $prestationsIds = $request->query->get('prestations');
if ($prestationsIds) {
    $prestationsIds = explode(',', $prestationsIds);
    $prestations = $em->getRepository(Prestation::class)->findBy(['id' => $prestationsIds]);
    foreach ($prestations as $prestation) {
        $rendezVous->addPrestation($prestation);
    }
}
    }

    return $this->render('rendez_vous/new.html.twig', [
        'rendez_vous' => $rendezVous,
        'form' => $form,
        'slots_taken' => json_encode($slotsTaken), // à utiliser côté JS
    ]);
}
 // Affiche le détail d'un rendez-vous
    #[Route('/{id}', name: 'app_rendez_vous_show', methods: ['GET'])]
    public function show(RendezVous $rendezVou): Response
    {
        return $this->render('rendez_vous/show.html.twig', [
            'rendez_vou' => $rendezVou,
        ]);
    }
    // Modifie un rendez-vous existant
    #[Route('/{id}/edit', name: 'app_rendez_vous_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, RendezVous $rendezVou, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RendezVousForm::class, $rendezVou);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('app_rendez_vous_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('rendez_vous/edit.html.twig', [
            'rendez_vou' => $rendezVou,
            'form' => $form,
        ]);
    }

    // Affiche le calendrier des rendez-vous (pour FullCalendar par exemple)
#[Route('/rendez_vous/calendrier', name: 'app_rendez_vous_calendrier')]
public function calendrier(RendezVousRepository $repo): Response
{
    $rendezVous = $repo->findAll();
    $events[] = [
    'startTime' => '12:00:00',
    'daysOfWeek' => [2,3,4,5,6], // 1=lundi, ..., 6=samedi (0=dimanche)
    // 'display' => 'background',
    'color' => '#441442', // Rouge pour la pause déjeuner
    'title' => 'Pause déjeuner',
    'borderColor' => '#a8266f',
    'endTime' => '13:30:00'
];
    // Prépare les données pour l'affichage dans le calendrier
    foreach ($rendezVous as $rdv) {
        $client = $rdv->getClient();
        $clientName = $client ? $client->getNom() . ' ' . $client->getPrenom() : 'Client inconnu';

        // Calcul de la durée totale du rendez-vous
        $duration = 0;
        foreach ($rdv->getPrestation() as $prestation) {
            $duration += $prestation->getDuree(); // getDuree() retourne la durée en minutes
        }
        $end = (clone $rdv->getDate())->modify("+$duration minutes");

        $events[] = [
            'title' => $clientName . ' : ' . implode(', ', array_map(fn($p) => $p->getNom(), $rdv->getPrestation()->toArray())),
            'start' => $rdv->getDate()->format('Y-m-d\TH:i:s'),
            'end'   => $end->format('Y-m-d\TH:i:s'),
            'url' => $this->generateUrl('app_rendez_vous_show', ['id' => $rdv->getId()])
        ];
    }
    return $this->render('rendez_vous/calendrier.html.twig', [
        'events' => json_encode($events),
    ]);
}
    // Supprime un rendez-vous (via POST)
    #[Route('/{id}', name: 'app_rendez_vous_delete', methods: ['POST'])]
    public function delete(Request $request, RendezVous $rendezVou, EntityManagerInterface $entityManager): Response
    {
        // Vérifie le token CSRF avant suppression
        if ($this->isCsrfTokenValid('delete'.$rendezVou->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($rendezVou);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_rendez_vous_index', [], Response::HTTP_SEE_OTHER);
    }

}

