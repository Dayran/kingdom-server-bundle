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

namespace Kori\KingdomServerBundle\Service;


use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class ServerManager
 * @package Kori\KingdomServerBundle\Service
 */
class ServerManager
{

    /**
     * @var array
     */
    protected static $servers = [];

    /**
     * @var array
     */
    protected static $domainToName = [];

    public function addServer(string $serverName, string $domain, Server $server)
    {
        if(array_key_exists($serverName, self::$servers))
            throw new \RuntimeException("Server name is already registered");
        if(array_key_exists($domain, self::$domainToName))
            throw new \RuntimeException("Domain is already registered");

        self::$servers[$serverName] = $server;
        self::$domainToName[$domain] = $serverName;
    }

    /**
     * @param string $serverName
     * @return Server|null
     */
    public function getServer(string $serverName): ?Server
    {
        if(array_key_exists($serverName, self::$servers))
        {
            return self::$servers[$serverName];
        }
        return null;
    }

    /**
     * @return array
     */
    public function getServers(): array
    {
        return self::$servers;
    }

    /**
     * @param RequestStack $requestStack
     * @return Server|null
     */
    public static function matchDomain(RequestStack $requestStack): ?Server
    {
        $domain = $requestStack->getCurrentRequest()->getHost();
        if(array_key_exists($domain, self::$domainToName))
        {
            return self::$servers[self::$domainToName[$domain]];
        }
        return null;
    }

}
