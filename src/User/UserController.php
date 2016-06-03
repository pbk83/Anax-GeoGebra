<?php

namespace Anax\User;

// Handle users
class UserController implements \Anax\DI\IInjectionAware
{
	use \Anax\DI\TInjectable;
	
	//Initialize the controller.
	public function initialize()
	{				
		$this->users = new \Anax\User\User();
		$this->users->setDI($this->di);				
	}

	//View all users.
	public function viewAction()
	{	
		$all = $this->users->query()->execute();				
		
		// Number of questions
		$query = 'SELECT id FROM question
							WHERE user_id = ?';
		
		for($n = 0; $n < count($all); $n++)
		{
			$params = array($all[$n]->id);
			$nQuestions = count($this->db->executeFetchAll($query, $params));
			$all[$n]->nQuestions = $nQuestions;
		}
		
		// Number of answers
		$query = 'SELECT id FROM answer
							WHERE user_id = ?';
		
		for($n = 0; $n < count($all); $n++)
		{
			$params = array($all[$n]->id);
			$nAnswers = count($this->db->executeFetchAll($query, $params));
			$all[$n]->nAnswers = $nAnswers;
		}
		
		$this->views->add('geogebra/anvandare', [
				'anvandare' => $all,
		]);
	}

	// Check if the user is signed in
	public function isSignedIn()
	{
		return $this->session->get('userdata') !== null; 
	}
	
	// Login user
	public function loginAction()
	{
		$this->di->session();
		$form = new \Anax\GeogebraForms\CFormLoginUser();
		$form->setDI($this->di);
		$form->check();
		$this->di->theme->setTitle("Logga in");
		$this->di->views->add('geogebra/login_form', [
			'html' => $form->getHTML(),
		]);
	}
	
	// Check if the user and password is in the db
	public function checkLogin($userData)
	{
		$userFromDb = $this->users->query()->where('username = "' . $userData['username'] . '"')->execute();
		
		if(!isset($userFromDb[0]))
			return false;
		
		if($userData['username'] == $userFromDb[0]->username && $userData['password'] == $userFromDb[0]->password)
		{	
			// Username och pass i correct. Login.
			$this->login($userFromDb[0]);
			return true;
		}
		else
			return false;
	}
	
	// Set the user to signed in
	private function login($userData)
	{
		$this->session->set('userdata', $userData);
	}
	
	// Logout the user
	public function logoutAction()
	{
		$this->session->set('userdata', null);
		$url = $this->url->create('');  
		$this->response->redirect($url); 
	}

	// Add user
	public function addAction()
	{			
		$this->di->session();
		$form = new \Anax\GeogebraForms\CFormAddUser();
		$form->setDI($this->di);
		$form->check();
		$this->di->theme->setTitle("Skapa användare");
		$this->di->views->add('geogebra/reg_form', [
			'html' => $form->getHTML(),
		]);
	}
		
	// View user information
	public function profileAction($id)
	{
		$u = $this->users->find($id);

		// Get asked questions
		$query = 'SELECT * FROM question q
							WHERE user_id = ?';
		$params = array($id);
		$questions = $this->db->executeFetchAll($query, $params);

		// Get tags
		$tag = new \Anax\Tag\Tag();
		$tag->setDI($this->di);
		for($n = 0; $n < count($questions); $n++)
		{
			$questions[$n]->tags = $tag->getTags($questions[$n]->id);
		}
		
		// GEt answered questions		
		$query = 'SELECT question_id FROM answer a
							WHERE user_id = ?';
		$params = array($id);
		$questionsIds = $this->db->executeFetchAll($query, $params);
		
		$ansQ = array();
		$query = 'SELECT * FROM question q
							WHERE id = ?';		
		
		for($n = 0; $n < count($questionsIds); $n++)
		{
			$params = array($questionsIds[$n]->question_id);
			$ansQ[] = $this->db->executeFetchAll($query, $params)[0];
		}
		// Get the tags
		for($n = 0; $n < count($ansQ); $n++)
		{
			$ansQ[$n]->tags = $tag->getTags($ansQ[$n]->id);
		}
		
		$ansQ = array_unique($ansQ, SORT_REGULAR);
		
		$this->theme->setTitle("Profil");
		$this->views->add('geogebra/user_profile', [
			'userdata' => $u,
			'askedQ'	 => $questions,
			'ansQ'		 => $ansQ,
		]);	
	}
	
	// Update user information 
	public function updateAction()
	{
		if(!$this->isSignedIn())
		{
			$url = $this->url->create('');
			$this->response->redirect($url); 
		}
		
		$this->di->session();
		$form = new \Anax\GeogebraForms\CFormUpdateUser($this->di->session->get('userdata'));
		$form->setDI($this->di);
		$form->check();
		$this->di->theme->setTitle("Uppdatera info");
		$this->di->views->add('geogebra/reg_form', [
			'html' => $form->getHTML(),
		]);
	}
	
	public function getMostActive($number)
	{
		$this->initialize();
		$all = $this->users->query()->execute();				
		
		// Number of questions
		$query = 'SELECT id FROM question
							WHERE user_id = ?';
		
		for($n = 0; $n < count($all); $n++)
		{
			$params = array($all[$n]->id);
			$nQuestions = count($this->db->executeFetchAll($query, $params));
			$all[$n]->nQuestions = $nQuestions;
		}
		
		// Number of answers
		$query = 'SELECT id FROM answer
							WHERE user_id = ?';
		
		for($n = 0; $n < count($all); $n++)
		{
			$params = array($all[$n]->id);
			$nAnswers = count($this->db->executeFetchAll($query, $params));
			$all[$n]->nAnswers = $nAnswers;
		}
		
		usort($all, array($this, "sortActive"));
		
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
	
	private function sortActive($u_1, $u_2)
	{
		return ($u_1->nQuestions + $u_1->nAnswers) > ($u_2->nQuestions + $u_2->nAnswers);
	}
}
