<?php

namespace Knp\DictionaryBundle\DependencyInjection;

use Knp\DictionaryBundle\DependencyInjection\Compiler\DictionaryRegistrationPass;
use Knp\DictionaryBundle\Dictionary;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class KnpDictionaryExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $config, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $config);
        $container->setParameter('knp_dictionary.configuration', $config);

        $loader = new XmlFileLoader(
            $container,
            new FileLocator(__DIR__ . '/../Resources/config')
        );
        $loader->load('dictionary.xml');

        // BC Layer for Symfony < 3.3
        if (\method_exists($container, 'registerForAutoconfiguration')) {
            $container
                ->registerForAutoconfiguration(Dictionary::class)
                ->addTag(DictionaryRegistrationPass::TAG_DICTIONARY)
            ;
        }
    }
}
