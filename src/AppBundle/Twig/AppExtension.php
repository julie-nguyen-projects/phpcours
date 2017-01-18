<?php
/**
 * Created by PhpStorm.
 * User: Nea
 * Date: 12/01/2017
 * Time: 14:01
 */

namespace AppBundle\Twig;


class AppExtension extends \Twig_Extension
{
    private $fruitsLegumesService;

    public function __construct(\AppBundle\Services\FruitsLegumesServices $service)
    {
        $this->fruitsLegumesService = $service;
    }

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('saison', array($this, 'getSaison')),
        );
    }

    public function getSaison($mois)
    {
        $mois = $this->fruitsLegumesService->getSaison($mois);
        return $mois;
    }

    public function getName()
    {
        return 'app_extension';
    }

    /**
     * @return mixed
     */
    public function getFruitsLegumesService()
    {
        return $this->fruitsLegumesService;
    }

    /**
     * @param mixed $fruitsLegumesService
     */
    public function setFruitsLegumesService($fruitsLegumesService)
    {
        $this->fruitsLegumesService = $fruitsLegumesService;
    }


}