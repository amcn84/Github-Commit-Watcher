<?php
	$now = date('m-d-Y G:i:s'); // Check the current time against the cached timestamp
	$last = file_get_contents("updated.txt");	// Fetch the last timestamp created in the update
	if($now == $last) {
		echo $now;
		$update = file_get_contents("update.php");
		$json = file_get_contents("totals.json");
	} else {
		$json = file_get_contents("totals.json");
	}
	header("Refresh:900"); // Refresh the page every 15 minutes for display purposes
?>
<!--
	Setup the chart provided by amCharts.com to display the cached information in the GitHub API returns
-->
<!DOCTYPE html>
<html style="background: #282828;">
	<head>
		<title>chart created with amCharts | amCharts</title>
		<meta name="description" content="chart created using amCharts live editor" />

		<!-- amCharts custom font -->
		<link href='https://fonts.googleapis.com/css?family=Covered+By+Your+Grace' rel='stylesheet' type='text/css'>
		
		<!-- amCharts javascript sources -->
		<script type="text/javascript" src="https://www.amcharts.com/lib/3/amcharts.js"></script>
		<script type="text/javascript" src="https://www.amcharts.com/lib/3/serial.js"></script>
		<script type="text/javascript" src="https://www.amcharts.com/lib/3/themes/chalk.js"></script>
		

		<!-- amCharts javascript code -->
		<script type="text/javascript">
			AmCharts.makeChart("chartdiv",
				{
					"type": "serial",
					"categoryField": "category",
					"angle": 30,
					"depth3D": 30,
					"startDuration": 1,
					"theme": "chalk",
					"categoryAxis": {
						"gridPosition": "start"
					},
					"trendLines": [],
					"graphs": [
						{
							"balloonText": "[[title]] of [[category]]:[[value]]",
							"fillAlphas": 1,
							"id": "AmGraph-1",
							"title": "graph 1",
							"type": "column",
							"valueField": "column-1"
						}
					],
					"guides": [],
					"valueAxes": [
						{
							"id": "ValueAxis-1",
							"title": "Number of Commits"
						}
					],
					"allLabels": [],
					"balloon": {},
					"legend": {
						"enabled": false,
						"useGraphSettings": true
					},
					"titles": [
						{
							"id": "Title-1",
							"size": 15,
							"text": "Github Quackcon Commits so far"
						}
					],
					"dataProvider": <?php echo $json; ?>
				}
			);
		</script>
	</head>
	<body>
		<div id="chartdiv" style="width: 100%; height: 800px; background-color: #282828;" ></div> <!-- Display the chart as large as possible -->
	</body>
</html>