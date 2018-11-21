<?php
/**
 * @file
 * Base class for an exam question
 */

namespace CL\Exam;


/**
 * Base class for an exam question
 */
class Question {


	public function __construct(ExamView $view, $num) {
		$this->view = $view;
		$this->num = $num;

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


	public function present($part="") {
		$html = $this->present_actual($part, false);
		
		$html .= \Toggle::begin("Expand for answer.", "p");
		
		$html .= $this->present_actual($part, true);

		$html .= \Toggle::end();

		return $html;
	}

	/**
	 * Present exam question
	 * @param string $part Question part, like 'a'
	 * @param null $answered True if answered, false if not, null if use key value from the view.
	 * @return string
	 */
	public function present_actual($part='', $answered=null, $class=null) {
		$cls = $class !== null ? 'class="' . $class . '"' : '';
		$html = <<<HTML
<p $cls>$this->num $this->question</p>
HTML;
		$this->cnt = 0;
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
	
	protected function label($cnt) {
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
	protected function random_value($name, $fm, $to) {
		if(isset($this->values[$name])) {
			return $this->values[$name];
		}
		
		$r = mt_rand($fm, $to);
		$this->values[$name] = $r;
		return $r;
	}
	
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
	
	protected $roman = ['i', 'ii', 'iii', 'iv', 'v', 'vi', 'vii', 'viii', 
		'ix', 'x'];
	
	protected $cnt = 0;
	
	private $values = [];

	private $question = '';

	protected $view;
	private $num;
}