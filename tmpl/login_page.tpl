<div class="roundbox">
	<div class="boxcontent">
		
		<div id="ownerSignup">
			<h2></h2>
			
			<div class="signupContent">
				<p>
												   </p>
															 
			</div>
		</div>
		
		<form action="{ACTION}" method="post" onsubmit=""  id="loginForm">
		<input type="hidden" name="referer" value="{REFERER}">
			<h2>{login_title}:</h2>
				
			<table border="0" width="100%" cellpadding="5">
				<tr class="email">
					<td colspan="2">
						<label for="email">{login} / E-mail:</label><br>
						<input type="text" id="email" name="username" value="{USERNAME}" maxlength="60" size="35" tabindex=1 />
					</td>
				</tr>
				<tr class="password">
					<td colspan="2">
						<label for="password">&nbsp;{password}:</label><br>
						<input type="password" id="password" name="password" value="{PASSWORD}" maxlength="20"  size="35" tabindex=2 />
						<a href="/remind/">{remember_password}</a> | <a href="/reg/">{registration}</a>
					</td>
				</tr>
				<tr class="remember">
					<td width="1%" valign="top">
						<input type="Checkbox" name="savepass"{CHECKED} tabindex=4 id="rememberMe" value="1"  class="checkbox" />
					</td>
					<td><label for="rememberMe">{save_pass}</label></td>
				</tr>
				<tr>
					<td colspan="2">
						<div class="gtb"><input type="submit" id="btn_submit" value="¬ход" class="gt-bin" /></div>
					</td>
				</tr>
			</table>
		</form>
		<div class="clear-block">&nbsp;<br>
		{log_in_with}
		<br><br>
		{loginza}
		</div>
	</div>
</div>

