<h2>Входилка</h2>
<form action="/login" method="post" name="Login_form">
	<p><label>Логин</label><input type="text" name="Login[login]" /></p>
	<p><label>Пароль</label><input type="password" name="Login[pass]" /></p>
	<p><input type="submit" value="Войти" /></p>
</form>

<h1><?= $_GET['den']; ?></h1>