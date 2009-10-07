After the Deadline TinyMCE Plugin
------------------
http://www.afterthedeadline.com/

After the Deadline checks spelling, style, and grammar in TinyMCE.

Usage
-----
1. Extract the contents of this zip file into the tiny_mce/plugins directory.

2. You'll need an API key from AfterTheDeadline.com.  Registering is quick
   and painless (email address and password).  

3. You'll need to set three variables in your TinyMCE init:

atd_rpc_id : your API key

atd_rpc_url : the URL to post to, a proxy PHP script is included.  This will
redirect all calls to http://service.afterthedeadline.com/  Use:
htp://yourserver.com/path/to/AtD/proxy.php?url= for this. 

[Note: the '?url=' portion is expected by the included proxy.php script]

If you extracted TinyMCE to the root directory of your webserver and if you put
this plugin into the right place then the path will be:

http://www.yourserver.com/tinymce/jscripts/tiny_mce/plugins/AtD 

atd_css_url : the URL of the AtD CSS file

4. You'll need a proxy script on your server.  

Use the included proxy.php if you can.  Otherwise you'll need to write your
own.  If your TinyMCE is public facing then you should hardcode your API key
into the proxy script.  

5. Make sure you set span.mce_AtD to the button you want to use for After the Deadline.
This example assumes you're using the default TinyMCE skin.

<style>.defaultSkin span.mce_AtD { background: url(/path/to/AtD/atdbuttontr.gif) no-repeat; }</style>

Ignore Pesky Rules
------
After the Deadline lets you specify a list of phrases it should not highlight. 
You can hardcode these values in the atd_ignore_strings value in TinyMCE init. 
The format is a comma separated list of phrases.  (See example below for more).

Users can choose to ignore phrases by selecting "Ignore always" when clicking 
a highlighted phrase.  You have to enable this feature for the "Ignore always"
menu to show up.  Do so by setting the atd_ignore_enable to the string "true" 
in TinyMCE init.  

If you see "Ignore all" when clicking an error then this feature is disabled.

There is a caveat to this client side ignore capability.  You get to create the
user interface for unignoring rules.  The phrases are stored in a cookie named
atd_ignore.  This cookie was created with:

 tinymce.util.Cookie.setHash("atd_ignore", ...);

The format of the cookie is "some+phrase=1&someWord=1&..."  See the TinyMCE API
for tinymce.util.Cookie.

atdphrases.js and unignore.html are included in this archive to assist you.

Error Categories
-----
This TinyMCE plugin only shows grammar, spelling, and misused word errors by 
default.  All other categories of errors are filtered and you must explicitly
enable them in the TinyMCE init parameters using the AtD_show_types option.

atd_show_types: "Bias Language,Cliches,Complex Expression,Double Negatives,Hidden Verbs,Jargon Language,Passive voice,Phrases to Avoid,Redundant Expression"

You may omit any of these categories.  Note that categories are separated by
commas with no whitespace.  The category names are case sensitive and yes, 
voice is lowercase in Passive voice.

Example TinyMCE Init
-------

<!-- associate a button with the AtD plugin, if you see a blank button then you forgot to do this -->
<style>
.defaultSkin span.mce_AtD { background: url(/path/to/AtD/atdbuttontr.gif) no-repeat; }
</style>

<script language="javascript" type="text/javascript" src="/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
<script language="javascript" type="text/javascript" src="/tinymce/jscripts/tiny_mce/plugins/AtD/editor_plugin.js"></script>
<script language="javascript" type="text/javascript">
tinyMCE.init({
        mode : "textareas",
        plugins                     : "AtD",

        atd_rpc_url                 : "http://yourserver.com/path/to/AtD/proxy.php?url=",
        atd_rpc_id                  : "your API key here", 
        atd_css_url                 : "http://yourserver.com/path/to/AtD/css/content.css",
        
        atd_show_types              : "Complex Expression,Redundant Expression", /* explicit list of categories to show */
        atd_ignore_strings          : "AtD,rsmudge", /* strings that this plugin should ignore */
        atd_ignore_enable           : "false", /* set to true if you want to allow users to ignore rules--you get to make the unignore UI */ 

        gecko_spellcheck            : false, /* disable the gecko spellcheck since AtD provides one */

        theme_advanced_buttons3_add : "AtD", /* add AtD button to the toolbar */

        theme: "advanced",
        theme_advanced_toolbar_location    : "top",
        theme_advanced_toolbar_align       : "left",
        theme_advanced_statusbar_location  : "bottom",
        theme_advanced_resizing            : true,
        theme_advanced_resizing_use_cookie : false
});
</script>

Support and Contact
-------

Get support from:
   http://groups.google.com/group/atd-developers

Author:
   Raphael Mudge
   rsmudge@gmail.com

This code is a hack on the spellcheck plugin from Moxiecode.  Thanks for the 
hard work guys.

License
-------
LGPL


