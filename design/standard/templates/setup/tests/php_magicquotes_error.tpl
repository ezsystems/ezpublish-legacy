<h3>{$result_number}. PHP option <i>Magic Quotes</i> is enabled</h3>

<p>
 eZ publish will work with this option on however it will lead to some minor performance issues
 since all input variables need to be be converted back to <i>normal</i>.
</p>
<p>
 It's recommended that the option is turned off. To turn it off edit your <i>php.ini</i> configuration and
 set <i>magic_quotes_gpc</i> and <i>magic_quotes_runtime</i> to <i>0</i>.
 More information on the subject can be found at <a href="http://www.php.net/manual/en/ref.info.php#ini.magic-quotes-gpc">php.net</a>.
</p>
<label>Configuration example:</label><br/>
<pre class="example">
magic_quotes_gpc = 0
magic_quotes_runtime = 0
</pre>