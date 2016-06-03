<div class='user-wrapper'>
<div class="user-left">
	<p><?=$userdata->username;?></p>
	<img src="<?="https://www.gravatar.com/avatar/" . md5( strtolower( trim($userdata->email) ) )  . "&s=" . '40';?>" width="40"/>
</div>
<div class="user-right">
	<p>Namn: <?=$userdata->name?></p>
	<p>E-post: <?=$userdata->email?></p>
</div>
</div>
<?php if($this->di->session->get('userdata') != null && $this->di->session->get('userdata')->id == $userdata->id) : ?>
<a href="<?=$this->url->create('user/update')?>">Uppdatera profil</a>
<?php endif; ?>
<?php
	$url = $this->url->create(''); 
	$url2 =  $this->url->create('question/viewOne/'); 
?>
<script src="<?=$url. '/js/tagit-script.js'?>" type="text/javascript" charset="utf-8"></script>

<div id="activity-wrapper">
	<div id="asked-questions">
		<h3>Ställda frågor</h3>
		<?php foreach (array_reverse($askedQ) as $question) : ?>
			<hr style="width:95%;">
			<div class="question-left">
				<p class="question-heading"><a href="<?=$url2.'/'. $question->id?>"><?=$question->question_heading?></a></p>
				<ul class="readOnlyTags" style="border:none; padding-left:0px;background-color: #F9F9F9;">
				<?php
					foreach($question->tags as $tag)
					{
						echo '<li>' . $tag->tag_text . '</li>';
					}
				?>
				</ul>
			</div>			
		<?php endforeach; ?>
	</div>
	<div id="answered-questions">
		<h3>Besvarade frågor</h3>
		<?php foreach (array_reverse($ansQ) as $question) : ?>
			<hr style="width:95%;">
			<div class="question-left">
				<p class="question-heading"><a href="<?=$url2.'/'. $question->id?>"><?=$question->question_heading?></a></p>
				<ul class="readOnlyTags" style="border:none; padding-left:0px;background-color: #F9F9F9;">
				<?php
					foreach($question->tags as $tag)
					{
						echo '<li>' . $tag->tag_text . '</li>';
					}
				?>
				</ul>
			</div>			
		<?php endforeach; ?>
	</div>
</div>

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