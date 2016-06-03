<h2>Taggar</h2>
<?php
	$url = $this->url->create(''); 
?>
<script src="<?=$url. '/js/tagit-script.js'?>" type="text/javascript" charset="utf-8"></script>
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