<?php
namespace Framer\Commands\Helper;

class CloneRespository{
    static function getInitialRepository($output, $name) {
        /*
         *STEP 1: Get the initial project structure from the official github repo.
         */
        $repo_path = 'https://github.com/truestbyheart/PHP-MVC-Framework.git';
        $command = 'git clone ' . $repo_path;
        $output->writeln([' ', '========Cloning main repository========']);
        exec($command);


        /*
         * STEP 2: Rename the application;
         */
        $output->writeln([
            ' ',
            '========Renaming Main repository========',
            'Framer project ===> '.$name]);
        $command = "mv PHP-MVC-Framework " . $name;
        exec($command);


        /*
         * STEP 3: Remove the Git version control form the project.
         */
         $command = "cd ".$name." && rm -rf .git READ.md README.md";
         exec($command);

         /*
          * STEP 4: Install composer packages
          */
          $command = "composer install";
          $output->writeln([
              ' ',
              '========Installing composer packages========']);
          exec($command);

          /*
           * STEP 5: Final instructions for the dev.
           */
           $output->writeln([
            ' ',
            '========Instructions========',
            '1.Run <info>cd '.$name.' </info>']);

    }
}
