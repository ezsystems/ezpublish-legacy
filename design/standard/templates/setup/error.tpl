<div class="error">
<h2>Error</h2>
<h3>Description:</h3>
<p>{$errorDescription}</p>
<h3>Suggestion:</h3>
<p>{$errorSuggestion}</p>
</div>

<form method="post" action="ezsetup.php">
<input type="hidden" name="dbServer" value="{$dbServer}" />
<input type="hidden" name="dbName" value="{$dbName}" />
<input type="hidden" name="dbMainUser" value="{$dbMainUser}" />
<input type="hidden" name="dbCreateUser" value="{$dbCreateUser}" />

<div class="buttonblock">
<button name="nextStep" type="submit" value"Go back to step 1" />
</div>

</form>