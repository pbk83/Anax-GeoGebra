<?php
	$scriptUrl =  $this->url->create(''); 
	$userUrl = $this->url->create(''); 
	$url = $this->url->create(''); 
?>
<script src="<?=$scriptUrl. '/js/tagit-script.js'?>" type="text/javascript" charset="utf-8"></script>
<article>
Välkommen till allt om GeoGebra på svenska! Här kan du ställa frågor om allt som rör användandet av programmet GeoGebra. Tryck på logga in för att skapa ett användarkonto.
</article>
<div id="start-wrapper">
<div id="start-left">
<h2>Senaste frågorna</h2>

<?php
	$url =  $this->url->create('question/viewOne/'); 
?>
<?php if (is_array($fragor)) : ?>
<div class='questions'>
<?php foreach (array_reverse($fragor) as $id => $question) : ?>
<hr style="margin:5px">
<div class="question-wrapper">
<div class="question-left">
	<p class="question-heading"><a href="<?=$url.'/'. $question->id?>"><?=$question->question_heading?></a></p>
	<ul class="readOnlyTags" style="border:none; padding-left:0px;background-color: #F9F9F9;">
	<?php
		foreach($question->tags as $tag)
		{
			echo '<li>' . $tag->tag_text . '</li>';
		}
	?>
	</ul>
</div>
<div class="question-right">
	<p>Av: <a href="<?=$this->url->create('user/profile/'.$question->user_id)?>"><?=$question->username;?></a></p>
	<img src="<?="https://www.gravatar.com/avatar/" . md5( strtolower( trim($question->email) ) )  . "&s=" . '40';?>" width="40" alt="Användarbild"/>
</div>
</div>
<?php endforeach; ?>
</div>
<?php endif; ?>
</div>

<div id="start-right">
<h2>Mesta aktiva användare</h2>
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
<h2>Populära taggar</h2>
<?php
	$url = $this->url->create(''); 
?>

<ul class="readOnlyTags" style="border:none; padding-left:0px;background-color: #F9F9F9;">
		<?php
			foreach($tags as $tag)
			{
				echo '<li>' . $tag->tag_text . '</li>';
			}
		?>
</ul>

<script>
	$(".readOnlyTags").click(function(event){
		var tagText = '';
		
		if(event.target.innerHTML[0] == '<')
		{
			tagText = event.target.childNodes[0].innerHTML;
		}
		else
		{
			tagText = event.target.innerHTML;
		}
		
		window.location.href = "<?=$url?>/question/view/" + tagText;
});
</script>
</div>

</div>