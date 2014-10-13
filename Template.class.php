<?php
include_once('Functions.inc.php');
class Template
{
	private $templateDir = PATH_TEMPLATES;
	private $imageDir = PATH_IMAGES;
	
	
	private function directions()
	{

		$this->templateDir = $this->templateDir;
		$this->imageDir = '/'.$this->imageDir;
	}

    /**
     * Der linke Delimter f�r einen Standard-Platzhalter.
     *
     * @access    private
     * @var       string
     */
    private $leftDelimiter = '{$';

    /**
     * Der rechte Delimter f�r einen Standard-Platzhalter.
     *
     * @access    private
     * @var       string
     */
    private $rightDelimiter = '}';

    /**
     * Der linke Delimter f�r eine Funktion.
     *
     * @access    private
     * @var       string
     */
    private $leftDelimiterF = '{';

    /**
     * Der rechte Delimter f�r eine Funktion.
     *
     * @access    private
     * @var       string
     */
    private $rightDelimiterF = '}';

    /**
     * Der linke Delimter f�r ein Kommentar.
     * Sonderzeichen m�ssen escapt werden, weil der Delimter in einem regul�rem
     * Ausdruck verwendet wird.
     *
     * @access    private
     * @var       string
     */
    private $leftDelimiterC = '\{\*';

    /**
     * Der rechte Delimter f�r ein Kommentar.
     * Sonderzeichen m�ssen escapt werden, weil der Delimter in einem regul�rem
     * Ausdruck verwendet wird.
     *
     * @access    private
     * @var       string
     */
    private $rightDelimiterC = '\*\}';

    /**
     * Der linke Delimter f�r eine Sprachvariable
     * Sonderzeichen m�ssen escapt werden, weil der Delimter in einem regul�rem
     * Ausdruck verwendet wird.
     *
     * @access    private
     * @var       string
     */
    private $leftDelimiterL = '\{L_';

    /**
     * Der rechte Delimter f�r eine Sprachvariable
     * Sonderzeichen m�ssen escapt werden, weil der Delimter in einem regul�rem
     * Ausdruck verwendet wird.
     *
     * @access    private
     * @var       string
     */
    private $rightDelimiterL = '\}';

    /**
     * Der komplette Pfad der Templatedatei.
     *
     * @access    private
     * @var       string
     */
    private $templateFile = "";

    /**
     * Der komplette Pfad der Sprachdatei.
     *
     * @access    private
     * @var       string
     */
    private $languageFile = "";
    /**
     * Der komplette Pfad der Bilder
     *
     * @access    private
     * @var       string
     */
    private $imageFile = "";

    /**
     * Der Dateiname der Templatedatei.
     *
     * @access    private
     * @var       string
     */
    private $templateName = "";

    /**
     * Der Inhalt des Templates.
     *
     * @access    private
     * @var       string
     */
    private $template = "";


    /**
     * Die Pfade festlegen.
     *
     * @access    public
     */
    public function __construct($tpl_dir = "", $lang_dir = "",$img_dir = "") {
        // Template Ordner
        if ( !empty($tpl_dir) ) {
            $this->templateDir = $tpl_dir;
        }

        // Sprachdatei Ordner
        if ( !empty($lang_dir) ) {
            $this->languageDir = $lang_dir;
        }
		// Bilder Ordner
        if ( !empty($img_dir) ) {
            $this->imageDir = $img_dir;
        }
    }

    /**
     * Eine Templatedatei �ffnen.
     *
     * @access    public
     * @param     string $file Dateiname des Templates.
     * @uses      $templateName
     * @uses      $templateDir
     * @uses      parseFunctions()
     * @return    boolean
     */
    public function	LoadTemplate($file)    {
        // Eigenschaften zuweisen
        $this->templateName = $file;
        $this->templateFile = $this->templateName;
        // Wenn ein Dateiname �bergeben wurde, versuchen, die Datei zu �ffnen
        if(!empty($this->templateFile)) {
            if(file_exists($this->templateFile)) {
                $this->template = file_get_contents($this->templateFile);
            } else {
                return false;
            }
        } else {
           return false;
        }

        // Funktionen parsen
        $this->parseFunctions();
    }

    /**
     * Einen Standard-Platzhalter ersetzen.
     *
     * @access    public
     * @param     string $replace     Name des Platzhalters.
     * @param     string $replacement Der Text, mit dem der Platzhalter ersetzt
     *                                werden soll.
     * @uses      $leftDelimiter
     * @uses      $rightDelimiter
     * @uses      $template
     */
    public function Assign($replace, $replacement) {
        $this->template = str_replace( $this->leftDelimiter .$replace.$this->rightDelimiter,
                                       $replacement, $this->template );
    }

    /**
     * Die Sprachdateien �ffnen und Sprachvariablem im Template ersetzen.
     *
     * @access    public
     * @param     array $files Dateinamen der Sprachdateien.
     * @uses      $languageFiles
     * @uses      $languageDir
     * @uses      replaceLangVars()
     * @return    array
     */
    public function LoadLanguage() {
    	$lang="";
    	$query = mysql_query("SELECT * FROM languagecodes WHERE language='".CURRENT_LANGUAGE."'");
    	while ($row = mysql_fetch_array($query)) {
    		$lang[$row['code']] = html_entity_decode($row['text']);
    		$this->Assign($row['code'],$row['text']);
    	}
        $this->replaceLangVars($lang);
        return $lang;
    }
	
