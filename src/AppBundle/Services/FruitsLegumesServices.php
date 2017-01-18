<?php

namespace AppBundle\Services;


use Doctrine\ORM\EntityManager;

class FruitsLegumesServices
{
    private $em;

    private $repository;

    /**
     * @InjectParams
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->repository = $this->em ->getRepository('AppBundle:Produit');
    }

    function getSaison($mois){
        if ($mois < 3) {
            return 'Hiver';
        } elseif ($mois > 3 && $mois < 6) {
            return 'Printemps';
        } elseif ($mois > 6 && $mois < 9) {
            return 'Été';
        } else {
            return 'Automne';
        }
    }

    /**
     * 3 méthodes , tout rechercher, recherche par id, et save(entité) delete(entité)
     */

    public function getAllProducts()
    {
        $produits = $this->repository -> findAll();
        return $produits;
    }

    public function getById($id)
    {
        $produit = $this->repository -> find($id);
        return $produit;
    }

    public function save($produit)
    {
        if (!$produit->getId()) {
            $this->em -> persist($produit);
        }
        $this->em -> flush();
    }

    public function delete($id)
    {
        $produit = $this->getById($id);
        if (!$produit) {
            return false;
        } else {
            $this -> em -> remove($produit);
            $this -> em -> flush();
            return true;
        }
    }




}