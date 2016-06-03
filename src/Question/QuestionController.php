<?php

namespace Anax\Question;

/**
 * Handle Questions
 *
 */
class QuestionController implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable;

		/**
		 * Initialize the controller.
		 *
		 * @return void
		 */
		public function initialize()
		{				
			$this->questions = new \Anax\Question\Question();
			$this->questions->setDI($this->di);							
		}

    /**
     * View all questions.
     *
     * @return void
     */
		 
    public function viewAction($tag_text = null)
    {	
			if($tag_text != null)
			{
				// Finds tag id
				$tag_text = str_replace("%C3%A5", "å", $tag_text);
				$tag_text = str_replace("%C3%A4", "ä", $tag_text);
				$tag_text = str_replace("%C3%B6", "ö", $tag_text);
				
				$query = 'SELECT id FROM tag WHERE tag_text = ?';				
				$params = array($tag_text);
				
				$tempTagIds = $this->db->executeFetchAll($query, $params);
				if($tempTagIds != null)
				{
					$tagId = $tempTagIds[0]->id;
				}
				else
				{
					die($tag_text);
				}
				
				$query = 'SELECT * FROM question q
										LEFT JOIN question_tag qt ON qt.question_id = q.id
										WHERE qt.tag_id = ?';
				$params = array($tagId);
				$all = $this->db->executeFetchAll($query, $params);
			}
			else
			{
        $all = $this->questions->query()->execute();				
			}
				$query = 'SELECT username, email FROM user WHERE id = ?';				
				
				$tag = new \Anax\Tag\Tag();
				$tag->setDI($this->di);
				
				for($n = 0; $n < count($all); $n++)
				{
					$params = array($all[$n]->user_id);
					$res = $this->db->executeFetchAll($query, $params)[0];
					$all[$n]->username = $res->username;
					$all[$n]->email = $res->email;
					
					$all[$n]->tags = $tag->getTags($all[$n]->id);
				}
				
				$this->di->theme->setTitle('Frågor');
        $this->views->add('geogebra/fragor', [
            'fragor' => $all,
        ]);
    }
		
		public function viewOneAction($id)
		{
			// Get question
			$q = $this->questions->find($id);

			if($q == null)
			{
				$this->response->redirect(''); 
			}
			
			$query = 'SELECT username, email FROM user WHERE id = ?';
			$params = array($q->user_id);
			$res = $this->db->executeFetchAll($query, $params)[0];
			$user = $res->username;
			$email = $res->email;
			
			// Get tags
			$tag = new \Anax\Tag\Tag();
			$tag->setDI($this->di);
			
			// Get answers
			$query = 'SELECT answer_text, user_id, username, email, a.id FROM answer a 
								LEFT JOIN user u ON u.id = a.user_id
								WHERE a.question_id = ?';
			$params = array($id);
			$answers = $this->db->executeFetchAll($query, $params);			
			
			// Add answer
			$this->di->session();
      $form = new \Anax\GeogebraForms\CFormAddAnswer($id);
      $form->setDI($this->di);
      $form->check();      
			
			// Get comments
			$comments = $this->di->CommentController->getCommentsByQId($id);
			
			$this->di->theme->setTitle('Fråga');
			$this->views->add('geogebra/question', [
        'q' => $q,
				'username' 	=> $user,
				'email'			=> $email,
				'tags'		 	=> $tag->getTags($id),
				'answers'		=> $answers,
				'answerHTML'=> $form->getHTML(),
				'id'				=> $q->user_id,
				'comments'	=>$comments,
      ]);
			
		}

		public function addAction()
		{
			$this->di->session();
			
			if(!$this->di->UserController->isSignedIn())
			{
				$url = $this->di->url->create('user/login'); 
				$this->response->redirect($url); 
			}
      $form = new \Anax\GeogebraForms\CFormAddQuestion();
      $form->setDI($this->di);
      $form->check();
      $this->di->theme->setTitle("Ställ fråga");
      $this->di->views->add('geogebra/new_question', [
        'html' => $form->getHTML(),
      ]);
		}
		
		public function getLastQuestions($number)
		{
			$this->initialize();
			$all = $this->questions->query()->execute();				

			$query = 'SELECT username, email FROM user WHERE id = ?';				
			
			$tag = new \Anax\Tag\Tag();
			$tag->setDI($this->di);
			
			for($n = 0; $n < count($all); $n++)
			{
				$params = array($all[$n]->user_id);
				$res = $this->db->executeFetchAll($query, $params)[0];
				$all[$n]->username = $res->username;
				$all[$n]->email = $res->email;
				
				$all[$n]->tags = $tag->getTags($all[$n]->id);
			}
			
			$lastIndex = count($all) - 1;
			
			$res = array();
			
			$newIndex = 0;
			
			for($n = $lastIndex; $n >= 0 && $n > $lastIndex - $number; $n--)
			{
				$res[$newIndex] = $all[$n];
				$newIndex++;
			}

			return $res;
		}
}
