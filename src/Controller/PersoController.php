<?php
namespace App\Controller;

use App\Entity\Advert;
use App\Entity\Image;
use App\Entity\Avis;
use App\Form\AdvertType;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class PersoController extends Controller
{
    /*
     * Cette méthode permet d'afficher les dix derniers personnages ajoutés en base
     */
    public function indexAction()
    {
        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('App\Entity\Advert');

        $listAdverts = $repository->findBy(array(),array('id' => 'desc'),10,0);

        // Ici, on récupérera la liste des annonces, puis on la passera au template

        // Mais pour l'instant, on ne fait qu'appeler le template
        return $this->render('Perso/index.html.twig', array(
            'listAdverts' => $listAdverts,
        ));
    }

    /*
     * Cette méthode permettra de modifier un personnage
     */
    public function editAction()
    {

    }




    /*
    * Cette méthode permet d'ajouter un personnage
    *
    */
   /* public function addAction(Request $request)
    {

        $advert = new Advert();


        $advert->setDateajout(new \Datetime());

        // On crée le FormBuilder grâce au service form factory

        $form  = $this->get('form.factory')->create(AdvertType::class, $advert);





        if($request->isMethod('POST')) {

            $form->handleRequest($request);

            if ($form->isValid())  {
                $em = $this->getDoctrine()->getManager();
                $em->persist($advert);
                $em->persist($advert->getImage());
                $em->flush();

                $request->getSession()->getFlashBag()->add('notice', 'Personnage bien ajouté.');

                // On redirige vers la page de visualisation de l'annonce nouvellement créée
                return $this->redirectToRoute('app_character_details', array('id' => $advert->getId()));

            }
        }

        return $this->render('Perso/add.html.twig', array(
            'form' => $form->createView(),
        ));

    }*/

    public function addByWkAction(Request $request, $id)
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_AUTEUR')) {
            // Sinon on déclenche une exception « Accès interdit »
            throw new AccessDeniedException('Accès limité aux auteurs.');
        }
        $advert = new Advert();


        $advert->setDateajout(new \Datetime());

        // On crée le FormBuilder grâce au service form factory

        $form  = $this->get('form.factory')->create('App\Form\AdvertType', $advert);

        /*
        $em = $this->getDoctrine()->getManager();

        $advert = $em->getRepository('App\Entity\Advert')->find($id);*/



        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isValid())  {

                $em = $this->getDoctrine()->getManager();
                $em->persist($advert);

                $oeuvre = $em->getRepository('App\Entity\Oeuvre')->find($id);
                $advert->setOeuvre($oeuvre);
                $em->persist($advert->getImage());
                $em->flush();

                $request->getSession()->getFlashBag()->add('notice', 'Personnage bien ajouté.');

                // On redirige vers la page de visualisation de l'annonce nouvellement créée
                return $this->redirectToRoute('app_character_details', array('id' => $advert->getId()));

            }
            return $this->render('Perso/add.html.twig', array(
                'form' => $form->createView(),
            ));
            $request->getSession()->getFlashBag()->add('notice', 'Personnage bien enregistrée.');
            // Puis on redirige vers la page de visualisation de cettte annonce
            return $this->redirectToRoute('app_character_details', array('id' => $id));
        }
        // Si on n'est pas en POST, alors on affiche le formulaire
        return $this->render('Perso/add.html.twig', array('form' => $form->createView()));

        /*
        $avis1= new Avis();
        $avis1->setAuthor('Alexandre');
        $avis1->setContent("Excellent personnages très attachant.");
        $avis1->setNote(9);
        $avis1->setAdvert($advert);

        $em->persist($advert);

        $em->persist($avis1);

        $em->flush();

        */
    }


    /*
     * Cette méthode permet d'ajouter un commentaire à un personnage
     * Elle prend en paramètre le numéro du personnage
     */
    public function addComAction(Request $request, $id)
    {

        $avis = new Avis;


        $avis->setDate(new \Datetime());

        // On crée le FormBuilder grâce au service form factory

        $form  = $this->get('form.factory')->create('App\Form\AvisType', $avis);

        /*
        $em = $this->getDoctrine()->getManager();

        $advert = $em->getRepository('App\Entity\Advert')->find($id);*/



        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isValid())  {
                $em = $this->getDoctrine()->getManager();
                $em->persist($avis);
                $advert = $em->getRepository('App\Entity\Advert')->find($id);

                $advert->addAvis($avis);

                $avis->setAdvert($advert);


                $em->persist($advert->getImage());
                $em->flush();

                $request->getSession()->getFlashBag()->add('notice', 'Personnage bien ajouté.');

                // On redirige vers la page de visualisation de l'annonce nouvellement créée
                return $this->redirectToRoute('app_character_details', array('id' => $advert->getId()));

            }
        return $this->render('Perso/add.html.twig', array(
            'form' => $form->createView(),
        ));


            $request->getSession()->getFlashBag()->add('notice', 'Commentaire bien enregistrée.');

            // Puis on redirige vers la page de visualisation de cettte annonce
            return $this->redirectToRoute('app_character_details', array('id' => $id));
        }
        // Si on n'est pas en POST, alors on affiche le formulaire
        return $this->render('Perso/addCom.html.twig', array('form' => $form->createView()));

        /*
        $avis1= new Avis();
        $avis1->setAuthor('Alexandre');
        $avis1->setContent("Excellent personnages très attachant.");
        $avis1->setNote(9);
        $avis1->setAdvert($advert);

        $em->persist($advert);

        $em->persist($avis1);

        $em->flush();

        */
    }


    public function viewAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $avisRepository = $em->getRepository(Avis::class);


        // On récupère le repository
        $em = $this->getDoctrine()->getManager();

        $repository=$this->getDoctrine()
            ->getManager()->getRepository('App\Entity\Advert');


        // On récupère l'entité correspondante à l'id $id
        $advert = $repository->find($id);

        // $advert est donc une instance de OC\PlatformBundle\Entity\Advert
        // ou null si l'id $id  n'existe pas, d'où ce if :
        if (null === $advert) {
            throw new NotFoundHttpException("Le personnage d'id " . $id . " n'existe pas.");
        }

        //On récupère la liste de tout les Avis ici
        $listAvis = $em
            ->getRepository('App\Entity\Avis')
            ->findBy(array('advert' => $advert));

        $total=0;
        $nb_note=0;
        foreach($listAvis as $avis)
        {
            $total+=$avis->getNote();
            $nb_note+=1;
        }
        if($nb_note!=0) {
            $moyenne = $total / $nb_note;
        }else{
            $moyenne = "Pas de note";
        }
        // Le render ne change pas, on passait avant un tableau, maintenant un objet
        return $this->render('Perso/view.html.twig', array(
            'advert' => $advert,
            'moyenne' => $moyenne,
            'listAvis' => $listAvis
        ));


    }



    public function deleteAction($id){

        $em = $this->getDoctrine()->getManager();

        // On récupère l'annonce $id

        $advert = $em->getRepository('App\Entity\Advert')->find($id);

        if (null === $advert) {
            throw new NotFoundHttpException("Le personnage d'id ".$id." n'existe pas.");
        }
        $listAvis = $em
            ->getRepository('App\Entity\Avis')
            ->findBy(array('advert' => $advert));
        foreach($listAvis as $avis)
        {
            $em->remove($avis);
        }

        $em->remove($advert);

        $em->flush();

        return $this->render('Perso/delete.html.twig');

    }

    /*
     * Cette méthode permet de supprimer un commentaire
     */
    public function deleteComAction($id){
        $em = $this->getDoctrine()->getManager();
        $avis = $em->getRepository('App\Entity\Avis')->find($id);

        if (null === $avis) {
            throw new NotFoundHttpException("L'avis d'id ".$id." n'existe pas.");
        }

        $em->remove($avis);
        $em->flush();

        return $this->render('Perso/deleteCom.html.twig');
    }

    /*
     * Permet d'afficher les 5 derniers personnages ajoutés en base
     */
    public function menuAction(Request $request)
    {

        $repository = $this
        ->getDoctrine()
        ->getManager()
        ->getRepository('App\Entity\Advert');

        $listAdverts = $repository->findBy(array(),array('dateajout' => 'desc'),5,0);

        $repository1 = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('App\Entity\Oeuvre');

        $listOeuvres = $repository1->findBy(array(),array('date' => 'desc'),5,0);


        return $this->render('Perso/menu.html.twig', array(
            // Tout l'intérêt est ici : le contrôleur passe
            // les variables nécessaires au template !
            'listAdverts' => $listAdverts,
            'listOeuvres' => $listOeuvres
        ));
    }

}