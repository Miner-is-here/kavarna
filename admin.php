<?php

session_start();

require "stranky.php";

$chyba = "";

if (array_key_exists("prihlasit", $_POST)) {
	$jmeno = $_POST["jmeno"];
	$heslo = $_POST["heslo"];

	// zatim natvrdo, nasledne bude upraveno v ramci SQL
	if ($jmeno == "admin" && $heslo == "1234") {
		// uzivatel zadal spravne prihlasovaci udaje
		$_SESSION["prihlasenyUzivatel"] = $jmeno;
		header("Location = ?");
	} else {
		// uzivatel zadal spatne prihlasovaci udaje
		$chyba = "Nesprávné přihlašovací údaje (admin / 1234).";
	}
}

if (array_key_exists("odhlasit", $_POST)) {
	unset($_SESSION["prihlasenyUzivatel"]);
	header("Location: ?");
}

// zpracovani akci v admin casti je pouze pro prihlasene uzivatele
if (array_key_exists("prihlasenyUzivatel", $_SESSION)) {
	// pomocna promenna predstavujici stranku, kterou zrovna editujeme;
	$instanceAktualniStranky = null;

	if (array_key_exists("stranka", $_GET)) {
		$idStranky = $_GET["stranka"];
		$instanceAktualniStranky = $seznamStranek[$idStranky];
	}

	// ulozeni uprav
	if (array_key_exists("ulozit", $_POST)) {
		$obsah = $_POST["obsah"];
		$instanceAktualniStranky->setObsah($obsah);
	}
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Administrace</title>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" href="css/admin.css">
	<link rel="stylesheet" href="./fonts/fontawesome/css/all.min.css">
</head>

<body>
	<div class="admin-body">
		<?php
		if (array_key_exists("prihlasenyUzivatel", $_SESSION) == false) {
			// sekce pro neprihlasene uzivatele
		?>

			<main class="form-signin w-100 m-auto">
				<form method="post">
					<h1 class="h3 mb-3 fw-normal">Přihlašte se prosím</h1>

					<?php
					if ($chyba != "") { ?>
						<div class="alert alert-danger" role="alert">
							<?php echo $chyba; ?>
						</div>
					<?php } ?>

					<div class="form-floating">
						<input type="text" class="form-control" id="floatingInput" placeholder="login" name="jmeno">
						<label for="floatingInput">Přihl. jméno</label>
					</div>
					<div class="form-floating">
						<input type="password" class="form-control" id="floatingPassword" placeholder="heslo" name="heslo">
						<label for="floatingPassword">Heslo</label>
					</div>

					<button class="btn btn-primary w-100 py-2" type="submit" name="prihlasit">Přihlásit</button>
				</form>
			</main>
		<?php
		} else {
			// sekce pro prihlasene uzivatele
			echo "<main class='admin-logged'>";
			?>

			<div class="container">
				<header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom">

				<?php echo "Přihlášen uživatel: {$_SESSION["prihlasenyUzivatel"]}";
				?>
					<div class="col-md-3 text-end">
						<form method="post">
							<button name="odhlasit" class="btn btn-outline-primary me-2">Odhlásit</button>
						</form>
					</div>
				</header>
			</div>

			<?php


			// vypis seznamu stranek pro editaci
			echo "<ul id='pages' class='list-group'>";
			foreach ($seznamStranek as $idStranky => $instanceStranky) {
				$active = "";
				$buttonClass = "btn-primary";

				if ($instanceStranky == $instanceAktualniStranky) {
					$active = "active";
					$buttonClass = "btn-secondary";
				}
				echo "<li class='list-group-item $active'>
				
				<a class='btn $buttonClass' href='?stranka=$instanceStranky->id'><i class='fa-solid fa-pen-to-square'></i></a>
				
				<a class='btn $buttonClass' href='$instanceStranky->id' target='blank'><i class='fa-solid fa-eye'></i></a>

				<span>$instanceStranky->id</span>
				</li>";
		}
			echo "</ul>";

			// editacni formular
			// zobrazit pokud je nejaka stranka vybrana
			if ($instanceAktualniStranky != null) {
				echo "<div class='alert alert-secondary' role='alert'>
						<h1>Editace stranky: $instanceAktualniStranky->id</h1>
					</div>";
			?>

				<form method="post">
					<textarea name="obsah" id="obsah" cols="80" rows="15">
			<?php
				echo htmlspecialchars($instanceAktualniStranky->getObsah());
			?>
			</textarea>
					<br>

					<button class="btn btn-primary" name="ulozit"><i class="fa-solid fa-floppy-disk"></i> Uložit</button>
				</form>
				<script src="vendor\tinymce\tinymce\tinymce.min.js" referrerpolicy="origin"></script>
				<script>
					tinymce.init({
						selector: '#obsah',
						license_key: 'gpl|<your-license-key>',
						language: 'cs',
						language_url: '<?php echo dirname($_SERVER["PHP_SELF"]); ?>/vendor/tweeb/tinymce-i18n/langs/cs.js',
						height: '50vh',
						entity_encoding: 'raw',
						verify_html: false,
						content_css: [
							'https://fonts.googleapis.com/css2?family=Kaushan+Script&display=swap',
							'./fonts/fontawesome/css/all.min.css',
							'./css/reset.css',
							'./css/style.css',
							'./css/section.css',
						],
						body_id: "content",
						plugins: 'advlist anchor autolink charmap code colorpicker contextmenu directionality emoticons fullscreen hr image imagetools insertdatetime link lists nonbreaking noneditable pagebreak paste preview print save searchreplace tabfocus table textcolor textpattern visualchars',
						toolbar1: "insertfile undo redo | styleselect | fontselect | fontsizeselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | forecolor backcolor",
						toolbar2: "link unlink anchor | fontawesome | image media | responsivefilemanager | preview code",
						external_plugins: {
							'responsivefilemanager': '<?php echo dirname($_SERVER['PHP_SELF']); ?>/vendor/primakurzy/responsivefilemanager/tinymce/plugins/responsivefilemanager/plugin.min.js',
							'filemanager': '<?php echo dirname($_SERVER['PHP_SELF']); ?>/vendor/primakurzy/responsivefilemanager/tinymce/plugins/filemanager/plugin.min.js',
						},
						external_filemanager_path: "<?php echo dirname($_SERVER['PHP_SELF']); ?>/vendor/primakurzy/responsivefilemanager/filemanager/",
						filemanager_title: "Správce souborů",
					});
				</script>
		<?php
			}
			echo "<main>";
		}
		?>
	</div>
</body>

</html>