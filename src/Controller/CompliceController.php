<?php declare(strict_types=1);
/******************************************************************************/
/*                                   COMPLICE                                 */
/*     Auteur: Tristan Fleury - https://github.com/viduc - viduc@mail.fr      */
/*                              Licence: Apache-2.0                           */
/******************************************************************************/

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CompliceController extends AbstractController
{
    public function index(): Response
    {
        return $this->render('complice/index.html.twig', [
            'controller_name' => 'CompliceController',
        ]);
    }
}
