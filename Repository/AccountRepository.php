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
use Kori\KingdomServerBundle\Entity\Avatar;

/**
 * Class AccountRepository
 * @package Kori\KingdomServerBundle\Repository
 */
class AccountRepository extends EntityRepository
{

    /**
     * @var int
     */
    protected $protectionPeriod;

    /**
     * @param int $protectionPeriod
     */
    public function setProtectionPeriod(int $protectionPeriod)
    {
        $this->protectionPeriod = $protectionPeriod;
    }

    public function create(string $name, Avatar $avatar): Account
    {
        $account = new Account();
        $account->setName($name);
        $account->setProtection( strtotime("+".$this->protectionPeriod."days"));
        $this->getEntityManager()->persist($account);

        $avatar->setAccount($account);
        $this->getEntityManager()->persist($avatar);

        $this->getEntityManager()->flush();

        return $account;
    }
}
