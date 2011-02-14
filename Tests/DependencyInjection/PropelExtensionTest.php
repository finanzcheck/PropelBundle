<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Propel\PropelBundle\Tests\DependencyInjection;

use Propel\PropelBundle\Tests\TestCase;
use Propel\PropelBundle\DependencyInjection\PropelExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class PropelExtensionTest extends TestCase
{
    public function testConfigLoad()
    {
        $container = new ContainerBuilder();
        $loader = new PropelExtension();

        try {
            $loader->configLoad(array(array()), $container);
            $this->fail();
        } catch (\Exception $e) {
            $this->assertInstanceOf('InvalidArgumentException', $e, '->configLoad() throws an \InvalidArgumentException if the Propel path is not set.');
        }

        $loader->configLoad(array(array('path' => '/propel')), $container);
        $this->assertEquals('/propel', $container->getParameter('propel.path'), '->configLoad() sets the Propel path');

        $loader->configLoad(array(array()), $container);
        $this->assertEquals('/propel', $container->getParameter('propel.path'), '->configLoad() sets the Propel path');
    }

    public function testDbalLoad()
    {
        $container = new ContainerBuilder();
        $loader = new PropelExtension();

        $loader->dbalLoad(array(array()), $container);
        $this->assertEquals('Propel', $container->getParameter('propel.class'), '->dbalLoad() loads the propel.xml file if not already loaded');

        // propel.dbal.default_connection
        $this->assertEquals('default', $container->getParameter('propel.dbal.default_connection'), '->dbalLoad() overrides existing configuration options');
        $loader->dbalLoad(array(array('default_connection' => 'foo')), $container);
        $this->assertEquals('foo', $container->getParameter('propel.dbal.default_connection'), '->dbalLoad() overrides existing configuration options');

        $container = new ContainerBuilder();
        $loader = new PropelExtension();

        $loader->dbalLoad(array(array('password' => 'foo')), $container);

        $arguments = $container->getDefinition('propel.configuration')->getArguments();
        $config = $arguments[0];
        $this->assertEquals('foo', $config['datasources']['default']['connection']['password']);
        $this->assertEquals('root', $config['datasources']['default']['connection']['user']);

        $loader->dbalLoad(array(array('user' => 'foo')), $container);
        $this->assertEquals('foo', $config['datasources']['default']['connection']['password']);
        $this->assertEquals('root', $config['datasources']['default']['connection']['user']);
    }
}