	 /**
     * Die Bildlisten �ffnen und Variablem im Template ersetzen.
     *
     * @access    public
     * @param     array $files Dateinamen der Sprachdateien.
     * @uses      $languageFiles
     * @uses      $languageDir
     * @uses      replaceLangVars()
     * @return    array
     */
    public function LoadImages($files) {
    	
        $this->imageFiles = $files;
        //$this->imageDir = realpath($this->imageDir);

        for( $i = 0; $i < count( $this->imageFiles ); $i++ ) {
            if (!file_exists( $this->imageDir .$this->imageFiles[$i])) {
                return false;
            } else {
				include_once($this->imageDir .$this->imageFiles[$i]);
            }
        }
        $img = $this->imageFiles;
        $this->replaceImgVars($img);
        
        foreach($img as $sprachcode => $value)
        {
        	$this->Assign($sprachcode,$img[$sprachcode]);
        }

        return $img;
    }

    /**
     * Sprachvariablen im Template ersetzen.
     *
     * @access    private
     * @param     string $lang Die Sprachvariablen.
     * @uses      $template
     */
    private function replaceLangVars($lang) {
    	try{
    		$this->template = preg_replace("/\\{L_(.*)\\}/isUe", "\$lang[strtolower('\\1')]", $this->template);
    	}
    	catch(Exception $e)
    	{
    		return false;
    	}
    }
	/**
     * Bildvariablen im Template ersetzen.
     *
     * @access    private
     * @param     string $img Die Variablen.
     * @uses      $template
     */
    private function replaceImgVars($img) {
    	try{
			$this->template = preg_replace("/\\{IMG_(.*)}/isUe", "\$img[strtolower('\\1')]", $this->template);
    	}
    	catch(Exception $e)
    	{
    	return false;
    	}
    }

    /**
     * Includes parsen und Kommentare aus dem Template entfernen.
     *
     * @access    private
     * @uses      $leftDelimiterF
     * @uses      $rightDelimiterF
     * @uses      $template
     * @uses      $leftDelimiterC
     * @uses      $rightDelimiterC
     */
    private function parseFunctions() {
	$this->directions();
        // Includes ersetzen ( {include file="..."} )
        while( preg_match( "/" .$this->leftDelimiterF ."include file=\"(.*)\\.(.*)\"".$this->rightDelimiterF ."/isUe", $this->template))
        {
            $this->template = preg_replace("/" .$this->leftDelimiterF ."include file=\"(.*)\\.(.*)\""
                                            .$this->rightDelimiterF."/isUe",
                                            "file_get_contents(\$this->templateDir.'\\1'.'.'.'\\2')",
            								$this->template );
        }
		
        $comment_pattern = array('#/\*.*?\*/#s', '#(?<!:)//.*#');
        $this->template = preg_replace($comment_pattern, NULL, $this->template);
    }

    /**
     * Das "fertige Template" ausgeben.
     *
     * @access    public
     * @uses      $template
     */
    public function display() {
        echo $this->template;
    }
	
	/**
	* Baut die Navigation aus der Datenbank auf und gibt sie als HTML-String aus
	*
	* @return    string
	*/
	public function BuildNavigation($admin,$current_page,$lang)
	{	
		if(gettype($admin) != "string")$admin = '0';

		$query = mysql_query("SELECT id, IFNULL( parent, 0 ) AS parent, prio, text, href
							FROM navigation
							WHERE admin =".$admin."
							ORDER BY parent ASC , prio ASC 
							LIMIT 0 , 30");
		$nodes = array();
		$html = "";
		
		while ($row = mysql_fetch_assoc($query)) {
		  $nodes[$row['parent']][$row['id']] = $row['text'];
		}
		mysql_free_result($query);
		$html .= '<div id="navi"><ul id="navigation">';
		foreach ($nodes[0] as $id => $text) {
			try
			{
				$text = preg_replace("/\\{L_(.*)\\}/isUe", "\$lang[strtolower('\\1')]", $text);
			}
			catch(Exception $error){ }
			$html .= '<li><a href="index.php?p='.$id.'">'.$text.'</a>';
			if (isset($nodes[$id])) {
			$html .= "<ul>\n";
			foreach ($nodes[$id] as $cId => $cText) {
				$cText = preg_replace("/\\{L_(.*)\\}/isUe", "\$lang[strtolower('\\1')]", $cText);
				$html .= '<li><a href="index.php?p='.$cId.'">' .$cText. '</a></li>';
			}
			$html .= "</ul>\n";
			}
			$html .= "</li>\n";
		}
		$html .= '</ul></div>';
		
		return $html;
	}
	
	/*
	* Sucht Alle Css Dateien und gibt sie als HTML-String aus.
	*
	* @return    array
	*/
	public function SearchForCss($template_folder)
	{
		$dir = 'templates/'.$template_folder.'css/';
		$string = "";
		if (is_dir($dir)) {
			if ($dh = opendir($dir)) {
				while (($file = readdir($dh)) !== false) {
					if(filetype($dir . $file) == "file")
					$string.='<link rel="stylesheet" type="text/css" href="'.$dir.$file.'">'."\n";
				}
				closedir($dh);
			}
		}
		return $string;
	}
	
	/*
	* Holt sich aus der Datenbank den entsprechenden Content
	*
	* @return String
	*/
	public function GenerateContent($get)
	{
	$string = "";
		if($get == null)
		{
			$string = "<h3>Es ist ein Fehler aufgetreten!</h3>";
		}
		else
		{
			$result = mysql_query("SELECT * FROM pages WHERE id=".$get."");
			if(mysql_num_rows($result)==0)
			{
				$string = "<h3>Diese Seite existiert nicht</h3>";
			}
			else
			{
				while($row = mysql_fetch_array($result))
				{
					$string .= $row['content'];
				}
			}
		}
		return $string;
	}
}
?>