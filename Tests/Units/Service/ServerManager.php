<?php
/**
 * MIT License
 *
 * Copyright (c) 2017 Frisks
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 *
 */

namespace Kori\KingdomServerBundle\Tests\Units\Service;

use atoum\test;
use Kori\KingdomServerBundle\Service\ServerManager as TestedModel;

class ServerManager extends test
{

    public function testProcess()
    {
        $this
            ->given($requestStack = new \mock\Symfony\Component\HttpFoundation\RequestStack())
            ->and($this->mockGenerator()->orphanize('__construct'))
            ->and($this->mockGenerator()->shuntParentClassCalls())
            ->and($request = new \mock\Symfony\Component\HttpFoundation\Request())
            ->and($this->calling($request)->getHost[1] = function () {
                return "fail.test.com";
            })
            ->and($this->calling($request)->getHost[2] = function () {
                return "server.test.com";
            })
            ->and($this->calling($requestStack)->getCurrentRequest = function () use ($request) {
                return $request;
            })
            ->and($this->mockGenerator()->orphanize('__construct'))
            ->and($this->mockGenerator()->shuntParentClassCalls())
            ->and($server = new \mock\Kori\KingdomServerBundle\Service\Server())
            ->and($manager = new TestedModel())
            ->and($manager->addServer('test', 'server.test.com', $server))
            ->and(
                $this->exception(function() use($manager, $server) {
                    $manager->addServer('test', 'server.test.com', $server);
                })->hasMessage("Server name is already registered", "Should throw an error for repeated server name")
            )
            ->and(
                $this->exception(function() use($manager, $server) {
                    $manager->addServer('test_2', 'server.test.com', $server);
                })->hasMessage("Domain is already registered", "Should throw an error for repeated domain")
            )
            ->when($server = TestedModel::matchDomain($requestStack))
            ->then(
                $this->variable($server)->isNull("Server Manager should fail to retrieve any valid server because domain is not configured.")
            )
            ->when($server = TestedModel::matchDomain($requestStack))
            ->then(
                $this->object($server)->isInstanceOf("Kori\\KingdomServerBundle\\Service\\Server", "Server Manager should return a valid server.")
            )
            ->when($server = $manager->getServer("test"))
            ->then(
                $this->object($server)->isInstanceOf("Kori\\KingdomServerBundle\\Service\\Server", "Server Manager should return a valid server.")
            )
            ->when($server = $manager->getServer("not_configured"))
            ->then(
                $this->variable($server)->isNull( "Server Manager should return null for none configured server.")
            )
            ->when($servers = $manager->getServers())
            ->then(
                $this->array($servers)->hasSize(1, "There should only be one server configured.")
            )
        ;

    }

}
