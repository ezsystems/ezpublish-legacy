<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <title>Untitled Document</title>
    <link rel="stylesheet" type="text/css" href={"stylesheets/bookcorner.css"|ezdesign} />
    <link rel="stylesheet" type="text/css" href={"stylesheets/debug.css"|ezdesign} />

{* check if we need a http-equiv refresh *}
{section show=$site.redirect}
<meta http-equiv="Refresh" content="{$site.redirect.timer}; URL={$site.redirect.location}" />
{/section}

<!-- Meta information START -->

{section name=meta loop=$site.meta}
<meta name="{$meta:key}" content="{$meta:item}" />
{/section}

<meta name="MSSmartTagsPreventParsing" content="TRUE" />

<meta name="generator" content="eZ publish" />

<!-- Meta information END -->

<script language="JavaScript" type="text/JavaScript">
<!--
{literal}
function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}
{/literal}
//-->
</script>
</head>

<body link="#000000" vlink="#000000" alink="#000000">
<table width="700" border="0" cellspacing="0" cellpadding="0" style="border-style: solid; border-width: 1px; border-color: black;" bgcolor="#cccccc">
<tr>
   <th>
   Quick links
   </th>
</tr>
<tr>
    <td >
    <a href="/content/view/full/31/">Crossroads Forum</a>
    </td>
    <td >
    <a href="/content/view/news/26">News 24</a>
    </td>
    <td >
    <a href="/content/view/thumbnail/18/">Whitebox art gallery</a>
    </td>
    <td >
    <a href="/content/view/full/65/">the Book corner</a>
    </td>
</tr>
</table>

<p>&nbsp;</p><table width="700" height="633" border="0" align="left" cellpadding="0" cellspacing="0">
  <tr>
    <td height="106" valign="top" bgcolor="#333333"><a href={concat("content/view/full/",60)|ezurl}><img src={"bookcorner-logo.gif"|ezimage} width="700" height="87" border="0"></a> 
      <table width="700" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td height="19" bgcolor="#333333" class="links">

<!-- Quote START -->
            <div align="center"><font color="#FFFFFF">&quot;Wear the old coat and buy the new book&quot; (Austin Phelps)</font></div>
<!-- Quote END -->

          </td>
        </tr>
      </table> </td>
  </tr>
  <tr>
    <td valign="top">
<table width="702" height="398" border="0" cellpadding="0" cellspacing="0" bordercolor="#000000">
        <tr> 
          <td width="121" height="398" valign="top" bordercolor="#FFFFFF" bgcolor="#E2DCC0"> 

<!-- Left menu START -->
            <table width="108" border="0" align="center" cellpadding="2" cellspacing="0" bgcolor="#333399">
              <tr> 
                <td bgcolor="#E2DCC0" class="links">&nbsp;&nbsp;</td>
              </tr>
              <tr> 
                <td width="104" bgcolor="#E2DCC0" class="links">&nbsp;&nbsp;<strong><a href={concat("content/view/full/",65)|ezurl}>home</a></strong></td>
              </tr>
              <tr> 
                <td bgcolor="#E2DCC0" class="links">&nbsp;&nbsp;<strong><a href={concat("content/view/full/",64)|ezurl}>authors a-z</a></strong></td>
              </tr>
              <tr> 
                <td bgcolor="#E2DCC0" class="links">&nbsp;&nbsp;<strong><a href={concat("content/view/full/",62)|ezurl}>bestsellers</a></strong></td>
              </tr>
              <tr> 
                <td bgcolor="#E2DCC0" class="links">&nbsp;&nbsp;<strong>paperbacks</strong></td>
              </tr>
              <tr> 
                <td bgcolor="#E2DCC0">&nbsp;</td>
              </tr>
              <tr> 
                <td bgcolor="#E2DCC0"> 
                  <p>&nbsp; 
                    <select name="menu1" onChange="MM_jumpMenu('parent',this,0)">
                      <option>books</option>
                      <option>authors</option>
                    </select>
                  </p>
                  <p> <span class="links">&nbsp;&nbsp;<strong>Search</strong></span><br>
                    &nbsp; 
                    <input name="textfield" type="text" size="10">
                    <strong><span class="links">&nbsp;<br>
                    &nbsp;<font color="#FFFFFF">&nbsp;</font>GO</span></strong></p></td>
              </tr>
            </table>
<!-- Left menu START -->

            <p>&nbsp; </p></td>
          <td width="414" valign="top" bordercolor="#66CC00" bgcolor="#FFFFFF"><br> 

<!-- Content START -->
             {$module_result.content}
<!-- Content END -->

            <p class="links"><br>
            </p></td>
          <td width="165" valign="top" bordercolor="#66CC00" bgcolor="#333333"><br>

<!-- Top 20 books START -->
{section show=eq($DesignKeys:used.node,65)}
            <table width="138" height="352" border="0" align="right" cellpadding="0" cellspacing="0">
              <tr>
                <td width="138" valign="top">
<p class="heading"><strong><font color="#FFFFFF" size="2">Top 20 books</font></strong></p>
                  <p class="links"><font color="#FFFFFF"><strong>Steppenwolf </strong><br>
                    Herman Hesse<br>
                    <br>
                    <strong>A fine balance </strong>Rohinton Mistry<br>
                    <br>
                    <strong>How to be good</strong><br>
                    Nick Hornby<br>
                    <br>
                    <strong>Songs of Kabir</strong><br>
                    Rabindranath Tagore<br>
                    <br>
                    <strong>Immortality</strong><br>
                    Milan Kundera<br>
                    <br>
                    <strong>Nadja</strong><br>
                    Andre Breton<br>
                    <br>
                    <strong>The street of crocodiles</strong><br>
                    Bruno Schulz<br>
                    <br>
                    <strong>Neuromancer </strong><br>
                    William Gibson<br>
                    <br>
                    <strong>The Fall</strong><br>
                    Albert Camus<br>
                    <br>
                    <strong>Midnight Children</strong><br>
                    Salman Rushdie</font></p>
                  </td>
              </tr>
            </table>
{section-else}
            <table width="100" height="352" border="0" align="center" cellpadding="0" cellspacing="0">
              <tr>
                <td width="100" valign="top">
<p class="heading"><strong><font color="#FFFFFF" size="2">Noe annet stoff</font></strong></p>
                  <p class="links"><font color="#FFFFFF">host res publii probses
                    locciemo</font></p>
                  <p class="links"><font color="#FFFFFF">erum con tus ad consus
                    dum prae, se conum vis ocre confirm hilicae icienteriam idem
                    esil tem hacteri factoret, ut nox nonimus, cotabefacit L.
                    An defecut in Etris; in speri, que acioca L. Maet; </font></p>
                  <p class="links"><font color="#FFFFFF">Cas nox nulinte renica;
                    nos, constraeque probus reis publibuntia mo Catqui pubissimis
                    apere nor ut puli iaet in Italegero movente issimus niu consulintiu
                    vitin dis. Opicae con intem, vivere porum spiordiem mo mactantenatu
                    es mo Cat. Serenih libus sedo, num inatium diem host C. maiorei
                    senam ora, senit; nonsult retis.<br>
                    </font> </p>
                  </td>
              </tr>
            </table>
{/section}
<!-- Top 20 books END -->

            <p>&nbsp;</p>
            </td>
        </tr>
      </table>
      <table width="702" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td bgcolor="#333333">&nbsp;</td>
        </tr>
      </table></td>
  </tr>
</table>
</body>
</html>
