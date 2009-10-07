<?php
// After the Deadline Proxy Script


$apiKey = '';

// Get from ezoe.ini settings if defined and if it has any values
$ezoeIni  = eZINI::instance( 'ezoe.ini' );
if ( $ezoeIni->hasVariable( 'AtD', 'api_key' )
  && $ezoeIni->variable( 'AtD', 'api_key' ) !== '' )
{
    $apiKey = $ezoeIni->variable( 'AtD', 'api_key' );
}

if ( $_SERVER['REQUEST_METHOD'] === 'POST' )
{
   $postText = trim( file_get_contents('php://input') );
}

if ( $apiKey !== '' )
{
   $postText .= '&key=' . $apiKey;
}

$url = $_GET['url'];

/* this function directly from akismet.php by Matt Mullenweg.  *props* */
function AtD_http_post($request, $host, $path, $port = 80) 
{
   $http_request  = "POST $path HTTP/1.0\r\n";
   $http_request .= "Host: $host\r\n";
   $http_request .= "Content-Type: application/x-www-form-urlencoded\r\n";
   $http_request .= "Content-Length: " . strlen($request) . "\r\n";
   $http_request .= "User-Agent: AtD/0.1\r\n";
   $http_request .= "\r\n";
   $http_request .= $request;            

   $response = '';                 
   if( false != ( $fs = @fsockopen($host, $port, $errno, $errstr, 10) ) ) 
   {                 
      fwrite($fs, $http_request);

      while ( !feof($fs) )
      {
          $response .= fgets($fs);
      }
      fclose($fs);
      $response = explode("\r\n\r\n", $response, 2);
   }
   return $response;
}

$data = AtD_http_post($postText, 'service.afterthedeadline.com', $url);

// Set RPC response headers
header('Content-Type: text/xml');
echo $data[1];

eZDB::checkTransactionCounter();
eZExecution::cleanExit();

?>