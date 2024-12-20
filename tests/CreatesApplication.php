<?php

namespace Tests;

use Illuminate\Foundation\Console\Kernel;

if(!trait_exists("Tests\\CreatesApplication")) {
    trait CreatesApplication
    {
        protected $_app;

        /**
         * Creates the application.
         *
         * @return \Illuminate\Foundation\Application
         */
        public function createApplication()
        {
            $path = implode(DIRECTORY_SEPARATOR, [ __DIR__, "..", "bootstrap", "app.php"]);
            echo $path . PHP_EOL;
            if($bootapp = realpath($path)) {
                // do nothing
            } else if(array_key_exists('DEV_BOOTSTRAP', $_SERVER)) {
                $bootapp = $_SERVER['DEV_BOOTSTRAP'];
            }

            if(file_exists($bootapp)) {
                $app = require($bootapp);
                $app->make(Kernel::class)->bootstrap();
                return $app;
            } else
                throw new \Exception("Could not find bootapp");
        }
    }
}
