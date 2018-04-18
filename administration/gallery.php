<?php
require_once("core/gallery.php");

class ClGalleryList {
    
    public function get_header() {
        return "";
    }
   
    public function get_content() {
        $ret = "  <table>\n";
        $gals=build_galeries();
        foreach ($gals as $gal){
            $ret .="    <tr>";
            $ret .="<td>".htmlentities($gal->name)."</td>";
            $ret .="<td><input type=\"text\" name=\"$gal->name\"/ value=\"\"></td>";
            $ret .="</tr>\n";
        }
        $ret .= "  </tabme>\n";
        return $ret;
        
    }
}

class ClGallery {
    
    public function get_header() {
        $ret .= "	<!-- jQuery and jQuery UI (REQUIRED) -->\n";
        $ret .= '	<link rel="stylesheet" type="text/css" media="screen" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/themes/smoothness/jquery-ui.css">'."\n";
        $ret .= '	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>'."\n";
        $ret .= '	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.min.js"></script>'."\n";

        $ret .= "	<!-- elFinder CSS (REQUIRED) -->\n";
        $ret .= '	<link rel="stylesheet" type="text/css" media="screen" href="elFinder-2.1.37/css/elfinder.min.css">'."\n";
        $ret .= '	<link rel="stylesheet" type="text/css" media="screen" href="elFinder-2.1.37/css/theme.css">'."\n";

        $ret .= "	<!-- elFinder JS (REQUIRED) -->\n";
        $ret .= '	<script type="text/javascript" src="elFinder-2.1.37/js/elfinder.min.js"></script>'."\n";

        $ret .= "	<!-- elFinder translation (OPTIONAL) -->\n";
        $ret .= '	<script type="text/javascript" src="elFinder-2.1.37/js/i18n/elfinder.fr.js"></script>'."\n";

        $ret .= "	<!-- elFinder initialization (REQUIRED) -->\n";
        $ret .= '	<script type="text/javascript" charset="utf-8">'."\n";
        $ret .= "		$().ready(function() {\n";
        $ret .= "			var elf = $('#elfinder').elfinder({\n";
        $ret .= "				url : '../elFinder-2.1.37/php/connector.php',  // connector URL (REQUIRED)\n";
        $ret .= "				lang: 'fr',             // language (OPTIONAL)\n";
        $ret .= "                onlyMimes: [\"image\"]\n";
        $ret .= "			}).elfinder('instance');\n";
        $ret .= "		});\n";
        $ret .= "	</script>\n";
        return "";
		/*$ret .= '<script data-main="./main.default.js" src="//cdnjs.cloudflare.com/ajax/libs/require.js/2.3.5/require.min.js"></script>'."\n";
		$ret .= '<script>'."\n";
		$ret .= "	define('elFinderConfig', {\n";
		$ret .= '		// elFinder options (REQUIRED)'."\n";
		$ret .= '		// Documentation for client options:'."\n";
		$ret .= '		// https://github.com/Studio-42/elFinder/wiki/Client-configuration-options'."\n";
		$ret .= '		defaultOpts : {'."\n";
		$ret .= "			url : 'php/connector.minimal.php' // connector URL (REQUIRED)\n";
		$ret .= '			,commandsOptions : {'."\n";
		$ret .= '				edit : {'."\n";
		$ret .= '					extraOptions : {'."\n";
		$ret .= '						// set API key to enable Creative Cloud image editor'."\n";
		$ret .= '						// see https://console.adobe.io/'."\n";
		$ret .= "						creativeCloudApiKey : '',\n";
		$ret .= '						// browsing manager URL for CKEditor, TinyMCE'."\n";
		$ret .= '						// uses self location with the empty value'."\n";
		$ret .= "						managerUrl : ''\n";
		$ret .= '					}'."\n";
		$ret .= '				}'."\n";
		$ret .= '				,quicklook : {'."\n";
		$ret .= '					// to enable preview with Google Docs Viewer'."\n";
		$ret .= "					googleDocsMimes : ['application/pdf', 'image/tiff', 'application/vnd.ms-office', 'application/msword', 'application/vnd.ms-word', 'application/vnd.ms-excel', 'application/vnd.ms-powerpoint', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']\n";
		$ret .= '				}'."\n";
		$ret .= '			}'."\n";
		$ret .= '			// bootCalback calls at before elFinder boot up '."\n";
		$ret .= '			,bootCallback : function(fm, extraObj) {'."\n";
		$ret .= "				fm.bind('init', function() {\n";
		$ret .= '					// any your code'."\n";
		$ret .= '				});'."\n";
		$ret .= '				// for example set document.title dynamically.'."\n";
		$ret .= '				var title = document.title;'."\n";
		$ret .= "				fm.bind('open', function() {\n";
		$ret .= '					var path = '','."\n";
		$ret .= '						cwd  = fm.cwd();'."\n";
		$ret .= '					if (cwd) {'."\n";
		$ret .= '						path = fm.path(cwd.hash) || null;'."\n";
		$ret .= '					}'."\n";
		$ret .= "					document.title = path? path + ':' + title : title;\n";
		$ret .= "				}).bind('destroy', function() {\n";
		$ret .= '					document.title = title;'."\n";
		$ret .= '				});'."\n";
		$ret .= '			}'."\n";
		$ret .= '		},'."\n";
		$ret .= '		managers : {'."\n";
		$ret .= "			// 'DOM Element ID': { }\n";
		$ret .= "			'elfinder': {}\n";
		$ret .= '		}'."\n";
		$ret .= '	});'."\n";
		$ret .= '</script>'."\n";		*/
		
    }
   
    public function get_content() {
        $ret .="    <div id=\"elfinder\"></div>\n";
        $ret .="    <div><a href=\"admin\">Retour</a></div>\n";
        return $ret;
        
    }
}
