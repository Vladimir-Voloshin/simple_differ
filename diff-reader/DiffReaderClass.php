<?php
/**
 *
 *
 * @author  Sergey boonya Buynitskiy boonya41@gmail.com
 */
class DiffReader
{

    /**
     * Array of file lines
     *
     * @var array
     */
    protected $_content = array();

    /**
     * Read and procced file
     *
     * @return void
     */
    public function __construct()
    {}

    /**
     * Read and procced file
     *
     * @return void
     */
    public function getFile($file)
    {
        try {
            $this->_read($file)
                 ->_procceed();
        } catch (Exception $exc) {
            ;
        }
    }

    /**
     * ***
     *
     * @return void
     */
    public function listing($dir)
    {
        $dir = !empty($dir) ? $dir : __DIR__;
        $list = scandir($dir);
        $list = array_diff($list, array(
            '.',
            '..',
            '.htaccess',
            'diff-reader',
            'diff-reader.php',
            'ls.php',
        ));

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
     *
     */
    public function getAdditionalData()
    {
        /** Get username, location & current dir */
        preg_match('/([a-zA-Z0-9]+)\.dev\-([a-z]{2})\.cupidplc\.com$/i', $_SERVER['SERVER_NAME'], $match);
        $username = !empty($match[1]) ? $match[1] : 'username';
        $location = !empty($match[2]) ? $match[2] : 'dp';
        $pathArray = explode(DIRECTORY_SEPARATOR, __DIR__);
        $currentDir = end($pathArray);
    }

    /**
     * Checks file
     *
     * @return $this
     */
    protected function _isFileExists($file)
    {
        if (!preg_match('/([^\/]+)\.diff$/', $file)) {
            throw new Exception('File not found.');
        }
        return $this;
    }

    /**
     * Reading file
     *
     * @return $this
     */
    protected function _read($file)
    {
        $this->_isFileExists($file);
        $content = file($file);
        if (empty($content)) throw new Exception('File are empty.');
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
        if (empty($this->_content)) throw new Exception('Content is empty.');
        foreach ($this->_content as $i => &$line) {
            $line['content'] = $this->_trimEndString($line['content']);
            $line['content'] = $this->_replaceSymbols($line['content']);
            $line['class'] = $this->_addClasses($line['content']);
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
        $search = array('&', '<', '>');
        $replace = array('&amp;', '&lt;', '&gt;');
        return str_replace($search, $replace, $string);
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