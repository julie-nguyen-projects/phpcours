<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Produit;
use AppBundle\Services\FruitsLegumesServices;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{

    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
        ]);
    }

    public function afficherToutAction()
    {
        // Pas possible de faire un constructeur car container n'est pas instancié dans le contrôleur
        $prodServices = $this->container->get('app.fruitslegumesservices');
        $listFruitsLegumes = $prodServices->getAllProducts();

        $session = $this->container->get('session');

        if ($session->get('prod_session') != null) {
            return $this->render('AppBundle:default:list.html.twig', array(
                'listeFruitsLegumes' => $listFruitsLegumes,
                'prod_sess' => $session->get('prod_session'),
            ));
        } else {
            return $this->render('AppBundle:default:list.html.twig', array(
                'listeFruitsLegumes' => $listFruitsLegumes,
            ));
        }
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function afficherProduitAction(Request $request, $id)
    {
        /** @var FruitsLegumesServices $prodServices */
        $prodServices = $this->container->get('app.fruitslegumesservices');
        $produit = $prodServices->getById($id);

        $session = $this->container->get('session');
        $session->set('prod_session', $produit);
        return $this->render('AppBundle:default:product.html.twig', array(
            'produit' => $produit,
        ));
    }






}
