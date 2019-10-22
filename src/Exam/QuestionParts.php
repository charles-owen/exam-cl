<?php
/**
 * @file
 * A collection of question parts that can be selected randomly.
 */

namespace CL\Exam;

/**
 * A collection of question parts that can be selected randomly.
 * @cond
 * @property bool shuffle
 * @property int num
 * @property bool columns
 *
 * @endcond
 *
 */
class QuestionParts {
    public function __construct(Question $question) {
        $this->question = $question;
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
	 * num | int | Number of parts to present
     * shuffle | bool | If true the parts are shuffled before presentation (default=false)
     * columns | bool | If true, the parts are presented in two columns.
	 *
	 * @param string $property Property name
	 * @param mixed $value Value to set
	 */
	public function __set($property, $value) {
		switch($property) {
			case 'columns':
				$this->columns = $value;
				break;

			case 'num':
				$this->num = +$value;
				break;

			case 'shuffle':
				$this->shuffle = $value;
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
	 * @param string $group If non-null, no two questions will be selected from the same group.
	 */
	public function add(QuestionPart $part, $group = null) {
		$this->parts[] = ['part'=>$part, 'group'=>$group];
		$part->owner = $this->question;
	}

	/**
	 * Present the question.
	 * @param Question $question
	 * @param int $num Question number
	 * @param bool|null $answered True if displayed as answered
	 * @return string
	 */
	public function present(Question $question, $num, $answered = null) {
		$num = $num > 0 ? +$num : $this->num;

		if($num === 0) {
			$num = count($this->parts);
		}

		$html = '';

		if($this->columns) {
			$html .= '<div class="cl-exam-columns"><div>';
		}

		$labels = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i',
			'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't',
			'u', 'v', 'w', 'x', 'y', 'z'];

		$parts = $this->parts;

		if($this->shuffle) {
			shuffle($parts);
		}

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


			$label = $num === 1 || count($parts) === 1 ? '' : $labels[$cnt] . ') ';
			$questionPart = $part['part']->present_actual($question, $label, $answered);
            $questionPart = str_replace("{part}", $label, $questionPart);
			$html .= $questionPart;

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
	private $num = 0;
	private $shuffle = false;
	private $question;
}