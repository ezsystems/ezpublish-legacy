<form method="post" name="forgetpassword" action={"/user/forgetpassword/"|ezurl}>
<div>
<label for="login">Login</label>
<input type="text" name="UserLogin" value="" />
</div>
<div>
<label for="email">E-mail:</label>
<input type="text" name="UserEmail" size="40" value="">
</div>
<div class="buttonblock">
<input type="submit" name="GenerateButton" value="{'Generate'|i18n('design/standard/user')}" />
<input type="submit" name="CancelButton" value="{'Discard'|i18n('design/standard/user')}" />
</div>
</form>