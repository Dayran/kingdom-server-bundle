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

namespace Kori\KingdomServerBundle\Entity;

use Kori\KingdomServerBundle\Traits\CreatedAt;

/**
 * Class Message
 * @package Kori\KingdomServerBundle\Entity
 */
class Message
{
    use CreatedAt;

    /**
     * @var int
     */
    protected $id;

    /**
     * @var Account
     */
    protected $sender;

    /**
     * @var Account
     */
    protected $recipient;

    /**
     * @var string
     */
    protected $subject;

    /**
     * @var string
     */
    protected $message;

    /**
     * @var Message
     */
    protected $reply;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return Account
     */
    public function getSender(): Account
    {
        return $this->sender;
    }

    /**
     * @param Account $sender
     */
    public function setSender(Account $sender)
    {
        $this->sender = $sender;
    }

    /**
     * @return Account
     */
    public function getRecipient(): Account
    {
        return $this->recipient;
    }

    /**
     * @param Account $recipient
     */
    public function setRecipient(Account $recipient)
    {
        $this->recipient = $recipient;
    }

    /**
     * @return string
     */
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     */
    public function setSubject(string $subject)
    {
        $this->subject = $subject;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage(string $message)
    {
        $this->message = $message;
    }

    /**
     * @return Message
     */
    public function getReply(): Message
    {
        return $this->reply;
    }

    /**
     * @param Message $reply
     */
    public function setReply(Message $reply)
    {
        $this->reply = $reply;
    }

}
