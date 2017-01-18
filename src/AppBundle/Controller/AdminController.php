<?php
/**
 * Created by PhpStorm.
 * User: Nea
 * Date: 13/01/2017
 * Time: 14:30
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Produit;
use AppBundle\Form\ProductType;
use AppBundle\Services\FruitsLegumesServices;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AdminController extends Controller
{

    public function afficherToutAction()
    {
        /** @var FruitsLegumesServices $prodServices */
        // Pas possible de faire un constructeur car container n'est pas instancié dans le contrôleur
        $prodServices = $this->container->get('app.fruitslegumesservices');
        $listFruitsLegumes = $prodServices->getAllProducts();

        return $this->render('AppBundle:admin:listadmin.html.twig', array(
            'listeFruitsLegumes' => $listFruitsLegumes,
        ));
    }

    public function nouveauProduitAction(Request $request)
    {
        /** @var FruitsLegumesServices $prodServices */
        $produit = new Produit();
        $form = $this->createForm(ProductType::class, $produit);
        $form -> handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // On récupère ce que le formulaire a injecté dans son objet
            $file = $produit->getImage();

            // Génère un nom unique pour le fichier (facultatif)
            $fileName = md5(uniqid()).'.'.$file->guessExtension();

            // On déplace le fichier : 'kernel.root_dir' est souvent utilisé pour se localiser
            // dans l'arborescence : il est dans app/config
            $file->move(
                $this->getParameter('kernel.root_dir').'/../web/images',
                $fileName
            );

            // On ne stocke pas le fichier comme attribut de Produit mais juste le nom de l'image
            $produit->setImage($fileName);

            // Pas possible de faire un constructeur car container n'est pas instancié dans le contrôleur
            $prodServices = $this->container->get('app.fruitslegumesservices');
            $prodServices -> save($produit);
            $this->addFlash(
                'notice',
                'Produit ajouté!'
            );
            return $this->redirectToRoute('all_products_admin');
        }

        return $this->render('AppBundle:admin:addproduct.html.twig', [
            'form' => $form -> createView()
        ]);
    }

    public function deleteAction(Request $request)
    {
        /** @var FruitsLegumesServices $prodServices */
        // Pas possible de faire un constructeur car container n'est pas instancié dans le contrôleur
        $prodServices = $this->container->get('app.fruitslegumesservices');
        if ($prodServices -> delete($request->get('id'))) {
            return $this -> redirectToRoute('all_products_admin');
        } else {
            throw $this->createNotFoundException('Pas de produit');
        }
    }

    public function updateAction(Request $request)
    {
        // Pas possible de faire un constructeur car container n'est pas instancié dans le contrôleur
        /** @var FruitsLegumesServices $prodServices */
        $prodServices = $this->container->get('app.fruitslegumesservices');

        $produit = $prodServices->getById($request->get('id'));
        $form = $this->createForm(ProductType::class, $produit);

        // Stockage temporaire de l'image déjà présente
        $imageTemp = $produit->getImage();

        $form -> handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($produit->getImage() != null) {
                // On récupère ce que le formulaire a injecté dans son objet
                $file = $produit->getImage();

                // Génère un nom unique pour le fichier (facultatif)
                $fileName = md5(uniqid()).'.'.$file->guessExtension();

                // On déplace le fichier : 'kernel.root_dir' est souvent utilisé pour se localiser
                // dans l'arborescence : il est dans app/config
                $file->move(
                    $this->getParameter('kernel.root_dir').'/../web/images',
                    $fileName
                );

                // On ne stocke pas le fichier comme attribut de Produit mais juste le nom de l'image
                $produit->setImage($fileName);
            } else {
                $produit->setImage($imageTemp);
            }
            $prodServices -> save($produit);
            $this->addFlash(
                'notice',
                'Produit modifié!'
            );
            return $this->redirectToRoute('all_products_admin');
        }

        return $this->render('AppBundle:admin:addproduct.html.twig', [
            'form' => $form -> createView()
        ]);

    }

}