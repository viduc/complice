<?php declare(strict_types=1);
/******************************************************************************/
/*                                   COMPLICE                                 */
/*     Auteur: Tristan Fleury - https://github.com/viduc - viduc@mail.fr      */
/*                              Licence: Apache-2.0                           */
/******************************************************************************/

namespace App\Tests\Security;

use App\Security\Authentification;
use PHPUnit\Framework\TestCase;

class AuthentificationTest extends TestCase
{
    private Authentification $authentification;

    final public function setUp(): void
    {
        parent::setUp();
        $this->authentification = new Authentification();
    }

    /**
     * @return void
     * @test
     */
    final public function crypterMotDePasse() : void
    {echo $this->authentification->crypterMotDePasse('test');
        self::assertIsString($this->authentification->crypterMotDePasse('test'));
        self::assertStringContainsString(
            '$2y',
            $this->authentification->crypterMotDePasse('test')
        );
    }

    /**
     * @test
     */
    final public function verifieLeMotDePasse() : void
    {
        self::assertTrue(
            $this->authentification->verifieLeMotDePasse(
                'test',
                '$2y$10$hP4AU9N96Q0oUNwuFZ4bvepi2ZQdN78kcVnn02swV2NUYaY47k2lC'
            )
        );
        self::assertFalse(
            $this->authentification->verifieLeMotDePasse(
                'test',
                '$2y$10$hP4AU9N96Q0oUNwuFZ4'
            )
        );
    }
}