<?php declare(strict_types=1);
/******************************************************************************/
/*                                   COMPLICE                                 */
/*     Auteur: Tristan Fleury - https://github.com/viduc - viduc@mail.fr      */
/*                              Licence: Apache-2.0                           */
/******************************************************************************/

namespace App\Tests\Controller;

use App\Controller\UtilisateurController;
use App\Exception\CompliceException;
use PHPUnit\Framework\TestCase;

class UtilisateurControllerTest extends TestCase
{
    private UtilisateurController $utilisateur;

    final protected function setUp() : void
    {
        parent::setUp();
        $this->utilisateur = new UtilisateurController();
    }

    final public function tearDown(): void
    {
    }

}
