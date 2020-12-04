Lovestack installation instructions
===

With composer
--

* Install composer (https://getcomposer.org/)
* Download following composer config file:
`curl --output composer.json https://raw.githubusercontent.com/mugoweb/ezpublish-legacy/master/doc/composer_project_example.json`
* You have to edit the file `composer.json` and remove all comments. Decide if you'd like to use any packages that are currently commented out.
* Execute 'composer install' - it will install the Lovestack into a subdirectory
* Make sure your web server is serving a PHP application from the subdirectory the composer process created
* An example Apache vhost configuration file is [here](/doc/apache2/legacy.conf)
* Use a web browser to access the Lovestack application
* Follow the instructions of the web installation wizard
* It's a good idea to _Store the commit hash in eZ Publish_. See [update/README.md](/update/README.md)
