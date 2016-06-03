
<h2>Användare</h2>

<?php if (is_array($anvandare)) : ?>

<?php foreach ($anvandare as $id => $user) : ?>
<div class='user-wrapper'>
<div class="user-left">
	<p><a href="<?=$this->url->create('user/profile/'.$user->id)?>"><?=$user->username;?></a></p>
	<img src="<?="https://www.gravatar.com/avatar/" . md5( strtolower( trim($user->email) ) )  . "&s=" . '40';?>" width="40" alt="Användarbild"/>
</div>
<div class="user-right">
	<p>Antal frågor: <?=$user->nQuestions?></p>
	<p>Antal svar: <?=$user->nAnswers?></p>
</div>
</div>
<?php endforeach; ?>

<?php endif; ?>