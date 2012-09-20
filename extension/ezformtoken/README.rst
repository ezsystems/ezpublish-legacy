eZ Form Token extension
~~~~~~~~~~~~~~~~~~~~~~~

$Author$
$Revision$
$Date$
:Status: Draft

.. contents::

=====
Intro
=====
This extension aims to stop any CSRF attack against eZ Publish.
To accomplish that input and output filter events added in eZ Publish 4.5 "Matterhorn"
is used to be able to verify all POST requests using a pr user session form token.

This is all done transparently for html/xhtml forms but requires changes to all ajax POST code.
The changes needed to eZ Publish is included in 4.5, and last section in this doc explains how
you can modify your custom ajax code to work with this extension.

If form token does not verify, an Exception is currently thrown and an
error 500 is send to the HTTP client.


=======
WARNING
=======
Make sure you test this extension extensively with your custom solution before putting it into prod
on an existing install.

Known issues (by design):

* Will break any custom Ajax POST code, see last section for how to modify your code.
* Miss configured reverse proxies or miss configured site.ini\[HTTPHeaderSettings]
  settings causing logged in user response to be cached will lead to situations where
  form tokens does not verify.

Know issue:

* When the extension is enabled, a filter is applied to add an hidden
  span tag as the first child of body. This filter does not work if an
  attribute of the body contains the caracter ">".


=======
Install
=======

1. Unzip / copy ezfromtoken into extension/ folder
2. Re generate autoloads for extensions using:
   $ php bin/php/ezpgenerateautoloads.php -e
3. Enable the extension by adding it to [ExtensionSettings]\ActiveExtensions[] in
   settings/override/site.ini.php.
   Example:
   [ExtensionSettings]
   ActiveExtensions[]
   ActiveExtensions[]=ezformtoken



=======================
Modify custom Ajax code
=======================

If your custom ajax code only uses ezjscore jQuery.ez() or Y.io.ez(), then
you're already covered and don't need to look further.

This section is about making sure code that uses ajax functions directly on
any library or natively includes the correct post form token if available.

The output filter will do the following changes on the html code:

1. Add a hidden input tag with name='ezxform_token' for all form tags that
   have post method
2. Add a hidden tag with id='ezxform_token_js' after body tag that contain
   token in title attribute for ajax use.
3. Replaces any occurrence of @$ezxFormToken@ with form token.

This is done in such a way to be ensure it has no negative impact on eZ Publish cache.

Only eZ Publish response is covered, not external javascript/stylesheet/image files.
Hence why example #A bellow uses dom to get token for ajax post requests.

Using the hidden tag with id='ezxform_token_js' is the best option for ajax
code and it is explained in example #A. If your ajax code is executed before
body tag, then you will have to use option #3 as explained in example #B.

Examples:
A Using DOM:

    Given code like this in template or javascript file:

        $.post( url, {}, function(){} );

    Replace it with something like:

 	    var _token = '', _tokenNode = document.getElementById('ezxform_token_js');
 	    if ( _tokenNode ) _token = 'ezxform_token=' + _tokenNode.getAttribute('title');
 	    $.post( url, _token, function(){} );


B Using form token replace string:

    Given code like this in your template before body tag:

        jQuery.post( url, {}, function(){} );

    Replace it with something like:

 	    jQuery.post( url, 'ezxform_token=@$ezxFormToken@', function(){} );

Note: Example #B only works if code is inside a template that is part of (x)html output from eZ Publish.
