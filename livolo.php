
<html>

<script>
	String.prototype.rightJustify = function( length, char ) {
		var fill = [];
		while ( fill.length + this.length < length ) {
		fill[fill.length] = char;
		}
		return this + fill.join('');
	}
	String.prototype.leftJustify = function( length, char ) {
		var fill = [];
		while ( fill.length + this.length < length ) {
		  fill[fill.length] = char;
		}
		return fill.join('') + this;
	}
	function hexToBase64(hexstring) {
		return btoa(hexstring.match(/\w{2}/g).map(function(a) {
			return String.fromCharCode(parseInt(a, 16));
		}).join(""));
	}
	function convert(){
		header = "b280260013";
		id = document.getElementById("remoteid").value;
		id_bin = (+id).toString(2);
		id_bin = id_bin.leftJustify(16,0);
		btn = document.getElementById("remotebtn").value;
		btn_bin = (+btn).toString(2);
		btn_bin = btn_bin.leftJustify(7,0);
		//alert(btn_bin);
		id_btn_bin = id_bin.concat(btn_bin);

		id_btn_bin = id_btn_bin.replace(/0/g, "0606");
		id_btn_bin = id_btn_bin.replace(/1/g, "0c");

		//alert (id_bin);
		hex_out = header + id_btn_bin;
		//alert((hex_out.length - 24) % 32);
		pad_len = 32 - (hex_out.length - 24) % 32;

		//alert(pad_len);
		hex_out = hex_out + ('').leftJustify(pad_len,0);
		//alert(hex_out);
		document.getElementById('outputhex').value = hex_out;
		converthextobase10();
		}
	function converthextobase10(){
		document.getElementById('outputbase64').value = hexToBase64(document.getElementById('outputhex').value);
	}
	function converthextobin(){
		document.getElementById("remoteid").value;
			id = document.getElementById("remoteid").value;
		id_bin = (+id).toString(2);
		totalone = id_bin.match(/1/g).length;
		document.getElementById("remotebinary").value = id_bin + ' (' + totalone + ')';
	}

</script>

<body>

<p>Remote ID
	<input id='remoteid' type='text' onkeyup="converthextobin()"> Working sample eg. 7400, 6550, 8500, 19303, 10550. Range 1 - 65500. <a href="#select-remoteid">See below on getting remote id</a> </p>
<p>Remote ID Binary	<input id='remotebinary' type='text' ></p>

<p>Button Num
	<select id='remotebtn'>
	<option value='90'  >scene 1 (ex: on only)</option>
	<option value='114' >scene 2 (ex: off only)</option>
	<option value='10'  >scene 3</option>
	<option value='18'  >scene 4</option>
	<optgroup label="Button (VL-RMT-02)">
		<option value='8'   >A (toggle on/off)</option>
		<option value='16'	>B (dim +)</option>
		<option value='56'	>C (dim -)</option>
		<option value='42'  >D (off)</option>
	</optgroup>
	<optgroup label="Touch (VL-RMT-03 / VL-RMT-04)">
		<option value='0'   >btn 1 (toggle)</option>
		<option value='96'	>btn 2 (toggle)</option>
		<option value='120'	>btn 3 (toggle)</option>
		<option value='24'  >btn 4 (toggle)</option>
		<option value='108' >btn 5 (toggle)</option>
		<option value='80'  >btn 6 (toggle)</option>
		<option value='48'  >btn 7 (toggle)</option>
		<option value='12'  >btn 8 (toggle)</option>
		<option value='72'  >btn 9 (toggle)</option>
		<option value='40'  >btn 10 (toggle, not for dimmers)</option>
		<option value='106' >All off</option>
		<option value='90'  >scene 1</option>
		<option value='114' >scene 2</option>
	</optgroup>
	</select>
	<a href="#btn-reference">Button reference</a>

</p>
<p><input type='button' value='Generate' onclick="convert()" /> </p>
<p>Output Hex<br /><input id='outputhex' type='text' size='200' /> </p>
<p>Output Base64 (For home assistant)<br /><input id='outputbase64' type='text' size='200' /> </p>
<p>

<h3>Generate code for home-bridge, home-assistant, openhab to send RF code from broadlink rm 2/rm pro/rm plus to Livolo RF switch</h3>
<p>
<h4 id="select-remoteid">How to select remote id</h4>
<ol>
	<li> <font color='red'>Avoid using sample remoteID as permanent solution as your neighbour may have used the same code</font></li>
	<li> It appears that the remote id is based on odd number of '1' in binary format</li>
	<li> Assuming your remote id is 7400, you will get 1110011101000 binary.</li>
	<li> Counting number of '1' will give you total 7 of '1' which is odd number</li>
	<li> If the number of '1' is an odd number the code is for the touch remotes</li>
	<li> If the number of '1' is an even number the code is for 4 button remotes</li>
	<li> So far, I have tested 3,5,7,9 and 11 '1' which works</li>
</ol>

