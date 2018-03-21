<?php
/**
 * Contains the classes for easily exporting data
 *
 * @license GNU
 * @author  marcan <marcan@smartfactory.ca>
 * @link    http://www.smartfactory.ca The SmartFactory
 * @package SmartObject
 */

/**
 * SmartObjectExport class
 *
 * Class to easily export data from SmartObjects
 *
 * @package SmartObject
 * @author  marcan <marcan@smartfactory.ca>
 * @link    http://www.smartfactory.ca The SmartFactory
 */
class SmartObjectExport
{
    public $handler;
    public $criteria;
    public $fields;
    public $format;
    public $filename;
    public $filepath;
    public $options;
    public $outputMethods = false;
    public $notDisplayFields;

    /**
     * Constructor
     *
     * @param SmartPersistableObjectHandler $objectHandler SmartObjectHandler handling the data we want to export
     * @param CriteriaElement               $criteria      containing the criteria of the query fetching the objects to be exported
     * @param array|bool                    $fields        fields to be exported. If FALSE then all fields will be exported
     * @param bool|string                   $filename      name of the file to be created
     * @param bool|string                   $filepath      path where the file will be saved
     * @param string                        $format        format of the ouputed export. Currently only supports CSV
     * @param array|bool                    $options       options of the format to be exported in
     */
    public function __construct(
        SmartPersistableObjectHandler $objectHandler,
        CriteriaElement $criteria = null,
        $fields = false,
        $filename = false,
        $filepath = false,
        $format = 'csv',
        $options = false
    ) {
        $this->handler          = $objectHandler;
        $this->criteria         = $criteria;
        $this->fields           = $fields;
        $this->filename         = $filename;
        $this->format           = $format;
        $this->options          = $options;
        $this->notDisplayFields = false;
    }

    /**
     * Renders the export
     * @param $filename
     */
    public function render($filename)
    {
        $this->filename = $filename;

        $objects        = $this->handler->getObjects($this->criteria);
        $rows           = [];
        $columnsHeaders = [];
        $firstObject    = true;
        foreach ($objects as $object) {
            $row = [];
            foreach ($object->vars as $key => $var) {
                if ((!$this->fields || in_array($key, $this->fields)) && !in_array($key, $this->notDisplayFields)) {
                    if ($this->outputMethods && isset($this->outputMethods[$key])
                        && method_exists($object, $this->outputMethods[$key])) {
                        $method    = $this->outputMethods[$key];
                        $row[$key] = $object->$method();
                    } else {
                        $row[$key] = $object->getVar($key);
                    }
                    if ($firstObject) {
                        // then set the columnsHeaders array as well
                        $columnsHeaders[$key] = $var['form_caption'];
                    }
                }
            }
            $firstObject = false;
            $rows[]      = $row;
            unset($row);
        }
        $data                   = [];
        $data['rows']           = $rows;
        $data['columnsHeaders'] = $columnsHeaders;
        $smartExportRenderer    = new SmartExportRenderer($data, $this->filename, $this->filepath, $this->format, $this->options);
        $smartExportRenderer->execute();
    }

    /**
     * Set an array contaning the alternate methods to use instead of the default getVar()
     *
     * $outputMethods array example: 'uid' => 'getUserName'...
     * @param $outputMethods
     */
    public function setOuptutMethods($outputMethods)
    {
        $this->outputMethods = $outputMethods;
    }

    /*
     * Set an array of fields that we don't want in export
     */
    /**
     * @param $fields
     */
    public function setNotDisplayFields($fields)
    {
        if (!$this->notDisplayFields) {
            if (is_array($fields)) {
                $this->notDisplayFields = $fields;
            } else {
                $this->notDisplayFields = [$fields];
            }
        } else {
            if (is_array($fields)) {
                $this->notDisplayFields = array_merge($this->notDisplayFields, $fields);
            } else {
                $this->notDisplayFields[] = $fields;
            }
        }
    }
}

/**
 * SmartExportRenderer class
 *
 * Class that renders a set of data into a specific export format
 *
 * @package SmartObject
 * @author  marcan <marcan@smartfactory.ca>
 * @link    http://www.smartfactory.ca The SmartFactory
 */
class SmartExportRenderer
{
    public $data;
    public $format;
    public $filename;
    public $filepath;
    public $options;

