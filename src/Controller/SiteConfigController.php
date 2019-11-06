<?php

namespace App\Controller;

use App\Entity\SiteConfig;
use App\Form\SiteConfigType;
use App\Repository\SiteConfigRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/dashboard/siteconfig")
 */
class SiteConfigController extends AbstractController
{
    /**
     * @Route("/", name="site_config_index", methods="GET")
     */
    public function index(SiteConfigRepository $siteConfigRepository): Response
    {
        return $this->render('site_config/index.html.twig', ['site_configs' => $siteConfigRepository->findAll()]);
    }

    /**
     * @Route("/new", name="site_config_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $siteConfig = new SiteConfig();
        $form = $this->createForm(SiteConfigType::class, $siteConfig)
            ->add('value', TextareaType::class, [
                'attr' => [
                    'class' => 'uk-input',
                    'rows' => 5
                ]
            ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($siteConfig);
            $em->flush();

            return $this->redirectToRoute('site_config_index');
        }

        return $this->render('site_config/new.html.twig', [
            'site_config' => $siteConfig,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="site_config_show", methods="GET")
     */
    public function show(SiteConfig $siteConfig): Response
    {
        return $this->render('site_config/show.html.twig', ['site_config' => $siteConfig]);
    }

    /**
     * @Route("/{id}/edit", name="site_config_edit", methods="GET|POST")
     */
    public function edit(Request $request, SiteConfig $siteConfig): Response
    {
        $form = $this->createForm(SiteConfigType::class, $siteConfig)
            ->add('value', TextareaType::class, [
                'attr' => [
                    'class' => 'uk-input',
                    'rows' => 5
                ]
            ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('site_config_index', ['id' => $siteConfig->getId()]);
        }

        return $this->render('site_config/edit.html.twig', [
            'site_config' => $siteConfig,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="site_config_delete", methods="DELETE")
     */
    public function delete(Request $request, SiteConfig $siteConfig): Response
    {
        if ($this->isCsrfTokenValid('delete'.$siteConfig->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($siteConfig);
            $em->flush();
        }

        return $this->redirectToRoute('site_config_index');
    }
}
