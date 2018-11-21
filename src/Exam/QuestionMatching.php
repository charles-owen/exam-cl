<?php
/**
 * @file
 * Matching question
 */

namespace CL\Exam;


class QuestionMatching extends Question {

	public function __construct(ExamView $view, $num) {
		parent::__construct($view, $num);

		$this->question = <<<QUESTION
Match the term on the left to the most appropriate statement on the right. Not all statements will apply.
 Statements are only used once:
QUESTION;

	}

	public function add($a, $b, $exclusive=null) {
		$this->questions[] = ['a'=>$a, 'b'=>$b, 'exclusive'=>$exclusive];
	}

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
			case 'count':
				return $this->count;

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
			case 'count':
				$this->count = +$value;
				break;

			case 'count2':
				$this->count2 = +$value;
				break;

			default:
				parent::__set($property, $value);
				break;
		}
	}


	public function present_actual($part = '', $answered = null, $class=null) {
		$html = parent::present_actual($part, $answered);

		if($answered === null && $this->view->key) {
			$answered = true;
		}

		// Copy and shuffle the questions
		$shuffled = $this->questions;
		shuffle($shuffled);

		// Remove any exclusives
		$this->shuffled = [];
		$exclusive = [];
		foreach($shuffled as $question) {
			if(!empty($question['exclusive'])) {
				if(isset($exclusive[$question['exclusive']])) {
					continue;
				}

				$exclusive[$question['exclusive']] = 'true';
			}

			$question['num'] = count($this->shuffled);
			$this->shuffled[] = $question;
		}

		$html .= <<<HTML
<div class="cl-matching">
<div>
HTML;

		$items = [];
		for($i=0; $i<$this->count && $i<count($this->shuffled); $i++) {
			$question = $this->shuffled[$i];

			$items[] = $question;


		}

		shuffle($items);
		foreach($items as $question) {
			$a = $question['a'];
			$letter = $this->alpha($question['num']);

			if($answered === true) {
				$html .= "<p><span class=\"code underline\">&nbsp;$letter&nbsp;</span> $a</p>";
			} else {
				$html .= "<p>____ $a</p>";
			}
		}

		$html .= <<<HTML
</div><div>
HTML;

		if($this->count2 === 0) {
			$this->count2 = $this->count;
		}

		for($i=0; $i<$this->count2 && $i<count($this->shuffled); $i++) {
			$question = $this->shuffled[$i];
			$a = $question['a'];
			$b = $question['b'];
			$letter = $this->alpha($i);

			$html .= "<p><span class=\"cl-code\">$letter:</span> $b</p>";
		}

		$html .= <<<HTML
</div></div>
HTML;


		return $html;
	}

	private $questions = [];
	private $shuffled = [];

	private $count = 10;
	private $count2 = 0;
}