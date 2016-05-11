<?php
function readCSV($csvFile)
{
	$file_handle = fopen($csvFile, 'r');
   $first=1;
	while (!feof($file_handle) )
   {
		$line_of_text = fgetcsv($file_handle, 1024);
      if ($first)
      {
         $foo="INSERT INTO ADDRESS (";
         $num=count($line_of_text);
         for ($i=0;$i<$num-1;$i++)
         {
            $foo.=$line_of_text[$i].",";
         }
         $foo.=$line_of_text[$i];
         $foo.=")";
         $first=0;
      }
      else
      {
         print $foo;
         print ' VALUES (';
         $num=count($line_of_text);
         for ($i=0;$i<$num-1;$i++)
         {
            $new=str_replace('"','\"',$line_of_text[$i]);
            print $new.',';
         }
         print $line_of_text[$i];
         print ");\n";
      }
	}
	fclose($file_handle);
	return $line_of_text;
}


// Set path to CSV file
$csvFile = 'address.csv';

$csv = readCSV($csvFile);
?>