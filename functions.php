<?
function new_issues($apiUrl, $since, $programm, $go, $satzung, $unitid) {
	//Get issues
	if(!isset($since)) {$since = "2013-01-01";}
	if(!isset($areaid)) {$unitid = 1;}
	if($programm == 1) {$policy[] = 1;}
	if($go == 1) {$policy[] = 5;}
	if($satzung == 1) {$policy[] = 39;}
	if(count($policy) == 0) {$policy[] = 1;}
	$policy_id = "&policy_id=".implode(",",$policy);
	$url_issue = $apiUrl . "issue?issue_closed_after=".$since."T00:00:00&unit_id=".$unitid.$policy_id;
	$json_issue = json_decode(file_get_contents($url_issue),true);
	for ($i = 0; $i < count($json_issue['result']); $i++) {
		$array_issue_id[$i] = $json_issue['result'][$i]['id'];
		$time = strtotime($json_issue['result'][$i]['closed']);
		$array_issue_closed[$i] = date('Y-m-d H:i:s', $time);
		$array_issue_state[$i] = $json_issue['result'][$i]['state'];
		$array_issue_area[$i] = $json_issue['result'][$i]['area_id'];
		$array_issue_count[$i] = $json_issue['result'][$i]['voter_count'];
		$array_issue_policy_id[$i] = $json_issue['result'][$i]['policy_id'];
	}

	asort($array_issue_closed);
	$array_keys = array_keys($array_issue_closed);

	//Get area names
	$url_area = $apiUrl . "area";
	$json_area = json_decode(file_get_contents($url_area),true);
	foreach ($json_area['result'] as $area) {
		for ($i = 0; $i < count($array_issue_id); $i++) {
			if($area['id'] == $array_issue_area[$i]) {
				$array_issue_area[$i] = $area['name'];
			}
		}
	}

	//Get policy names
	$url_policy = $apiUrl . "policy";
	$json_policy = json_decode(file_get_contents($url_policy),true);
	foreach ($json_policy['result'] as $policy) {
		for ($i = 0; $i < count($array_issue_id); $i++) {
			if($policy['id'] == $array_issue_policy_id[$i]) {
				$array_issue_policy[$i] = $policy['name'];
			}
		}
	}


	for ($i = 0; $i < count($array_issue_id); $i++) {
		//Get best initiative name
		$url_initiative = $apiUrl . "initiative?issue_id=".$array_issue_id[$i];
		$json_initiative = json_decode(file_get_contents($url_initiative),true);
		foreach ($json_initiative['result'] as $initiative) {
			if($initiative['rank'] == 1) {
				$array_issue_initiative[$i] = $initiative['id'];
				$array_issue_initiative_name[$i] = $initiative['name'];
				$array_issue_initiative_pos[$i] = $initiative['positive_votes'];
				$array_issue_initiative_neg[$i] = $initiative['negative_votes'];
			}
		}

		$enthaltungen = $array_issue_count[$i] - $array_issue_initiative_pos[$i] - $array_issue_initiative_neg[$i];
		$abstimmung = $array_issue_initiative_pos[$i].':'.$enthaltungen.':'.$array_issue_initiative_neg[$i];

		$output[$i] = "";
		if($array_issue_state[$i] == "finished_with_winner") {
			$output[$i] = '|-style="background: PaleGreen"<br />';
			$accepted = "ja";
		} else {
			$output[$i] = '|-style="background: LightSalmon"<br />';
			$accepted = "nein";
		}
		$output[$i] .= '|'.$array_issue_closed[$i].'<br />';
		$output[$i] .= '|'.$array_issue_policy[$i].'<br />';
		$output[$i] .= '|'.$array_issue_area[$i].'<br />';
		$output[$i] .= '|[https://lqfb.piratenpartei.at/issue/show/'.$array_issue_id[$i].' ×]<br />';
		$output[$i] .= '|[https://lqfb.piratenpartei.at/initiative/show/'.$array_issue_initiative[$i].'.html '.$array_issue_initiative_name[$i].']<br />';
		$output[$i] .= '|'.$accepted.' ('.$abstimmung.')<br />';
		$output[$i] .= '|ja (Beteiligung: '.$array_issue_count[$i].'/xx)<br />';
		$output[$i] .= '|[https://wiki.piratenpartei.at/w/index.php?title=Parteiprogramm&diff=xxxxx&oldid=xxxxx ×]<br />';
	}
	
	$output1 = $array_keys;
	$output1[] = $output;
	return $output1;
}
?>
