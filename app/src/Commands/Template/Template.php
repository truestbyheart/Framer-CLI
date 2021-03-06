<?php

namespace Framer\Commands\Template;


class Template
{

    public static function controller_template(String $name)
    {
        $refer = "this->view";
        return "<?php 
        class " . ucfirst($name) . " extends Controller { \n
            public function " . ucfirst($name) . "(){ \n
                $$refer(\"" . ucfirst($name) . "\", ['title' => '" . ucfirst($name) . " is working']); \n
            }\n
        }\n";
    }

    public static function view_template()
    {
        $title = "title";
        return "<h3>{{\$" . $title . "}}</h3>";
    }

    public static function framer_json_template(String $app_name)
    {
        return "{ \n
         \"name\": \"" . $app_name . "\",\n
          \"cli\": \"v1.0.0\"\n
         }";
    }
}