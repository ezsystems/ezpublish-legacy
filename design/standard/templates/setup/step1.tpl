<html>
<head>
    <title>eZ publish Setup - Step {$step}</title>
    <link rel="stylesheet" type="text/css" href="design/standard/stylesheets/core.css" />
    <link rel="stylesheet" type="text/css" href="design/standard/stylesheets/admin.css" />
    <link rel="stylesheet" type="text/css" href="design/standard/stylesheets/debug.css" />	
</head>
<body>


<div align="center">
    <h1>eZ publish setup</h1>
    <h3>- Step {$step} -</h3>

    <hr width="50%" />
    <table border="0" cellspacing="5" cellpadding="5">
    <tr>
        <td></td>
        <td align="center"><b>Requirement</b></td>
        <td align="center"><b>Status</b></td>
        <td align="center"><b>Pass</b></td>
    </tr>
{section name=items loop=$itemsResult}
    <tr>
        <td>{$items:item.desc}</td>
        <td align="center">{$items:item.req}</td>
        <td align="center">{$items:item.status}</td>
        <td align="center" class="{$items:item.class}">{$items:item.pass}</td>                
    </tr>
{/section}
    </table>
<hr width="50%" />

{section name=continue show=$continue}
No critical test failed. You can continue installing eZ publish.
<hr width="50%" />
<form method="post" action="{$script}">
    {section name=handover loop=$handover}
    <input type="hidden" name="{$continue:handover:item.name}" value="{$continue:handover:item.pass}" />
    {/section}
    <input type="hidden" name="nextStep" value="{$nextStep}" />
    <button name="buttonNextStep" type="submit">Next Step</button>
</form>
{section-else}
<h1 class="ezsetup_fatal">Error:</h1>
<table border="0" cellspacing="5" cellpadding="5">
<tr valign="top">
    <td><b>Description:</b></td>
    <td>{$errorDescription}</td>
</tr>
<tr valign="top">
    <td><b>Suggestion:</b></td>
    <td>{$errorSuggestion}</td>
</tr>
</table>
{/section}

</div>
</body>
</html>    
