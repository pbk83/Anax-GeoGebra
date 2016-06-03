<?php

namespace Anax\GeogebraForms;

/**
 * Anax base class for wrapping sessions.
 *
 */
class CFormAddAnswer extends \Anax\HTMLForm\CForm
{
    use \Anax\DI\TInjectionaware,
        \Anax\MVC\TRedirectHelpers;

    /**
     * Constructor
     *
     */
		private $answerQuestion;
		 
    public function __construct($answerQuestion)
    {			
			$this->answerQuestion = $answerQuestion;
			
        parent::__construct([], [     
            'answer_text'		=> [
                'type'        => 'textarea',
                'label'       => 'Ditt svar',
                'required'    => true,
                'validation'  => ['not_empty'],
            ],
            'submit' => [
                'type'      => 'submit',
								'value'			=> 'Ok',
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
        $answer = new \Anax\Answer\Answer();
        $answer->setDI($this->di);

				// Not null
				$lastId = '1';
				
        $saved = $answer->create([
						'answer_text' => $this->Value('answer_text'),   						    
						'user_id'				=> $this->di->session->get('userdata')->id,
						'question_id'	=> $this->answerQuestion,
        ], $lastId);
		

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
        $this->redirectTo();
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