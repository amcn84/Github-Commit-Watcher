<?php
	function getStats($owner,$repo) {
		$method = "GET";
		$url = "https://api.github.com/repos/$owner/$repo/stats/commit_activity";
		$headers = array(
			'Content-Type: application/x-www-form-urlencoded', // required
			'outputtype: json', // optional - overrides the preferences in our API control page
			'User-Agent: Awesome-Octocat-App', // user agent required for API calls
			'Authorization: Basic [yourkey]', // Basic Authorization key
			'Postman-Token: [yourtoken]' // Optional authorization token I used postman to get this
		);
		/*
			Setup the curl process to fire off the api requests to GitHub
		*/
		$handle = curl_init();
		curl_setopt($handle, CURLOPT_URL, $url);
		curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
		$response = curl_exec($handle);
		$code = curl_getinfo($handle, CURLINFO_HTTP_CODE);
		$json = json_decode($response,1); // Decode the response so that we can use the JSON as an array
		RETURN $json; // return the array of the status analytics
	}
	/*
		Setup an array of user repos to monitor
	*/
	$repoList = array(
		array(
			"owner" => "JasonGreenC",
			"repo" => "Quackcon"
		),
		array(
			"owner" => "alecmerdler",
			"repo" => "quackcon-project"
		),
		array(
			"owner" => "therealAJ",
			"repo" => "CoachMe"
		),
		array(
			"owner" => "generatives",
			"repo" => "QuackCon2016App"
		),
		array(
			"owner" => "aturabi",
			"repo" => "QuackConAndroid"
		),
		array(
			"owner" => "generatives",
			"repo" => "QuackCon2016Pebble"
		),
		array(
			"owner" => "dnseitz",
			"repo" => "QuackCon2016Server"
		),
		array(
			"owner" => "generatives",
			"repo" => "QuackCon2016Sensor"
		)
	);
	header("Refresh:900"); // Refresh the page every 15 minutes
	$array = array();
	foreach($repoList as $getStat) {
		$owner = $getStat['owner'];
		$repo = $getStat['repo'];
		$results = getStats($owner,$repo);
		$total = 0;
		if(!empty($results)) {
			foreach ($results as $week) {
				if($week['week'] == 1475971200 || $week['week'] == 1476576000) { // UNIX timestamps to only look at the period of time that the event is occuring
					foreach($week['days'] as $day) {
						$total = $total + $day;
					}
				}
			}
		}
		echo "<p>$owner has made $total commits to their $repo at Quackcon so far!</p>"; // verify output for human consumption
		/*
			Create the JSON string that the front end chart will consume and cache the returned information from the API to lower our API calls to Github
		*/
		$array[] = array(
			"category" => $owner,
			"column-2" => $repo, // not needed - left in here for simplicity and laziness
			"column-1" => $total
		);
		$store = json_encode($array,1);
		file_put_contents("totals.json",$store);
	}
	/*
		Setup a timestamp system to verify the last updated list is current within the last 15 minutes
	*/
	$time = date("m-d-Y G:i:s", strtotime('+15 minutes'));
	file_put_contents("updated.txt",$time);	
?>