<?php
namespace Framer\Commands;

use Framer\Commands\Helper\Helper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Framer\Commands\Helper\CloneRespository;

class CreateApp extends Command
{

    protected function configure()
    {
        $this
            ->setName('generate')
            ->setDescription('Generates a new Framer Project/Component.')
            ->setHelp('Creates a New Framer Project/Component.')
            ->addArgument('app', InputArgument::REQUIRED,'The command to issue a new Framer Project/Component.')
            ->addArgument('name',InputArgument::REQUIRED, "The name of your app/component.");
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        switch ($input->getArgument('app')) {
            case 'new':
                Helper::getInitialRepository($output, $input->getArgument('name'));
                break;
            case 'component':
                Helper::createNewComponent($output, $input->getArgument('name'));
                break;
            default:
                $output->writeln(["Please refer to this:",
                    "<info>new</info>: Create a new Framer project.",
                    "<info>component</info>: Create a new Framer component/page."]);
        }

        return 0;
    }
}