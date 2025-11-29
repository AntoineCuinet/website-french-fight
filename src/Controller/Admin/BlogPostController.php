<?php

namespace App\Controller\Admin;

use App\Entity\BlogPost;
use App\Form\BlogPostType;
use App\Repository\BlogPostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile; // Add for file uploads
use Symfony\Component\String\Slugger\SluggerInterface; // Add for file uploads
use Symfony\Component\Filesystem\Filesystem; // Add for file operations
use Symfony\Component\HttpFoundation\File\Exception\FileException; // Add for file exception handling

#[Route('/admin/blog')]
class BlogPostController extends AbstractController
{
    #[Route('/', name: 'app_blog_post_index', methods: ['GET'])]
    public function index(BlogPostRepository $blogPostRepository): Response
    {
        return $this->render('admin/blog_post/index.html.twig', [
            'blog_posts' => $blogPostRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_blog_post_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $blogPost = new BlogPost();
        $form = $this->createForm(BlogPostType::class, $blogPost);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile|null $imageFile */
            $imageFile = $form->get('imageFile')->getData();

            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('kernel.project_dir').'/public/uploads/blog_posts',
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('error', 'Erreur lors de l\'upload de l\'image: '.$e->getMessage());
                    return $this->render('admin/blog_post/new.html.twig', [
                        'blog_post' => $blogPost,
                        'form' => $form,
                    ]);
                }
                $blogPost->setImageUrl('/uploads/blog_posts/'.$newFilename);
            }

            $entityManager->persist($blogPost);
            $entityManager->flush();

            return $this->redirectToRoute('app_blog_post_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/blog_post/new.html.twig', [
            'blog_post' => $blogPost,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_blog_post_show', methods: ['GET'])]
    public function show(BlogPost $blogPost): Response
    {
        return $this->render('admin/blog_post/show.html.twig', [
            'blog_post' => $blogPost,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_blog_post_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, BlogPost $blogPost, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(BlogPostType::class, $blogPost);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile|null $imageFile */
            $imageFile = $form->get('imageFile')->getData();

            if ($imageFile) {
                // If there's an old image, delete it
                if ($blogPost->getImageUrl()) {
                    $oldImagePath = $this->getParameter('kernel.project_dir').'/public'.$blogPost->getImageUrl();
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
                        $this->getParameter('kernel.project_dir').'/public/uploads/blog_posts',
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('error', 'Erreur lors de l\'upload de l\'image: '.$e->getMessage());
                    return $this->render('admin/blog_post/edit.html.twig', [
                        'blog_post' => $blogPost,
                        'form' => $form,
                    ]);
                }
                $blogPost->setImageUrl('/uploads/blog_posts/'.$newFilename);
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_blog_post_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/blog_post/edit.html.twig', [
            'blog_post' => $blogPost,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_blog_post_delete', methods: ['POST'])]
    public function delete(Request $request, BlogPost $blogPost, EntityManagerInterface $entityManager, Filesystem $filesystem): Response
    {
        if ($this->isCsrfTokenValid('delete'.$blogPost->getId(), $request->request->get('_token'))) {
            // Remove image file if exists
            if ($blogPost->getImageUrl()) {
                $imagePath = $this->getParameter('kernel.project_dir').'/public'.$blogPost->getImageUrl();
                if ($filesystem->exists($imagePath)) {
                    $filesystem->remove($imagePath);
                }
            }
            $entityManager->remove($blogPost);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_blog_post_index', [], Response::HTTP_SEE_OTHER);
    }
}
