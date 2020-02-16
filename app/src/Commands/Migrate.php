<?php


namespace Framer\Commands;


use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Framer\Commands\Migration\MigrationBuilder;

class Migrate extends Command
{
    protected function configure()
    {
             $this
                 ->setName("db:migrate")
                 ->setDescription("Migrates all migration files")
                 ->setHelp("Migrates and creates table on the database");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
  {
      (new MigrationBuilder())->get_Table_Name_Properties($output);
      return 0;
  }
}
