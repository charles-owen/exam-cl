<?php
/**
 * @file
 * A question part where we fill in the blank.
 */

namespace CL\Exam;

/**
 * A question part where we fill in the blank.
 *
 * @cond
 * @property string question
 * @property string answer
 *
 * @endcond
 */
class QuestionPartBlank extends QuestionPart {

	/**
	 * Property set magic method
	 *
	 * <b>Properties</b>
	 * Property | Type | Description
	 * -------- | ---- | -----------
	 * answer | string | An expected answer (can be multiple)
	 * question | string | The question
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
		$html = $this->present_start();

		$question = str_replace("{part}", $part, $this->question);

		if($answered) {
			$answers = '';
			foreach($this->answers as $answer) {
				if(strlen($answers) > 0) {
					$answers .= ' <em>or</em> ';
				}

				$answers .= $answer;
			}

			$question = preg_replace('/\s__*($|[^_])/',
				' ___' . $answers . '___${1}', $question);
		}


		$html .= $question . $this->present_end($answered);

		return $html;
	}

	private $question = '';
	private $answers = [];
}