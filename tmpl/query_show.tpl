№ {id}, Добавлена {post_date} {post_ip}<br>
<br>
Объект: <a href="?op=villa&act=show&id={villa_id}">{villa_id}, {villa_title}</a><br>
<br>

Автор: 
#{user_id}, {user_name}<br><br>


<b>Имя:</b> {first_name}<br>
<b>Фамилия:</b> {last_name}<br>
<br>
<b>E-mail:</b> {email}<br>
<b>Телефон</b>: {phone}<br>
<br>
Сообщение:
<br><br>
<i>{body}</i>
<br>
<!-- BEGIN FEEDBACK -->
<form method=post action="">
<br>Важно: в своей переписке, не указывайте свои контактные данные:<br>
емэйл, телефон, адрес сайта, название компании.<br><br>
Эти данные будут удалены модератором.<br>
<br>Ответить:<br>
<textarea name="body" rows="10" cols="50"></textarea><br><input type="submit" name="__submit__" value="Отправить">
</form>
<!-- END FEEDBACK -->