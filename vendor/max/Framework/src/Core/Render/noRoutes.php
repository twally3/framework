<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
	<!-- <link rel="stylesheet" href="/core_files/css/style.css"> -->
	<style>
		@import url('https://fonts.googleapis.com/css?family=Josefin+Sans:100');
		body {
			font-family: "Josefin Sans", sans-serif;
			text-align: center;
			font-size: 50px;
			color: #AAA;
		}

		h1 {
			margin: 0;
			font-weight: 100;
		}
		h3 {
			margin: 0;
			font-weight: 100;
		}

		.centered {
			margin-top: 5%;
			transform: scale(0.7);
			animation: move 3s ease;
		}

		.icons {
			display: flex;
		}

		.one {
			animation: one 3.5s ease;
		}
		.two {
			animation: two 3.5s ease;
		}
		.three {
			animation: three 3.5s ease;
		}

		.card {
			width: 30%;
			min-height: 250px;
			flex-grow: 1;
			background: #fefefe;
			border-radius: 2px;
			display: block;
			margin: 0 1rem;
			padding: 0;
			box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
			transition: all 0.2s ease-in-out;
		}

		.card:hover {
			box-shadow: 0 10px 20px rgba(0,0,0,0.19), 0 6px 6px rgba(0,0,0,0.23);
		}

		.card h4 {
			font-size: 32px;
			font-weight: 100;
		}

		.card p {
			font-size: 24px;
		}

		@keyframes one {
			0% {opacity: 0}
			70% {opacity: 0}
			100% {opacity: 1}
		}
		@keyframes two {
			0% {opacity: 0}
			80% {opacity: 0}
			100% {opacity: 1}
		}
		@keyframes three {
			0% {opacity: 0}
			90% {opacity: 0}
			100% {opacity: 1}
		}

		@keyframes move {
			0% {
				margin-top: 20%;
				transform: scale(1);
			}
			30% {
				margin-top: 20%;
				transform: scale(1);
			}
			100% {
				margin-top: 5%;
				transform: scale(0.7);
			}
		}
	</style>
</head>
<body>
	<div class="centered">
		<h1>
			Framework 1.0
		</h1>
		<h3>
			Design is intelligence made visible
		</h3>
		<p>
			-Lou Danziger
		</p>
	</div>
	<div class="icons">
		<div class="card one">
			<h4>Design</h4>
			<p>
				Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nemo, placeat!
			</p>
		</div>
		<div class="card two">
			<h4>Create</h4>
			<p>
				Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quam, fuga!
			</p>
		</div>
		<div class="card three">
			<h4>
				Publish
			</h4>
			<p>
				Lorem ipsum dolor sit amet, consectetur adipisicing elit. Atque, maiores.
			</p>
		</div>
	</div>

	<script src="https://use.fontawesome.com/55102f3da3.js"></script>
</body>
</html>