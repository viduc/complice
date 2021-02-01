<?php declare(strict_types=1);
/******************************************************************************/
/*                                   COMPLICE                                 */
/*     Auteur: Tristan Fleury - https://github.com/viduc - viduc@mail.fr      */
/*                              Licence: Apache-2.0                           */
/******************************************************************************/

namespace App\Tests\Security;

use App\Exception\CompliceException;
use App\Security\CompliceAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\UserProvider\UserProviderFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class CompliceAuthenticatorTest extends TestCase
{
    private EntityManagerInterface $entityManager;
    private UrlGeneratorInterface $urlGenerator;
    private CsrfTokenManagerInterface $csrfTokenManager;
    private UserProviderInterface $userProvider;
    private CompliceAuthenticator $ca;
    private ObjectRepository $repository;
    private UserInterface $user;

    final protected function setUp(): void
    {
        parent::setUp();
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->urlGenerator = $this->createMock(UrlGeneratorInterface::class);
        $this->csrfTokenManager = $this->createMock(CsrfTokenManagerInterface::class);
        $this->ca = new CompliceAuthenticator(
            $this->entityManager,
            $this->urlGenerator,
            $this->csrfTokenManager
        );
        $this->userProvider = $this->createMock(UserProviderInterface::class);
        $this->repository = $this->createMock(ObjectRepository::class);
        $this->user = $this->createMock(UserInterface::class);
    }

    /**
     * @test
     */
    final public function supports() : void
    {
        $request = new Request();
        $request->setMethod('POST');

        $attribute = new Attributes();
        $request->attributes = $attribute;
        self::assertTrue($this->ca->supports($request));
    }

    /**
     * @test
     */
    final public function getUserInvalideToken() : void
    {
        $this->csrfTokenManager->method('isTokenValid')->willReturn(false);
        $credentials['csrf_token'] = 'test';
        try {
            $this->ca->getUser($credentials, $this->userProvider);
        } catch (CompliceException $ex) {
            self::assertEquals('Token Csrf invalide', $ex->getMessage());
            self::assertEquals(101, $ex->getCode());
        }
    }

    /**
     * @test
     */
    final public function getUserPasUtilisateurTrouve() : void
    {
        $this->csrfTokenManager->method('isTokenValid')->willReturn(true);
        $this->repository->method('findOneBy')->willReturn(null);
        $this->entityManager->method('getRepository')->willReturn($this->repository);
        $credentials['csrf_token'] = 'test';
        $credentials['username'] = 'test';
        try {
            $this->ca->getUser($credentials, $this->userProvider);
        } catch (CompliceException $ex) {
            self::assertEquals('Utilisateur non trouvé', $ex->getMessage());
            self::assertEquals(102, $ex->getCode());
        }
    }

    /**
     * @test
     */
    final public function getUserUtilisateurTrouve() : void
    {
        $this->csrfTokenManager->method('isTokenValid')->willReturn(true);
        $this->repository->method('findOneBy')->willReturn($this->user);
        $this->entityManager->method('getRepository')->willReturn($this->repository);
        $credentials['csrf_token'] = 'test';
        $credentials['username'] = 'test';
        self::assertInstanceOf(
            UserInterface::class,
            $this->ca->getUser($credentials, $this->userProvider)
        );
    }
}

class Attributes
{
    final public function get(string $get) : string
    {
        return 'app_login';
    }
}