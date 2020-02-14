<?php


namespace Framer\Commands;


use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Framer\Commands\Helper\Helper;

class CreateMigration extends Command
{
    public function execute(InputInterface $input, OutputInterface $output)
    {
        Helper::create_New_Migration_File($output, $input->getArgument("file_name"));
        return 0;
    }

    protected function configure()
    {
        $this
            ->setName("create:migration")
            ->setDescription("Creates a new migration file")
            ->setHelp("Creates a new migration file")
            ->addArgument("file_name", InputArgument::REQUIRED, "The name of the migration file to be generated.");
    }
}