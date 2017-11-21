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


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Kori\KingdomServerBundle\Activity\ActivityInterface;
use Kori\KingdomServerBundle\Entity\ActivityLog;

/**
 * Class ActivityManager
 * @package Kori\KingdomServerBundle\Service
 */
final class ActivityManager
{
    /**
     * @var Collection
     */
    protected $activities;

    /**
     * @var array
     */
    protected $servers;

    public function __construct(ServerManager $serverManager)
    {
        $this->activities = new ArrayCollection();
        $this->servers = $serverManager->getServers();
    }

    /**
     * @param ActivityInterface $activity
     * @return bool
     */
    public function addActivity(ActivityInterface $activity): bool
    {
        if(!$this->activities->contains($activity)) {
            $this->activities->add($activity);
            return true;
        }
        return false;
    }

    /**
     * @return int
     */
    public function process(): int
    {
        $processed = 0;

        $this->activities->filter(function (ActivityInterface $activity) use(&$processed) {
            foreach($this->servers as $server)
            {
                if($server instanceof Server && $server->canTrigger($activity))
                {
                    $activity->trigger($server);
                    $log = new ActivityLog();
                    $log->setActivity(get_class($activity));
                    $log->getCreatedAt();
                    $processed++;
                    $server->getEntityManager()->persist($log);
                    $server->getEntityManager()->flush();
                }
            }
        });

        return $processed;
    }
}
