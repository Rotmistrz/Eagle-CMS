<!DOCTYPE html>
<html lang="pl">
	<head>
		<meta charset="utf-8" />

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
		<script src="{{ path }}/js/jquery.scrollTo.js"></script>
		<script src="{{ path }}/js/velocity.js"></script>
		<script src="{{ path }}/js/trumbowyg.js"></script>

		<link rel="stylesheet" href="{{ path }}/css/trumbowyg.css" />
		<link rel="stylesheet" href="{{ path }}/css/fonts.css" type="text/css" />
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" />
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
						<li><a href="{{ path }}/index.php?module=categories">Kategorie</a></li>
						<li><a href="{{ path }}/index.php?module=data">Dane</a></li>
						<li><a href="{{ path }}/index.php?module=settings">Ustawienia</a></li>
					</ul>
				</nav>

				<p class="loged-as">
				Zalogowany jako <span class="username">{{ username }}</span> (<a href="{{ path }}/logout.php">wyloguj</a>)
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
					<section id="correct-message" class="message correct">
						<p>
						
						</p>
					</section>

					<section id="error-message" class="message error">
						<p>
						
						</p>
					</section>

					{{ content }}
				</section>
			</div>
		</main>

		<footer class="site-footer">
		Copyright 2017 by <a href="http://www.filipmarkiewicz.pl">Filip Markiewicz</a>
		</footer>

		<section id="overlayer" class="overlayer">
			<div id="overlayer-inner-anchor" class="overlayer__inner">
				<section id="overlayer-error-message" class="message error">
					<p>
					
					</p>
				</section>

				<section class="overlayer__content">

				</section>

				<div class="overlayer__close">
					<div class="icon-cross icon-cross--rotated">
						<div></div>
						<div></div>
					</div>
				</div>
			</div>
		</section>

		<section id="simple-overlayer" class="simple-overlayer">
			<div class="wrapper">
				<div>
					<div class="simple-overlayer__inner">
						<section class="simple-overlayer__content">

						</section>
					</div>
				</div>
			</div>
		</section>

		<section id="spinner-overlayer" class="spinner-overlayer">
			<div class="wrapper">
				<div>
					<div class="spinner">
						<div class="spinner__inner">
							<i class="fa fa-spinner fa-spin" aria-hidden="true"></i>
						</div>
					</div>
				</div>
			</div>
		</section>

		<script src="{{ path }}/js/main.js"></script>
	</body>
</html>