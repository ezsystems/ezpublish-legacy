<form enctype="multipart/form-data" method="post" action={'package/upload'|ezurl}>

<h2>{'Upload package'|i18n('design/standard/package')}</h2>

<p>{'Select the file containing your package and click the upload button'|i18n('design/standard/package')}</p>


<input type="hidden" name="MAX_FILE_SIZE" value="32000000"/>
<input name="PackageBinaryFile" type="file" />

<br/>
<input type="checkbox" name="InstallPackageCheck" value="1" checked="checked" /> {'Install package'|i18n('design/standard/package')}

<div class="buttonblock">
    <input class="button" type="submit" name="UploadPackageButton" value="{'Upload package'|i18n('design/standard/package')}" />
</div>

</form>
