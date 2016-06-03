<?php

namespace Anax\GeogebraForms;

/**
 * Anax base class for wrapping sessions.
 *
 */
class CFormAddQuestion extends \Anax\HTMLForm\CForm
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
						'question_heading'=> [
                'type'        => 'text',
                'label'       => 'Rubrik',
                'required'    => true,
                'validation'  => ['not_empty'],
            ],
            'question_text'		=> [
                'type'        => 'textarea',
                'label'       => 'Din fråga',
                'required'    => true,
                'validation'  => ['not_empty'],
            ],
						'tags' => array(
								'type'        => 'tag-selector',
								'required'    => true,
                'validation'  => ['not_empty'],
						),
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
        $question = new \Anax\Question\Question();
        $question->setDI($this->di);

				// Not null
				$lastId = '1';
				
        $saved = $question->create([
						'question_heading' => $this->Value('question_heading'),  
            'question_text' => $this->Value('question_text'),    						    
						'user_id'				=> $this->di->session->get('userdata')->id,
        ], $lastId);
		

        if ($saved == true) {
					$tag = new \Anax\Tag\Tag();
					$tag->setDI($this->di);
					$tag->saveTags($lastId, $this->Value('tags'));
				
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
			$query = 'SELECT MAX(id) maxid FROM question';
			$lastID = $this->di->db->executeFetchAll($query, [])[0];
			$url =  $this->di->url->create('question/viewOne/'.$lastID->maxid); 			
			$this->redirectTo($url);
    }

    /**
     * Callback for submit-button.
     *
     */
    public function callbackSubmitFail()
    {
        $this->AddOutput("<p><i>Ange minst en tag!</i></p>");
        return false;
    }






    /**
     * Callback What to do when form could not be processed?
     *
     */
    public function callbackFail()
    {
        $this->AddOutput("<p><i>Ange minst en tag!</i></p>");
        $this->redirectTo();
    }
}