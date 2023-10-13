<!DOCTYPE html>
<html lang="en">
<head>
	<title>Applicant</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="csrf-token" value="{{ csrf_token() }}"/>
	<link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">

	@vite('resources/css/app.css')
</head>
<body>
	<div id="app">
        @yield('content')
    </div>
	
	@vite('resources/js/app.js')
</body>
</html>