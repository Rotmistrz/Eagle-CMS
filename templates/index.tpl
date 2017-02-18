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
			<section class="bar">
				<nav class="main-menu">
					<ul>
						<li><a href="{{ path }}/">Home</a></li>
						<li><a href="{{ path }}/index.php?module=pages">Strony</a></li>
						<li><a href="{{ path }}/index.php?module=data">Dane</a></li>
						<li><a href="{{ path }}/index.php?module=settings">Ustawienia</a></li>
					</ul>
				</nav>

				<p class="loged-as">
				Zalogowany jako <span class="username">admin</span> (<a href="{{ path }}/logout.php">wyloguj</a>)
				</p>
			</section>

			<div class="main-container container">
				<aside class="sidebar">
					<nav class="side-menu">
						<ul>
							<li><a href="">Strona główna</a></li>
							<li><a href="">O nas</a></li>
							<li><a href="">Oferta</a></li>
						</ul>
					</nav>
				</aside>

				<section class="content">
					{{ content }}
				</section>
			</div>
		</main>

		<footer class="site-footer">
		Copyright 2017 by <a href="http://www.filipmarkiewicz.pl">Filip Markiewicz</a>
		</footer>
	</body>
</html>