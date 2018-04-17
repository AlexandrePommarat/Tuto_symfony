<?php
namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CategoryController extends Controller
{
    public function addAction(Request $request)
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            // Sinon on déclenche une exception « Accès interdit »
            throw new AccessDeniedException('Accès limité aux administrateurs.');
        }

        $category = new Category();



        // On crée le FormBuilder grâce au service form factory

        $form  = $this->get('form.factory')->create(CategoryType::class, $category);





        if($request->isMethod('POST')) {

            $form->handleRequest($request);

            if ($form->isValid())  {
                $em = $this->getDoctrine()->getManager();
                $em->persist($category);
                $em->flush();

                $request->getSession()->getFlashBag()->add('notice', 'Categorie bien ajouté.');

                // On redirige vers la page de visualisation de l'annonce nouvellement créée
                return $this->redirectToRoute('app_work_add');

            }
        }

        return $this->render('Category/add.html.twig', array(
            'form' => $form->createView(),
        ));

    }
}