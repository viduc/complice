<?php declare(strict_types=1);
/******************************************************************************/
/*                                   COMPLICE                                 */
/*     Auteur: Tristan Fleury - https://github.com/viduc - viduc@mail.fr      */
/*                              Licence: Apache-2.0                           */
/******************************************************************************/

namespace App\Controller;

use App\Entity\User;
use App\Exception\CompliceException;
use App\Form\UserType;
use App\Interfaces\utilisateurInterface;
use App\Security\Authentification;
use App\Security\CompliceAuthenticator;
use DateTime;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;

class UtilisateurController extends AbstractController implements utilisateurInterface
{
    private EntityManagerInterface $entityManager;
    private Authentification $auth;

    final public function __construct(
        EntityManagerInterface $em,
        Authentification $authentification
    ) {
        $this->entityManager = $em;
        $this->auth = $authentification;
    }
    /**
     * @return Response
     */
    final public function index(): Response
    {
        $users = $this->getDoctrine()
                      ->getRepository(User::class)
                      ->findAll();
        return $this->render('utilisateur/index.html.twig', [
            'users' => $users
        ]);
    }

    final public function creer(
        Request $request,
        TranslatorInterface $translator
    ) : Response {
        $user = new User();
        $roles = array(
            $translator->trans('roles.ROLE_USER') => 'ROLE_USER',
            $translator->trans('roles.ROLE_ADMIN') => 'ROLE_ADMIN'
        );
        $form = $this->createForm(
            UserType::class,
            $user,
            array('transaltor' => $translator, 'roles' => $roles)
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($this->auth->creerUnMotDePasseAleatoire());
            $user->setCreeLe(new DateTime('NOW'));
            $user->setActif(true);
            $user->setRoles($request->get('user')['roles']);
            $user->setToken(bin2hex(openssl_random_pseudo_bytes(30)));
            $this->entityManager->persist($this->genererLogin($user));
            $this->entityManager->flush();
        }
        return $this->render('utilisateur/creer.html.twig', [
            'user' => $user,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param User $user
     * @return User
     * @throws \Exception
     */
    private function genererLogin(User $user) : User
    {
        $nom = strtolower($this->enleverCaracteresSpeciaux($user->getNom()));
        $prenom = strtolower($this->enleverCaracteresSpeciaux($user->getPrenom()));
        $i = 1;
        while (true) {
            if (strlen($prenom) >= $i) {
                $login = $nom . substr($prenom, 0, $i);
                $i++;
            } else {
                $login = $nom . $prenom . random_int(1, 999);
            }
            if (!$this->entityManager->getRepository(
                User::class
            )->findByUsername($login)) {
                $user->setUsername($login);
                return $user;
            }
        }
    }

    /**
     * Supprime les caractères spéciaux d'une chaine
     * @param String $str
     * @param string $charset
     * @return string|string[]|null
     */
    private function enleverCaracteresSpeciaux(
        string $str,
        string $charset = 'utf-8'
    ) : string {
        $str = htmlentities($str, ENT_NOQUOTES, $charset);
        $str = preg_replace('#&([A-za-z])(?:acute|cedil|caron|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $str);
        $str = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $str);
        $str = preg_replace('#&[^;]+;#', '', $str);

        return $str;
    }
}
