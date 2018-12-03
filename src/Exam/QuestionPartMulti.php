<?php
/**
 * @file
 * Class that represents a multiple choice question for an exam.
 */

namespace CL\Exam;

/**
 * Class that represents a multiple choice question for an exam.
 */
class QuestionPartMulti {


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
				$trace = debug_backtrace();
				trigger_error(
					'Undefined property ' . $property .
					' in ' . $trace[0]['file'] .
					' on line ' . $trace[0]['line'],
					E_USER_NOTICE);
				return null;
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

			default:
				$trace = debug_backtrace();
				trigger_error(
					'Undefined property ' . $property .
					' in ' . $trace[0]['file'] .
					' on line ' . $trace[0]['line'],
					E_USER_NOTICE);
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
<div class="cl-exam-multi"><p>$part $this->question</p>
HTML;

		$imgs = $question->view->site->root . '/vendor/cl/exam/img';
		shuffle($this->answers);
		foreach($this->answers as $answer) {
			$html .= '<p class="cl-exam-answer">';
			if($answered === true && $answer['correct']) {
				$html .= '<img src="' . $imgs . '/box-x.png">';
			} else {
				$html .= '<img src="' . $imgs . '/box.png">';
			}

			$html .= <<<HTML
 {$answer['text']}</p>
HTML;
		}

		$html .= '</div>';

		return $html;
	}

	/**
	 * Add an answer
	 * @param string $text Answer to add
	 * @param bool $correct True if this answer is correct.
	 */
	public function answer($text, $correct=false) {
		$this->answers[] = ['text'=>$text, 'correct'=>$correct];
	}


	private $question = '';
	private $answers = [];
}