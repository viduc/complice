<?php declare(strict_types=1);
/******************************************************************************/
/*                                   COMPLICE                                 */
/*     Auteur: Tristan Fleury - https://github.com/viduc - viduc@mail.fr      */
/*                              Licence: Apache-2.0                           */
/******************************************************************************/

namespace App\Security;

interface AuthentificationInterface
{
    /**
     * Crypte le mot de passe
     * @param string $motDePasse
     * @return string
     * Crypte un mot de passe
     * @test crypterMotDePasse()
     */
    public function crypterMotDePasse(string $motDePasse) : string;

    /**
     * Vérifie le mot de passe
     * @param string $motDePasse
     * @param string $hash
     * @return bool
     * @test verifieLeMotDePasse()
     */
    public function verifieLeMotDePasse(string $motDePasse, string $hash) : bool;
}