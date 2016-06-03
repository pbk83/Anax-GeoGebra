<p>
<?php
	if($this->di->UserController->isSignedIn())
	{
		$url = $this->url->create('user/logout'); 
		$url2 = $this->url->create('user/profile/'. $this->di->session->get('userdata')->id); 
		echo 'Du är inloggad som <a id="user-profile-header" href="' . $url2. '">' .$this->di->session->get('userdata')->name . '</a><a id="login-out" href="' . $url . '">Logga ut</a>';		
	}
	else
	{
		$url = $this->url->create('user/login'); 
		echo 'Du är inte inloggad <a id="login-out" href="' . $url . '">Logga in</a>';
	}
?>
</p>

