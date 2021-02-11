<?php declare(strict_types=1);
/******************************************************************************/
/*                                   COMPLICE                                 */
/*     Auteur: Tristan Fleury - https://github.com/viduc - viduc@mail.fr      */
/*                              Licence: Apache-2.0                           */
/******************************************************************************/

namespace App\Security;

use App\Exception\CompliceException;

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


    /**
     * @return string
     * @throws CompliceException
     */
    final public function creerUnMotDePasseAleatoire() : string
    {
        try {
            return $this->crypterMotDePasse(md5(uniqid('', true)));
        } catch (\Exception $e) {
            throw new CompliceException(
                'Une erreur s\'est produite lors de la génération du mot de
                passe par défaut',
                103
            );
        }
    }
}