{let program_info=$test_result[2]}
<h3>{$result_number}. Missing ImageMagick program</h3>

<p>
 The ImageMagick program is not available to eZ publish. Without it eZ publish will not be able to do
 image conversion unless the imagegd extension is available.
</p>
<p>
 If you known where the program is installed, the executable is called <i>convert</i> or <i>convertim</i>, then enter the path
 in the input field below and do a recheck (Separate multiple directories with a {$program_info.env_separator}).
 <div class="buttonblock">
  <label>Path to ImageMagick:</label><br/>
  <input class="box" type="text" size="40" name="imagemagick_program_ExtraPath" value="{section name=Path loop=$program_info.extra_path}{$:item}{delimiter}{$program_info.env_separator}{/delimiter}{/section}" />
 </div>
</p>
<h3>Installation</h3>
<p>ImageMagick may be downloaded from <a target="_other" href="http://www.imagemagick.org/">imagemagick.org</a>.</p>
{/let}