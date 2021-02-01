<?php declare(strict_types=1);
/******************************************************************************/
/*                                   COMPLICE                                 */
/*     Auteur: Tristan Fleury - https://github.com/viduc - viduc@mail.fr      */
/*                              Licence: Apache-2.0                           */
/******************************************************************************/

namespace App\Interfaces;

use App\Exception\CompliceException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

interface ConfigurationInterface
{
    /**
     * Créer le fichier de config si il n'existe pas
     * @throws CompliceException
     * @test testCreerLeFichier()
     */
    public function creerLeFichier() : void;

    /**
     * Enregistre le tableau de configuration
     * @param array $config - le tableau complet de configuration
     */
    public function enregistrer(string $param, string $value) : void;

    /**
     * Lit le fichier de configuration
     * @return array
     * @test testLire()
     */
    public function lire() : array;

    /**
     * Récupère un paramètre par son nom
     * @param string $param - le nom du paramètre
     * @return string
     * @throws GepadbalException
     * @test testRecupererParametre()
     */
    public function recupererParametre(string $param): string;

    /**
     * Change la locale pour la langue
     * @param string $locale
     * @param Request $request
     * @return RedirectResponse
     * @codeCoverageIgnore
     */
    public function changeLocale(
        string $locale,
        Request $request
    ) : RedirectResponse;
}
