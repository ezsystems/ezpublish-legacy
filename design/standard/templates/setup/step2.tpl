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

    <table width="600" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td>
            <hr />
            <p>Now we need information about the database eZ publish should use.</p>
            <p>
            <form method="post" action="{$script}">
                <table border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td class="normal">Database type:</td>
                    <td rowspan="7" class="normal">&nbsp;&nbsp;</td>
                    <td class="normal">
                        <select name="dbType">
                            {section name=databases loop=$databasesArray}
                            <option value="{$databases:item.name}">{$databases:item.desc}</option>
                            {/section}
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="normal">Database server:</td>
                    <td class="normal"><input type="text" name="dbServer" size="25" value="{$dbServer}" /></td>
                </tr>
                <tr>
                    <td class="normal">Database name:</td>
                    <td class="normal"><input type="text" name="dbName" size="25" value="{$dbName}" maxlength="60" /></td>
                </tr>
                <tr>
                    <td class="normal">Username:</td>
                    <td class="normal"><input type="text" name="dbMainUser" size="25" value="{$dbMainUser}" /></td>
                </tr>
                <tr>
                    <td class="normal">Password:</td>
                    <td class="normal"><input type="password" name="dbMainPass" size="25" /></td>
                </tr>
                <tr>
                    <td class="normal">Database charset:</td>
                    <td class="normal"><select name="dbCharset">
                        {section name=charsets loop=$charsetArray}
                        <option value="{$charsets:item}">{$charsets:item}</option>
                        {/section}
                    </select></td>
                </tr>
                <tr>
                    <td class="normal">Delete old tables?</td>
                    <td class="normal"><input type="checkbox" name="dbDeleteTables" {$dbDeleteTables} value="yes" /> (dangerous!)</td>
                </tr>
                <tr>
                    <td colspan="3">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="3" class="normal">
                        <p>If you want to create a new user for this database, then enter the username and password of a user who is
                        allowed to create new users in the field above. Then enter the new username and password who you want to create
                        in the fields below. If you only have one username and no rights to create new users, leave the fields below
                        blank.</p>
                    </td>
                </tr>
                <tr>
                    <td class="normal">New username:</td>
                    <td rowspan="3" class="normal">&nbsp;&nbsp;</td>
                    <td class="normal"><input type="text" name="dbCreateUser" size="25" value="{$dbCreateUser}" /></td>
                </tr>
                <tr>
                    <td class="normal">New password:</td>
                    <td class="normal"><input type="password" name="dbCreatePass" size="25" /></td>
                </tr>
                <tr>
                    <td class="normal">Password again:</td>
                    <td class="normal"><input type="password" name="dbCreatePass2" size="25" /></td>
                </tr>
                {section name=unpackDemo show=$unpackDemo}
                <tr>
                    <td class="normal" colspan="3">
                    	<p>The tests showed that you have zlib installed. This means that we can install some demo data which shows you what eZ publish can do.</p>
                    	<p>Shall we install the demo data? <input type="checkbox" name="unpackDemo" value="true" checked /></p>
            	    </td>
            	</tr>
            	{/section}
                </table>

                <input type="hidden" name="dbEncoding" value="{$dbEncoding}" />
                {section name=handover loop=$handover}
                <input type="hidden" name="{$handover:item.name}" value="{$handover:item.value}" />
                {/section}

                <hr />
            	<p>The next step will take some time because we will create the database structures. Please be patient.</p>
                <input type="hidden" name="nextStep" value="{$nextStep}" />
                <p align="center"><button name="buttonNextStep" type="submit">Next step</button></p>
            </form>
            </td>
        </tr>
    </table>

</div>
</body>
</html>
