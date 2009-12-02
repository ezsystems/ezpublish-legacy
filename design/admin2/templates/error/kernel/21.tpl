<div class="message-error">
<h2><span class="time">[{currentdate()|l10n( shortdatetime )}]</span> {'The requested page could not be displayed. (21)'|i18n( 'design/admin/error/kernel' )}</h2>
<p>{'The requested view <%view> could not be found in the <%module> module.'|i18n( 'design/admin/error/kernel',, hash( '%view', $parameters.view, '%module', $parameters.module ) )|wash}</p>
<p>{'Possible reasons'|i18n( 'design/admin/error/kernel' )}:</p>
<ul>
    <li>{'The name of the view was misspelled. Try changing the URL.'|i18n( 'design/admin/error/kernel' )}</li>
    <li>{'The <%module> module does not have a <%view> view.'|i18n( 'design/admin/error/kernel',, hash( '%module', $parameters.module, '%view', $parameters.view ) )|wash}</li>
    <li>{'The site is using URL matching to determine which siteaccess to use, but the name of the siteaccess is missing from the URL. Try to add the name of the siteaccess; it should be specified before the name of the module.'|i18n( 'design/admin/error/kernel' )}</li>
</ul>
</div>

{if $embed_content}
    {$embed_content}
{/if}
