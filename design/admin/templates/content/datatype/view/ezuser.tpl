<table class="list" cellspacing="0">

<tr>
    <th>{'User ID'|i18n( 'design/admin/content/datatype/ezuser' )}</th>
    <th>{'Username'|i18n( 'design/admin/content/datatype/ezuser' )}</th>
    <th>{'E-mail'|i18n( 'design/admin/content/datatype/ezuser' )}</th>
</tr>

<tr>
    <td>{$attribute.content.contentobject_id}</td>
    <td>{$attribute.content.login|wash( xhtml )}</td>
    <td><a href="mailto:{$attribute.content.email}">{$attribute.content.email}</a></td>
</tr>

</table>

<p><a href={concat( '/user/setting/', $attribute.contentobject_id )|ezurl} title="{'Enable/disable the user account and set the maximum allowed number of concurrent logins.'}">{'Configure user account settings'|i18n( 'design/admin/content/datatype/ezuser' )}</a></p>
