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

    <h4>You selected:</h4>
    <table border="0" cellspacing="5" cellpadding="0">
    <tr>
        <td><b>Database type:</b></td>
        <td>{$dbType}</td>
        <td>&nbsp;&nbsp;</td>
        <td><b>Database server:</b></td>
        <td>{$dbServer}</td>
    </tr>
    <tr>
        <td><b>Database name:</b></td>
        <td>{$dbName}</td>
        <td></td>
        <td><b>Database main user:</b></td>
        <td>{$dbMainUser}</td>
    </tr>
    <tr>
        <td><b>Database new user:</b></td>
        <td>{$dbCreateUser}</td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    </table>

    <p>
    <table border="0" cellspacing="5" cellpadding="0">
    {section name=unpackDemo show=$unpackDemo}
    <tr>
        <td>Trying to unpack the demo data:</td>
        <td>{$unpackDemo_msg}</td>
    </tr>
    {/section}
    <tr>
        <td>Trying to connect to database:</td>
        <td>{$dbConnect}</td>
    </tr>
    {section name=createDb show=$createDb}
    <tr>
        <td>Trying to create the database "{$dbName}":</td>
        <td>{$dbCreate}</td>
    </tr>
    {/section}
    {section name=deleteTables show=$deleteTables}
    <tr>
        <td>Trying to delete old tables:</td>
        <td>{$deleteTablesOK}</td>
    </tr>
    {/section}
    {section name=createUser show=$createUser}
    <tr>
        <td>Trying to create the user "{$dbCreateUser}":</td>
        <td>{$dbCreateUserMsg}</td>
    </tr>
    {/section}
    {section name=createSql show=$createSql}
    <tr>
        <td>Trying to create the database structures:</td>
        <td>{$dbCreateSql}</td>
    </tr>
    {/section}    
    </table>
    </p>
<form method="post" action="{$script}">
    {section name=handover loop=$handover}
    <input type="hidden" name="{$handover:item.name}" value="{$handover:item.value}" />
    {/section}
    
    {section name=continue show=$continue}
    
    <input type="hidden" name="nextStep" value="{$nextStep}" />
    <p><button name="buttonNextStep" type="submit">Next step</button></p>
    
    {section-else}
    <hr width="50%" />
    <h2>Error:</h2>
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
    <hr width="50%" />
    <input type="hidden" name="nextStep" value="{$prevStep}" />
    <p><button type="submit">previous step</button></p>
    {* doesn't work yet {include uri="design/standard/templates/setup/error.tpl" name="error"} *}
    
    {/section}
</form>
</div>




</body>
</html>    
