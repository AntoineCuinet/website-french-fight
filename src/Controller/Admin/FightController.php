<?php

namespace App\Controller\Admin;

use App\Entity\Fight;
use App\Form\FightType;
use App\Repository\FightRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/admin/fight')]
class FightController extends AbstractController
{
    private TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    #[Route('/', name: 'app_fight_index', methods: ['GET'])]
    public function index(FightRepository $fightRepository): Response
    {
        return $this->render('admin/fight/index.html.twig', [
            'fights' => $fightRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_fight_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $fight = new Fight();
        $form = $this->createForm(FightType::class, $fight);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile|null $imageFile */
            $imageFile = $form->get('imageFile')->getData();

            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('kernel.project_dir').'/public/uploads/fights',
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                    $this->addFlash('error', $this->translator->trans('admin.flash.upload_error', ['%error%' => $e->getMessage()], 'admin'));
                    return $this->render('admin/fight/new.html.twig', [
                        'fight' => $fight,
                        'form' => $form,
                    ]);
                }
                $fight->setImageUrl('/uploads/fights/'.$newFilename);
            }

            $entityManager->persist($fight);
            $entityManager->flush();

            return $this->redirectToRoute('app_fight_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/fight/new.html.twig', [
            'fight' => $fight,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_fight_show', methods: ['GET'])]
    public function show(Fight $fight): Response
    {
        return $this->render('admin/fight/show.html.twig', [
            'fight' => $fight,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_fight_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Fight $fight, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(FightType::class, $fight);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile|null $imageFile */
            $imageFile = $form->get('imageFile')->getData();

            if ($imageFile) {
                // If there's an old image, delete it
                if ($fight->getImageUrl()) {
                    $oldImagePath = $this->getParameter('kernel.project_dir').'/public'.$fight->getImageUrl();
                    $filesystem = new Filesystem();
                    if ($filesystem->exists($oldImagePath)) {
                        $filesystem->remove($oldImagePath);
                    }
                }

                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('kernel.project_dir').'/public/uploads/fights',
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception
                    $this->addFlash('error', $this->translator->trans('admin.flash.upload_error', ['%error%' => $e->getMessage()], 'admin'));
                    return $this->render('admin/fight/edit.html.twig', [
                        'fight' => $fight,
                        'form' => $form,
                    ]);
                }
                $fight->setImageUrl('/uploads/fights/'.$newFilename);
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_fight_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/fight/edit.html.twig', [
            'fight' => $fight,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_fight_delete', methods: ['POST'])]
    public function delete(Request $request, Fight $fight, EntityManagerInterface $entityManager, Filesystem $filesystem): Response
    {
        if ($this->isCsrfTokenValid('delete'.$fight->getId(), $request->request->get('_token'))) {
            // Remove image file if exists
            if ($fight->getImageUrl()) {
                $imagePath = $this->getParameter('kernel.project_dir').'/public'.$fight->getImageUrl();
                if ($filesystem->exists($imagePath)) {
                    $filesystem->remove($imagePath);
                }
            }
            $entityManager->remove($fight);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_fight_index', [], Response::HTTP_SEE_OTHER);
    }
}
