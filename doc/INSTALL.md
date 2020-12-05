Lovestack installation instructions
===

With composer
--

* Install composer (https://getcomposer.org/)
* Clone this repository `git clone https://github.com/mugoweb/ezpublish-legacy.git`
* Optional: Edit the file `composer.json` and adjust it to your needs. For example add eZ Publish extensions into the 'required' section. 
* Execute 'composer install' - it will install all required dependencies
* Use [this vhost configuration file](/doc/apache2/legacy.conf) as an example to configure Apache
* Use a web browser to access the Lovestack application
* Follow the instructions of the web installation wizard

Moving the code base to a different repository
--
Most likely you want to push the code base to a new code repository. You can do the following:
* delete the `.git` directory
* clone the new repository on top of the existing code base
* commit and push all files into the new repository

It's a good idea to _Store the commit hash in eZ Publish_. See [update/README.md](/update/README.md)
