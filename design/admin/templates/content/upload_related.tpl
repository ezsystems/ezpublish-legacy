{let version=fetch( content, version, hash( object_id, $upload.content.object_id, version_id, $upload.content.object_version ) )}

<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h1 class="context-title">{'Upload a file and relate it to <%version_name>'|i18n( 'design/admin/content/upload_related',, hash( '%version_name', $version.version_name ) )|wash}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-bl"><div class="box-br"><div class="box-content">

<div class="block">
<p>{'Choose a file from your local machine and click the "Upload" button. An object will be created according to file type and placed in the chosen location.'|i18n( 'design/admin/content/upload_related' )}</p>
</div>

{* DESIGN: Content END *}</div></div></div></div></div></div>

</div>

{/let}
