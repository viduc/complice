<?php declare(strict_types=1);
/******************************************************************************/
/*                                   COMPLICE                                 */
/*     Auteur: Tristan Fleury - https://github.com/viduc - viduc@mail.fr      */
/*                              Licence: Apache-2.0                           */
/******************************************************************************/

namespace App\Controller;

use App\Interfaces\ConfigurationInterface;
use App\Interfaces\InstalleurInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;

class InstalleurController extends AbstractController implements InstalleurInterface
{
    private ConfigurationInterface $configuration;

    public function __construct(KernelInterface $kernel)
    {
        $this->configuration = new ConfigurationController($kernel);
    }

    /**
     * Méthode d'entrée pour la partie installation
     * @return Response
     * @codeCoverageIgnore
     */
    final public function index(): Response
    {
        return $this->render(
            'installeur/index.html.twig',
            [
                'configuration' => $this->configuration->lire()['complice']
            ]
        );
    }

    /**
     * Enregsitre la configuration
     * Méthode utilisée en ajax
     * @param Request $request
     * @return JsonResponse
     * @codeCoverageIgnore
     */
    final public function enregistrerConfiguration(Request $request) : JsonResponse
    {
        $configs = $request->get('configs');
        foreach ($configs as $config) {
            $this->configuration->enregistrer($config[0], $config[1]);
        }

        return new JsonResponse('ok');
    }
}
