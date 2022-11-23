<?php
// src/Controller/ProgramController.php
namespace App\Controller;

use App\Repository\EpisodeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use App\Repository\ProgramRepository;
use App\Entity\Program;
use App\Repository\SeasonRepository;
use App\Entity\Season;
use App\Entity\Episode;

#[Route('/program', name: 'program_')]
class ProgramController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(ProgramRepository $programRepository): Response
    {
        $programs = $programRepository->findAll();
        return $this->render(
            'program/index.html.twig',
            ['programs' => $programs]
        );
    }

    #[Route('/{id}', methods: ['GET'],  requirements: ['id' => '\d+'], name: 'show')]
    public function show(Program $program, SeasonRepository $seasonRepository): Response
    {
        if (!$program) {
            throw $this->createNotFoundException(
                'No program with this id found in program\'s table.'
            );
        }

        $seasons = $seasonRepository->findBy(['program' => $program], ['id' => 'DESC']);

        return $this->render('program/show.html.twig', [
            'program' => $program,
            'seasons' => $seasons,
        ]);
    }

    #[Route('/{programId}/season/{seasonId}', methods: ['GET'],  requirements: ['programId' => '\d+', 'seasonId' => '\d+'], name: 'season_show')]
    #[ParamConverter('program', options: ['mapping' => ['programId' => 'id']])]
    #[ParamConverter('season', options: ['mapping' => ['seasonId' => 'id']])]
    public function showSeason(Program $program, Season $season, EpisodeRepository $episodeRepository): Response
    {
        if (!$program) {
            throw $this->createNotFoundException(
                'No program with this id found in program\'s table.'
            );
        }

        if (!$season) {
            throw $this->createNotFoundException(
                'No season with this id found for this program.'
            );
        }

        $episodes = $episodeRepository->findBy(['season' => $season]);

        return $this->render('program/season_show.html.twig', [
            'program' => $program,
            'season' => $season,
            'episodes' => $episodes,
        ]);
    }

    #[Route('/{programId}/season/{seasonId}/episode/{episodeId}', methods: ['GET'],  requirements: ['programId' => '\d+', 'seasonId' => '\d+'], name: 'episode_show')]
    #[ParamConverter('program', options: ['mapping' => ['programId' => 'id']])]
    #[ParamConverter('season', options: ['mapping' => ['seasonId' => 'id']])]
    #[ParamConverter('episode', options: ['mapping' => ['episodeId' => 'id']])]
    public function showEpisode(Program $program, Season $season, Episode $episode): Response
    {
        if (!$program) {
            throw $this->createNotFoundException(
                'No program with this id found in program\'s table.'
            );
        }

        if (!$season) {
            throw $this->createNotFoundException(
                'No season with this id found for this program.'
            );
        }

        if (!$episode) {
            throw $this->createNotFoundException(
                'No episode with this id found for this program.'
            );
        }

        return $this->render('program/episode_show.html.twig', [
            'program' => $program,
            'season' => $season,
            'episode' => $episode,
        ]);
    }
}
