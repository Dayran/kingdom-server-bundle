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
final class ServerManager
{

    /**
     * @var array
     */
    protected $config;

    /**
     * @var array
     */
    protected $defaultRules;

    /**
     * @var RuleManager
     */
    protected $ruleManager;

    /**
     * @var EffectManager
     */
    protected $effectManager;

    /**
     * ServerManager constructor.
     * @param array $config
     * @param array $defaultRules
     * @param RuleManager $ruleManager
     * @param EffectManager $effectManager
     */
    public function __construct(array $config, array $defaultRules, RuleManager $ruleManager, EffectManager $effectManager)
    {
        $this->config = $config;
        $this->defaultRules = $defaultRules;
        $this->ruleManager = $ruleManager;
        $this->effectManager = $effectManager;
    }

    /**
     * @param string $serverName
     * @return Server|null
     */
    public function getServer(string $serverName): ?Server
    {
        if(array_key_exists($serverName, $this->config))
        {
            $config = $this->config[$serverName];
            $server = new Server($config['db_connection'], $config['rate'], $config['days_of_protection']);

            //Start Add rules
            $buildRules = [];
            $buildConfig = $config['rules']['build'] ?? $this->defaultRules['build'];
            foreach($buildConfig as $name)
                $buildRules[] = $this->ruleManager->getBuildRule($name);
            $server->setBuildRules($buildRules);

            $server->setAttackRule($this->ruleManager->getAttackRule($config['rules']['attack'] ?? $this->defaultRules['attack']));
            $server->setInfluenceRule($this->ruleManager->getInfluenceRule($config['rules']['influence'] ?? $this->defaultRules['influence']));
            //End Add rules
            $server->setEffectManager($this->effectManager);

            return $server;
        }
        return null;
    }

    /**
     * @return array
     */
    public function getServers(): array
    {
        $servers = [];
        foreach(array_keys($this->config) as $name)
        {
            $servers[] = $this->getServer($name);
        }
        return $servers;
    }

    /**
     * @param RequestStack $requestStack
     * @param array $config
     * @param array $defaultRules
     * @param RuleManager $ruleManager
     * @param EffectManager $effectManager
     * @return Server|null
     */
    public static function matchDomain(RequestStack $requestStack, array $config, array $defaultRules, RuleManager $ruleManager, EffectManager $effectManager): ?Server
    {
        foreach($config as $name => $c)
        {
            if($c["domain"] === $requestStack->getCurrentRequest()->getHost())
            {
                $self = new self($config, $defaultRules, $ruleManager, $effectManager);
                return $self->getServer($name);
            }
        }
        return null;
    }

}
