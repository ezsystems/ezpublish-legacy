<h3>{$result_number}. Missing MBString extension</h3>

<p>
 eZ publish comes with a good list of supported charsets by default, however they can be a bit slow
 due to being made in pure PHP code. Luckily eZ publish supports the mbstring extension
 for handling some of the charset.
</p>
<p>
 By enabling the mbstring extension eZ publish will have access to more charset and also be able
 to process some of them faster, such as Unicode and iso-8859-*. This is recommended for multilingual sites
 and sites with more exotic charsets.
</p>
<p>
 The complete list of charsets mbstring supports are:
</p>
<p class="example">
 {section name=Charset loop=$test_result[2].charset_list}
 {$:item}
 {delimiter}, {/delimiter}
 {/section}
</p>
<h3>Installation</h3>
<p>
 Installation the mbstring extension is done by compiling PHP with the <tt class="option">--enable-mbstring</tt>.
 More information on enabling the extension can be found at <a target="_other" href="http://www.php.net/manual/en/ref.mbstring.php">php.net</a>.
</p>
<blockquote class="note">
 <p>
  <b>Note:</b>
  Do not enable mbstring function overloading, eZ publish will only use the extension whenever it's needed.
 </p>
</blockquote>