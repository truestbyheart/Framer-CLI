<?php
namespace Framer\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class CreateApp extends Command
{

    protected function configure()
    {
        $this
            ->setName('framer:generate')
            ->setDescription('Generates a new Framer project.')
            ->setHelp('Creates a New Framer Project.')
            ->addArgument('app', InputArgument::OPTIONAL,'the name of your app');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
       // $output->writeln($input->getArgument('app'));
        switch ($input->getArgument('app')) {
            case 'app':
                $output->writeln("hello app");
                break;
            case 'component':
                $output->writeln("hello Component");
                break;
            default:
                $output->writeln(['Please refer to this:',
                    "<info>app</info>: Create a new Framer project.",
                    '<info>component</info>: Create a new Framer component/page.']);
        }
        $output->write("Finished successfully");

        return 0;
    }
}