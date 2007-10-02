{let version=fetch( content, version, hash( object_id, $upload.content.object_id, version_id, $upload.content.object_version ) )}

<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h1 class="context-title">{'Upload a file and relate it to <%version_name>'|i18n( 'design/admin/content/upload_related',, hash( '%version_name', $version.version_name ) )|wash}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-bl"><div class="box-br"><div class="box-content">

<div class="block">

<p>{'This operation allows you to upload a file and add it as a related object.'|i18n( 'design/admin/content/upload_related' )}</p>
<ul>
<li>{'When the file is uploaded, an object will be created according to the type of the file.'|i18n( 'design/admin/content/upload_related' )}</li>
<li>{'The newly created object will be placed within the specified location.'|i18n( 'design/admin/content/upload_related' )}</li>
<li>{'The newly created object will be automatically related to the draft being edited (<%version_name>).'|i18n( 'design/admin/content/upload_related',, hash( '%version_name', $version.version_name ) )|wash}</li>
</ul>
<p>{'Select the file you want to upload then click the "Upload" button.'|i18n( 'design/admin/content/upload_related' )}</p>

</div>

{* DESIGN: Content END *}</div></div></div></div></div></div>

</div>

{/let}
