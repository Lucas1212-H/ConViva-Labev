<template>
	<div class="login-page">
		<header class="top-bar">
			<h1 class="brand">Faunaaqui!</h1>
		</header>

		<main class="login-wrapper">
			<section class="illustration-card" aria-hidden="true">
				<div class="blob"></div>
				<img src="../assets/images/map.png" alt="Mapa da regiao" class="animal-image">
			</section>

			<section class="form-card">
				<h2 class="form-title">Login Organizacional</h2>

				<form class="login-form" @submit.prevent>
					<label for="email">Email</label>
					<input id="email" type="email" autocomplete="email">

					<label for="password">Senha</label>
					<input id="password" type="password" autocomplete="current-password">

					<button type="submit" class="submit-btn">Entrar</button>

					<div class="links-row">
						<span>Nao possui conta?</span>
						<a href="#">Cadastre Aqui</a>
						<a href="#">Esqueci minha senha</a>
					</div>
				</form>
			</section>
		</main>
	</div>
</template>

<script setup>
</script>

<style scoped>
.login-page {
	min-height: 100vh;
	background: #ececec;
}

.top-bar {
	background: #4fd09c;
	padding: 0.8rem 1.2rem;
}

.brand {
	margin: 0;
	color: #fff;
	font-weight: 800;
	font-size: 2.6rem;
	line-height: 1;
}

.login-wrapper {
	max-width: 1200px;
	margin: 2rem auto;
	padding: 0 1rem;
	display: grid;
	grid-template-columns: 260px 1fr;
	align-items: center;
	gap: 2rem;
}

.illustration-card {
	position: relative;
	min-height: 320px;
	display: flex;
	align-items: center;
	justify-content: center;
}

.blob {
	width: 220px;
	height: 220px;
	border-radius: 55% 45% 60% 40% / 50% 35% 65% 50%;
	background: #48d2a0;
}

.animal-image {
	position: absolute;
	width: 180px;
	height: 180px;
	object-fit: cover;
	border-radius: 18px;
	box-shadow: 0 12px 30px rgba(0, 0, 0, 0.2);
}

.form-card {
	background: #96db78;
	border-radius: 2rem;
	padding: 3rem;
	max-width: 760px;
}

.form-title {
	margin: 0 0 1rem;
	font-size: 2rem;
	font-weight: 500;
}

.login-form {
	display: flex;
	flex-direction: column;
	gap: 0.7rem;
}

.login-form label {
	font-size: 0.95rem;
}

.login-form input {
	border: 1px solid #4c7e3c;
	border-radius: 4px;
	padding: 0.45rem 0.6rem;
	background: rgba(255, 255, 255, 0.4);
}

.submit-btn {
	margin-top: 0.6rem;
	border: 0;
	background: #000;
	color: #fff;
	border-radius: 999px;
	padding: 0.55rem;
}

.links-row {
	margin-top: 0.25rem;
	display: grid;
	grid-template-columns: 1fr auto;
	column-gap: 0.8rem;
	row-gap: 0.25rem;
}

.links-row span {
	grid-column: 1;
}

.links-row a:first-of-type {
	grid-column: 1;
}

.links-row a:last-of-type {
	grid-column: 2;
	grid-row: 1 / span 2;
	align-self: end;
}

@media (max-width: 992px) {
	.brand {
		font-size: 2.1rem;
	}

	.login-wrapper {
		grid-template-columns: 1fr;
		margin-top: 1.2rem;
	}

	.illustration-card {
		min-height: 220px;
	}

	.form-card {
		padding: 2rem 1.3rem;
	}

	.form-title {
		font-size: 1.7rem;
	}

	.links-row {
		grid-template-columns: 1fr;
	}

	.links-row a:last-of-type {
		grid-column: 1;
		grid-row: auto;
		justify-self: start;
	}
}
</style>
