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
    
    <input type="hidden" name="nextStep" value="{$nextStep}" />
    <p><button name="buttonNextStep" type="submit">Next step</button></p>
</form>

</div>




</body>
</html>    