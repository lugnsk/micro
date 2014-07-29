<h1>Личный кабинет (<?= $user->login; ?>)
    <small><?= $user->fio; ?></small>
</h1>

<form method="post" name="Setup_form">
    <p><label>ФИО</label><input type="text" name="Setup[fio]"/></p>

    <p><label>Новый пароль</label><input type="password" name="Setup[pass]"/></p>

    <p><input type="submit" value="Обновить"/></p>
</form>