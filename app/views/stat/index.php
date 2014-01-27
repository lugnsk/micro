<h2>Статистика</h2>
<p>Список дублей email: <?php foreach ($emails AS $user) { echo '<br />' . $user->email; } ?></p>
<p>Список пользователей без заказов: <?php foreach ($norders AS $user) { echo '<br />' . $user->login; } ?></p>
<p>Список пользователей заказавших более 2 раз: <?php foreach ($moreorders AS $user) { echo '<br />' . $user->login; } ?></p>