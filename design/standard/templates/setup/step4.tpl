<html>
<head>
    <title>eZ publish Setup - Step {$step}</title>
</head>
<body>


<div align="center">
    <h1>eZ publish setup</h1>
    <h3>- Step {$step} -</h3>

    <p>
    <table border="0" cellspacing="5" cellpadding="0">
    <tr>
        <td>Writing the new configuration to "site.ini":</td>
        <td>{$configWrite}</td>
    </tr>
    </table>
{section name=continue show=$continue}
    <h3>eZ publish should now run on this system.</h3>
    <a href="{$url}">Go here to see eZ publish</a>
{section-else}
Sorry, but it was not successful.
{/section}
</div>




</body>
</html>    