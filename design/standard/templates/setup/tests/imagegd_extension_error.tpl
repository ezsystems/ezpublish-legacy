<h3>{$result_number}. Missing imagegd extension</h3>
<p>
 The imagegd extension is not available to eZ publish. Without it eZ publish will only be able to do
 conversion using ImageMagick and the <i>texttoimage</i> template operator will not be available.
</p>
<blockquote class="note">
 <p><b>Note:</b> Future releases of eZ publish will have more advanced image support by using the imagegd extension.</p>
</blockquote>
<p>
 To enable imagegd you need to recompile PHP with support for it,
 more information on that subject is available at <a target="_other" href="http://www.php.net/manual/en/ref.image.php">php.net</a>.
</p>
