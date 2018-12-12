<?php
/**
 * @file
 * A collection of question parts that can be selected randomly.
 */

namespace CL\Exam;

/**
 * A collection of question parts that can be selected randomly.
 */
class QuestionParts {
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
			case 'columns':
				$this->columns = $value;
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
	 * Add a question part option
	 * @param QuestionPart $part Question part to add
	 * @param $group If non-null, no two questions will be selected from the same group.
	 */
	public function add(QuestionPart $part, $group = null) {
		$this->parts[] = ['part'=>$part, 'group'=>$group];
	}

	/**
	 * Present the question.
	 * @param Question $question
=	 * @param bool|null $answered True if displayed as answered
	 * @return string
	 */
	public function present(Question $question, $num, $answered = null) {
		$html = '';

		if($this->columns) {
			$html .= '<div class="cl-exam-columns"><div>';
		}

		$labels = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i',
			'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't',
			'u', 'v', 'w', 'x', 'y', 'z'];

		$parts = $this->parts;
		shuffle($parts);

		$cnt = 0;
		$groups = [];
		foreach($parts as $part) {

			if($part['group'] !== null) {
				//
				// Ensure we do not have two items from the same group
				//
				if(isset($groups[$part['group']])) {
					continue;
				}

				$groups[$part['group']] = true;
			}


			$label = $labels[$cnt];
			$html .= $part['part']->present_actual($question, $label . ') ', $answered);

			$cnt++;
			if($cnt >= $num) {
				break;
			}

			if($this->columns && $cnt === ($num / 2)) {
				$html .= '</div><div>';
			}
		}

		if($this->columns) {
			$html .= '</div></div>';
		}

		return $html;
	}

	private $parts = [];
	private $columns = false;
}