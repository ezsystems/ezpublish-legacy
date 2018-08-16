Lovestack installation instructions
===

With composer
--

1) Install composer (https://getcomposer.org/)
2) Download following composer config file:
`wget -O composer.json https://raw.githubusercontent.com/mugoweb/ezpublish-legacy/master/doc/composer_project_example.json`
3) Edit the file `composer.json` and remove all comments. Decide if you'd like to use some of the packages that are currently commented out.
4) Execute 'composer install' - it will install the Lovestack into a subdirectory
5) Make sure your web server is serving a PHP application from the subdirectory the composer process created
6) Use a web browser to access the Lovestack application
7) Follow the instructions of the web installation wizard
8) It's a good idea to _Store the commit hash in eZ Publish_. See [update/README.md](update/README.md)
