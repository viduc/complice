<?php declare(strict_types=1);
/******************************************************************************/
/*                                   COMPLICE                                 */
/*     Auteur: Tristan Fleury - https://github.com/viduc - viduc@mail.fr      */
/*                              Licence: Apache-2.0                           */
/******************************************************************************/

namespace App\Exception;

use Exception;

/**
 * Class CompliceException
 * @package App\Exception
 * [0->99]
 * [100->114] : Authentification
 *      100 => UserNonSupporte
 *      101 => token csrf invalide
 *      102 => utilisateur non trouvé
 *      103 => Une erreur s\'est produite lors de la génération du mot de passe par défaut
 *      104 => Aucun token trouvé
 * [115->129] : Class User
 *      115 => Chaque rôle doit commencer par 'ROLE_'
 */
class CompliceException extends Exception
{
}