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
<form method="post" action="{$script}">

    {section name=handover loop=$handover}
    <input type="hidden" name="{$handover:item.name}" value="{$handover:item.value}" />
    {/section}


    <input type="hidden" name="nextStep" value="{$prevStep}" />
    
    Choose a title for your site: <input type="text" size="20" name="siteName" value="{$siteName}" /><br />
    Enter the URL to this site: <input type="text" size="20" name="siteURL" value="{$siteURL}" /><br />
    {* Enter the relative URL to the default index page: <input type="text" size="20" name="siteIndexPage" value="{$siteIndexPage}" /><br /> *}
    Charset to use: <select name="siteCharset">
    {* this should be the first item so people use it as a default *}
    <option value="iso-8859-1">iso-8859-1 (Default)</option>
    {section name=charsets loop=$charsetArray}
    <option value="{$charsets:item}">{$charsets:item}</option>
    {/section}
    </select>
	<hr />
	<p>eZ systems would like you to register this installation of eZ publish. No confidential data
	will be transmitted and you will not get any emails of eZ systems because of this. This data
	will help to improve eZ publish.</p>

	<p>The following data will be send to eZ systems:</p>
	<ul>
		<li>The test results of the first test page (PHP version, modules, programs)</li>
		<li>The database type you are using<li>
		<li>The name of your site as you enter above</li>
		<li>The domain of your site</li>
		<li>The charset you choose to use</li>
	</ul>
	Send register email to eZ systems? <input type="checkbox" name="sendEmail" value="sendEmail" />

	<p>Please enter your email address: <input type="text" size="20" name="emailAddress" /><br />
	If you can't send emails from your web server, please enter a mail server: <input type="text"
	size="20" name="emailServer" /><br />
	If you need to enter a login to be able to send emails, please enter it here:<br />
	Username: <input type="text" size="20" name="emailUser" /><br />
	Password: <input type="password" size="20" name="emailPassword" /><br />
	Please write any comments that you have about this install or eZ publish:<br />
	<textarea name="comment" cols="70" rows="5"></textarea></p>
	<hr />
	If you are sending an email over SMTP then the next step might take a bit longer. Please be
	patient.<br />
	
    <input type="hidden" name="nextStep" value="{$nextStep}" />
    <p><button name="buttonNextStep" type="submit">Next step</button></p>
</form>

</div>




</body>
</html>    
