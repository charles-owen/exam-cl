<?php
/**
 * View class for automatic exam creation.
 */

namespace CL\Exam;


use CL\Site\Site;
use CL\Site\System\Server;
use CL\Course\Member;

/**
 * View class for automatic exam creation.
 *
 * @property boolean key true to represent the exam key
 * @property string exam Which exam (A, B, etc.)
 * @property boolean crowdmark True to format for a Crowdmark exam
 */
class ExamView extends \CL\Course\View {
	/**
	 * ExamView constructor.
	 * @param Site $site Site object
	 * @param array $get $_GET - If set to null, this is a student examples page.
	 * @param array $options Options to pass to startup
	 * @param Server|null $server Server object
	 */
	public function __construct(Site $site, array $get=null, array $options = [], Server $server=null) {
	    if($get !== null) {
	        $this->actual = true;

            if($server === null) {
                $server = new Server();
            }

            $options['at-least'] = Member::TA;
            parent::__construct($site, $options);

            $this->key = !empty($get['key']);
            $this->exam = strtoupper(isset($get['exam']) ? strip_tags($get['exam']) : 'A' );
            $this->seed = isset($get['seed']) ? strip_tags($get['seed']) : mt_rand(1, 999999);
            if(isset($get['reseed'])) {
                $this->seed = mt_rand(1, 999999);
                $keystr = $this->key ? "&key" : "";
                $server->redirect("?exam=$this->exam&seed=$this->seed$keystr");
            }
        } else {
            parent::__construct($site, $options);

            $this->actual = false;
            $this->key = false;
            $this->seed = mt_rand(1, 999999);
        }

        $this->addCSS('vendor/cl/exam/exam.css');

		$this->seed();
	}

	/**
	 * Property get magic method
	 *
	 * <b>Properties</b>
	 * Property | Type | Description
	 * -------- | ---- | -----------
	 * key | boolean | true to represent the exam key
     * exam | string | Which exam (A, B, etc.)
     * crowdmark | boolean | True to format for a Crowdmark exam
     *
	 * @param string $property Property name
	 * @return mixed
	 */
	public function __get($property) {
		switch($property) {
			case 'key':
				return $this->key;

			case 'exam':
				return $this->exam;

			case 'crowdmark':
				return $this->crowdmark;

			default:
				return parent::__get($property);
		}
	}


    /**
     * Convert the exam ID (A, B, etc.) to an index value.
     *
     * Index values start at 1, so A=1, B=2, etc. If $cnt is
     * specified, the index will start again after each $cnt exams.
     * For example, if $cnt = 3, then A=1, B=2, C=3, D=1, E=2, etc.
     *
     * @param int $cnt Maximum index value.
     * @return int Index value.
     */
	public function index($cnt = 0) {
	    $ndx = ord($this->exam) - 1;
	    if($cnt > 0) {
	        return $ndx % $cnt + 1;
        } else {
	        return $ndx + 1;
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
			case 'title':
				$this->title = $value;

				$value .= $this->exam;
				if($this->key) {
					$value .= ' Key';
				}
				$this->setTitle($value);
				break;

			case 'crowdmark':
				$this->crowdmark = $value;
				if($this->crowdmark) {
					$this->style = <<<STYLE
@page {
  margin: 1.5in;
}
STYLE;

				}
				break;

            case 'matching':
                $this->matching = $value;
                break;

			case 'titlePageContent':
				$this->titlePageContent = $value;
				break;

			default:
				parent::__set($property, $value);
				break;
		}
	}


	/**
	 * Generate the page header.
	 * @param bool $contentDiv Ignored
	 * @param string $nav Ignored
	 * @return string HTML
	 */
	public function header($contentDiv = true, $nav = '') {
	    if(!$this->actual) {
	        return parent::header($contentDiv, $nav);
        }

		$keyCheck = $this->key ? " checked" : '';
		$title = $this->site->siteName . ' ' . $this->title;
		$keyStr = $this->key ? " Key-" . $this->exam : "&nbsp;";
		$season = $this->section->season;
		$year = $this->section->year;

		$pageClass = 'cl-exam';
		if($this->crowdmark) {
			$pageClass .= ' cl-crowdmark';
		}

		$html = <<<HTML
<form class="cl-exam-form" method="get">
<p>Exam: <input class="cl-exam-name" name="exam" type="text" value="$this->exam">
	Key: <input type="checkbox" name="key"$keyCheck>
	Seed: <input class="cl-exam-seed" name="seed" type="text" value="$this->seed"> <input type="submit" name="reseed" value="New">
	<input type="submit">
</p>
</form>
<div class="$pageClass">
HTML;

		if(!$this->crowdmark) {
			$html .= <<<HTML
	<header>
		<div class="left"><h1>$title</h1>
			<p><em>$season, $year exam $this->seed</em></p></div>
		<div class="right"><h2 class="name">Name: <span class="blank">$keyStr</span><br>
				ID: <span class="blank">&nbsp;</span></h2></div>
	</header>			
HTML;
		} else {
			$html .= <<<HTML
	<header>
		<div class="title">
			<h1>$title</h1>
			<p><em>$season, $year exam $this->seed</em></p>
HTML;

			if($this->matching) {
			    $html .= '<div style="height:4in">&nbsp;</div>';
            }

			$html .= <<<HTML
			$this->titlePageContent
		</div>
HTML;

			if(!$this->matching) {

			    $html .= <<<HTML
		<div class="name">
			<p><span>Last Name: </span><span class="blank">&nbsp;</span></p>
			<p><span>First Name: </span><span class="blank">&nbsp;</span></p>
			<p><span>User ID: </span><span class="blank">&nbsp;</span></p>	
		</div>
HTML;
            }

			$html .= <<<HTML
	</header>		
	<div class="break"></div>
HTML;
		}

		return $html;
	}

	/**
	 * Generate the page footer
	 * @param bool $contentDiv ignored
	 * @return string HTML
	 */
	public function footer($contentDiv = true) {
	    if(!$this->actual) {
	        return parent::footer($contentDiv);
        }

		return '';
	}

	/**
	 * Create a page break
	 * @return string HTML
	 */
	public function page() {
		return '<div class="break"></div>';
	}

	/**
	 * Seed the random number generators for reproducible questions.
	 */
	public function seed() {
		mt_srand($this->seed);
		srand($this->seed);
	}

	private $title = 'Exam';

	private $key;
	private $exam;
	private $seed;
	private $crowdmark = false;
	private $matching = false;
	private $titlePageContent = '';

	// If true, this is an actual exam presentation rather than
    // student example questions.
	private $actual;
}