{let required_version=$test_result[2].needed_version
     current_version=$test_result[2].current_version}
<h3>{$result_number}. Insufficient PHP version</h3>
<p>
 Your PHP version, which is {$current_version}, does not meet the minimum requirements of {$required_version}.
<p>
 A newer version of PHP can be download at <a target="_other" href="http://www.php.net">php.net</a>.
 You must upgrade to at least version {$required_version}, but an even newer version, such as 4.2.3, is highly recommended.
</p>
{/let}