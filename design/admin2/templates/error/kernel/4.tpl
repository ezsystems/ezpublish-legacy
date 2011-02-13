<div class="message-warning">
<h2><span class="time">[{currentdate()|l10n( shortdatetime )}]</span> {'The requested page could not be displayed. (4)'|i18n( 'design/admin/error/kernel' )}</h2>
<p>{'The requested object has been moved and thus it is no longer available at the specified address.'|i18n( 'design/admin/error/kernel' )}</p>
<p>{'The system should automatically redirect you to the new location of the object.'|i18n( 'design/admin/error/kernel' )}
<p>{'If redirection fails, click on the following address: %url.'|i18n( 'design/admin/error/kernel',, hash( '%url', concat( '<a href=', $parameters.new_location|ezurl(), '>', $parameters.new_location|ezurl(), '</a>' ) ) )}</p>
</div>

