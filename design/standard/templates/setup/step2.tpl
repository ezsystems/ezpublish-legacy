<html>
<head>
    <title>eZ publish Setup - Step {$step}</title>
</head>
<body>


<div align="center">
    <h1>eZ publish setup</h1>
    <h3>- Step {$step} -</h3>

    <hr width="50%" />

<p>
Now we need information about the database eZ publish should use.<br />
</p>
<p>
<form method="post" action="{$script}">
    <table border="1" cellspacing="1" cellpadding="1">
    <tr>
        <td>Please choose a database type:</td>
        <td><select name="dbType">
            {section name=databases loop=$databasesArray}
            <option value="{$databases:item.name}">{$databases:item.desc}</option>
            {/section}
        </select></td>
    </tr>
    <tr valign="top">
        <td>Database server:</td>
        <td><input type="text" name="dbServer" size="25" value="{$dbServer}" /><br />({$dbServerExpl})</td>
    </tr>
    <tr>
        <td>Name of database:</td>
        <td><input type="text" name="dbName" size="25" value="{$dbName}" maxlength="60" /></td>
    </tr>
    </table>
    
    <p>Now we need a user that is allowed to create databases:</p>

    <table border="1" cellspacing="1" cellpadding="1">
    <tr>
        <td>Username:</td>
        <td><input type="text" name="dbMainUser" size="25" value="{$dbMainUser}" /></td>
    </tr>
    <tr>
        <td>Password:</td>
        <td><input type="password" name="dbMainPass" size="25" /></td>
    </tr>
{*    <tr>
        <td colspan="2">If you want to create a new user to use the database, please enter the information here:</td>
    </tr>
    <tr>
        <td>Username:</td>
        <td><input type="text" name="dbCreateUser" size="25" value="{$dbCreateUser}" /></td>
    </tr>
    <tr>
        <td>Password:</td>
        <td><input type="password" name="dbCreatePass" size="25" /></td>
    </tr>
    <tr>
        <td>Password again:</td>
        <td><input type="password" name="dbCreatePass2" size="25" /></td>
    </tr> *}
    </table>
    {section name=databases2 loop=$databasesArray2}
    <input type="hidden" name="{$databases2:item.name}" value="{$databases2:item.pass}" />
    {/section}

    
    {* <input type="hidden" name="prevStep" value="{$prevStep}" /> *}
    <input type="hidden" name="nextStep" value="{$nextStep}" />
    <p>{* <button name="buttonPrevStep" type="submit">Previous step</button>&nbsp; *}
    <button name="buttonNextStep" type="submit">Next step</button></p>
</form>

</div>
</body>
</html>    