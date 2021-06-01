<?php

namespace App\Controller;

use App\Entity\Comentario;
use App\Entity\Comentarios;
use App\Entity\Peliculas;
use App\Entity\Posts;
use App\Form\ComentarioType;
use App\Form\PeliculasType;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class PeliculasController extends AbstractController
{
    /**
     * @Route("/registrar-peliculas", name="RegistrarPeliculas")
     */
    public function index(Request $request, SluggerInterface $slugger)
    {
        $post = new Peliculas();
        $form = $this->createForm(PeliculasType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $brochureFile */
            $brochureFile = $form->get('foto')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $brochureFile->move(
                        $this->getParameter('photos_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    throw new \Exception('UPs! ha ocurrido un error, sorry :c');
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $post->setFoto($newFilename);
            }
            $user = $this->getUser();
            $post->setUser($user);
            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();
            return $this->redirectToRoute('dashboard');
        }

        return $this->render('peliculas/index.html.twig', [
             'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/peliculas/{id}", name="VerPeliculas")
     */
    public function VerPeliculas($id, Request $request, PaginatorInterface $paginator){
        $em = $this->getDoctrine()->getManager();
                                             $comentario = new Comentario();
        $post = $em->getRepository(Peliculas::class)->find($id);
                                    $queryComentario = $em->getRepository(Comentario::class)->BuscarcomentarioDeUNPost($post->getId());
                                    $form = $this->createForm(ComentarioType::class, $comentario);
                                    $form->handleRequest($request);
                                    if($form->isSubmitted() && $form->isValid()){
        $user = $this->getUser();
                                        $comentario->setPeliculas($post);
                                        $comentario->setUser($user);
                                        $em->persist($comentario);
                                        $em->flush();
                                        $this->addFlash('Exito', Comentario::COMENTARIO_AGREGADO_EXITOSAMENTE);
                                        return $this->redirectToRoute('VerPeliculas',['id'=>$post->getId()]);
                                    }
                                    $pagination = $paginator->paginate(
                                        $queryComentario, /* query NOT result */
                                        $request->query->getInt('page', 1), /*page number*/
                                        20 /*limit per page*/
                                    );
        return $this->render('peliculas/VerPeliculas.html.twig',['post'=>$post, 'form'=>$form->createView(), 'comentario'=>$pagination ]);
//
    }

    /**
     * @Route("/mis-peliculas", name="MisPeliculas")
     */
    public function MisPeliculas(){
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $posts = $em->getRepository(Peliculas::class)->findBy(['user'=>$user]);
        return $this->render('peliculas/MisPeliculas.html.twig',['posts'=>$posts]);
    }

    /**
     * @Route("/Likes", options={"expose"=true}, name="Likes")
     */
    public function Like(Request $request){
        if($request->isXmlHttpRequest()){
            $em = $this->getDoctrine()->getManager();
            $user = $this->getUser();
            $id = $request->request->get('id');
            $post = $em->getRepository(Peliculas::class)->find($id);
            $likes = $post->getLikes();
            $likes .= $user->getId().',';
            $post->setLikes($likes);
            $em->flush();
            return new JsonResponse(['likes'=>$likes]);
        }else{
            throw new \Exception('Estás tratando de hackearme?');
        }
    }
}
