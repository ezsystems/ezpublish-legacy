<div class="message-error">
<h2>{'The requested page could not be displayed. (22)'|i18n( 'design/admin/error/kernel' )}</h2>
{section show=$parameters.check.view_checked}
    <p>{'The requested view can not be accessed.'|i18n( 'design/admin/error/kernel' )}</h2>
    <p>{'The <%view> within the <%module> is disabled and thus it can not be accessed.'|i18n( 'design/admin/error/kernel',, hash( '%view', $parameters.check.view, '%module', $parameters.check.module ) )|wash}</li>
{section-else}
    <p>{'The requested module can not be accessed.'|i18n( 'design/admin/error/kernel' )}</h2>
    <p>{'The <%module> module is disabled and thus it can not be accessed.'|i18n( 'design/admin/error/kernel',, hash( '%module', $parameters.check.module ) )|wash}</h2>
{/section}
</div>


{section show=$embed_content}
    {$embed_content}
{/section}
