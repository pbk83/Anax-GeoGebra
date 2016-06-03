<?php

namespace Anax\Comment;

/**
 * Handle Comment
 *
 */
class CommentController implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable;

		/**
		 * Initialize the controller.
		 *
		 * @return void
		 */
		public function initialize()
		{				
			$this->comments = new \Anax\Comment\Comment();
			$this->comments->setDI($this->di);							
		}

    /**
     * View all questions.
     *
     * @return void
     */
		 
    public function saveAction()
		{
			$comment_text = $_POST['comment-text'];
			$user_id = $_POST['user-id'];
			$question_id = $_POST['question-id'];
			if(isset($_POST['answer-id']))
				$answer_id = $_POST['answer-id'];
			else
				$answer_id = null;
				
			$this->comments->create([
						'comment_text' 	=> $comment_text,
            'user_id' 			=> $user_id,
						'question_id'		=> $question_id,
						'answer_id'			=> $answer_id,
        ]);
				
			$url = $this->di->url->create('question/viewOne/'. $question_id); 
			$this->response->redirect($url); 
		}
		
		public function getCommentsByQId($qId)
		{
			// Get answers
			$query = 'SELECT * FROM comment c 
									LEFT JOIN user u ON u.id = c.user_id
									WHERE c.question_id = ?';
			$params = array($qId);
			$res = $this->db->executeFetchAll($query, $params);	
			
			
			
			
			return $res;
		}
}
