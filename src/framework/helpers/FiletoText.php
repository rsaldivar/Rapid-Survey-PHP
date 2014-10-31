<?php
/**
 * File to enable reading text or HTML from a file 
 * 
 *
 */
		ini_set('include_path', FRAMEWORK.'/helpers/documentreader');
class FiletoText
{
    private $path, $extension; 

    function __construct($filepath, $fileextension) {
        $this->path = $filepath; // absolute path to the file do whatever conversions you need to do here 
        $this->extension = $fileextension; 
    }
    
    /**
     * Return the text or HTML from the file 
     * 
     * @return String 
     */
    function getText() {

        switch ($this->extension) {
            case "doc"; 
            case "docx";
            case "rtf"; 
			require_once("MailMerge.php");
                $mailMerge = new Zend_Service_LiveDocx_MailMerge();
                $mailMerge->setUsername('websree')->setPassword('livedocxtest987'); // credetnials 
                $mailMerge->setLocalTemplate($this->path);
                $mailMerge->assign(null);  // must be called as of phpLiveDocx 1.2
                $mailMerge->createDocument();
                return $mailMerge->retrieveDocument('html');
                break;
                
				
           case "csv";
			$row = 1;
			$str = "";
			if (($handle = fopen($this->path, "r")) !== FALSE) {
				while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
					$num = count($data);

					$row++;
					for ($c=0; $c < $num; $c++) {
						//echo $data[$c] . "<br />\n";
						$str .= $data[$c]." ";
					}
				}
				}
				fclose($handle);
               return $str;
               break;
			   
			   
           case "pdf";
				require_once("class.pdf2text.php");
               $a = new PDF2Text(); 
               $a->setFilename($this->path); //grab the test file at http://www.newyorklivearts.org/Videographer_RFP.pdf
               $a->decodePDF();
               return $a->output();
               break;
               
           case "xls"; 
           case "xlsx"; 
               $html_writer = new PHPExcel_Writer_HTML(PHPExcel_IOFactory::load($this->path)); 
               
               $tmp_file_name = time().".htm"; 
               $html_writer->save($tmp_file_name); 
               return file_get_contents($tmp_file_name); 
               
            default: 
                return ""; 
        }
    }
}