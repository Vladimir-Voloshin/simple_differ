<?php
/**
 * Main class
 *
 * @author  Sergey boonya Buynitskiy boonya41@gmail.com
 * @url     https://github.com/boonya/Diff-Reader
 */
class DiffReader
{
    /**
     * weather we should print copy/paste-friendly version 
     *
     * @var boolean
     */
    protected $_strip_line_numbers = false; 
    /**
     * Array of file lines
     *
     * @var array
     */
    protected $_content = array();
    /**
     * Array of symbols should be replaced
     *
     * @var array
     */
    protected $_search = array('&', '<', '>', ' ');
    /**
     * Array with symbols for replacement
     *
     * @var array
     */
    protected $_replace = array('&amp;', '&lt;', '&gt;', '&nbsp;');

    /**
     * Read and procced file
     *
     * @return void
     */
    public function __construct($strip_line_numbers)
    {
      if(!empty($strip_line_numbers))
      {
        $this->_search  = array_merge($this->_search,  array('+', '-'));
        $this->_replace = array_merge($this->_replace, array(' ', ' '));
      }
    }

    /**
     * Read and procced file
     *
     * @return void
     */
    public function getFile($file)
    {
        $this->_read($file)->_procceed();
        return $this;
    }

    /**
     * Return array of diff files only
     *
     * Scans the specified directory, discards all found items
     * except files with the extension ".diff" and returns array with it.
     *
     * @param  string $dir
     * @return array
     */
    public function listing($dir)
    {
        $dir = (empty($dir) || !is_dir($dir))
            ? realpath(__DIR__ . '/../')
            : $dir;
        $list = scandir($dir);
        $list = preg_filter('/.\.diff$/i', '$0', $list);
        return $list;
    }

    /**
     * Returns the content array
     *
     * @return array
     */
    public function getContent()
    {
        return $this->_content;
    }

    /**
     * Checks file
     *
     * @return boolean
     */
    protected function _isFileExists($file)
    {
        if (!preg_match('/([^\/]+)\.diff$/', $file)) return false;
        if (!file_exists($file)) return false;
        return true;
    }

    /**
     * Reading file
     *
     * @return $this
     */
    protected function _read($file)
    {
        if (!$this->_isFileExists($file)) {
            throw new InvalidArgumentException('File not found.');
        }
        $content = file($file);
        if (empty($content)) throw new LengthException('File are empty.');
        foreach ($content as $i => &$line) {
            $this->_content[]['content'] = $line;
        }
        return $this;
    }

    /**
     *
     *
     * @return $this
     */
    protected function _procceed()
    {
        if (empty($this->_content)) throw new LengthException('Content is empty.');
        foreach ($this->_content as $i => &$line) {
            $line['content'] = $this->_trimEndString($line['content']);
            $line['class'] = $this->_addClasses($line['content']);
            $line['content'] = $this->_replaceSymbols($line['content']);
            $line['content'] = $this->_addTags($line['content']);
        }
        return $this;
    }

    /**
     *
     */
    protected function _trimEndString($string)
    {
        return rtrim($string, "\n");
    }

    /**
     *
     */
    protected function _replaceSymbols($string)
    {
        return str_replace($this->_search, $this->_replace, $string);
    }

    /**
     *
     * @return string
     */
    protected function _addClasses(&$string)
    {
        $class = array();
        /** *** */
        if (preg_match('/^diff --git/', $string)
            || preg_match('/^index [a-z0-9]{7}\.\.[a-z0-9]{7} [0-9]{6}/', $string)
            || preg_match('/^\-{3}/', $string)
            || preg_match('/^\+{3}/', $string)
        ) $class[] = 'file-info';
        /** *** */
        if (preg_match('/^\-/', $string) 
            && !preg_match('/^\-{3}/', $string)
        ) {
            $class[] = 'removed';
        }
        /** *** */
        if (preg_match('/^\+/', $string) 
            && !preg_match('/^\+{3}/', $string)
        ) {
            $class[] = 'added';
        }
        /** *** */
        return implode(' ', $class);
    }

    /**
     *
     * @return string
     */
    protected function _addTags($string)
    {
        /** *** */
        if (preg_match('/^(\@\@ \-[\d]+,[\d]+ \+[\d]+,[\d]+ \@\@)(.*)/', $string, $match)) {
            $string = '<span class="content-start">' . $match[1] . '</span>' . $match[2];
        }
        /** *** */
        // $string = $this->_checkWhiteSpaces($string);
        /** *** */
        return $string;
    }

    /**
     *
     * @return string
     */
    protected function _checkWhiteSpaces($string)
    {
        $trimmedString = rtrim($string);
        if (strlen($trimmedString) < strlen($string)) {
            $whiteSpaces = '';
            $whiteSpacesCount = strlen($string) - strlen($trimmedString);
            for ($i = 0; $i < $whiteSpacesCount; $i++) {
                $whiteSpaces .= ' ';
            }
            $string = $trimmedString . '<span class="empty-string">' . $whiteSpaces . '</span>';
        }
        return $string;
    }

}