<?php
namespace App\Controller;

use App\Entity\Advert;
use App\Entity\Image;
use App\Entity\Avis;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PersoController extends Controller
{

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
            'listAdverts' => $listAdverts
        ));
    }

    public function addAction(Request $request)
    {

        $advert = new Advert();


        $advert->setDateajout(new \Datetime());

        // On crée le FormBuilder grâce au service form factory

        $formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $advert);

        // On ajoute les champs de l'entité que l'on veut à notre formulaire
        $formBuilder
            ->add('title',     TextType::class)
            ->add('firstname',   TextType::class)
            ->add('lastname',    TextType::class)
            ->add('valider',      SubmitType::class)
        ;

        // À partir du formBuilder, on génère le formulaire
        $form = $formBuilder->getForm();


        if($request->isMethod('POST')) {

            $form->handleRequest($request);

            if ($form->isValid())  {
                $em = $this->getDoctrine()->getManager();
                $em->persist($advert);
                $em->flush();

                $request->getSession()->getFlashBag()->add('notice', 'Personnage bien ajouté.');

                // On redirige vers la page de visualisation de l'annonce nouvellement créée
                return $this->redirectToRoute('app_character_details', array('id' => $advert->getId()));

            }
        }

        return $this->render('Perso/add.html.twig', array(
            'form' => $form->createView(),
        ));

        // Pour l'instant, pas de candidatures, catégories, etc., on les gérera plus tard




       /* // Création de l'entité
        $advert = new Advert();
        $advert->setTitle('Violet Evergarden');
        $advert->setFirstName('Violet');
        $advert->setLastName('Evergarden');
        $advert->setDateajout(new \Datetime());
        // On peut ne pas définir ni la date ni la publication,
        // car ces attributs sont définis automatiquement dans le constructeur

        //PARTIE IMAGE
        $image = new Image();
        $image->setUrl('https://www.nautiljon.com/images/galerie/11/37/violet_evergarden_952073.jpg');
        $image->setAlt('Pas de chance');

        $advert->setImage($image);

        //PARTIE AVIS

        //création d'un premier avis
        $avis1= new Avis();
        $avis1->setAuthor('Alexandre');
        $avis1->setContent("Excellent personnages très attachant.");
        $avis1->setNote(9);

        //création d'un deuxième avis
        $avis2= new Avis();
        $avis2->setAuthor('Yohann');
        $avis2->setContent("Personnage sans intérêt car j'ai envie d'avoir un avis différent");
        $avis2->setNote(5);

        $avis1->setAdvert($advert);
        $avis2->setAdvert($advert);

        // On récupère l'EntityManager
        $em = $this->getDoctrine()->getManager();

        // Étape 1 : On « persiste » l'entité
        $em->persist($advert);

        $em->persist($avis1);
        $em->persist($avis2);

        $em->persist($image);


        $em->flush();

        // Reste de la méthode qu'on avait déjà écrit
        if ($request->isMethod('POST')) {
            $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');

            // Puis on redirige vers la page de visualisation de cettte annonce
            return $this->redirectToRoute('oc_platform_view', array('id' => $advert->getId()));
        }
        // Si on n'est pas en POST, alors on affiche le formulaire
        return $this->render('Perso/add.html.twig', array('advert' => $advert));
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


        // Le render ne change pas, on passait avant un tableau, maintenant un objet
        return $this->render('Perso/view.html.twig', array(
            'advert' => $advert,
            'listAvis' => $listAvis
        ));


    }

    public function deleteAction($id){
        $em = $this->getDoctrine()->getManager();

        // On récupère l'annonce $id

        $advert = $em->getRepository('App\Entity\Advert')->find($id);

        if (null === $advert) {
            throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
        }

        $listAvis = $em
            ->getRepository('App\Entity\Avis')
            ->findBy(array('advert' => $advert));

        foreach ($listAvis as $avis)
        {
            $em->remove($avis);
        }
        $em->remove($advert);

        $em->flush();

        return $this->render('Perso/delete.html.twig');

    }

    public function menuAction(Request $request)
    {

        $repository = $this
        ->getDoctrine()
        ->getManager()
        ->getRepository('App\Entity\Advert');

        $listAdverts = $repository->findBy(array(),array('dateajout' => 'desc'),5,0);


        return $this->render('Perso/menu.html.twig', array(
            // Tout l'intérêt est ici : le contrôleur passe
            // les variables nécessaires au template !
            'listAdverts' => $listAdverts
        ));
    }

}