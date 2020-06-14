<!--btnclick1.php-->
<pre>
<?php
session_start();
 $dir = 'myDir';

 // create new directory with 744 permissions if it does not exist yet
 // owner will be the user/group the PHP script is run under
 if ( !file_exists($dir) ) {
     mkdir ($dir, 0744);
 }

 file_put_contents ($dir.'/test.txt', 'Hello File');

if(isset($_GET['Save'])) {
        SaveFile();
    }
        function SaveFile() { 
            echo "This is Savefile that is saved \n";
			echo $_SESSION["data"];
			$myfilename = "/home/pkp/Desktop/php/".$_SESSION["nodename"].".txt";
			$myfile = fopen($myfilename, "w") or die("Unable to open file!");
			fwrite($myfile, $_SESSION["data"]);
			fclose($myfile);
        }
?>
</pre>
