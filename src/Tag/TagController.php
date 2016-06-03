<?php

namespace Anax\Tag;

/**
 * Handle Tags
 *
 */
class TagController implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable;

		/**
		 * Initialize the controller.
		 *
		 * @return void
		 */
		public function initialize()
		{				
			$this->tags = new \Anax\Tag\Tag();
			$this->tags->setDI($this->di);							
		}

    /**
     * View all tags.
     *
     * @return void
     */
		 
    public function viewAction()
    {	
			$allTags = $this->tags->query()->execute();
				
        $this->views->add('geogebra/all_tags', [
            'tags' => $allTags,
        ]);
    }
		
		public function getPopTags($number)
		{
			$this->initialize();

			$query = 'SELECT tag_id FROM question_tag';
			$params = array();
			$res = $this->db->executeFetchAll($query, $params);	
			
			usort($res, array($this, "sortQuestionTag"));
			
			for($n = 0; $n < count($res); $n++)
			{
				$res[$n]->number = $this->countTagId($res, $res[$n]->tag_id);
			}
			
			$res = array_unique($res, SORT_REGULAR);
			
			usort($res, array($this, "sortOnNumOfTagId"));
			
			$lastIndex = count($res) - 1;
			
			$tagIds = array();
		
			$newIndex = 0;
		
			for($n = $lastIndex; $n >= 0 && $n > $lastIndex - $number; $n--)
			{
				$tagIds[$newIndex] = $res[$n];
				$newIndex++;
			}
			
			$query = 'SELECT * FROM tag
									WHERE id = ?';
			
			$popTags = array();
			
			foreach($tagIds as $tId)
			{
				$params = array($tId->tag_id);
				$popTags[] = $this->db->executeFetchAll($query, $params)[0];	
			}
			
			return $popTags;
		}
		
	private function sortQuestionTag($u_1, $u_2)
	{
		return ($u_1->tag_id) > ($u_2->tag_id);
	}
	
	private function countTagId($tags, $id)
	{
		$number = 0;
		for($n = 0; $n < count($tags); $n++)
		{
			if($tags[$n]->tag_id == $id)
			{
				$number++;
			}
		}
		return $number;
	}
	
	private function sortOnNumOfTagId($u_1, $u_2)
	{
		return ($u_1->number) > ($u_2->number);
	}
}
