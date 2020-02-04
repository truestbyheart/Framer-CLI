<?php

namespace Framer\Commands\Helper;

class Helper
{
    /**
     * Create a new Framer application through cloning the main repo.
     * @param $output
     * @param $name
     */
    static function getInitialRepository($output, $name)
    {
        /*
         *STEP 1: Get the initial project structure from the official github repo.
         */
        $repo_path = 'https://github.com/truestbyheart/Framer.git';
        $command = 'git clone ' . $repo_path;
        $output->writeln([' ', '========Cloning main repository========']);
        exec($command);


        /*
         * STEP 2: Rename the application;
         */
        $output->writeln([
            ' ',
            '========Renaming Main repository========',
            'Renaming Framer project -> ' . $name]);
        $command = "mv Framer " . $name;
        exec($command);


        /*
         * STEP 3: Remove the Git version control form the project.
         */
        $command = "cd " . $name . " && rm -rf .git READ.md README.md";
        exec($command);

        /*
         * STEP 4: Final instructions for the dev.
        */
        $output->writeln([
            ' ',
            '========Instructions========',
            '1.Run <info>cd ' . $name . ' </info>',
            '2.Run <info>composer install</info>',
            '3.Run <info>framer start</info>']);

    }

    /**
     * Create new component, generate the controller and view template.
     * @param $output
     * @param $component
     */
    static function createNewComponent($output, $component)
    {
        #STEP 1: Check if we are in a framer app.
        $framer_file = APPROOT . "/framer.json";

        if (file_exists($framer_file)) {
            #STEP 2: Check if the component contains a folder path.
            $path = explode("/", $component);

            if (sizeof($path) > 1) {
                #STEP 2-1: if folder doesn't exist generate one
                $lastIndex = sizeof($path) - 1;
                $dir = '';

                # File name variables
                $controller_file_name = $path[$lastIndex] . ".php";
                $view_file_name = $path[$lastIndex] . ".blade.php";


                # Building the folder path based on the passed parameter
                foreach ($path as $key => $value) {
                    if ($key == $lastIndex) break;
                    $dir = ($dir == '') ? "/" . $path[$key] : $dir . "/" . $path[$key];
                }

                # Absolute path to put the files
                $abs_path_controller = APPROOT . "/app/controller" . $dir;
                $abs_path_view = APPROOT . "/app/views";

                if (!file_exists($abs_path_controller)) {

                    #STEP 2-2: generate the file in the controller and views
                    if (mkdir($abs_path_controller, 0777, true)) {
                        fopen($abs_path_controller . "/" . $controller_file_name, "w");
                        fopen($abs_path_view . "/" . $view_file_name, "w");
                        $output->writeln([
                            "<info>Controller:</info> " . $abs_path_controller . "/" . $controller_file_name,
                            "<info>View:</info> " . $abs_path_view . "/" . $view_file_name
                        ]);
                    } else {
                        $output->writeln([
                            "Failed to generate new component.",
                            "Please check your write permission on PHP ini."]);
                    }

                } else {
                    if (file_exists($abs_path_controller . "/" . $controller_file_name) &&
                        file_exists($abs_path_view . "/" . $view_file_name)) {
                        $output->writeln([
                            "Component already exists",
                            "<info>Controller:</info> " . $abs_path_controller . "/" . $controller_file_name,
                            "<info>View:</info> " . $abs_path_view . "/" . $view_file_name
                        ]);
                    }

                    fopen($abs_path_controller . "/" . $controller_file_name, "w");
                    fopen($abs_path_view . "/" . $view_file_name, "w");

                    $output->writeln([
                        "<info>Controller:</info> " . $abs_path_controller . "/" . $controller_file_name,
                        "<info>View:</info> " . $abs_path_view . "/" . $view_file_name
                    ]);
                }

            }

        } else {
            $output->writeln([
                "<error>Framer command couldn't run.</error>",
                "<info>Possible causes:</info>",
                "1. you are running this command outside a framer project.",
                "2. You are not at the root of the project."
            ]);
        }


        #STEP 2-3: insert initial template for the files.

    }
}
