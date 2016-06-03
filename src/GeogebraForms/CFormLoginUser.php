<?php

namespace Anax\GeogebraForms;

/**
 * Anax base class for wrapping sessions.
 *
 */
class CFormLoginUser extends \Anax\HTMLForm\CForm
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
			),         
        'submit' => [
					'type'      	=> 'submit',
					'value'				=> 'Logga in',
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
			$saved = $this->di->UserController->checkLogin([								
						'username' => $this->Value('username'),
						'password' => hash('sha256', $this->Value('password')),
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
        $this->AddOutput("<p><i>Felaktigt användarnamn och/eller lösenord</i></p>");
        return false;
    }

    /**
     * Callback What to do when form could not be processed?
     *
     */
    public function callbackFail()
    {
        $this->AddOutput("<p><i>Felaktigt användarnamn och/eller lösenord</i></p>");
        $this->redirectTo();
    }
}