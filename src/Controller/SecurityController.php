<?php declare(strict_types=1);
/******************************************************************************/
/*                                   COMPLICE                                 */
/*     Auteur: Tristan Fleury - https://github.com/viduc - viduc@mail.fr      */
/*                              Licence: Apache-2.0                           */
/******************************************************************************/

namespace App\Controller;

use App\Entity\User;
use App\Exception\CompliceException;
use App\Security\Authentification;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Contracts\Translation\TranslatorInterface;

class SecurityController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private TranslatorInterface $translator;
    private Authentification $authentification;

    /**
     * SecurityController constructor.
     * @param EntityManagerInterface $entityManager
     * @param TranslatorInterface $translator
     * @codeCoverageIgnore
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        TranslatorInterface $translator,
        Authentification $authentification
    ) {
        $this->entityManager = $entityManager;
        $this->translator = $translator;
        $this->authentification = $authentification;
    }

    /**
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     * @codeCoverageIgnore
     */
    final public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render(
            'security/login.html.twig',
            ['last_username' => $lastUsername,'error' => $error]
        );
    }

    /**
     * @codeCoverageIgnore
     */
    final public function logout() : void
    {
        throw new \LogicException(
            'This method can be blank - it will be intercepted by the logout
             key on your firewall.'
        );
    }

    /**
     * Formulaire pour changer le mot de passe via un token
     * @param Request $request
     * @param string|null $token
     * @return Response
     */
    final public function changerMotDePasseToken(
        Request $request,
        string $token = null
    ): Response {
        if ($token !== null) {
            $repository = $this->entityManager->getRepository(User::class);
            try {
                $user = $repository->findByToken($token);
                $form = $this->formChangerMotDePasse();
                $form->handleRequest($request);
                if ($form->isSubmitted() && $form->isValid()) {
                    $user->setPassword(
                        $this->authentification->crypterMotDePasse(
                            $form->getData()['password']
                    ));
                    $user->setToken(null);
                    $this->entityManager->persist($user);
                    $this->entityManager->flush();
                    $this->addFlash(
                        'success',
                        $this->translator->trans('securite.password.success')
                    );
                    return $this->redirectToRoute('index');
                }
                return $this->render(
                    'security/motdepasse.html.twig',
                    ['form' => $form->createView()]
                );
            } catch (CompliceException $ex) {
            }
        }
        $this->addFlash(
            'danger',
            $this->translator->trans('securite.token.invalide')
        );
        return $this->redirectToRoute('app_login');
    }

    /**
     * Créé le formulaire pour le changement de mot de passe
     * @return FormInterface
     */
    private function formChangerMotDePasse() : FormInterface
    {
        return $this->createFormBuilder()
             ->add('password', RepeatedType::class, [
                 'constraints' => new Length(['min' => 3]),
                 'type' => PasswordType::class,
                 'invalid_message' => $this->translator->trans(
                     'securite.password.invalid_message'
                 ),
                 'options' => [
                     'attr' => ['class' => 'form-control password-field']
                 ],
                 'first_options'  => ['label' => $this->translator->trans(
                     'securite.password.password1'
                 )],
                 'second_options' => ['label' => $this->translator->trans(
                     'securite.password.password2'
                 )]
             ])
             ->add('save', SubmitType::class, [
                 'attr' => ['class' => 'btn btn-lg btn-primary'],
                 'label' => $this->translator->trans(
                     'utilisateur.edit.save'
                 )
             ])
             ->getForm();
    }
}
