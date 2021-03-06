<?php

/*
 * This file is part of the flysystem-bundle project.
 *
 * (c) Titouan Galopin <galopintitouan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace League\FlysystemBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * @author Titouan Galopin <galopintitouan@gmail.com>
 *
 * @internal
 */
class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('flysystem');
        $rootNode = $this->getRootNode($treeBuilder, 'flysystem');

        $rootNode
            ->fixXmlConfig('storage')
            ->children()
                ->arrayNode('storages')
                    ->useAttributeAsKey('name')
                    ->arrayPrototype()
                        ->performNoDeepMerging()
                        ->children()
                            ->scalarNode('adapter')->isRequired()->end()
                            ->arrayNode('options')
                                ->variablePrototype()
                                ->end()
                            ->defaultValue([])
                            ->end()
                            ->scalarNode('visibility')->defaultNull()->end()
                            ->booleanNode('case_sensitive')->defaultTrue()->end()
                            ->booleanNode('disable_asserts')->defaultFalse()->end()
                        ->end()
                    ->end()
                    ->defaultValue([])
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }

    private function getRootNode(TreeBuilder $treeBuilder, $name)
    {
        // BC layer for symfony/config 4.1 and older
        if (!\method_exists($treeBuilder, 'getRootNode')) {
            return $treeBuilder->root($name);
        }

        return $treeBuilder->getRootNode();
    }
}
