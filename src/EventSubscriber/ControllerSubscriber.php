<?php declare(strict_types=1);
/******************************************************************************/
/*                          GEPADBAL - Librairie                              */
/* Auteur: Tristan FLeury - tristan.fleury@univ-grenoble-alpes.fr             */
/* Universite Grenoble Alpes - https://www.univ-grenoble-alpes.fr/            */
/* Service DGDSI - DANA - 2021                                                */
/******************************************************************************/

namespace App\EventSubscriber;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Twig\Environment;

class ControllerSubscriber implements EventSubscriberInterface
{
    /**
     * @var Environment
     */
    private $twig;

    /**
     * ControllerSubscriber constructor.
     * @param Environment $twig
     * @codeCoverageIgnore
     */
    public function __construct( Environment $twig) {
        $this->twig     = $twig;
    }

    /**
     * @param ControllerEvent $event
     * @codeCoverageIgnore
     */
    final public function onKernelController(ControllerEvent $event) : void
    {
        $controller = $event->getController();
        if (is_array($controller)) {
            $controller = $controller[0];
        }

        $this->twig->addGlobal(
            'titre','test'
            //$this->filtrerLeNomDuController($controller)
        );
    }

    /**
     * @return array|string[]
     * @codeCoverageIgnore
     */
    public static function getSubscribedEvents() : array
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }

    /**
     * Filtre le nom du controller
     * @param AbstractController $controller
     * @return string
     * @codeCoverageIgnore
     */
    final public function filtrerLeNomDuController(
        AbstractController $controller
    ) : string {
        $class = get_class ($controller);
        return str_replace(['App\Controller', 'Controller', '\\'], '', $class);
    }
}