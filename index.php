<?php
require "stranky.php";

if (array_key_exists("stranka", $_GET)) {
	$stranka = $_GET["stranka"];

	// kontrola zdali zadana stranka existuje
	if (array_key_exists($stranka, $seznamStranek) == false) {
		$stranka = "404";
		// odeslani informace vyhledavaci
		http_response_code(404);
	}
}
else {
	$stranka = "uvod";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!-- favicon a title-->
	<link rel="shortcut icon" href="./img/favicon.png" type="image/x-icon">
	<title><?php echo $seznamStranek["$stranka"]->titulek; ?></title>
	<!-- fonts -->
	<link rel="stylesheet" href="./fonts/fontawesome/css/all.min.css">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Kaushan+Script&display=swap" rel="stylesheet">
	<!-- css -->
	<link rel="stylesheet" href="./css/reset.css">
	<link rel="stylesheet" href="./css/style.css">
	<link rel="stylesheet" href="./css/header.css">
	<link rel="stylesheet" href="./css/section.css">
	<link rel="stylesheet" href="./css/footer.css">
</head>

<body>
	<header>
		<menu>
			<div class="container">
				<a href="./" class="logo"><img src="./img/logo.png" alt="logo-prima-kavarna" title="kolace" width="142"
						height="80"></a>
				<nav>
					<ul>
						<?php
						foreach ($seznamStranek as $idStranky => $instanceStranky) {
							if ($instanceStranky->menu != "") {
								echo "<li><a href='$instanceStranky->id'>$instanceStranky->menu</a></li>";
							}
						};
						?>
					</ul>
				</nav>
			</div>
		</menu>

		<div class="nadpis">
			<h2>PrimaKavárna</h2>
			<h3>Jsme tu pro vás již od roku 2002</h3>
			<div class="social">
				<a href="https://www.facebook.com/" target="_blank"><i class="fa-brands fa-facebook"></i></a>
				<a href="https://www.instagram.com/" target="_blank"><i class="fa-brands fa-instagram"></i></a>
				<a href="https://www.youtube.com/" target="_blank"><i class="fa-brands fa-youtube"></i></a>
			</div>
		</div>
	</header>

	<section id="content">
		<?php
		 echo $seznamStranek["$stranka"]->getObsah();
		?>
	</section>


	<footer>
		<div class="container">
			<nav>
				<h3>Menu</h3>
				<ul>
					<?php
						foreach ($seznamStranek as $idStranky => $instanceStranky) {
							if ($instanceStranky->menu != "") {
								echo "<li><a href='$instanceStranky->id'>$instanceStranky->menu</a></li>";
							}
						};
					?>
				</ul>
			</nav>

			<div class="adresa">
				<h3 class="kontakt">Kontakt</h3>
				<address>
					<a href="https://maps.app.goo.gl/NeuzFZLnvxZYbujq5" target="_blank">
						PrimaKavarna <br>
						Jablonskeho 2 <br>
						Praha, Holesovice
					</a>
				</address>
			</div>

			<div>
				<h3 class="otevreno">Otevreno</h3>
				<table>
					<tr>
						<th>Po - Pa:</th>
						<td>8h - 20h</td>
					</tr>
					<tr>
						<th>So:</th>
						<td>10h - 22h</td>
					</tr>
					<tr>
						<th>Ne:</th>
						<td>12h - 20h</td>
					</tr>
				</table>
			</div>
		</div>

	</footer>
</body>

</html>