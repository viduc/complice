<?php declare(strict_types=1);
/******************************************************************************/
/*                                   COMPLICE                                 */
/*     Auteur: Tristan Fleury - https://github.com/viduc - viduc@mail.fr      */
/*                              Licence: Apache-2.0                           */
/******************************************************************************/

namespace App\Interfaces;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface InstalleurInterface
{
    /**
     * Méthode d'entrée pour la partie installation
     * @return Response
     * @codeCoverageIgnore
     */
    public function index(): Response;

    /**
     * Enregsitre la configuration
     * Méthode utilisée en ajax
     * @param Request $request
     * @return JsonResponse
     * @codeCoverageIgnore
     */
    public function enregistrerConfiguration(Request $request) : JsonResponse;
}
