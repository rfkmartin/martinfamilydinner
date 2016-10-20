<?php
function print_header()
{
	echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">';
	echo '<html><head>';
	echo '<title>Martin Family Dinner</title>';
	echo '<link href="/martinfamilydinner/style.css" rel="stylesheet" type="text/css">';
	echo '<script src="js/jquery.js" type="text/javascript"></script>';
	echo '<script src="js/moment.min.js" type="text/javascript"></script>';
	echo '<script src="js/combodate.js" type="text/javascript"></script>';	
	echo '</head>';
}
function print_footer()
{
	echo '</html>';
}
function set_timezone()
{
	date_default_timezone_set("America/Chicago");
}
?>