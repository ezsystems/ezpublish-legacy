<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="no" lang="no">

<head>
    <title>eZ publish Setup - Step {$step}</title>

    <link rel="stylesheet" type="text/css" href="/design/standard/stylesheets/core.css" />
    <link rel="stylesheet" type="text/css" href="/design/standard/stylesheets/debug.css" />
</head>
<body>

    <h1>eZ publish setup</h1>

    <h2>Step {$step}</h2>

    {section name=continue show=$continue}
            <div class="error">
            <p>Very good, no critical test failed! You might want to look through the tests to see which ones failed but were not critical.
            Fixing them will improve the functionality of eZ publish.</p>
            <p>You can continue to the next step.</p>
            </div>

            <form method="post" action="{$script}">
                {section name=handover loop=$handover}
                <input type="hidden" name="{$continue:handover:item.name}" value="{$continue:handover:item.value}" />
                {/section}
                <input type="hidden" name="nextStep" value="{$nextStep}" />
                <div class="buttonblock">
                <button class="button" name="buttonNextStep" type="submit" value="Next Step" />
                </div>
            </form>

            {section-else}

            <div class="error">
            <p>One or more critical tests failed. Please have a look through the tests below to
            see which tests failed. Each failed test should tell you how you can fix the problem. Also you might
            want to have a look at the tests that failed but were not critical. Fixing those problems will improve the
            functionality of eZ publish.</p>

            <p>Reload this page or click <a href="{$script}">here</a> once you've fixed the critical tests.</p>
            </div>
            {/section}

    <h2>Tests</h2>

    <table width="100%" border="0" cellspacing="0" cellpadding="0">
{section name=items loop=$itemsResult}
    <tr>
        <td>
            <table width="100%" border="0" cellspacing="0" cellpadding="2">
            <tr>
                <td class="ezsetup_header">{$items:item.desc}</td>
                <td class="{$items:item.class_pass}" align="right">{$items:item.pass}</>
            </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td width="100%">
            <table width="100%" border="0" cellspacing="0" cellpadding="2">
            <tr>
                <td width="150" valign="top">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td valign="top" class="ezsetup_req"><b>Requirement:</b></td>
                        <td valign="top" class="ezsetup_req">&nbsp;&nbsp;</td>
                        <td valign="top" class="ezsetup_req">{$items:item.req}</td>
                    </tr>
                    <tr>
                        <td valign="top" class="ezsetup_req" width="1%"><b>Status:</b></td>
                        <td valign="top" class="ezsetup_req">&nbsp;&nbsp;</td>
                        <td valign="top" class="ezsetup_req">{$items:item.status}</td>
                    </tr>
                    </table>
                </td>
                <td valign="top" rowspan="2" class="ezsetup_req">&nbsp;&nbsp;&nbsp;&nbsp;</td>
                <td width="450" valign="top" width="98%" rowspan="2" class="ezsetup_req" valign="top">{$items:item.message}</td>
            </tr>
            <tr>
            </tr>
            </table>
        </td>
    </tr>
    <tr><td>&nbsp;</td></tr>
{/section}
    </table>

</body>
</html>
