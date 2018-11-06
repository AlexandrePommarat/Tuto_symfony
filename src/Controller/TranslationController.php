<?php
/**
 * Created by PhpStorm.
 * User: apm
 * Date: 06/11/2018
 * Time: 17:18
 */

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


class TranslationController extends Controller
{
    public function translationAction($name){
        return $this->render('Translation/translation.html.twig', array(
            'name' => $name,
        ));
    }
}

