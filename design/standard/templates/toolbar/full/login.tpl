<div class="toolbox">
    <div class="toolbox-design">
    <h2>Login</h2>
     {section show=eq($current_user.is_logged_in)}
     <form method="post" action={"/user/login/"|ezurl}>
         <label for="id1">{"Login"|i18n("design/standard/user")}</label>
         <input class="textinput" type="text" size="10" name="Login" id="id1" value="{$User:login}" />

         <label for="id2">{"Password"|i18n("design/standard/user")}</label>
         <input class="textinput" type="password" size="10" name="Password" id="id2" value="" />

         <input type="image" src={"t1/t1-button.gif"|ezimage} align="bottom" width="18" height="18" alt="Login" />
     </form>
{section-else}
      <div class="info-text">
          <p>
           Logged in as: {$current_user.contentobject.name}
          </p>
      </div>
      <div class="content-link">
          <p>
          <a href={"/user/logout"|ezurl}>Logout</a>
          </p>
      </div>
{/section}
    </div>
</div>
