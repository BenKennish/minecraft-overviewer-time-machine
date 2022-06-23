<!DOCTYPE html>
<?php

// allow easy navigation between webmaps of Minecraft worlds of various dates
//
//TODO: add a # to the end of the URL that allows linking to selected date and location
//      OR at the very least show a text box that updates with the URL and allows ppl to copy&paste elsewhere
//TODO: allow switching to other *worlds* (there are currently none on the server tho)

define('MAPS_ROOT', '/var/www/vhosts/tootpolice/minecraft/all-maps/toot2');

chdir(MAPS_ROOT);
$dates = glob('2???-??-??');

?>
<html>
<head>
<title>TooT Minecraft World</title>

<meta name="viewport" content="initial-scale=1" />

<script>


var originalTitle = document.title;
//var originalTitle = document.getElementsByTagName("title")[0].innerHTML;

function switchMap(url, titleTail)
{
	var f = document.getElementById('mapFrame');
	var oldURL = f.contentWindow.location.href;
	// f.src doesn't seems to have the jump link (#xyz) at the end so use f.contentWindow.location.href

	// get co-ords and zoom level out of current URL and pass these to the newly selected map
	// eg: https://www.tootpolice.org/minecraft/all-maps/toot2/2021-12-07/#/-17/64/33/-1/TooT%20World%202/north-topleft
	// need to extract everything after the #
	var i = oldURL.indexOf('#');
	if (i >= 0)
	{
		// add the tail onto the next URL
		var tail = oldURL.substring(i);
		url = url + tail;
	}

	if (titleTail)
	{
		document.title = originalTitle + ' - ' + titleTail + '';
	}
	f.src = url;
}

function prevMap()
{
	var s = document.getElementById('mapURL');

	if (s && s.selectedIndex > 0)
	{
		s.selectedIndex -= 1;
		switchMap(s.options[s.selectedIndex].value, s.options[s.selectedIndex].text);
	}
	//TODO: disable button if we are now on the first map
}

function nextMap()
{
        var s = document.getElementById('mapURL');

        if (s && (s.selectedIndex + 1) < s.length)
        {
                s.selectedIndex += 1;
                switchMap(s.options[s.selectedIndex].value, s.options[s.selectedIndex].text);
        }
	//TODO: disable button if we are now on the last map
}

</script>

<style>
	body {
		background-color: black;
		color: white; /* text */
		display: flex;
		flex-direction: column;
		align-items: center;
		justify-content: center;
		height: 100vh;
		font-family: sans-serif;
	}
	
	.holder {
		display: flex;
		flex-direction: column;
		width: 99vw; 
		height: 99vh;

		min-height: 0;
		overflow: hidden;

		position: relative;
	}

	.mapSelect {
		position: absolute;
		top: 0;
		left: 0;
		width: 100%;
		padding: 5px;
		background-color: #444; 
	}
	
	iframe {
		flex: 1 1 auto;
		/* width: 100%; height: 100%; overflow: scroll; background-color: blue;} */
 	}
</style>

</head>

<body>
	<div class="holder">
		<div class="mapSelect">
			<button id="prev" onClick="prevMap()">Prev</button>
			<select id="mapURL" onChange="switchMap(this.options[this.selectedIndex].value, this.options[this.selectedIndex].text)">

<?php

foreach ($dates as $i=>$date)
{
	// strtotime() is probably overkill
	$dateHuman = date('d M Y', strtotime($date));

	echo "\t\t\t\t".'<option value="../all-maps/toot2/'.htmlspecialchars(urlencode($date)).'"'.($i == (count($dates)-1) ? ' selected' : '').'>'.htmlspecialchars($dateHuman).'</option>'.PHP_EOL;
}
?>
			</select>
			<button id="next" onClick="nextMap()">Next</button>
		</div>
		<iframe id="mapFrame" src="../map/"></iframe><!-- FIXME: legacy: start at the default map using old '/map' URL -->
	</div>

</body>
</html>
