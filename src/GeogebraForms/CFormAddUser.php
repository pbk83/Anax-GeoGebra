<?php

namespace Anax\GeogebraForms;

/**
 * Anax base class for wrapping sessions.
 *
 */
class CFormAddUser extends \Anax\HTMLForm\CForm
{
    use \Anax\DI\TInjectionaware,
        \Anax\MVC\TRedirectHelpers;



    /**
     * Constructor
     *
     */
    public function __construct()
    {
       parent::__construct([], [            
        'name' => array(
					'type'        => 'text',
					'label'       => 'Namn:',
					'required'    => true,
					'validation'  => array('not_empty'),
				),     
				'username' => array(
					'type'        => 'text',
					'label'       => 'Användarnamn:',
					'required'    => true,
					'validation'  => array('not_empty'),
				), 
				'password' => array(
					'type'        => 'password',
					'label'       => 'Lösenord:',
					'required'    => true,
					'validation'  => array('not_empty'),
				), 
				'email' => array(
					'type'        => 'email',
					'label'       => 'E-post:',
					'required'    => true,
					'validation'  => array('not_empty'),
				),
        'submit' => [
					'type'      	=> 'submit',
					'value'				=> 'Skapa',
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
				$saved = $this->di->UserController->users->save([
								'name' => $this->Value('name'),
								'username' => $this->Value('username'),
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