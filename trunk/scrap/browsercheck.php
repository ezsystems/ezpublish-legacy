<!-- Browser check / Hellstrøm 6.8.2002 -->

<?
if ( strstr( getenv( 'HTTP_USER_AGENT' ), 'Windows' ) ) 
{
$browser_os = "Windows";
}
else if ( strstr( getenv( 'HTTP_USER_AGENT' ), 'Linux' ) )
{
$browser_os = "Linux";
}
else if ( strstr( getenv( 'HTTP_USER_AGENT' ), 'Mac' ) )
{
$browser_os = "Mac";
}
?>

<?
if ( strstr( getenv( 'HTTP_USER_AGENT' ), '/1' ) ) 
{
$browser_version = 1;
}
else if ( strstr( getenv( 'HTTP_USER_AGENT' ), '/2' ) )
{
$browser_version = 2;
}
else if ( strstr( getenv( 'HTTP_USER_AGENT' ), '/3' ) )
{
$browser_version = 3;
}
else if ( strstr( getenv( 'HTTP_USER_AGENT' ), '/4' ) )
{
$browser_version = 4;
}
else if ( strstr( getenv( 'HTTP_USER_AGENT' ), '/5' ) )
{
$browser_version = 5;
}
else if ( strstr( getenv( 'HTTP_USER_AGENT' ), '/6' ) )
{
$browser_version = 6;
}
?>

<?
if ( strstr( getenv( 'HTTP_USER_AGENT' ), 'Opera' ) ) 
{
$browser_name = "Opera";
}
else if ( strstr( getenv( 'HTTP_USER_AGENT' ), 'MSIE' ) )
{
$browser_name = "Internet Explorer";

$browser_version = intval(trim(substr($HTTP_USER_AGENT, 4 + strpos($HTTP_USER_AGENT, "MSIE"), 2)));
}
else if ( strstr( getenv( 'HTTP_USER_AGENT' ), 'Konqueror' ) )
{
$browser_name = "Konqueror";
}
else if ( strstr( getenv( 'HTTP_USER_AGENT' ), 'Netscape6' ) )
{
$browser_name = "Netscape";
$browser_version = 6;
}
else if ( strstr( getenv( 'HTTP_USER_AGENT' ), 'Mozilla' ) && ( !( strstr( $HTTP_USER_AGENT, "compatible" ) ) ) )
{
$browser_name = "Netscape";
}
else if ( strstr( getenv( 'HTTP_USER_AGENT' ), 'Mozilla' ) )
{
$browser_name = "Mozilla something";
}
else
{
$browser_name = "Unknown";
}
?>

<?
if ( $browser_os == "Windows" )
{
	if ( ( ( $browser_name == "Internet Explorer" ) && ( $browser_version >= 6 ) )
	|| ( $browser_name == "Netscape" ) )
	{
	?>
		<link rel="stylesheet" type="text/css" href="<? print $GlobalSiteIni->WWWDir; ?>/sitedesign/oneworld/style-larger.css" />
	<?	
	}
}
else if ( $browser_os == "Mac" )
{
	if ( ( $browser_name == "Netscape" ) && ( $browser_version = 4 ) )
	{
	?>
		<link rel="stylesheet" type="text/css" href="<? print $GlobalSiteIni->WWWDir; ?>/sitedesign/oneworld/style-larger.css" />
	<?	
	}
}
?>
