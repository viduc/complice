<?php declare(strict_types=1);
/******************************************************************************/
/*                                   COMPLICE                                 */
/*     Auteur: Tristan Fleury - https://github.com/viduc - viduc@mail.fr      */
/*                              Licence: Apache-2.0                           */
/******************************************************************************/

namespace App\Tests\Controller;

use App\Controller\ConfigurationController;
use App\Exception\CompliceException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Yaml\Yaml;


class ConfigurationControllerTest extends TestCase
{
    private ConfigurationController $configuration;
    private string $dir;

    final protected function setUp() : void
    {
        parent::setUp();
        $this->dir = str_replace('/Controller', '', __DIR__);
        $kernel = $this->createMock(KernelInterface::class);
        $kernel->method('getProjectDir')->willReturn($this->dir);
        $this->configuration = new ConfigurationController($kernel);
        if (file_exists($this->dir . '/config/gepadbal.yaml')) {
            unlink($this->dir . '/config/gepadbal.yaml');
        }
        $this->configuration->creerLeFichier();
    }

    final public function tearDown(): void
    {
    }

    /**
     * @test
     */
    final public function creerLeFichier() : void
    {echo $this->dir . '/config/gepadbal.yaml';
        if (file_exists($this->dir . '/config/gepadbal.yaml')) {
            unlink($this->dir . '/config/gepadbal.yaml');
        }
        self::assertNull($this->configuration->creerLeFichier());
    }

    /**
     * @test
     */
    final public function enregistrer() : void
    {
        self::assertNull($this->configuration->enregistrer('test', 'test'));
    }

    /**
     * @test
     */
    final public function lire() : void
    {
        self::assertGreaterThanOrEqual(1, $this->configuration->lire());
    }


    /**
     * @test
     */
    final public function recupererParametre(): void
    {
        $this->configuration->enregistrer('test', 'test');
        self::assertEquals(
            'test',
            $this->configuration->recupererParametre('test')
        );
        if (file_exists($this->dir . '/config/gepadbal.yaml')) {
            unlink($this->dir . '/config/gepadbal.yaml');
        }
        $config['gepadbal'] = '';
        $yaml = Yaml::dump($config);
        file_put_contents($this->dir . '/config/gepadbal.yaml', $yaml);
        try {
            $this->configuration->recupererParametre('testerreur');
        } catch (CompliceException $exception) {
            self::assertEquals(
                "le paramètre testerreur n'est pas accessible",
                $exception->getMessage()
            );
        }
    }
}