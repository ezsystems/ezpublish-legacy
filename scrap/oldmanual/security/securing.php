<h1>Securing an eZ publish site</h1>

<p>Securing the site depends on whether you are using a virtualhost setup or a non-virtualhost setup.
A virtualhost setup means that all urls are redirected to the index.php script while non-virtualhost
requires that the index.php script is mentioned in the url.</p>

<h2>Virtualhost setups</h2>

<p>
Virtualhost setups are secure by default since all request are sent to index.php script, with the
exception of images, stylesheets and javascripts. The only thing that is required for securing
the site is to make sure all custom made templates follow the <a href={"/sdk/doc/view/template_coding_standard"|ezurl}>security guidelines for templates</a>.
</p>

<h2>Non-Virtualhost setups</h2>

<p>
Non-Virtualhost setups are insecure by default. The index.php must explicitly be placed in the url,
this means that any other scripts may be executed directly as well as open up .ini files with password information.
Because of this it is not recommened to use non-virtualhost setups, however if you don't have any choice there are some
guidelines which can be used to secure the site.
</p>
<p>
You also need to make sure that all custom made templates follow the <a href={"/sdk/doc/view/template_coding_standard"|ezurl}>security guidelines for templates</a>.
</p>

<h3>Install a .htaccess file</h3>
<p>
The Apache webserver allows each site to install a <i>.htaccess</i> file which can control which files are
accessible as well as set PHP options. The <i>.htaccess</i> file is placed in the root of your eZ publish installation,
an example of how it may look follows.
</p>

<pre class="example">{'<FilesMatch ".">
order allow,deny
deny from all
</FilesMatch>

<FilesMatch "(index\.php|\.(gif|jpe?g|png))$">
order allow,deny
allow from all
</FilesMatch>

RewriteEngine On
RewriteRule !\.(gif|css|jpe?g|png|js)$ index.php

DirectoryIndex index.php'|wash}</pre>

<h3>Use .ini.php files</h3>
<p>
All .ini files in eZ publish are readable when in non-virtualhost mode, this means that placing
items such as usernames and passwords in these files are dangerous.
</p>
<p>
Fortunately the .ini file reader in eZ publish supports reading so called PHP wrapped .ini files. This
means to create a file with the suffix <i>.ini.php</i> (<i>.ini.append.php</i> for append files), wrap it in a
PHP comment and place it in the <i>settings</i> (<i>settings/override</i> for append files) directory.
For instance the web setup will automatically create such files in <i>settings/override/</i> for you
with all the personal settings.
</p>

<h3>site.ini.php</h3>
<pre class="example">{'<?php /*
[DatabaseSettings]
Server=mydbserver
User=myuser
Password=mypassword
*/ ?>'|wash}</pre>
