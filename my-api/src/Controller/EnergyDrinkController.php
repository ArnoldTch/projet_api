<?php

namespace App\Controller;

use App\Entity\EnergyDrink;
use App\Repository\EnergyDrinkRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Http\Attribute\IsGranted;


#[Route('/api/energy-drinks')]
#[IsGranted('ROLE_USER')]
final class EnergyDrinkController extends AbstractController
{
    // Récupérer toutes les boissons énergétiques

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('', name: 'api_energy_drink_index', methods: ['GET'])]
    public function index(EnergyDrinkRepository $repository): JsonResponse
    {
        $drinks = $repository->findAll();
        $data = [];

        foreach ($drinks as $drink) {
            $data[] = [
                'id' => $drink->getId(),
                'name' => $drink->getName(),
                'image' => $drink->getImage(),
            ];
        }

        return $this->json($data, Response::HTTP_OK);
    }

    #[Route('', name: 'add_energy_drink', methods: ['POST'])]
    public function addEnergyDrink(
        Request $request,
        EntityManagerInterface $entityManager,
        SluggerInterface $slugger
    ): JsonResponse {
        $name = $request->request->get('name');
        $imageFile = $request->files->get('image');

        if (!$name || !$imageFile) {
            return new JsonResponse(['error' => 'Name and image are required'], 400);
        }

        $uploadDir = $this->getParameter('kernel.project_dir') . '/public/images/';
        $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $slugger->slug($originalFilename);
        $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

        try {
            $imageFile->move($uploadDir, $newFilename);
        } catch (FileException $e) {
            return new JsonResponse(['error' => 'Failed to upload image'], 500);
        }

        $imagePath = '/images/' . $newFilename;

        $energyDrink = new EnergyDrink();
        $energyDrink->setName($name);
        $energyDrink->setImage($imagePath);

        $entityManager->persist($energyDrink);
        $entityManager->flush();

        return new JsonResponse([
            'message' => 'Energy Drink added successfully',
            'data' => [
                'id' => $energyDrink->getId(),
                'name' => $energyDrink->getName(),
                'image' => $energyDrink->getImage(),
            ],
        ], 201);
    }

    #[Route('/{id}', name: 'api_energy_drinks_put', methods: ['PUT'])]
    public function put(Request $request, EnergyDrink $drink, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!$data) {
            return new JsonResponse(['error' => 'Invalid JSON'], Response::HTTP_BAD_REQUEST);
        }

        $drink->setName($data['name'] ?? $drink->getName());
        $drink->setImage($data['image'] ?? $drink->getImage());

        $entityManager->flush();

        return $this->json([
            'message' => 'EnergyDrink updated successfully',
            'data' => [
                'id' => $drink->getId(),
                'name' => $drink->getName(),
                'image' => $drink->getImage(),
            ],
        ], Response::HTTP_OK);
    }


    /**
     * @Route("/api/energy-drinks/{id}", methods={"PATCH"})
     */
    #[Route('/{id}', name: 'api_energy_drinks_patch', methods: ['PATCH'])]
    public function patch(EnergyDrink $energyDrink, Request $request): JsonResponse
    {
        if (!$energyDrink) {
            throw new NotFoundHttpException('EnergyDrink not found');
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['name'])) {
            $energyDrink->setName($data['name']);
        }

        if (isset($data['image'])) {
            $energyDrink->setImage($data['image']);
        }

        $this->entityManager->flush();
        return new JsonResponse($energyDrink);
    }

    #[Route('/{id}', name: 'api_energy_drinks_delete', methods: ['DELETE'])]
    public function delete(EnergyDrink $energyDrink, EntityManagerInterface $entityManager): JsonResponse
    {
        if (!$energyDrink) {
            return $this->json(['error' => 'EnergyDrink not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $entityManager->remove($energyDrink);
        $entityManager->flush();

        return $this->json(['message' => 'EnergyDrink deleted successfully'], JsonResponse::HTTP_NO_CONTENT);
    }


    // Afficher une boisson énergétique spécifique
    #[Route('/{id}', name: 'api_energy_drink_show', methods: ['GET'])]
    public function show(EnergyDrink $drink): JsonResponse
    {
        return $this->json([
            'id' => $drink->getId(),
            'name' => $drink->getName(),
            'image' => $drink->getImage(),
        ], Response::HTTP_OK);
    }

    // Modifier une boisson énergétique
    #[Route('/{id}', name: 'api_energy_drink_edit', methods: ['PUT'])]
    public function edit(Request $request, EnergyDrink $drink, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!$data) {
            return $this->json(['error' => 'Invalid JSON'], Response::HTTP_BAD_REQUEST);
        }

        $drink->setName($data['name'] ?? $drink->getName());
        $drink->setImage($data['image'] ?? $drink->getImage());

        $em->flush();

        return $this->json(['message' => 'EnergyDrink updated successfully'], Response::HTTP_OK);
    }

    #[Route('/api/login', name: 'api_login', methods: ['POST'])]
    public function login(): JsonResponse
    {
        throw new AuthenticationException('Invalid login process.');
    }

}
