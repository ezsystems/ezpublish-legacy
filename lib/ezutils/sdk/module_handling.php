<?php

// $Result = array( 'title' => 'Module handling' );

?>

<h1>Module handling</h1>

<p>
Modules are an abstract way of seeing a system, they are used for grouping together
related functionality. For instance, all content handling code is placed in a module
called <i>content</i>. Each module has a number of views associated with it. Each view
represents a page in the web browser and can have a number of parameters associated to
it. There are ordered parameters, which are the basis for the view, and additional parameters
which are named and are unordered.
</p>

<p>
This system allows collections of view scripts to be seen as modules and views.
All files belonging to a module are placed in a directory named after the module. This directory must
contain a file called <i>module.php</i>, which defines the module and all it's views.
The file normally looks like this (taken from <i>content/module.php</i>):
</p>

<pre class="example">
$Module = array( "name" => "eZContentObject",
                 "variable_params" => true );

$ViewList = array();
$ViewList["edit"] = array(
    "functions" => array( 'edit' ),
    'single_post_actions' => array( 'PreviewButton' => 'Preview',
                                    'TranslateButton' => 'Translate',
                                    'VersionsButton' => 'VersionEdit',
                                    'PublishButton' => 'Publish',
                                    'BrowseNodeButton' => 'BrowseForNodes',
                                    'DeleteNodeButton' => 'DeleteNode',
                                    'BrowseObjectButton' => 'BrowseForObjects',
                                    'StoreButton' => 'Store' ),
    'post_actions' => array( 'BrowseActionName' ),
    'post_action_parameters' => array( 'DeleteNode' => array( 'SelectedNodeList' => 'Content_node_id_checked' ) ),
    "script" => "edit.php",
    "params" => array( "ObjectID", "EditVersion" ) );

$ViewList["view"] = array(
    "functions" => array( 'read' ),
    "script" => "view.php",
    "params" => array( "ViewMode", "NodeID", "LanguageCode" ),
    "unordered_params" => array( "language" => "Language",
                                 "offset" => "Offset" )
    );
</pre>

<h3>$Module</h3>
<p>
An array that must contain the <i>name</i> key which defines the real name of the module
and <i>variable_params</i> which defines whether parameters are set as normal variables
when the script is run.
</p>

<h3>$ViewList</h3>
<p>
An array with the available views in the module. Each entry consists of the following keys:
</p>

<ul>
<li>
<b>functions</b> - An array with functions that are used by the view, used for determining whether
the view can be seen or not according to policies.
</li>

<li>
<b>script</b> - The PHP file which should be run, it will be run using the eZProcess class.
</li>

<li>
<b>params</b> - The ordered parameters for the view, they are usually taken from the URI or sent
from the script which run this view. Each entry gives the name of the parameter
which will be available in the script trough the <i>$Params</i> variable.
</li>

<li>
<b>unordered_params</b> - An associative array with parameters which are defined by their name instead
of order. They are usually optional to the view. Each key defines the name taken from the URL and
the value defines the name available to the view.
</li>

<li>
<b>single_post_actions</b> - Defines the post actions which should be translated into an action, the first
one to be found is used. The key is the name found among the HTTP post variables and the value is the action
name. This action is only set if an action is not set by the called code.
</li>

<li>
<b>post_actions</b> - Similar to single_post_actions but instead it takes the value in the post variable
and uses it as action name.
</li>

<li>
<b>post_action_parameters</b> - An associative array of action names and their parameters. Each key is
the action name and each value is another associative array of parameters. Each key is the parameter name
and the value is the post variable name.
</li>
</ul>

<h2>Fetching input parameters</h2>
<p>
Fetching the parameters is done by using the <i>$Params</i> variable, it is an associative
array with all the parameters. The name of the parameter keys are defined by <i>params</i>.
The <i>Module</i> parameter is always present and contains the current eZModule object.
</p>

<pre class="example">
$Module =& $Params['Module'];
$ObjectID =& $Params['ObjectID'];
</pre>

<h2>Hooks</h2>
<p>
Hooks are a way of letting external code <i>hook</i> in extra code, the code will be run
at places defined by the view using the <i>runHooks()</i> function. The external code can
create a function and add it to the hook name. It is also possible to run a method in an object
by supplying an array with the object and method name instead of the function name.
Hooks can also have priorities to allow a hook to be run before/after another. The default is
to give everything the same priority.
</p>

<pre class="example">
// Define the function and add the hook
function checkSomething( &$module, $objectID, $editVersion )
{
    // Do something and return
}

$Module->addHook( 'pre_fetch', 'checkSomething' );

// Run the hooks
if ( $Module->runHooks( 'pre_fetch', array( $ObjectID, $EditVersion ) ) )
    return;
</pre>

<h2>Actions</h2>
<p>
To check which action is to be run use the <i>currentAction</i> or <i>isCurrentAction</i> functions.
<i>isCurrentAction</i> will return true if the current action matches the action name supplied.
Parameters for the action can be fetched with <i>actionParameter</i> and checked with
<i>hasActionParameter</i>.
These parameters are either fetched from post variables or set by the code running this view.
</p>

<h2>Return values</h2>
<p>
The return value is determined by the value sent by the return statement, or if there are no
return statement it reads the variable called <i>$Result</i>. This variable is is sent to the
client code executing the module/view.
</p>

<h2>Redirection</h2>
<p>
It's possible for the view code to issue a redirection, the redirection request will be read by
the client that executes the view and it's up to that code to do anything.
There are several different methods available for redirection.
</p>

<ul>
<li>
<b>redirect</b> - Redirects to a given module and view with some optional ordered and unordered parameters.
</li>

<li>
<b>redirectToView</b> - Same as <i>redirect</i> but doesn't require a module since it's done in the current
module.
</li>

<li>
<b>redirectTo</b> - Redirects to a given URL, this is best used for custom URLs, if it's a module/view use
<i>redirect</i> and <i>redirectToView</i> instead.
</li>

<li>
<b>redirectionURI</b> - Creates a URI usable for redirection and can be passed to <i>redirectTo</i>,
use this if you need a URI for a module/view and have to store it or send to some other code.
</li>
</ul>
