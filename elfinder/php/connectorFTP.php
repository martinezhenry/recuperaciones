<?php

error_reporting(0); // Set E_ALL for debuging

include_once dirname(__FILE__).DIRECTORY_SEPARATOR.'elFinderConnector.class.php';
include_once dirname(__FILE__).DIRECTORY_SEPARATOR.'elFinder.class.php';
include_once dirname(__FILE__).DIRECTORY_SEPARATOR.'elFinderVolumeDriver.class.php';
include_once dirname(__FILE__).DIRECTORY_SEPARATOR.'elFinderVolumeLocalFileSystem.class.php';
// Required for MySQL storage connector
// include_once dirname(__FILE__).DIRECTORY_SEPARATOR.'elFinderVolumeMySQL.class.php';
// Required for FTP connector support
 include_once dirname(__FILE__).DIRECTORY_SEPARATOR.'elFinderVolumeFTP.class.php';


/**
 * Simple function to demonstrate how to control file access using "accessControl" callback.
 * This method will disable accessing files/folders starting from  '.' (dot)
 *
 * @param  string  $attr  attribute name (read|write|locked|hidden)
 * @param  string  $path  file path relative to volume root directory started with directory separator
 * @return bool|null
 **/
/*
function access($attr, $path, $data, $volume) {
	return strpos(basename($path), '.') === 0       // if file/folder begins with '.' (dot)
		? !($attr == 'read' || $attr == 'write')    // set read+write to false, other (locked+hidden) set to true
		:  null;                                    // else elFinder decide it itself
}

$opts = array(
	// 'debug' => true,
	'roots' => array(
		array(
			'driver'        => 'LocalFileSystem',   // driver for accessing file system (REQUIRED)
			'path'          => '../files/',         // path to files (REQUIRED)
			'URL'           => dirname($_SERVER['PHP_SELF']) . '/../files/', // URL to files (REQUIRED)
			'accessControl' => 'access'             // disable and hide dot starting files (OPTIONAL)
		)
	)
);


function access2($attr, $path, $data, $volume) {
	return strpos(basename($path), '.') === 0       // if file/folder begins with '.' (dot)
		? !($attr == 'read' || $attr == 'write')    // set read+write to false, other (locked+hidden) set to true
		:  null;                                    // else elFinder decide it itself
}

$opts = array(
	// 'debug' => true,
	'roots' => array(
		array(
			'driver'        => 'LocalFileSystem',   // driver for accessing file system (REQUIRED)
			'path'          => '../files/',         // path to files (REQUIRED)
			'URL'           => dirname($_SERVER['PHP_SELF']) . '/../files/', // URL to files (REQUIRED)
			'accessControl' => 'access'             // disable and hide dot starting files (OPTIONAL)
		)
	)
);*/
function fichero($carpeta){
        
function access($attr, $path, $data, $volume) {
	return strpos(basename($path), '.') === 0       // if file/folder begins with '.' (dot)
		? !($attr == 'read' || $attr == 'write')    // set read+write to false, other (locked+hidden) set to true
		:  null;                                    // else elFinder decide it itself
}

/*
$opts = array(
'locale' => 'en_US.UTF-8',
'bind' => array(
// '*' => 'logger',
'mkdir mkfile rename duplicate upload rm paste' => 'logger'
),
'debug' => true,
'roots' => array(
array(
'driver' => 'LocalFileSystem',
'path' => '../files/',
'startPath' => '../files/',
'URL' => dirname($_SERVER['PHP_SELF']) . '/../files/',
// 'treeDeep' => 3,
// 'alias' => 'File system',
'mimeDetect' => 'internal',
'tmbPath' => '.tmb',
'utf8fix' => true,
'tmbCrop' => false,
'tmbBgColor' => 'transparent',
'accessControl' => 'access',
'acceptedName' => '/^[^\.].*$/',
// 'disabled' => array('extract', 'archive'),
// 'tmbSize' => 128,
'attributes' => array(
array(
'pattern' => '/\.js$/',
'read' => true,
'write' => false
),
array(
'pattern' => '/^\/icons$/',
'read' => true,
'write' => false
)
)
// 'uploadDeny' => array('application', 'text/xml')
),
    
    
             array(
            'driver' => 'FTP',
            'host' => '192.20.15.157',
            'user' => 'proyecto',
            'pass' => 'proyecto',
            'path' => '/',
            'tmpPath' => '../files/ftp',
            ),
    
        )
             );

*/

/*
$opts = array(
    'roots'  => array(
        array(
            'driver' => 'LocalFileSystem',
            'path'   => '../files',
            'URL'    => 'http://localhost/to/files/'
        ),
      
 /*  array(
    'driver'        => 'FTP',
    'host'          => '192.20.15.157',
    'user'          => 'proyecto',
    'pass'          => 'proyecto',
    'port'          => 21,
    'mode'          => 'passive',
    'path'          => '/',
    'timeout'       => 10,
    'owner'         => true,
    'tmbPath'       => '',
    'tmpPath'       => '',
    'dirMode'       => 0755,
    'fileMode'      => 0644
)*/
  /*  )
);*/

           
$opts = array(
	// 'debug' => true,
	'roots' => array(
		array(
			'driver'        => 'LocalFileSystem',   // driver for accessing file system (REQUIRED)
			'path'          => '../',         // path to files (REQUIRED)
                        //'URL'           => $carpeta, // URL to files (REQUIRED)
		//	'URL'           => dirname($_SERVER['PHP_SELF']) . '/../files/'.$carpeta, // URL to files (REQUIRED)
			'accessControl' => 'access',             // disable and hide dot starting files (OPTIONAL)
                        'attributes'    => array(
			array(
				'pattern' => '/./', //You can also set permissions for file types by adding, for example, .jpg inside pattern.
				'read'    => true,
				'write'   => true,
				'locked'  => false,
                                'hidden' => false
			)
		)
		)
	));
   
            
         
/* *

	array(
'driver' => 'FTP',
'host' => 'work.std42.ru',
'user' => 'dio',
'pass' => 'wallrus',
'path' => '/',
'tmpPath' => '../files/ftp',
),

 
 */


// run elFinder
$connector = new elFinderConnector(new elFinder($opts));
$connector->run();

}
$carpeta = $_GET['carpeta'];

fichero($carpeta);




