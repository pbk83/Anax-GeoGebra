<?php
	$url = $this->url->create(''); 
?>
<script src="<?=$url. '/js/tagit-script.js'?>" type="text/javascript" charset="utf-8"></script>
<hr style="margin-bottom:0px">
<h2><?=$q->question_heading?></h2>
<article class="question-text">
	<?=$this->di->textFilter->doFilter($q->question_text, 'markdown');?>
</article>
<div class="question-wrapper">
	<div class="question-left">
		<ul class="readOnlyTags" style="border:none; padding-left:0px;background-color: #F9F9F9;">
		<?php
			foreach($tags as $tag)
			{
				echo '<li>' . $tag->tag_text . '</li>';
			}
		?>
		</ul>
	</div>
	<div class="question-right">
		<p>Av: <a href="<?=$this->url->create('user/profile/'.$id)?>"><?=$username;?></a></p>
		<img src="<?="https://www.gravatar.com/avatar/" . md5( strtolower( trim( $email ) ) ) . "&s=" . '40';?>" width="40" alt="Användarbild"/>
	</div>
</div>
<div class = "comments">
	<?php foreach ($comments as $comment) : ?>
		<?php if($comment->answer_id == null) : ?>
			<div class="comment-wrapper">
				<div class="comment-left"><?=$this->di->textFilter->doFilter($comment->comment_text, 'markdown'); ?></div>
				<div class="comment-right"><p>Av: <a href="<?=$this->url->create('user/profile/'.$comment->user_id)?>"><?=$comment->username;?></a></p></div>
			</div>
		<?php endif; ?>
	<?php endforeach; ?>
</div>
<?php if($this->di->UserController->isSignedIn()) : ?>
<div class="new-comment-wrapper">
	<img class="comment-img" src="<?=$url?>/img/comment.png" style="width:20px; margin-top:10px;" onclick="commentClick(this)">
	<form style="display:none; margin-bottom:15px;" action="<?=$url?>/comment/save" method="post">
		<input type="hidden" name="user-id" value="<?=$this->di->session->get('userdata')->id?>">
		<input type="hidden" name="question-id" value="<?=$q->id?>">		
		<textarea name="comment-text" class="comment-textarea" rows="4" cols="50" required="required"></textarea>
		<br>
		<button type="summit">Kommentera</button>
	</form>
</div>
<?php endif; ?>
<hr style="margin-top:0px">

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
	
	function commentClick(p) {		
	
		if(p.parentElement.children[1].style.display == 'block')
		{
			p.parentElement.children[1].style.display = 'none';
		}
		else
		{
			p.parentElement.children[1].style.display = 'block';
		}		
	}

</script>

<h2>Svar</h2>
<?php $n = 1;?>
<?php foreach ($answers as $answer) : ?>

<h3>#<?=$n++;?></h3>
<div class="answer-wrapper">
	<div class="answer-left">
		<article>
			<?=$this->di->textFilter->doFilter($answer->answer_text, ' markdown');?>
		</article>
	</div>
	<div class="answer-right">
		<p>Av: <a href="<?=$this->url->create('user/profile/'.$answer->user_id)?>"><?=$answer->username;?></a></p>
		<img src="<?="https://www.gravatar.com/avatar/" . md5( strtolower( trim( $answer->email ) ) ) . "&s=" . '40';?>" width="40" alt="Användarbild"/>
	</div>
</div>
<div class = "comments">
	<?php foreach ($comments as $comment) : ?>
		<?php if($comment->answer_id == $answer->id) : ?>
			<div class="comment-wrapper">
				<div class="comment-left"><p><?=$this->di->textFilter->doFilter($comment->comment_text, 'markdown'); ?></p></div>
				<div class="comment-right"><p>Av: <a href="<?=$this->url->create('user/profile/'.$comment->user_id)?>"><?=$comment->username;?></a></p></div>
			</div>
		<?php endif; ?>
	<?php endforeach; ?>
</div>
<?php if($this->di->UserController->isSignedIn()) : ?>
<div class="new-comment-wrapper">
	<img class="comment-img" src="<?=$url?>/img/comment.png" style="width:20px; margin-top:10px;" onclick="commentClick(this)" alt="Användarbild"/>
	<form style="display:none; margin-bottom:15px;" action="<?=$url?>/comment/save" method="post">
		<input type="hidden" name="user-id" value="<?=$this->di->session->get('userdata')->id?>">
		<input type="hidden" name="question-id" value="<?=$q->id?>">		
		<input type="hidden" name="answer-id" value="<?=$answer->id?>">	
		<textarea name="comment-text" class="comment-textarea" rows="4" cols="50" required="required"></textarea>
		<br>
		<button type="summit">Kommentera</button>
	</form>
</div>
<?php endif; ?>


<?php endforeach; ?>
<?php if($this->di->UserController->isSignedIn()) : ?>
<h2>Nytt svar</h2>
<?=$answerHTML?>
<?php endif; ?>