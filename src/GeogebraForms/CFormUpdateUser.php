<?php

namespace Anax\GeogebraForms;

/**
 * Anax base class for wrapping sessions.
 *
 */
class CFormUpdateUser extends \Anax\HTMLForm\CForm
{
    use \Anax\DI\TInjectionaware,
        \Anax\MVC\TRedirectHelpers;

		private $id;

    /**
     * Constructor
     *
     */
    public function __construct($userData)
    {
			$this->userData =$userData;
			$this->id = $this->userData->id;
			
       parent::__construct([], [            
        'name' => array(
					'type'        => 'text',
					'label'       => 'Namn:',
					'required'    => true,
					'validation'  => array('not_empty'),
					'value'				=> $userData->name,
				),        
				'password' => array(
					'type'        => 'password',
					'label'       => 'Lösenord:',
					'required'    => true,
					'validation'  => array('not_empty'),
					'value'				=> '',
			),   
				'email' => array(
					'type'        => 'email',
					'label'       => 'Lösenord:',
					'required'    => true,
					'validation'  => array('not_empty'),
					'value'				=> $userData->email,
			), 
        'submit' => [
					'type'      	=> 'submit',
					'value'				=> 'Uppdatera',
          'callback'  => [$this, 'callbackSubmit'],
          ],
        ]);
    }



    /**
     * Customise the check() method.
     *
     * @param callable $callIfSuccess handler to call if function returns true.
     * @param callable $callIfFail    handler to call if function returns true.
     */
    public function check($callIfSuccess = null, $callIfFail = null)
    {
        return parent::check([$this, 'callbackSuccess'], [$this, 'callbackFail']);
    }

   /**
     * Callback for submit-button.
     *
     */
    public function callbackSubmit()
    {			
			$saved = $this->di->UserController->users->update([
							'id'	=> $this->id,
							'name' => $this->Value('name'),
							'password' => hash('sha256', $this->Value('password')),
							'email' => $this->Value('email'),
					]);		        

        if ($saved == true) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Callback What to do if the form was submitted?
     *
     */
    public function callbackSuccess()
    {
			$userName = $this->di->session->get('userdata')->username;
			$this->di->session->set('userdata', null);
		
			$newUserData = $this->di->UserController->users->query()->where('username = "' . $userName . '"')->execute()[0];
		
		$this->di->session->set('userdata', $newUserData);
        $this->redirectTo("");
    }

    /**
     * Callback for submit-button.
     *
     */
    public function callbackSubmitFail()
    {
        $this->AddOutput("<p><i>DoSubmitFail(): Form was submitted but I failed to process/save/validate it</i></p>");
        return false;
    }

    /**
     * Callback What to do when form could not be processed?
     *
     */
    public function callbackFail()
    {
        $this->AddOutput("<p><i>Form was submitted and the Check() method returned false.</i></p>");
        $this->redirectTo();
    }
}