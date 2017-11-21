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

namespace Kori\KingdomServerBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Kori\KingdomServerBundle\Entity\Account;
use Kori\KingdomServerBundle\Entity\Message;

/**
 * Class ChatRepository
 * @package Kori\KingdomServerBundle\Repository
 */
class MessageRepository extends EntityRepository
{

    /**
     * @param Account $to
     * @param Account $from
     * @param string $subject
     * @param string $message
     * @return Message
     */
    public function message(Account $to, Account $from, string $subject, string $message): Message
    {
        $object = new Message();
        $object->setSender($from);
        $object->setRecipient($to);
        $object->setSubject($subject);
        $object->setMessage($message);
        $object->getCreatedAt();
        $this->getEntityManager()->persist($object);
        $this->getEntityManager()->flush();

        return $object;
    }

    /**
     * @param Account $account
     * @return array
     */
    public function getMessages(Account $account): array
    {
        $qb = $this->createQueryBuilder('m');
        $qb->where('m.recipient = ?1');
        $qb->setParameter(1, $account);
        return $qb->getQuery()->getArrayResult();
    }

    /**
     * @param Message $message
     */
    public function deleteMessage(Message $message)
    {
        $this->getEntityManager()->remove($message);
        $this->getEntityManager()->flush();
    }
}
