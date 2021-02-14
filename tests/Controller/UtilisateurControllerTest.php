<?php declare(strict_types=1);
/******************************************************************************/
/*                                   COMPLICE                                 */
/*     Auteur: Tristan Fleury - https://github.com/viduc - viduc@mail.fr      */
/*                              Licence: Apache-2.0                           */
/******************************************************************************/

namespace App\Tests\Controller;

use App\Controller\UtilisateurController;
use App\Entity\User;
use App\Security\AuthentificationInterface;
use Doctrine\ORM\EntityManagerInterface;
use Mockery;
use \Mockery\Adapter\Phpunit\MockeryTestCase;
use Symfony\Contracts\Translation\TranslatorInterface;

class UtilisateurControllerTest extends MockeryTestCase
{
    private UtilisateurController $utilisateur;
    private EntityManagerInterface $em;
    private AuthentificationInterface $auth;
    private TranslatorInterface $translator;
    private \ReflectionClass $reflector;

    final protected function setUp() : void
    {
        parent::setUp();
        $this->em = $this->createMock(EntityManagerInterface::class);
        $this->auth = $this->createMock(AuthentificationInterface::class);
        $this->translator = $this->createMock(TranslatorInterface::class);
        $this->utilisateur = new UtilisateurController(
            $this->em,
            $this->auth,
            $this->translator
        );
        $this->reflector = new \ReflectionClass(UtilisateurController::class);
    }

    final public function tearDown(): void
    {
    }

    /**
     * @test
     * @throws \ReflectionException
     */
    final public function genererLogin() : void
    {

        $methode = $this->reflector->getMethod('genererLogin');
        $methode->setAccessible(true);
        $user = new User();
        $user->setNom('test');
        $user->setPrenom(('test'));
        $repository = Mockery::mock('RepositoryFactory');
        $repository->shouldReceive('findByUsername')->andReturn(null);
        $this->em->method('getRepository')->willReturn($repository);
        $test = $methode->invoke($this->utilisateur, $user);
        self::assertInstanceOf(
            User::class,
            $test
        );
    }

    /**
     * @throws \ReflectionException
     * @test
     */
    final public function enleverCaracteresSpeciaux() : void
    {
        $methode = $this->reflector->getMethod('enleverCaracteresSpeciaux');
        $methode->setAccessible(true);
        $test = $methode->invoke($this->utilisateur, 'ssstildesss');
        self::assertEquals('ssstildesss', $test);
    }

}
