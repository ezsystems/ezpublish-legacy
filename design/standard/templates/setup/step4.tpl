<html>
<head>
    <title>eZ publish Setup - Step {$step}</title>
    <link rel="stylesheet" type="text/css" href="design/standard/stylesheets/core.css" />
    <link rel="stylesheet" type="text/css" href="design/standard/stylesheets/admin.css" />
    <link rel="stylesheet" type="text/css" href="design/standard/stylesheets/debug.css" />
</head>
<body>


<div align="center">
    <h1>eZ publish setup</h1>
    <h3>- Step {$step} -</h3>
    <hr width="600" />

    <h2>Configuration</h2>
    <br />
    <form method="post" action="{$script}">
        <table width="600" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td colspan="2">
                <p>Now you can set some initial configuration settings. You can always change them by directly editing "site.ini.php".</p>
                <p>eZ systems would also like you to register this installation of eZ publish. No confidential data
            	will be transmitted and eZ systems will not use or sell your personal details for unsolicited emails. This data
            	will help to improve eZ publish.</p>
            	<p>The following data will be send to eZ systems:</p>
            	<ul>
            		<li>The test results of the first test page (PHP version, modules, programs)</li>
            		<li>The database type you are using</li>
            		<li>The name of your site as you enter above</li>
            		<li>The domain of your site</li>
            		<li>The charset you choose to use</li>
            	</ul>
            	<p>If you want to register, then please check the checkbox and fill out the fields below. If you can't send emails directly from
            	your webserver, then fill out the details for the SMTP server. Only fill in the username and password if you have to login
            	into your SMTP server. Otherwise leave these fields empty.</p>
            	<hr />
            </td>
        </tr>
        <tr>
            <td class="normal">Title of your site:</td>
            <td><input type="text" size="20" name="siteName" value="{$siteName}" /></td>
        </tr>
        <tr>
            <td class="normal">URL to your site:</td>
            <td><input type="text" size="20" name="siteURL" value="{$siteURL}" /></td>
        </tr>
        <tr>
            <td class="normal">Charset to use:</td>
            <td class="normal">
                <select name="siteCharset">
                {* this should be the first item so people use it as a default *}
                <option value="iso-8859-1">iso-8859-1 (Default)</option>
                {section name=charsets loop=$charsetArray}
                <option value="{$charsets:item}">{$charsets:item}</option>
                {/section}
                </select>
            </td>
        </tr>
        <tr><td colspan="2">&nbsp;</td></tr>
        <tr>
            <td class="normal">Send register email to eZ systems?</td>
            <td class="normal"><input type="checkbox" name="sendEmail" value="sendEmail" /></td>
        </tr>
        <tr>
            <td class="normal">Your email address:</td>
            <td class="normal"><input type="text" size="20" name="emailAddress" /></td>
        </tr>
        <tr>
            <td class="normal">SMTP server:</td>
            <td class="normal"><input type="text" size="20" name="emailServer" /></td>
        </tr>
        <tr>
            <td class="normal">SMTP username:</td>
            <td class="normal"><input type="text" size="20" name="emailUser" /></td>
        </tr>
        <tr>
            <td class="normal">SMTP password:</td>
            <td class="normal"><input type="password" size="20" name="emailPassword" /></td>
        </tr>
        <tr>
            <td align="center" colspan="2" class="normal">
	            Please write any comments that you have about this install or eZ publish:<br />
	            <textarea name="comment" cols="70" rows="5"></textarea></p>
	        </td>
	    </tr>
        <tr><td colspan="2">&nbsp;</td></tr>
        <tr>
	        <td align="center" colspan="2" class="normal">
	        <hr />
	        If you are sending an email over SMTP then the next step might take a bit longer.
	        Please be patient.<br />
            <input type="hidden" name="nextStep" value="{$nextStep}" />
            <p><button name="buttonNextStep" type="submit">Next step</button></p>
        </tr>
    </table>

    {section name=handover loop=$handover}
    <input type="hidden" name="{$handover:item.name}" value="{$handover:item.value}" />
    {/section}

</form>

</div>




</body>
</html>
