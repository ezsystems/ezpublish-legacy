<h3>{$result_number}. {"File uploading is disabled"|i18n("design/standard/setup/tests")}</h3>

<p>
 {"File uploading is not enabled which means that it's impossible for eZ publish to handle file uploading. All other parts of eZ publish will still work fine but it's recommended to enable file uploads."|i18n("design/standard/setup/tests")}
</p>
<h3>{"Configuration"|i18n("design/standard/setup/tests")}</h3>
<p>
 {"Enabling file uploads is done by setting %1 in php.ini. Refer to the PHP manual for how to set configuration switches"|i18n("design/standard/setup/tests",,array('<tt class="option">file_uploads = "1"</tt>'))}
 {"More information on enabling the extension can be found by reading %1 and %2"|i18n("design/standard/setup/tests",,array('<a target="_other" href="http://www.php.net/manual/en/features.file-upload.php">file uploads</a>','<a target="_other" href="http://www.php.net/manual/en/configuration.directives.php#ini.file-uploads">configuration directives</a>'))}.
</p>
