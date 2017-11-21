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

namespace Kori\KingdomServerBundle\Tests\Units\Repository;

use atoum\test;
use Kori\KingdomServerBundle\Entity\Account;
use Kori\KingdomServerBundle\Entity\Message;

/**
 * Class MessageRepository
 * @package Kori\KingdomServerBundle\Tests\Units\Repository
 */
class MessageRepository extends test
{

    public function testMessage()
    {
        $sender = new Account();
        $to = new Account();

        $this
            ->given($this->mockGenerator()->orphanize('__construct'))
            ->given($objectManager = new \mock\Doctrine\Common\Persistence\ObjectManager())
            ->given($this->mockGenerator()->orphanize('__construct'))
            ->given($meta = new \mock\Doctrine\ORM\Mapping\ClassMetadata())
            ->given($manager = new \mock\Kori\KingdomServerBundle\Repository\MessageRepository($objectManager, $meta))
            ->when($result = $manager->message($to, $sender, "subject", "message"))
            ->then(
                $this->object($result)->isInstanceOf(Message::class)
                    ->and($this->string($result->getSubject())->isEqualTo("subject", "Subject was not set properly"))
                    ->and($this->string($result->getMessage())->isEqualTo("message", "Message was not set properly."))
                    ->and($this->integer($result->getCreatedAt())->isNotNull("Created at should be initialized."))
                    ->and($this->object($result->getSender())->isEqualTo($sender))
                    ->and($this->object($result->getRecipient())->isEqualTo($to))
            )
        ;
    }

}
