<?php
error_reporting(E_ALL);
session_start();
// taken from https://www.php.net/manual/en/ref.network.php
function ipCIDRCheck ($IP, $CIDR) {
    list ($net, $mask) = explode ('/', $CIDR);
   
    $ip_net = ip2long ($net);
    $ip_mask = ~((1 << (32 - $mask)) - 1);

    $ip_ip = ip2long ($IP);

    return (($ip_ip & $ip_mask) == ($ip_net & $ip_mask));
}
?>
<html>
<head>
<style>
p {
  color: red;
  margin-left: 40px;
}
</style>
<title>SBS template test</title>
</head>
<body>
<img src='Shaw_Business_RGB.png' />
<?php
$data = json_decode(file_get_contents('lookup'));


// these are the names we want to replace

$fields = array('IP', 'NODENAME','CIRCUIT','VLAN','RATE');
// this is > 0 if data was POST'ed to the web server
if (count($_POST) > 0) {
        // read template
        $template1 = file_get_contents('template1');

        // replace all @NAME@ with the actual value from the template
        foreach ($fields as $field) {
                $template1 = preg_replace("/#$field#/", $_POST[$field], $template1);
        }

        // Did we get an IP? If so, replace all the options as well.
        if (!empty($_POST['IP'])) {
                foreach ($data as $key => $value) {
                        //if (strncmp($key,$_POST['IP'],strlen($key)) === 0) {
                        if (ipCIDRCheck($_POST['IP'], $key)) {
                                foreach($value as $k => $v) {
                                        $template1 = preg_replace("/#$k#/", $v, $template1);
                                }
                        }
                }
        }
}
?>
<form action="" method="POST">

<?php
// this builds the input values, and if we had a previous value put it there as well
foreach ($fields as $field) {
        $val = "";
        if (isset($_POST[$field])) {
        	$val = $_POST[$field];
			//saving nodename seperately
				if ($field == 'NODENAME') {
					$_SESSION["nodename"] = $_POST[$field];
				}
        }

        echo "$field: <input type=\"text\" name=\"$field\" value=\"$val\"><br />";
}
?>
<br />
<input type="submit" value="Make it so">
</form>
<br />
<pre>
<?php
// finally print the template
if (isset($template1)) {
		$_SESSION["data"] = $template1;
        echo $template1;
		//saving template temporarily
		
		?>

        <form action="btnclick1.php" method="get">
        	<p>Please check the configuration.</p>    
			<input type="submit" name="Save" value="Save">	
        </form>

<!-- If save file is selected -->
<?php } 
?>

</pre>
</body>
</html>
