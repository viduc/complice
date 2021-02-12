<?php declare(strict_types=1);
/******************************************************************************/
/*                                   COMPLICE                                 */
/*     Auteur: Tristan Fleury - https://github.com/viduc - viduc@mail.fr      */
/*                              Licence: Apache-2.0                           */
/******************************************************************************/

namespace App\EventSubscriber;

use Symfony\Bridge\Twig\Extension\AssetExtension;
use Symfony\Bundle\TwigBundle\DependencyInjection\TwigExtension;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

class LocaleSubscriber implements EventSubscriberInterface
{
    // Langue par défaut
    private string $defaultLocale;

    /**
     * @var Environment
     */
    private $twig;

    /**
     * LocaleSubscriber constructor.
     * @param string $defaultLocale
     * @param Environment $twig
     * @codeCoverageIgnore
     */
    public function __construct(
        Environment $twig,
        string $defaultLocale = 'fr'
    ) {
        $this->twig = $twig;
        $this->defaultLocale = $defaultLocale;
    }

    /**
     * @param RequestEvent $event
     * @codeCoverageIgnore
     */
    final public function onKernelRequest(RequestEvent $event) : void
    {
        $request = $event->getRequest();
        if (!$request->hasPreviousSession()) {
            return;
        }
        // On vérifie si la langue est passée en paramètre de l'URL
        if ($locale = $request->query->get('_locale')) {
            $request->setLocale($locale);
        } else {
            // Sinon on utilise celle de la session
            $request->setLocale(
                $request->getSession()->get('_locale', $this->defaultLocale)
            );
        }
        $locale = 'French';
        if ($request->getLocale() === 'en') {
            $locale = 'English';
        }
        $this->twig->addGlobal('locale', $locale);
    }

    /**
     * @return array|string[]
     * @codeCoverageIgnore
     */
    final public static function getSubscribedEvents() : array
    {
        return [
            // On doit définir une priorité élevée
            KernelEvents::REQUEST => [['onKernelRequest', 20]],
        ];
    }
}
