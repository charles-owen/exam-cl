<?php
/**
 * @file
 * Base class for an exam question
 */

namespace CL\Exam;


/**
 * Base class for an exam question
 *
 * @cond
 * @property QuestionParts parts
 * @endcond
 */
class Question {

	/**
	 * Question constructor.
	 * @param ExamView $view
	 * @param int $num Question number
	 */
	public function __construct(ExamView $view, $num) {
		$this->view = $view;
		$this->num = $num;

		$this->parts = new QuestionParts($this);

		$view->seed();
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
			case 'num':
				return $this->num;

			case 'view':
				return $this->view;

			case 'rubric':
				return $this->rubric;

			case 'parts':
				return $this->parts;

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

			case 'rubric':
				$this->rubric = $value;
				break;

			case 'answer':
				$this->answer = $value;
				break;

			case 'more':
				$this->more = $value;
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
	 * Present the question
	 * @return string HTML
	 */
	public function present() {
		$html = $this->present_actual(false);
		
		$html .= \Toggle::begin("Expand for answer.", "p");
		
		$html .= $this->present_actual(true);

		$html .= \Toggle::end();

		return $html;
	}

	/**
	 * Present exam question
	 * @param string|null $answered True if answered, false if not, null if use key value from the view.
	 * @param string|null $class Additional class to add to p tag
	 * @return string
	 */
	public function present_actual($answered=null, $class=null) {
		if($answered === null && $this->view->key) {
			$answered = true;
		}

		$cls = $class !== null ? 'class="' . $class . '"' : '';
		$question = $this->question;
		$question = preg_replace('/\R\R/', '</p><p>', $question);
		$html = <<<HTML
<p $cls>$this->num $question</p>$this->more
HTML;
		$this->cnt = 0;

		if($answered && $this->answer !== null) {
			$html .= $this->answer;
		}

		if($answered && $this->rubric !== null) {
			$html .= '<div class="cl-rubric">' . $this->rubric . '</div>';
		}

		$html .= $this->parts->present($this, 0, $answered);
		return $html;
	}

	/**
	 * Convert an index to a letter A-Z
	 * @param int $i Index starting at zero
	 * @return string letter A to Z
	 */
	protected function alpha($i) {
		$letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		return $letters[$i];
	}

	/**
	 * Generate a question label as a roman numeral
	 * @param int $cnt
	 * @return string
	 */
	public function label($cnt) {
		if($cnt <= 1) {
			return '<p>';
		}
		
		$ret = $this->roman[$this->cnt];
		
		$this->cnt++;
		return '<p>' . $ret . ') ';
	}
	
	/**
	 * Create random values in some integer range with a name.
	 * If the function is called with the same name again, the 
	 * same random value is returned.
	 */
	public function random_value($name, $fm, $to) {
		if(isset($this->values[$name])) {
			return $this->values[$name];
		}
		
		$r = mt_rand($fm, $to);
		$this->values[$name] = $r;
		return $r;
	}

	/**
	 * Generate a random floating point alue
	 * @param float $min
	 * @param float $max
	 * @return float Random value
	 */
	protected static function rand_float($min, $max) {
		$f = mt_rand(0, 100000) * 0.00001;
		return $min + (1 - $f) * $max;
	}
	
	/**
	 * Choose an option by chance
	 * @param double $prob Probability of the option
	 * @return true if option is selected
	 */
	protected static function chance($prob) {
		if($prob == 0) {
			return false;
		}
		
		$f = self::rand_float(0, 1);
		return $prob >= $f;
	}

	/// Roman numbers from 1-10
	protected $roman = ['i', 'ii', 'iii', 'iv', 'v', 'vi', 'vii', 'viii', 
		'ix', 'x'];

	/// Count of how many sub-parts of this question.
	protected $cnt = 0;
	
	private $values = [];

	private $question = '';
	private $more = '';
	private $answer = null;

	/// The view class
	protected $view;
	private $num;

	// Display additional rubric information flag
	private $rubric = null;

	// Question parts
	private $parts;
}