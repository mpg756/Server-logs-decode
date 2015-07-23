<?php

class LogsDecode
{
    private $_fileName = '';
    private $_file;
    private $_outputFileName = '';
    private $_outputFile;

    public function __construct($inputFile, $outputFile)
    {
        $this->_fileName = $inputFile;
        $this->_outputFileName = $outputFile;
        $this->openOutputFile();

    }

    public function decode()
    {
        $i = 1;
        $this->_file = fopen($this->_fileName,'r');
        while(!feof($this->_file)){
            $buffer = fgets($this->_file);
            $pattern = '^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$';
            $res = preg_grep($pattern,$buffer);
            try{
                $this->save(implode(' ',[$res,$i++]));
            }
            catch(Exception $e){
                echo "Error occurred: " . $e->getMessage();
            }
        }
        fclose($this->_file);
        $this->closeOutputFile();
    }

    private function save($string)
    {
        $fl = fwrite($this->_outputFile,$string);
        if($fl) throw new Exception('Error while writing the file');
    }

    private function openOutputFile()
    {
        if(file_exists($this->_outputFileName)) {
            $this->_outputFile = fopen($this->_outputFileName,'a+');
        }
        else{
            $this->_outputFile = fopen($this->_outputFileName,'w+');
        }
    }


    private function closeOutputFile()
    {
        return fclose($this->_outputFile);
    }
}