<p>
<h4>How to teach your Livolo switch for use the code (toggle)</h4>
<ol>
<li> Generate the code using any btn(1-10 from the touch remotes). Use 10 if you want to use ON or OFF only code (this button does not work for dimmers!).</li>
<li> Touch Livolo switch for 5 sec till you hear a beep. </li>
<li> Send the code. You will hear the beep when Livolo learn correctly. </li>
<li> Send the same code again, your Livolo switch should toggle on/off. </li>
<li> The "All off" btn only works for switches that have the same remoteID stored, you will have to use btn 1-9 for your switches with the same remote ID. </li>
</ol>
<p>
<h4>How to teach your Livolo dimmers for use the code (dim+ and dim-)</h4>
<ol>
<li> Generate the code using any VL-RMT-02 buttons. You will need the toggle, dim+ and dim- codes. </li>
<li> Touch Livolo dimmer for 5 sec till you hear a beep. </li>
<li> Send the TOGGLE code. You will hear the beep when Livolo learn correctly.</li>
<li> Send the same code again, your Livolo dimmer should toggle on/off.</li>
<li> Now that this remote is learned by the dimmer it will automatically recognize the dim+ and dim- commands. </li>
</ol>
<p>
<h4>How to teach your Livolo for use the code (On Only/Off only)</h4>
<ol>
<h5> You will need one remote id per button (not per switch).</h5>
<li> Generate the code using "on only(scn1)".
<li> Touch Livolo switch for 5 sec till you hear a beep.
<li> Touch the switch to turn on for on only.(Touch the switch to turn off for off only).
<li> Send the code. You will hear the beep when Livolo learn correctly.
<li> Send the same code again, your Livolo switch should turn on. It does nothing if the switch already on.
<li> Repeat using "off only(scn2)". On step 3, touch the switch to turn off before sending the code.

</ol>
<h5>Add the "on only(scn1)" code to your home automation script as on<br>
Add the "off only(scn2)" code to your home automation script as off</h5>
<p>
<h4>How to unlearn the code from switch</h4>
<ol>
<li> Touch Livolo switch for 5 sec till u hear a beep. </li>
<li> Send the code. You will hear the beep twice when Livolo unlearn correctly</li>
</ol>

<h4 id="btn-reference">Button references</h4>
<table style="text-align: left; border-spacing: 0;" border="1">
	<thead>
		<tr>
			<th></th>
			<th>Buttons: VL-RMT-02</th>
			<th>Touch: VL-RMT-03</th>
			<th>Touch: VL-RMT-04</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<th>Btn 1 (toggle)</th>
			<td><font color="red">X</font></td>
			<td>V</td>
			<td>V</td>
		</tr>
		<tr>
			<th>Btn 2 (toggle)</th>
			<td><font color="red">X</font></td>
			<td>V</td>
			<td>V</td>
		</tr>
		<tr>
			<th>Btn 3 (toggle)</th>
			<td><font color="red">X</font></td>
			<td>V</td>
			<td>V</td>
		</tr>
		<tr>
			<th>Btn 4 (toggle)</th>
			<td><font color="red">X</font></td>
			<td>V</td>
			<td>V</td>
		</tr>
		<tr>
			<th>Btn 5 (toggle)</th>
			<td><font color="red">X</font></td>
			<td>V</td>
			<td>V</td>
		</tr>
		<tr>
			<th>Btn 6 (toggle)</th>
			<td><font color="red">X</font></td>
			<td>V</td>
			<td>V</td>
		</tr>
		<tr>
			<th>Btn 7 (toggle)</th>
			<td><font color="red">X</font></td>
			<td>V</td>
			<td>V</td>
		</tr>
		<tr>
			<th>Btn 8 (toggle)</th>
			<td><font color="red">X</font></td>
			<td>V</td>
			<td>V</td>
		</tr>
		<tr>
			<th>Btn 9 (toggle)</th>
			<td><font color="red">X</font></td>
			<td>V</td>
			<td>V</td>
		</tr>
		<tr>
			<th>Btn 10 (toggle)</th>
			<td><font color="red">X</font></td>
			<td>V (* - no dimmers)</td>
			<td><font color="red">X</font></td>
		</tr>
		<tr>
			<th>All off</th>
			<td><font color="red">X</font></td>
			<td>V</td>
			<td>V</td>
		</tr>
		<tr>
			<th>A (toggle)</th>
			<td>V</td>
			<td><font color="red">X</font></td>
			<td><font color="red">X</font></td>
		</tr>
		<tr>
			<th>B (dim +)</th>
			<td>V</td>
			<td><font color="red">X</font></td>
			<td><font color="red">X</font></td>
		</tr>
		<tr>
			<th>C (dim -)</th>
			<td>V</td>
			<td><font color="red">X</font></td>
			<td><font color="red">X</font></td>
		</tr>
		<tr>
			<th>D (off)</th>
			<td>V</td>
			<td><font color="red">X</font></td>
			<td><font color="red">X</font></td>
		</tr>
		<tr>
			<th>Scene 1 (ex: only ON)</th>
			<td><font color="red">X</font></td>
			<td>V</td>
			<td>V</td>
		</tr>
		<tr>
			<th>Scene 2 (ex: only off)</th>
			<td><font color="red">X</font></td>
			<td>V</td>
			<td>V</td>
		</tr>
		<tr>
			<th>Scene 3</th>
			<td><font color="red">X</font></td>
			<td>V (*)</td>
			<td>V (*)</td>
		</tr>
		<tr>
			<th>Scene 4</th>
			<td><font color="red">X</font></td>
			<td>V (*)</td>
			<td>V (*)</td>
		</tr>
		<tr>
			<td colspan=4>
				* These buttons are not available on any remotes but the codes seem to work.
			</td>
		</tr>
	</tbody>
</table>
<p>
<font color='red'>Please reference this page if you reuse the code here</font>
</body>
</html>
