


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

<form method="post" action="ezsetup.php">
<input type="hidden" name="dbServer" value="{$dbServer}" />
<input type="hidden" name="dbName" value="{$dbName}" />
<input type="hidden" name="dbMainUser" value="{$dbMainUser}" />
<input type="hidden" name="dbCreateUser" value="{$dbCreateUser}" />
<p><button name="nextStep" type="submit">go back to step 1</button></p>
</form>