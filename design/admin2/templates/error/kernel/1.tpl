<div class="message-error">
<h2><span class="time">[{currentdate()|l10n( shortdatetime )}]</span> {'The requested page could not be displayed. (1)'|i18n( 'design/admin/error/kernel' )}</h2>
<p>{'The system is unable to display the requested page because of security issues.'|i18n( 'design/admin/error/kernel' )}
<p>{'Possible reasons'|i18n( 'design/admin/error/kernel' )}:</p>
<ul>
    {if ne( $current_user.contentobject_id, $anonymous_user_id )}
    <li>{'Your account does not have the proper privileges to access the requested page.'|i18n( 'design/admin/error/kernel' )}</li>
    {else}
    <li>{'You are not logged in to the system. Please log in.'|i18n( 'design/admin/error/kernel' )}</li>
    {/if}
    <li>{'The requested page does not exist. Try changing the URL.'|i18n( 'design/admin/error/kernel' )}</li>
</ul>

{if is_set( $module_required )}
<p>&nbsp;</p>
<p>{'The following permission setting is required'|i18n( 'design/admin/error/kernel' )}:</p>
<ul>
<li>{'Module'|i18n( 'design/admin/error/kernel' )}:{$module_required}</li>
<li>{'Function'|i18n( 'design/admin/error/kernel' )}:{$function_required}</li>
</ul>
{/if}

</div>



{if eq( $current_user.contentobject_id, $anonymous_user_id )}

    {if $embed_content}

    {$embed_content}
    {else}

        <form method="post" action={'/user/login/'|ezurl}>

        <p>{'Click the "Log in" button in order to log in.'|i18n( 'design/admin/error/kernel' )}</p>
        <div class="buttonblock">
        <input class="button" type="submit" name="LoginButton" value="{'Login'|i18n( 'design/admin/error/kernel','Button')}" />
        </div>

        <input type="hidden" name="Login" value="" />
        <input type="hidden" name="Password" value="" />
        <input type="hidden" name="RedirectURI" value="{$redirect_uri}" />
        </form>

    {/if}

{/if}
