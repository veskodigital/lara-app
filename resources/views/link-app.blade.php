<!DOCTYPE html>
<html lang="en" class="h-100">
<head>
	<title>Link LaraApp via the QR code</title>

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<link href="https://fonts.googleapis.com/css?family=Bubblegum+Sans|Alata&display=swap" rel="stylesheet">
	<style type="text/css" media="screen">
		body {
			font-family: 'Alata', sans-serif;
		}
		h1 {
			font-family: 'Bubblegum Sans', cursive;
		}
	</style>
</head>
<body class="bg-faded d-flex vertical-center h-100 w-100">

	<div class="h-100 jumbotron m-0 text-center w-100 align-self-center">
		<div class="row border-bottom justify-content-center jumbotron">
			<div class="col-md-12">
				<h1 class="text-danger font-weight-bold"><span class="border-bottom d-block p-2 w-100">Link LaraApp</span></h1>
			</div>
			<div class="col-md-12 py-4">
				<img src="{{ (new \chillerlan\QRCode\QRCode)->render(json_encode($payloadInfo)) }}" class="bg-white border shadow rounded-lg" alt="QR Code" />
			</div>
			<div class="col-md-12">
				<p class="text-secondary">Download the LaraApp app and link your phone to your Laravel app with the above code</p>
			</div>
			<div class="col-md-3 text-center">
				<a class="text-danger" href="https://apps.apple.com/us/app/laraapp-for-laravel-artisans/id1489590015" title="LaraApp ios app" target="_BLANK">IOS - LaraApp</a>
			</div>
			<div class="col-md-3 text-center">
				<a class="text-danger" href="https://play.google.com/store/apps/details?id=com.mavsoft.laraapp" title="LaraApp android app" target="_BLANK">Android - LaraApp</a>
			</div>
		</div>
	</div>

	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>