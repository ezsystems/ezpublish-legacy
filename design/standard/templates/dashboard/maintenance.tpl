<h2>{'Software update and Maintenance'|i18n( 'design/admin/dashboard/maintenance' )}</h2>

<p>{'Your installation: <span id="ez-version">%1</span>'|i18n( 'design/admin/dashboard/maintenance', , array( fetch( 'setup', 'version' ) ) )}</p>
<p>{'You are using %edition, the <span id="ez-publish-community-project-is-innovative-and-cutting-edge">innovative and cutting-edge</span> version of eZ Publish, built by <a href="%ez_link">eZ Systems</a> and the <a href="%ez_community_link">eZ Community</a>.</p>
<p>If this platform is critical for your business, we strongly recommend to subscribe to the Enterprise Edition of eZ Publish. More on <a href="%ez_link">eZ Systems</a>\' website.'|i18n( 'design/admin/dashboard/maintenance', , hash( '%edition', $edition, '%ez_link', "http://ez.no", '%ez_community_link', concat( 'http://share.ez.no?utm_content=eZ+Publish+Community+Project+', fetch( 'setup', 'version' ) , '&utm_source=eZ+Publish+Community+Project+Dashboard&utm_medium=eZ+Publish+Community+Project+Dashboard&utm_campaign=eZ+Publish+Community+Project+Dashboard' ) ) )}
</p>
