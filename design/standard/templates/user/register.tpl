<form action={concat($module.functions.register.uri)|ezurl} method="post" name="Register">

<div class="maincontentheader">
<h1>Sign up form:</h1>
</div>

{section show=$message}
<div class="warning">
<h2>{$message}</h2>
</div>
{/section}

<h2>User Profile:</h2>

{section name=UserAttributes loop=$userAttributes}
<div class="block">
<label>{$UserAttributes:item.name}:</label><div class="labelbreak"></div>
<input class="box" type="text" name="ContentObjectAttribute_{$UserAttributes:item.classAttribute_id}" size="20" value="{$UserAttributes:item.value}" />
</div>
{/section}

<h2>User Account:</h2>

<div class="block">
{section show=$userIDNotValid}*{/section}
<label>Login ID:</label><div class="labelbreak"></div>
<input class="halfbox" type="text" name="Login_id" size="20" value="{$login}" />
</div>

<div class="block">
{section show=$emailNotValid}*{/section}
<label>E-mail:</label><div class="labelbreak"></div>
<input class="halfbox" type="text" name="Email" size="20" value="{$email}" />
</div>

<div class="block">
<div class="element">
{section show=$passwordNotValid}*{/section}
<label>Password:</label><div class="labelbreak"></div>
<input class="halfbox" type="password" name="Password" size="20" value="{$password}" />
</div>
<div class="element">
{section show=$passwordNotValid}*{/section}
<label>Password confirm:</label><div class="labelbreak"></div>
<input class="halfbox" type="password" name="Password_confirm" size="20" value="{$passwordConfirm}" />
</div>
<div class="break"></div>
</div>

<div class="buttonblock">
<input class="button" type="submit" name="StoreButton" value="Register" />
<input class="button" type="submit" name="CancelButton" value="Cancel" />
</div>

</form>
