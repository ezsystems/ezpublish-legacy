<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="no" lang="no">

<head>
    <title>eZ publish Setup - Step {$step}</title>

    <link rel="stylesheet" type="text/css" href="/design/standard/stylesheets/core.css" />
    <link rel="stylesheet" type="text/css" href="/design/standard/stylesheets/debug.css" />
</head>
<body>

    <h1>eZ publish setup</h1>
    <h2>Step {$step}</h3>

    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td class="normal">Writing the new configuration to "settings/site.ini.php":</td>
        <td class="normal">{$configWrite}</td>
    </tr>
    <tr>
        <td colspan="2">
        {section name=continue show=$continue}
            <hr />
            <div align="center">
            <h2>Congratulations!</h2>
            <h2>eZ publish should now run on your system.</h2>
            </div>
            <p>
            If you need help with eZ publish, you can go to the <a href="http://developer.ez.no">eZ publish website</a>. If you find a bug (error),
            please go to <a href="http://developer.ez.no/developer/bugreports/">eZ publish bug reports</a> and report it. Only with your help
            we can fix the errors eZ publish might have and implement new features.
            </p>
            <p>
            If you ever want to restart this setup, change in the file "site.ini.php" the value "CheckValidity" under the block
            "SiteAccessSettings" to "true".
            </p>
            <p>
            You can find your new eZ publish website <a href="{$url}">here</a>. Enjoy one of the most successful web content management systems!
            </p>
        {section-else}
            <p>
            Sorry, but it was not successful. This situation should not occur because we tested everything before, but it did happen.
            Try to find the problem and then reload this page.
            </p>
        {/section}
        </td>
    </tr>
    </table>

</body>
</html>