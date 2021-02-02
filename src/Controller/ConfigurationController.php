<?php declare(strict_types=1);
/******************************************************************************/
/*                                   COMPLICE                                 */
/*     Auteur: Tristan Fleury - https://github.com/viduc - viduc@mail.fr      */
/*                              Licence: Apache-2.0                           */
/******************************************************************************/

namespace App\Controller;

use App\Exception\CompliceException;
use App\Interfaces\ConfigurationInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Yaml\Yaml;

class ConfigurationController extends AbstractController implements ConfigurationInterface
{
    protected KernelInterface $kernel;
    private Filesystem $filesystem;
    private string $fichier;

    /**
     * ConfigurationController constructor.
     * @throws CompliceException
     * @codeCoverageIgnore
     * @param KernelInterface $kernel
     */
    final public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
        $this->filesystem = new Filesystem();
        $this->fichier = $this->kernel->getProjectDir();
        $this->fichier .= '/config/complice.yaml';
        $this->creerLeFichier();
    }

    /**
     * Créer le fichier de config si il n'existe pas
     * @throws CompliceException
     * @test testCreerLeFichier()
     */
    final public function creerLeFichier(): void
    {
        if (!$this->filesystem->exists($this->fichier)) {
            $config =  str_replace(
                'Controller',
                'Ressources/config/complice.yaml',
                __DIR__);
            try {
                $this->filesystem->copy($config, $this->fichier);
            // @codeCoverageIgnoreStart
            } catch (FileNotFoundException | IOException $ex) {
                if (strpos($ex->getMessage(), 'file does not exist') !== false) {
                    throw new CompliceException(
                        'Le fichier model de configuration n\'est pas présent'
                    );
                }
                if (strpos($ex->getMessage(), 'could not be opened for reading') !== false) {
                    throw new CompliceException(
                        'Le fichier model de configuration n\'est pas valide'
                    );
                }
                if (strpos($ex->getMessage(), 'could not be opened for writing') !== false) {
                    throw new CompliceException(
                        'Le fichier destination de configuration n\'est pas valide'
                    );
                }
            }// @codeCoverageIgnoreEnd
        }
    }

    /**
     * Enregistre le tableau de configuration
     * @param string $param
     * @param string $value
     */
    final public function enregistrer(string $param, string $value) : void
    {
        $config = $this->lire();
        $config['complice'][$param] = $value;
        $yaml = Yaml::dump($config);
        file_put_contents($this->fichier, $yaml);
    }

    /**
     * Lit le fichier de configuration
     * @return array
     * @test testLire()
     */
    final public function lire() : array
    {
        return Yaml::parseFile($this->fichier);
    }

    /**
     * Récupère un paramètre par son nom
     * @param string $param - le nom du paramètre
     * @return string
     * @throws CompliceException
     * @test testRecupererParametre()
     */
    final public function recupererParametre(string $param): string
    {
        $config = $this->lire();
        if (isset($config['complice'][$param])) {
            return $config['complice'][$param];
        }

        throw new CompliceException(
            'le paramètre ' . $param .' n\'est pas accessible'
        );
    }

    /**
     * Change la locale pour la langue
     * @param string $locale
     * @param Request $request
     * @return RedirectResponse
     * @codeCoverageIgnore
     */
    final public function changeLocale(
        string $locale,
        Request $request
    ) : RedirectResponse {
        $request->getSession()->set('_locale', $locale);
        return $this->redirect($request->headers->get('referer'));
    }
}