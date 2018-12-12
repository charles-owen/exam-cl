<?php
/**
 * @file
 * A question part where we fill in the blank.
 */

namespace CL\Exam;


class QuestionPartBlank extends QuestionPart {

	/**
	 * Property get magic method
	 *
	 * <b>Properties</b>
	 * Property | Type | Description
	 * -------- | ---- | -----------
	 *
	 * @param string $property Property name
	 * @return mixed
	 */
	public function __get($property) {
		switch($property) {


			default:
				return parent::__get($property);
		}
	}

	/**
	 * Property set magic method
	 *
	 * <b>Properties</b>
	 * Property | Type | Description
	 * -------- | ---- | -----------
	 *
	 * @param string $property Property name
	 * @param mixed $value Value to set
	 */
	public function __set($property, $value) {
		switch($property) {
			case 'question':
				$this->question = $value;
				break;

			case 'answer':
				$this->answers[] = $value;
				break;

			default:
				parent::__set($property, $value);
				break;
		}
	}

	/**
	 * Present the question.
	 * @param Question $question
	 * @param string $part Question part (as in a,b,c)
	 * @param bool|null $answered True if displayed as answered
	 * @return string
	 */
	public function present_actual(Question $question, $part = '', $answered = null) {
		$html = <<<HTML
<div><p>$part 
HTML;

		if(!$answered) {
			$html .= $this->question;
		} else {
			$question = $this->question;
			$answers = '';
			foreach($this->answers as $answer) {
				if(strlen($answers) > 0) {
					$answers .= ' <em>or</em> ';
				}

				$answers .= $answer;
			}

			$html .= preg_replace('/\s_*\s/',
				' ___' . $answers . '___ ', $question);
		}


		$html .= '</p></div>';

		return $html;
	}

	private $question = '';
	private $answers = [];
}