<form action="{ACTION}" method=post>
	<fieldset>
		<legend>Войти</legend>
		<p class="row"><input type="text" name="username" class="inp fade" value="{USERNAME}" onfocus="if(this.value=='Логин')this.value='';this.className='inp'" onblur="if(this.value==''){this.value='Логин';this.className = 'inp fade'}" />
			<input type="{PASSWORD_TYPE}" name="password" class="inp fade"  value="{PASSWORD}" onfocus="if(this.value=='Пароль')this.value='';this.className='inp';this.type='password'" onblur="if(this.value==''){this.value='Пароль';this.className = 'inp fade';this.type='text'}" />
			 <input type="image" src="/img/voyti.png" alt="Войти" value="Войти" /></p>
	</fieldset>
	<p class="u"><a href="/remind/">Забыли пароль?</a>&nbsp;&nbsp;&nbsp;<input type="checkbox" name="savepass"{CHECKED}> Запомнить пароль</p>
</form>
