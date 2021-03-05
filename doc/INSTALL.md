Lovestack installation instructions
===

With composer
--

1) Install composer (https://getcomposer.org/)
1) Clone this repository `git clone https://github.com/mugoweb/ezpublish-legacy.git`
1) Optional: Edit the file `composer.json` and adjust it to your needs. For example add eZ Publish extensions into the 'required' section. 
1) Execute 'composer install' - it will install all required dependencies
1) Use [this vhost configuration file](/doc/apache2/legacy.conf) as an example to configure Apache
1) Create a database - following example is for mysql
   ```
   CREATE DATABASE ezpublish charset utf8mb4;
   CREATE USER 'ezpublish'@'localhost' IDENTIFIED BY 'secretPassword';
   GRANT ALL PRIVILEGES ON * . * TO 'ezpublish'@'localhost';
   FLUSH PRIVILEGES;
   ```
1) Use a web browser to access the Lovestack application
1) Follow the instructions of the web installation wizard

Moving the code base to a different repository
--
Most likely you want to push the code base to a new code repository. You can do the following:
* delete the `.git` directory
* clone the new repository on top of the existing code base
* commit and push all files into the new repository

It's a good idea to _Store the commit hash in eZ Publish_. See [update/README.md](/update/README.md#store-the-commit-hash-in-ez-publish)
