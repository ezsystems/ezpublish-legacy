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

    <form method="post" action="{$script}">

        <table width="600" border="0" cellspacing="0" cellpadding="0">
        {section name=unpackDemo show=$unpackDemo}
        <tr>
            <td class="normal">Trying to unpack the demo data:</td>
            <td class="normal">{$unpackDemo_msg}</td>
        </tr>
        {/section}
        {section name=connectDb show=$connectDb}
        <tr>
            <td class="normal">Trying to connect to database:</td>
            <td class="normal">{$dbConnect}</td>
        </tr>
        {/section}
        {section name=createDb show=$createDb}
        <tr>
            <td class="normal">Trying to create the database "{$dbName}":</td>
            <td class="normal">{$dbCreate}</td>
        </tr>
        {/section}
        {section name=deleteTables show=$deleteTables}
        <tr>
            <td class="normal">Trying to delete old tables:</td>
            <td class="normal">{$deleteTablesOK}</td>
        </tr>
        {/section}
        {section name=createUser show=$createUser}
        <tr>
            <td class="normal">Trying to create the user "{$dbCreateUser}":</td>
            <td class="normal">{$dbCreateUserMsg}</td>
        </tr>
        {/section}
        {section name=createSql show=$createSql}
        <tr>
            <td class="normal">Trying to create the database structures:</td>
            <td class="normal">{$dbCreateSql}</td>
        </tr>
        {/section}
        <tr>
            <td colspan="2" align="center" class="normal">
                <hr />
            {section name=continue show=$continue}
                <p>No errors occurred.</p>
                <input type="hidden" name="nextStep" value="{$nextStep}" />
                <p><button name="buttonNextStep" type="submit">Next step</button></p>
            {section-else}
                <h2>Error:</h2>
                <table border="0" cellspacing="5" cellpadding="5">
                <tr valign="top">
                    <td class="normal"><b>Description:</b></td>
                    <td class="normal">{$errorDescription}</td>
                </tr>
                <tr valign="top">
                    <td class="normal"><b>Suggestion:</b></td>
                    <td class="normal">{$errorSuggestion}</td>
                </tr>
                </table>
                <input type="hidden" name="nextStep" value="{$prevStep}" />
                <p><button type="submit">previous step</button></p>
            {/section}
            </td>
        </tr>
        </table>
        {section name=handover loop=$handover}
        <input type="hidden" name="{$handover:item.name}" value="{$handover:item.value}" />
        {/section}
    </form>
</div>
</body>
</html>
