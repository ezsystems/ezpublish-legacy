<h2>Test of different variable types</h2>

<table >

<tr><th>Type</th>                                        <th>Expects</th>  <th>Got</th></tr>
<tr><td>Numerical</td>                                   <td>1</td>        <td>{1}</td></tr>
<tr><td>Numerical</td>                                   <td>250</td>      <td>{250}</td></tr>
<tr><td>Text</td>                                        <td>abc</td>      <td>{"abc"}</td></tr>
<tr><td>Text</td>                                        <td>def</td>      <td>{'def'}</td></tr>
<tr><td>Text</td>                                        <td>{ldelim}backslashed text {rdelim}</td>
                                                         <td>{"{backslashed text \}"}</td></tr>
<tr><td>Variable</td>                                    <td>var1</td>     <td>{$var1}</td></tr>
{* Same variable name as above but in different namespace *}
<tr><td>Variable in namespace Global</td>                <td>var2</td>     <td>{$Global:var1}</td></tr>
{* Same variable name as above but again in different namespace *}
<tr><td>Variable in namespace eZ:Global</td>             <td>var3</td>     <td>{$eZ:Global:var1}</td></tr>
<tr><td>Variable + 1 attribute</td>                      <td>var2.a</td>   <td>{$var2.a}</td></tr>
<tr><td>Variable + 1 attribute</td>                      <td>var2.b</td>   <td>{$var2.b}</td></tr>
<tr><td>Variable + 2 attributes</td>                     <td>var3.a.b</td> <td>{$var3.a.b}</td></tr>
{* Same as above but with braced attribute access *}
<tr><td>Variable + 2 attributes(braced)</td>             <td>var3.a.b</td> <td>{$var3[a].b}</td></tr>
{* Attribute lookup with variable, $var4 contains a *}
<tr><td>Variable + 2 attributes(braced w/ variable)</td> <td>var3.a.b</td> <td>{$var3[$var4].b}</td></tr>
<tr><td>Variable w/ object</td>                          <td>obj1.a</td>   <td>{$obj1.a}</td></tr>
<tr><td>Variable w/ object</td>                          <td>obj1.b</td>   <td>{$obj1.b}</td></tr>

</table>