    /**
     * Constructor
     *
     * @param array       $data     contains the data to be exported
     * @param bool|string $filename name of the file in which the exported data will be saved
     * @param bool|string $filepath path where the file will be saved
     * @param string      $format   format of the ouputed export. Currently only supports CSV
     * @param array       $options  options of the format to be exported in
     */
    public function __construct(
        $data,
        $filename = false,
        $filepath = false,
        $format = 'csv',
        $options = ['separator' => ';']
    ) {
        $this->data     = $data;
        $this->format   = $format;
        $this->filename = $filename;
        $this->filepath = $filepath;
        $this->options  = $options;
    }

    /**
     * @param         $dataArray
     * @param         $separator
     * @param  string $trim
     * @param  bool   $removeEmptyLines
     * @return string
     */
    public function arrayToCsvString($dataArray, $separator, $trim = 'both', $removeEmptyLines = true)
    {
        if (!is_array($dataArray) || empty($dataArray)) {
            return '';
        }
        switch ($trim) {
            case 'none':
                $trimFunction = false;
                break;
            case 'left':
                $trimFunction = 'ltrim';
                break;
            case 'right':
                $trimFunction = 'rtrim';
                break;
            default: //'both':
                $trimFunction = 'trim';
                break;
        }
        $ret = [];
        foreach ($dataArray as $key => $field) {
            $ret[$key] = $this->valToCsvHelper($field, $separator, $trimFunction);
        }

        return implode($separator, $ret);
    }

    /**
     * @param $val
     * @param $separator
     * @param $trimFunction
     * @return mixed|string
     */
    public function valToCsvHelper($val, $separator, $trimFunction)
    {
        if ($trimFunction) {
            $val = $trimFunction($val);
        }
        //If there is a separator (;) or a quote (") or a linebreak in the string, we need to quote it.
        $needQuote = false;
        do {
            if (false !== strpos($val, '"')) {
                $val       = str_replace('"', '""', $val);
                $needQuote = true;
                break;
            }
            if (false !== strpos($val, $separator)) {
                $needQuote = true;
                break;
            }
            if ((false !== strpos($val, "\n")) || (false !== strpos($val, "\r"))) { // \r is for mac
                $needQuote = true;
                break;
            }
        } while (false);
        if ($needQuote) {
            $val = '"' . $val . '"';
        }

        return $val;
    }

    public function execute()
    {
        $exportFileData = '';

        switch ($this->format) {
            case 'csv':
                $separator      = isset($this->options['separator']) ? $this->options['separator'] : ';';
                $firstRow       = implode($separator, $this->data['columnsHeaders']);
                $exportFileData .= $firstRow . "\r\n";

                foreach ($this->data['rows'] as $cols) {
                    $exportFileData .= $this->arrayToCsvString($cols, $separator) . "\r\n";
                }
                break;
        }
        $this->saveExportFile($exportFileData);
    }

    /**
     * @param $content
     */
    public function saveExportFile($content)
    {
        switch ($this->format) {
            case 'csv':
                $this->saveCsv($content);
                break;
        }
    }

    /**
     * @param $content
     */
    public function saveCsv($content)
    {
        if (!$this->filepath) {
            $this->filepath = XOOPS_UPLOAD_PATH . '/';
        }
        if (!$this->filename) {
            $this->filename .= time();
            $this->filename .= '.csv';
        }

        $fullFileName = $this->filepath . $this->filename;

        if (!$handle = fopen($fullFileName, 'a+')) {
            trigger_error('Unable to open ' . $fullFileName, E_USER_WARNING);
        } elseif (false === fwrite($handle, $content)) {
            trigger_error('Unable to write in ' . $fullFileName, E_USER_WARNING);
        } else {
            $mimeType  = 'text/csv';
            $file      = strrev($this->filename);
            $temp_name = strtolower(strrev(substr($file, 0, strpos($file, '--'))));
            if ('' === $temp_name) {
                $file_name = $this->filename;
            } else {
                $file_name = $temp_name;
            }
            $fullFileName = $this->filepath . stripslashes(trim($this->filename));

            if (ini_get('zlib.output_compression')) {
                ini_set('zlib.output_compression', 'Off');
            }

            header('Pragma: public');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Cache-Control: private', false);
            header('Content-Transfer-Encoding: binary');
            if (isset($mimeType)) {
                header('Content-Type: ' . $mimeType);
            }

            header('Content-Disposition: attachment; filename=' . $file_name);

            if (isset($mimeType) && false !== strpos($mimeType, 'text/')) {
                $fp = fopen($fullFileName, 'r');
            } else {
                $fp = fopen($fullFileName, 'rb');
            }
            fpassthru($fp);
            exit();
        }
        fclose($handle);
    }
}
