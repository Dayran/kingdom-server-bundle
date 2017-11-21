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

namespace Kori\KingdomServerBundle\Command;


use Doctrine\ORM\Tools\SchemaTool;
use Kori\KingdomServerBundle\Entity\ServerStats;
use Kori\KingdomServerBundle\Service\Server;
use Kori\KingdomServerBundle\Service\ServerManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

/**
 * Class SetupCommand
 * @package Kori\KingdomServerBundle\Command
 */
class SetupCommand extends ContainerAwareCommand
{

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('kingdom:setup')
            ->setDescription('Set up the server for kingdom instance')
            ->addArgument("name", InputArgument::OPTIONAL, "Name of the server to set up")
            ->addOption("generator", "g", InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, "Generators to use during setup")
            ->addOption('ignore_restrictions', "ignore", InputOption::VALUE_OPTIONAL, "Ignore generator restrictions")
            ->addOption('override', "o", InputOption::VALUE_OPTIONAL, "Override current server");
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper("question");
        $serverName = $input->getArgument("name") ?? $helper->ask($input, $output, new Question("Please enter the name of the server to be setup:"));

        $generators = [];
        foreach($input->getOption("generator") as $name)
        {
            $generator = $this->getContainer()->get('kori_kingdom.generator_manager')->findGenerator($name);
            if(array_key_exists($generator->getType(), $generators))
                throw new RuntimeException("Multiple generators of the same type detected.");
            $generators[$generator->getType()] = $generator;
        }

        //Start check required generators
        if($input->getOption('ignore_restrictions') === null)
        {
            if(!array_key_exists(0, $generators))
                throw new RuntimeException("World generator not found.");
            if(!array_key_exists(1, $generators))
                throw new RuntimeException("Race generator not found.");
            if(!array_key_exists(2, $generators))
                throw new RuntimeException("Buildings generator not found.");
            if(!array_key_exists(3, $generators))
                throw new RuntimeException("Technology generator not found.");
            if(!array_key_exists(4, $generators))
                throw new RuntimeException("Units generator not found.");
        }
        //End check required generators
        $server = $this->getContainer()->get(ServerManager::class)->getServer($serverName);

        if(!is_null($server->getStatus(ServerStats::CREATED_AT)))
        {
            if(is_null($input->getOption('override')))
                throw new RuntimeException("Server is already populated.");
            else
                $this->overrideServer($server);
        }

        foreach($generators as $generator)
        {
            if(!$generator->generate($server))
                throw new RuntimeException(sprintf("%s has failed on generator pass.", get_class($generator)));
        }
        $stat = new ServerStats();
        $stat->setName(ServerStats::CREATED_AT);
        $stat->setValue(time());
        $server->getEntityManager()->persist($stat);
        $server->getEntityManager()->flush();

        return 0;
    }

    /**
     * @param Server $server
     */
    protected function overrideServer(Server $server)
    {
        $metaData = $server->getEntityManager()->getMetadataFactory()->getAllMetadata();
        $tool = new SchemaTool($server->getEntityManager());
        $tool->dropSchema($metaData);
        $tool->createSchema($metaData);
    }

}
