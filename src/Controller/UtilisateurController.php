<?php declare(strict_types=1);
/******************************************************************************/
/*                                   COMPLICE                                 */
/*     Auteur: Tristan Fleury - https://github.com/viduc - viduc@mail.fr      */
/*                              Licence: Apache-2.0                           */
/******************************************************************************/

namespace App\Controller;

use App\Entity\User;
use App\Interfaces\utilisateurInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class UtilisateurController extends AbstractController implements utilisateurInterface
{
    final public function index(): Response
    {
        $users = $this->getDoctrine()
                      ->getRepository(User::class)
                      ->findAll();
        return $this->render('utilisateur/index.html.twig', [
            'users' => $users
        ]);
    }
}
