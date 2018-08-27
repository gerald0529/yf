<?php
/**
 * 
 * $poeditParser = new Text_Poedit_Parser('HelloWorld.po');
 * $poeditParser->parse();
 * 
 *
 * $this->data->dataRows['body'] =  $poeditParser->getArray();
 * echo HTML_Javascript_Convert::convertArrayToProperties($this->data->dataRows['body']);
 */

class Text_Poedit_Parser {

	protected $file;
	protected $header = '';
	protected $strings = array();

	protected function _fixQuotes($str) {
		return stripslashes($str);
	}

	public function __construct($file) {
		$this->file = $file;
	}

	public function parse() {
		$contents = file_get_contents($this->file);

		$parts = preg_split('#(\r\n|\n){2}#', $contents, -1, PREG_SPLIT_NO_EMPTY);

		$this->header = array_shift($parts);

		foreach ($parts as $part) {

			// parse comments
			$comments = array();
			preg_match_all('#^\\#: (.*?)$#m', $part, $matches, PREG_SET_ORDER);
			foreach ($matches as $m) $comments[] = $m[1];

			$isFuzzy = preg_match('#^\\#, fuzzy$#im', $part) ? true : false;

			preg_match_all('# ^ (msgid|msgstr)\ " ( (?: (?>[^"\\\\]++) | \\\\\\\\ | (?<!\\\\)\\\\(?!\\\\) | \\\\" )* ) (?<!\\\\)" $ #ixm', $part, $matches2, PREG_SET_ORDER);

			$k = $this->_fixQuotes($matches2[0][2]);
			$v = !empty($matches2[1][2]) ? $this->_fixQuotes($matches2[1][2]) : '';

			$this->strings[$k] = new Text_Poedit_String($k, $v, $isFuzzy, $comments);
		}
	}

	public function merge($strings) {
		foreach ((array)$strings as $str) {
			if (!in_array($str, array_keys($this->strings))) {
				$this->strings[$str] = new Text_Poedit_String($str);
			}
		}
	}

	public function getHeader() {
		return $this->header;
	}

	public function getStrings() {
		return $this->strings;
	}

	public function getJson() {
		return encode_json($this->getArray());
	}

	public function getArray() {
		$str = array();
		foreach ($this->strings as $s) {
			if ($s->value) $str[$s->key] = $s->value;
		}
		return $str;
	}

	public function toJson($outputFilename, $varName = 'l10n') {
		$str = "$varName = " . $this->getJson();
		return file_put_contents($outputFilename, $str) !== false;
	}

	public function save($filename = null) {
		$data = $this->header . "\n\n";
		foreach ($this->strings as $str) {
			$data .= $str;
		}
		return file_put_contents($filename ? $filename : $this->file, $data) !== false;
	}
}


?>