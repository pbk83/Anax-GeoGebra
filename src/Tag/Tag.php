<?php

namespace Anax\Tag;
 
/**
 * Model for Tag.
 *
 */
class Tag extends \Anax\MVC\CDatabaseModel
{
	public function saveTags($qId, $tags)
	{
		$tagsArray = explode(',', $tags);
		$tagIds = array();
		
		$q_t = new \Anax\Question_Tag\Question_Tag();
    $q_t->setDI($this->di);
		
		foreach($tagsArray as $tag)
		{
			$tagArr['tag_text'] = $tag;
					
			try
			{
				$this->create($tagArr);
			}
			catch(\Exception $e)
			{
				// Tag already exists. Continue
			}			
				
		}
		
		$index = 0;
		
		// Get the new tag id
		foreach($tagsArray as $tag)
		{
			//$tagIds[] = $this->query('id')->where('tag_text = "'. $tag .'"')->execute()[0]->id;
			$values['tag_id'] = $this->query('id')->where('tag_text = "'. $tag .'"')->execute()[0]->id;
			$values['question_id'] = $qId;
						
			$q_t->create($values);
		}
	}
	
	public function getTags($qId)
	{
		$query = 'SELECT tag_text FROM tag t
								LEFT JOIN question_tag qt ON qt.tag_id = t.id
								WHERE qt.question_id = ?';
			$params = array($qId);
		return $this->db->executeFetchAll($query, $params);
	}
}