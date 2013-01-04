<?
require('config.php');
require('functions.php');

if($_POST['submit'] == "true") {
	if($_POST['date'] != "") {
		$datum = $_POST['date'];
	}
	if($_POST['programm'] == "programm") {$programm = 1; $checked_programm = "checked";}
	if($_POST['go'] == "go") {$go = 1; $checked_go = "checked";}
	if($_POST['satzung'] == "satzung") {$satzung = 1; $checked_satzung = "checked";}
} else {
	$checked_programm = "checked";
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>lqfb-issue-register</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
		<link rel="stylesheet" type="text/css" href="css/bootstrap-responsive.css">
		<style>
			<?echo $display_view;?>
			body {
				background-color: #4c2582;
				padding-top: 60px;
				padding-bottom: 40px;
			}
			footer {
				color: white;
			}
		</style>
		<script src="js/bootstrap.js"></script> 
	</head>
	<body>
		<div class="container">
			<div class="row">
				<div class="span12">
					<div class="well">
						<h1>lqfb-issue-register</h1>
						<p>
						<form class="form-inline" action="index.php" method="POST">
								<input type="text" name="date" class="input" value="<?echo $datum;?>" placeholder="Datum z.B. 2013-01-01" />
							</label>
							<label class="checkbox">
								<input type="checkbox" name="programm" value="programm" <?echo $checked_programm;?>> Programm direkt
							</label>
							<label class="checkbox">
								<input type="checkbox" name="go" value="go" <?echo $checked_go;?>> GO direkt
							</label>
							<label class="checkbox">
								<input type="checkbox" name="satzung" value="satzung" <?echo $checked_satzung;?>> Satzung direkt
							</label>
							<input type="hidden" name="submit" value="true" />
							<button type="submit" class="btn">Refresh</button>
						</form>
						</p>
						<div><pre>
<?$output1 = new_issues($api_Url, $datum, $programm, $go, $satzung);
$output_count = count($output1) - 1;
$output = $output1[$output_count];
for ($i = 0; $i < count($output); $i++) {
	$e = $output1[$i];
	echo $output[$e];
}?>
						</pre></div>
					</div>
				</div><!--/span-->
			</div><!--/row-->

			<footer>
				<p>Eine kleine Spielerei von <a href="http://wiki.piratenpartei.at/wiki/Benutzer:Burnoutberni">'burnoutberni'</a>.</p>
				<p>Datenquelle: <a href="https://lqfb.piratenpartei.at">https://lqfb.piratenpartei.at</a> &bull; <a href="https://lfapi.piratenpartei.at">https://lfapi.piratenpartei.at</a></p>
			</footer>

		</div><!--/.fluid-container-->
	</body>
</html>
