<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

/**
 * Some bundles have been commented out because they are not
 * used in this test-case application, under normal circumstances 
 * these commented out bundles would (probably/eventually) be used, 
 * therefore they have been left here as a reminder.
 *
 * NB: the Twig bundle is currently only loaded in "dev" and "test"
 *     environments because we only need it to render the _profiler!
 */

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle(),
            new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle(),
//          new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new Hangman\Bundle\ApiBundle\HangmanApiBundle(),
            new Hangman\Bundle\DatastoreBundle\HangmanDatastoreBundle(),
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $bundles[] = new Symfony\Bundle\TwigBundle\TwigBundle();
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
//          $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
            $bundles[] = new Liip\FunctionalTestBundle\LiipFunctionalTestBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
    }
}
