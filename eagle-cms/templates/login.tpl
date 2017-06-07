<!DOCTYPE html>
<html lang="pl">
	<head>
		<meta charset="utf-8" />

		<link rel="stylesheet" href="{{ path }}/css/main.css" type="text/css" />

		<title>{{ title }}</title>
	</head>
	<body>
		<header class="site-header">
			<h1 class="site-title">
				<a href="{{ path }}/"><span class="title">EagleCMS</span> <span class="subtitle">Flexible comfortable CMS</span></a>
			</h1>
		</header>

		<main>
			{{ content }}
		</main>

		<footer class="site-footer">
		Copyright 2017 by <a href="http://www.filipmarkiewicz.pl">Filip Markiewicz</a>
		</footer>
	</body>
</html>