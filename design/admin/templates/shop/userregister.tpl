{* Warning. *}
{section show=$input_error}
<div class="message-warning">
<h2><span class="time">[{currentdate()|l10n( shortdatetime )}]</span> {'Required information is missing...'|i18n( 'design/admin/shop/userregister' )}</h2>
<ul>
<li>
{'Please please fill in the fields that are marked with a star.'|i18n( 'design/admin/shop/userregister' )}
</li>
</ul>
</div>
{/section}

<form method="post" action={'/shop/userregister/'|ezurl}>

<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h1 class="context-title">{'Account information'|i18n( 'design/admin/shop/userregister' )}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<div class="context-attributes">

<p>{'Please fill in the necessary information. Required fields are marked with a star.'|i18n( 'design/admin/shop/userregister' )}</p>

{* First name. *}
<div class="block">
<label>{'First name'|i18n( 'design/admin/shop/userregister' )}:*</label>
<input class="halfbox" type="text" name="FirstName" size="20" value="{$first_name}" />
</div>

{* Last name. *}
<div class="block">
<label>{'Last name'|i18n( 'design/admin/shop/userregister' )}:*</label>
<input class="halfbox" type="text" name="LastName" size="20" value="{$last_name}" />
</div>

{* E-mail. *}
<div class="block">
<label>{'E-mail'|i18n( 'design/admin/shop/userregister' )}:*</label>
<input class="halfbox" type="text" name="EMail" size="20" value="{$email}" />
</div>

{* Company. *}
<div class="block">
<label>{'Company'|i18n( 'design/admin/shop/userregister' )}:</label>
<input class="halfbox" type="text" name="Street1" size="20" value="{$street1}" />
</div>

{* Street. *}
<div class="block">
<label>{'Street'|i18n( 'design/admin/shop/userregister' )}:*</label>
<input class="halfbox" type="text" name="Street2" size="20" value="{$street2}" />
</div>

{* ZIP code. *}
<div class="block">
<label>{'ZIP code'|i18n( 'design/admin/shop/userregister' )}:*</label>
<input class="halfbox" type="text" name="Zip" size="20" value="{$zip}" />
</div>

{* City. *}
<div class="block">
<label>{'City'|i18n( 'design/admin/shop/userregister' )}:*</label>
<input class="halfbox" type="text" name="Place" size="20" value="{$place}" />
</div>

{* State. *}
<div class="block">
<label>{'State'|i18n( 'design/admin/shop/userregister' )}:</label>
<input class="halfbox" type="text" name="State" size="20" value="{$state}" />
</div>

