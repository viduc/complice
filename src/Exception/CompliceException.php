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
 * [100->150] : Authentification
 *      100 => UserNonSupporte
 *      101 => token csrf invalide
 *      102 => utilisateur non trouvé
 */
class CompliceException extends Exception
{
}