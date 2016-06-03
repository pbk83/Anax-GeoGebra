<?php
	$scriptUrl =  $this->url->create(''); 
	$userUrl = $this->url->create(''); 
?>
<script src="<?=$scriptUrl. '/js/tagit-script.js'?>" type="text/javascript" charset="utf-8"></script>



<h2>Frågor</h2>

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
		
		window.location.href = "<?=$userUrl?>/question/view/" + tagText;
});
</script>