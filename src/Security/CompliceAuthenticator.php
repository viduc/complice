<?php declare(strict_types=1);
/******************************************************************************/
/*                                   COMPLICE                                 */
/*     Auteur: Tristan Fleury - https://github.com/viduc - viduc@mail.fr      */
/*                              Licence: Apache-2.0                           */
/******************************************************************************/

namespace App\Security;

use App\Entity\User;
use App\Exception\CompliceException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class CompliceAuthenticator extends AbstractFormLoginAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';

    private EntityManagerInterface $entityManager;
    private UrlGeneratorInterface $urlGenerator;
    private CsrfTokenManagerInterface $csrfTokenManager;
    private AuthentificationInterface $authentification;

    public function __construct(
        EntityManagerInterface $entityManager,
        UrlGeneratorInterface $urlGenerator,
        CsrfTokenManagerInterface $csrfTokenManager
    ) {
        $this->entityManager = $entityManager;
        $this->urlGenerator = $urlGenerator;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->authentification = new Authentification();
    }

    /**
     * @param Request $request
     * @return bool
     * @test supports()
     */
    final public function supports(Request $request) : bool
    {
        return self::LOGIN_ROUTE === $request->attributes->get('_route')
            && $request->isMethod('POST');
    }

    /**
     * @param Request $request
     * @return array
     * @codeCoverageIgnore
     */
    final public function getCredentials(Request $request) : array
    {
        $credentials = [
            'username' => $request->request->get('username'),
            'password' => $request->request->get('password'),
            'csrf_token' => $request->request->get('_csrf_token'),
        ];
        $request->getSession()->set(
            Security::LAST_USERNAME,
            $credentials['username']
        );

        return $credentials;
    }

    /**
     * @param mixed $credentials
     * @param UserProviderInterface $userProvider
     * @return UserInterface
     * @throws CompliceException
     * @test getUserInvalideToken - getUserPasUtilisateurTrouve
     * @test getUserUtilisateurTrouve
     */
    final public function getUser(
        $credentials,
        UserProviderInterface $userProvider
    ) : UserInterface {
        $token = new CsrfToken('authenticate', $credentials['csrf_token']);
        if (!$this->csrfTokenManager->isTokenValid($token)) {
            throw new CompliceException('Token Csrf invalide', 101);
        }
        $user = $this->entityManager->getRepository(User::class)->findOneBy(
            ['username' => $credentials['username']]
        );

        if (!$user) {
            throw new CompliceException('Utilisateur non trouvé', 102);
        }

        return $user;
    }

    /**
     * Vérifie le mot de passe
     * @param mixed $credentials
     * @param UserInterface $user
     * @return bool
     * @codeCoverageIgnore
     */
    final public function checkCredentials(
        $credentials,
        UserInterface $user
    ) : bool {
        return $this->authentification->verifieLeMotDePasse(
            $credentials['password'],
            $user->getPassword()
        );
    }

    /**
     * @param Request $request
     * @param TokenInterface $token
     * @param string $providerKey
     * @return RedirectResponse
     * @codeCoverageIgnore
     */
    final public function onAuthenticationSuccess(
        Request $request,
        TokenInterface $token,
        string $providerKey
    ) : RedirectResponse{
        return new RedirectResponse('index');
    }

    /**
     * @return string
     * @codeCoverageIgnore
     */
    final protected function getLoginUrl() : string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
