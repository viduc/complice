<?php declare(strict_types=1);
/******************************************************************************/
/*                                   COMPLICE                                 */
/*     Auteur: Tristan Fleury - https://github.com/viduc - viduc@mail.fr      */
/*                              Licence: Apache-2.0                           */
/******************************************************************************/

namespace App\Security;

class Authentification implements AuthentificationInterface
{
    /**
     * Crypte le mot de passe
     * @param string $motDePasse
     * @return string
     * Crypte un mot de passe
     * @test crypterMotDePasse()
     */
    final public function crypterMotDePasse(string $motDePasse) : string
    {
        return password_hash($motDePasse, PASSWORD_DEFAULT);
    }

    /**
     * Vérifie le mot de passe
     * @param string $motDePasse
     * @param string $hash
     * @return bool
     * @test verifieLeMotDePasse()
     */
    final public function verifieLeMotDePasse(string $motDePasse, string $hash)
    : bool {
        return password_verify($motDePasse, $hash);
    }
}