{* Country. *}
<div class="block">
<label>{'Country'|i18n( 'design/admin/shop/userregister' )}:*</label>
<select name="Country" size="1">
<option  value="Afghanistan">Afghanistan</option>
<option  value="Albania">Albania</option>
<option  value="Algeria">Algeria</option>
<option  value="American Samoa">American Samoa</option>
<option  value="Andorra">Andorra</option>
<option  value="Angola">Angola</option>
<option  value="Anguilla">Anguilla</option>
<option  value="Antarctica">Antarctica</option>
<option  value="Antigua and Barbuda">Antigua and Barbuda</option>
<option  value="Argentina">Argentina</option>
<option  value="Armenia">Armenia</option>
<option  value="Aruba">Aruba</option>
<option  value="Australia">Australia</option>
<option  value="Austria">Austria</option>
<option  value="Azerbaijan">Azerbaijan</option>
<option  value="Bahamas">Bahamas</option>
<option  value="Bahrain">Bahrain</option>
<option  value="Bangladesh">Bangladesh</option>
<option  value="Barbados">Barbados</option>
<option  value="Belarus">Belarus</option>
<option  value="Belgium">Belgium</option>
<option  value="Belize">Belize</option>
<option  value="Benin">Benin</option>
<option  value="Bermuda">Bermuda</option>
<option  value="Bhutan">Bhutan</option>
<option  value="Bolivia">Bolivia</option>
<option  value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
<option  value="Botswana">Botswana</option>
<option  value="Bouvet Island">Bouvet Island</option>
<option  value="Brazil">Brazil</option>
<option  value="British Indian Ocean Territory">British Indian Ocean Territory</option>
<option  value="Brunei Darussalam">Brunei Darussalam</option>
<option  value="Bulgaria">Bulgaria</option>
<option  value="Burkina Faso">Burkina Faso</option>
<option  value="Burundi">Burundi</option>
<option  value="Cambodia">Cambodia</option>
<option  value="Cameroon">Cameroon</option>
<option  value="Canada">Canada</option>
<option  value="Cape Verde">Cape Verde</option>
<option  value="Cayman Islands">Cayman Islands</option>
<option  value="Central African Republic">Central African Republic</option>
<option  value="Chad">Chad</option>
<option  value="Chile">Chile</option>
<option  value="China">China</option>
<option  value="Christmas Island">Christmas Island</option>
<option  value="Cocos ">Cocos (Keeling) Islands</option>
<option  value="Colombia">Colombia</option>
<option  value="Comoros">Comoros</option>
<option  value="Congo">Congo</option>
<option  value="Cook Islands">Cook Islands</option>
<option  value="Costa Rica">Costa Rica</option>
<option  value="Cote d">Cote d'Ivoire</option>
<option  value="Croatia">Croatia</option>
<option  value="Cuba">Cuba</option>
<option  value="Cyprus">Cyprus</option>
<option  value="Czech Republic">Czech Republic</option>
<option  value="Denmark">Denmark</option>
<option  value="Djibouti">Djibouti</option>
<option  value="Dominica">Dominica</option>
<option  value="Dominican Republic">Dominican Republic</option>
<option  value="East Timor">East Timor</option>
<option  value="Ecuador">Ecuador</option>
<option  value="Egypt">Egypt</option>
<option  value="El Salvador">El Salvador</option>
<option  value="Equatorial Guinea">Equatorial Guinea</option>
<option  value="Eritrea">Eritrea</option>
<option  value="Estonia">Estonia</option>
<option  value="Ethiopia">Ethiopia</option>
<option  value="Falkland Islands ">Falkland Islands (Malvinas)</option>
<option  value="Faroe Islands">Faroe Islands</option>
<option  value="Fiji">Fiji</option>
<option  value="Finland">Finland</option>
<option  value="France">France</option>
<option  value="France">France, Metropolitan</option>
<option  value="French Guiana">French Guiana</option>
<option  value="French Polynesia">French Polynesia</option>
<option  value="French Southern Territories">French Southern Territories</option>
<option  value="Gabon">Gabon</option>
<option  value="Gambia">Gambia</option>
<option  value="Georgia">Georgia</option>
<option  value="Germany">Germany</option>
<option  value="Ghana">Ghana</option>
<option  value="Gibraltar">Gibraltar</option>
<option  value="Greece">Greece</option>
<option  value="Greenland">Greenland</option>
<option  value="Grenada">Grenada</option>
<option  value="Guadeloupe">Guadeloupe</option>
<option  value="Guam">Guam</option>
<option  value="Guatemala">Guatemala</option>
<option  value="Guinea">Guinea</option>
<option  value="Guinea">Guinea-Bissau</option>
<option  value="Guyana">Guyana</option>
<option  value="Haiti">Haiti</option>
<option  value="Heard Island and McDonald Islands">Heard Island and McDonald Islands</option>
<option  value="Honduras">Honduras</option>
<option  value="Hong Kong">Hong Kong</option>
<option  value="Hungary">Hungary</option>
<option  value="Iceland">Iceland</option>
<option  value="India">India</option>
<option  value="Indonesia">Indonesia</option>
<option  value="Iran ">Iran (Islamic Republic of)</option>
<option  value="Iraq">Iraq</option>
<option  value="Ireland">Ireland</option>
<option  value="Israel">Israel</option>
<option  value="Italy">Italy</option>
<option  value="Jamaica">Jamaica</option>
<option  value="Japan">Japan</option>
<option  value="Jordan">Jordan</option>
<option  value="Kazakhstan">Kazakhstan</option>
<option  value="Kenya">Kenya</option>
<option  value="Kiribati">Kiribati</option>
<option  value="Korea">Korea, Democratic People's Republic of</option>
<option  value="Korea">Korea, Republic of</option>
<option  value="Kuwait">Kuwait</option>
<option  value="Kyrgyzstan">Kyrgyzstan</option>
<option  value="Lao People">Lao People's Democratic Republic</option>
<option  value="Latin America">Latin America</option>
<option  value="Latvia">Latvia</option>
<option  value="Lebanon">Lebanon</option>
<option  value="Lesotho">Lesotho</option>
<option  value="Liberia">Liberia</option>
<option  value="Libyan Arab Jamahiriya">Libyan Arab Jamahiriya</option>
<option  value="Liechtenstein">Liechtenstein</option>
<option  value="Lithuania">Lithuania</option>
<option  value="Luxembourg">Luxembourg</option>
<option  value="Macau">Macau</option>
<option  value="Macedonia">Macedonia</option>
<option  value="Madagascar">Madagascar</option>
<option  value="Malawi">Malawi</option>
<option  value="Malaysia">Malaysia</option>
<option  value="Maldives">Maldives</option>
<option  value="Mali">Mali</option>
<option  value="Malta">Malta</option>
<option  value="Marshall Islands">Marshall Islands</option>
<option  value="Martinique">Martinique</option>
<option  value="Mauritania">Mauritania</option>
<option  value="Mauritius">Mauritius</option>
<option  value="Mayotte">Mayotte</option>
<option  value="Mexico">Mexico</option>
<option  value="Micronesia ">Micronesia (Federated States of)</option>
<option  value="Moldova">Moldova, Republic of</option>
<option  value="Monaco">Monaco</option>
<option  value="Mongolia">Mongolia</option>
<option  value="Montserrat">Montserrat</option>
<option  value="Morocco">Morocco</option>
<option  value="Mozambique">Mozambique</option>
<option  value="Myanmar">Myanmar</option>
<option  value="Namibia">Namibia</option>
<option  value="Nauru">Nauru</option>
<option  value="Nepal">Nepal</option>
<option  value="Netherlands">Netherlands</option>
<option  value="Netherlands Antilles">Netherlands Antilles</option>
<option  value="New Caledonia">New Caledonia</option>
<option  value="New Zealand">New Zealand</option>
<option  value="Nicaragua">Nicaragua</option>
<option  value="Niger">Niger</option>
<option  value="Nigeria">Nigeria</option>
<option  value="Niue">Niue</option>
<option  value="Norfolk Island">Norfolk Island</option>
<option  value="Northern Mariana Islands">Northern Mariana Islands</option>
<option  value="Norway" selected="selected">Norway</option>
<option  value="Oman">Oman</option>
<option  value="Pakistan">Pakistan</option>
<option  value="Palau">Palau</option>
<option  value="Panama">Panama</option>
<option  value="Papua New Guinea">Papua New Guinea</option>
<option  value="Paraguay">Paraguay</option>
<option  value="Peru">Peru</option>
<option  value="Philippines">Philippines</option>
<option  value="Pitcairn">Pitcairn</option>
<option  value="Poland">Poland</option>
<option  value="Portugal">Portugal</option>
<option  value="Puerto Rico">Puerto Rico</option>
<option  value="Qatar">Qatar</option>
<option  value="Reunion">Reunion</option>
<option  value="Romania">Romania</option>
<option  value="Russian Federation">Russian Federation</option>
<option  value="Rwanda">Rwanda</option>
<option  value="Saint Helena">Saint Helena</option>
<option  value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
<option  value="Saint Lucia">Saint Lucia</option>
<option  value="Saint Pierre and Miquelon">Saint Pierre and Miquelon</option>
<option  value="Saint Vincent and the Grenadines">Saint Vincent and the Grenadines</option>
<option  value="Samoa">Samoa</option>
<option  value="San Marino">San Marino</option>
<option  value="Sao Tome and Principe">Sao Tome and Principe</option>
<option  value="Saudi Arabia">Saudi Arabia</option>
<option  value="Senegal">Senegal</option>
<option  value="Seychelles">Seychelles</option>
<option  value="Sierra Leone">Sierra Leone</option>
<option  value="Singapore">Singapore</option>
<option  value="Slovakia">Slovakia</option>
<option  value="Slovenia">Slovenia</option>
<option  value="Solomon Islands">Solomon Islands</option>
<option  value="Somalia">Somalia</option>
<option  value="South Africa">South Africa</option>
<option  value="South Georgia and the South Sandwich Island">South Georgia and the South Sandwich Island</option>
<option  value="Spain">Spain</option>
<option  value="Sri Lanka">Sri Lanka</option>
<option  value="Sudan">Sudan</option>
<option  value="Suriname">Suriname</option>
<option  value="Svalbard and Jan Mayen Islands">Svalbard and Jan Mayen Islands</option>
<option  value="Swaziland">Swaziland</option>
<option  value="Sweden">Sweden</option>
<option  value="Switzerland">Switzerland</option>
<option  value="Syrian Arab Republic">Syrian Arab Republic</option>
<option  value="Taiwan">Taiwan, Republic of China</option>
<option  value="Tajikistan">Tajikistan</option>
<option  value="Tanzania">Tanzania, United Republic of</option>
<option  value="Thailand">Thailand</option>
<option  value="Togo">Togo</option>
<option  value="Tokelau">Tokelau</option>
<option  value="Tonga">Tonga</option>
<option  value="Trinidad and Tobago">Trinidad and Tobago</option>
<option  value="Tunisia">Tunisia</option>
<option  value="Turkey">Turkey</option>
<option  value="Turkmenistan">Turkmenistan</option>
<option  value="Turks and Caicos Islands">Turks and Caicos Islands</option>
<option  value="Tuvalu">Tuvalu</option>
<option  value="Uganda">Uganda</option>
<option  value="Ukraine">Ukraine</option>
<option  value="United Arab Emirates">United Arab Emirates</option>
<option  value="United Kingdom">United Kingdom</option>
<option  value="United States Minor Outlying Islands">United States Minor Outlying Islands</option>
<option  value="United States of America">United States of America</option>
<option  value="Uruguay">Uruguay</option>
<option  value="Uzbekistan">Uzbekistan</option>
<option  value="Vanuatu">Vanuatu</option>
<option  value="Vatican City State ">Vatican City State (Holy See)</option>
<option  value="Venezuela">Venezuela</option>
<option  value="Viet Nam">Viet Nam</option>
<option  value="Virgin Islands ">Virgin Islands (British)</option>
<option  value="Virgin Islands ">Virgin Islands (U.S.)</option>
<option  value="Wallis and Futuna Islands">Wallis and Futuna Islands</option>
<option  value="Western Sahara">Western Sahara</option>
<option  value="Yemen">Yemen</option>
<option  value="Yugoslavia">Yugoslavia</option>
<option  value="Zaire">Zaire</option>
<option  value="Zambia">Zambia</option>
</select>
</div>

{* Comments. *}
<div class="block">
<label>{'Comments'|i18n( 'design/admin/shop/userregister' )}:</label>
<textarea name="Comment" cols="80" rows="5">{$comment}</textarea>
</div>

</div>

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
<div class="block">
    <input class="button" type="submit" name="StoreButton" value="{'OK'|i18n( 'design/admin/shop/userregister' )}" />
    <input class="button" type="submit" name="CancelButton" value="{'Cancel'|i18n('design/admin/shop/userregister' )}" />
</div>
{* DESIGN: Control bar END *}</div></div></div></div></div></div>
</div>

</div>

</form>
