<form action={concat($module.functions.activate.uri,"/",$LoginID,"/",$UserIDHash)|ezurl} method="post" name="Register">

<div class="maincontentheader">
<h1>Activate account</h1>
</div>

<h2>{$message}</h2>

<div class="block">
<div class="element">
<label>Login ID:</label><div class="labelbreak"></div>
<input class="halfbox" type="text" name="Login_id" size="20" value="" />
</div>
<div class="element">
<label>Password:</label><div class="labelbreak"></div>
<input class="halfbox" type="password" name="Password" size="20" value="" />
</div>
<div class="break"></div>
</div>

<div class="buttonblock">
<input class="button" type="submit" name="ActivateButton" value="Activate" />
</div>

</form>
