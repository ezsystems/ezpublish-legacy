<?php
/**
 * $Id: rpc.php 822 2008-04-28 13:45:03Z spocke $
 *
 * @author Moxiecode
 * @copyright Copyright � 2004-2007, Moxiecode Systems AB, All rights reserved.
 */

include_once( 'extension/ezoe/modules/ezoe/classes/utils/mcejson.php' );
include_once( 'extension/ezoe/modules/ezoe/classes/SpellChecker.php' );

/**
 * Returns an request value by name without magic quoting.
 *
 * @param String $name Name of parameter to get.
 * @param String $default_value Default value to return if value not found.
 * @return String request value by name without magic quoting or default value.
 */
function getRequestParam($name, $default_value = false, $sanitize = false)
{
    if (!isset($_REQUEST[$name]))
        return $default_value;

    if (is_array($_REQUEST[$name]))
    {
        $newarray = array();

        foreach ($_REQUEST[$name] as $name => $value)
            $newarray[formatParam($name, $sanitize)] = formatParam($value, $sanitize);

        return $newarray;
    }

    return formatParam($_REQUEST[$name], $sanitize);
}


$config = array();
// see doc page: http://wiki.moxiecode.com/index.php/TinyMCE:Plugins/spellchecker
// General settings
$config['general.engine'] = 'GoogleSpell';

// Get from ezoe.ini settings if defined and if it has any values
$ezoeIni  = eZINI::instance( 'ezoe.ini' );
if ( $ezoeIni->hasVariable( 'SpellChecker', 'config' )
  && count( $ezoeIni->variable( 'SpellChecker', 'config' ) ) )
{
    $config = $ezoeIni->variable( 'SpellChecker', 'config' );
}


if ( defined('PSPELL_FAST') )
{
    $config['PSpell.mode'] = PSPELL_FAST;
    $config['PSpellShell.mode'] = PSPELL_FAST;
}




if ( isset( $config['general.engine'] ) )
{
    include_once( 'extension/ezoe/modules/ezoe/classes/' . $config['general.engine'] . '.php' );
}

// Set RPC response headers
header('Content-Type: text/plain');
header('Content-Encoding: UTF-8');
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

$raw = '';

// Try param
if (isset($_POST["json_data"]))
    $raw = getRequestParam("json_data");

// Try globals array
if (!$raw && isset($_GLOBALS["HTTP_RAW_POST_DATA"]))
    $raw = $_GLOBALS["HTTP_RAW_POST_DATA"];
else if (!$raw && isset($HTTP_RAW_POST_DATA))
    $raw = $HTTP_RAW_POST_DATA;

// Try stream
if (!$raw)
{
    if (!function_exists('file_get_contents'))
    {
        $fp = fopen("php://input", "r");
        if ($fp)
        {
            $raw = "";

            while (!feof($fp))
                $raw = fread($fp, 1024);

            fclose($fp);
        }
    } else
        $raw = "" . file_get_contents("php://input");
}

// No input data
if (!$raw)
{
    echo '{"result":null,"id":null,"error":{"errstr":"Could not get raw post data.","errfile":"","errline":null,"errcontext":"","level":"FATAL"}}';
    eZDB::checkTransactionCounter();
    eZExecution::cleanExit();
}

// Passthrough request to remote server
if (isset($config['general.remote_rpc_url']))
{
    $url = parse_url($config['general.remote_rpc_url']);

    // Setup request
    $req = "POST " . $url["path"] . " HTTP/1.0\r\n";
    $req .= "Connection: close\r\n";
    $req .= "Host: " . $url['host'] . "\r\n";
    $req .= "Content-Length: " . strlen($raw) . "\r\n";
    $req .= "\r\n" . $raw;

    if (!isset($url['port']) || !$url['port'])
        $url['port'] = 80;

    $errno = $errstr = "";

    $socket = fsockopen($url['host'], intval($url['port']), $errno, $errstr, 30);
    if ($socket) {
        // Send request headers
        fputs($socket, $req);

        // Read response headers and data
        $resp = "";
        while (!feof($socket))
                $resp .= fgets($socket, 4096);

        fclose($socket);

        // Split response header/data
        $resp = explode("\r\n\r\n", $resp);
        echo $resp[1]; // Output body
    }

    eZDB::checkTransactionCounter();
    eZExecution::cleanExit();
}

// Get JSON data
$json = new Moxiecode_JSON();
$input = $json->decode($raw);

// Execute RPC
if (isset($config['general.engine']) && class_exists( $config['general.engine'] ))
{
    $spellchecker = new $config['general.engine']($config);
    $result = call_user_func_array(array($spellchecker, $input['method']), $input['params']);
}
else
{
    echo '{"result":null,"id":null,"error":{"errstr":"You must choose an valid spellchecker engine in the ezoe.ini file.","errfile":"","errline":null,"errcontext":"","level":"FATAL"}}';
    eZDB::checkTransactionCounter();
    eZExecution::cleanExit();
}

// Request and response id should always be the same
$output = array(
    "id" => $input->id,
    "result" => $result,
    "error" => null
);

// Return JSON encoded string
echo $json->encode($output);

eZDB::checkTransactionCounter();
eZExecution::cleanExit();

?>