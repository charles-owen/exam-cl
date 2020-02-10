<?php
/**
 * @file
 * Base class for part of a question.
 */

namespace CL\Exam;

/**
 * Base class for part of a question.
 *
 * @cond
 * @property Question owner
 * @property string rubric Rubric information to present with the exam
 * @endcond
 */
class QuestionPart {

	public function __construct() {
	}

	/**
	 * Property get magic method
	 *
	 * <b>Properties</b>
	 * Property | Type | Description
	 * -------- | ---- | -----------
     * endColumns | bool | If set true, this question part ends columns
	 * owner | Question | Question that owns this question part
     *
	 * @param string $property Property name
	 * @return mixed
	 */
	public function __get($property) {
		switch($property) {
            case 'endColumns':
                return $this->endColumns;

			case 'question':
				return $this->question;

			case 'rubric':
				return $this->rubric;

            case 'owner':
                return $this->owner;

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
     * endColumns | bool | If set true, this question part ends columns
     * owner | Question | Question that owns this question part
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
				$this->answer = $value;
				break;

			case 'space':
				$this->space = $value;
				break;

			case 'page':
				$this->page = $value;
				break;

			case 'rubric':
				$this->rubric = $value;
				break;

            case 'owner':
                $this->owner = $value;
                break;

            case 'endColumns':
                $this->endColumns = $value;
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

	public function generate() {
		$this->generated = true;
	}

	protected function present_start() {
		$html = '';

		if($this->page) {
			$html .= '<div class="break"></div>';
		}

		$html .= '<div>';

		return $html;
	}

	protected function present_end($answered = false) {
		$html = '';

		if($this->space !== null && !$answered) {
			$html .= '<div style="height: ' . $this->space . '">';
		}

		if($answered && $this->rubric !== null) {
			$html .= '<div class="cl-rubric">' . $this->rubric . '</div>';
		}

		$html .= '</div>';

		return $html;

	}

	/**
	 * Present the question.
	 * @param Question $question
	 * @param string $part Question part (as in a,b,c)
	 * @param bool|null $answered True if displayed as answered
	 * @return string
	 */
	public function present_actual(Question $question, $part = '', $answered = null) {
		if(!$this->generated) {
			$this->generate();
		}

		$html = $this->present_start();

		$question = str_replace("{part}", $part, $this->question);
		$html .= $question;


		if($answered) {
			$html .= $this->answer;
		}

		$html .= $this->present_end($answered);

		return $html;
	}

    /**
     * Convert a part number to a roman numeral
     * @param int $num 1 for i, 2 for ii, etc.
     * @return string
     */
    public function roman($num) {
        /// Roman numbers from 1-10
        $roman = ['i', 'ii', 'iii', 'iv', 'v', 'vi', 'vii', 'viii', 'ix', 'x'];

        return $roman[$num - 1];
    }


    /**
     * Create random values in some integer range with a name.
     * If the function is called with the same name again, the
     * same random value is returned.
     */
    protected function random_value($name, $fm, $to) {
        return $this->owner->random_value($name, $fm, $to);
    }



	private $generated = false;
	private $question = '';
	private $answer = '';
	private $space = null;
	private $page = false;
	private $rubric = null;
	private $owner = null;
	private $endColumns = false;
}