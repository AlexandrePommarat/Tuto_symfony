<?php

namespace App\Controller;

use App\Entity\Oeuvre;
use App\Entity\Advert;
use App\Entity\Avis;
use App\Form\OeuvreType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class WorkController extends Controller
{
    /*
    * Cette méthode permet d'ajouter une oeuvre
    *
    */
    public function addAction(Request $request)
    {

        $oeuvre= new Oeuvre();


        $oeuvre->setDate(new \Datetime());

        // On crée le FormBuilder grâce au service form factory

        $form  = $this->get('form.factory')->create(OeuvreType::class, $oeuvre);



        if($request->isMethod('POST')) {

            $form->handleRequest($request);

            if ($form->isValid())  {
                $em = $this->getDoctrine()->getManager();
                $em->persist($oeuvre);
                $em->flush();

                $request->getSession()->getFlashBag()->add('notice', 'Oeuvre bien ajouté.');

                // On redirige vers la page de visualisation de l'annonce nouvellement créée
                return $this->redirectToRoute('app_work_details', array('id' => $oeuvre->getId()));

            }
        }

        return $this->render('Work/add.html.twig', array(
            'form' => $form->createView(),
        ));

    }

    public function viewAction($id)
    {

        // On récupère le repository
        $em = $this->getDoctrine()->getManager();
        $advertRepository = $em->getRepository(Advert::class);
        $repository=$this->getDoctrine()
            ->getManager()->getRepository('App\Entity\Oeuvre');


        // On récupère l'entité correspondante à l'id $id
        $oeuvre = $repository->find($id);

        // $advert est donc une instance de OC\PlatformBundle\Entity\Advert
        // ou null si l'id $id  n'existe pas, d'où ce if :
        if (null === $oeuvre) {
            throw new NotFoundHttpException("l'oeuvre d'id " . $id . " n'existe pas.");
        }
        $listPerso = $em->getRepository('App\Entity\Advert')->findBy(array('oeuvre' => $oeuvre));





        // Le render ne change pas, on passait avant un tableau, maintenant un objet
        return $this->render('Work/view.html.twig', array(
            'oeuvre' => $oeuvre,
            'listPerso' => $listPerso
        ));


    }

    public function deleteAction($id){

        $em = $this->getDoctrine()->getManager();

        $oeuvre = $em->getRepository('App\Entity\Oeuvre')->find($id);

        if (null === $oeuvre) {
            throw new NotFoundHttpException("L'oeuvre d'id ".$id." n'existe pas.");
        }
        $listPerso = $em
            ->getRepository('App\Entity\Advert')
            ->findBy(array('oeuvre' => $oeuvre));

        foreach ($listPerso as $perso)
        {
            $advert = $em->getRepository('App\Entity\Advert')->find($perso->getId());

            $listAvis = $em
                ->getRepository('App\Entity\Avis')
                ->findBy(array('advert' => $advert));

            foreach ($listAvis as $avis)
            {
                $em->remove($avis);
            }
            $em->remove($advert);

            $em->flush();
        }

        $em->remove($oeuvre);

        $em->flush();

        return $this->render('Perso/delete.html.twig');
    }




}