<?php
function awsdetailsFunc() {
    $requests = getAwsRequests();
    print "awsdetails successfully called.<br>\n";
    
    $count = count($requests);
    // for ($i = 0, $i < $count; i++, $text = ''){

    // }

    $outerText = '';

    $outerText .= "<table>\n";
    $outerText .= "<tr>\n";
    $outerText .= "<th>Public IP</th>\n";
    $outerText .= "<th>Key Name</th>\n";
    $outerText .= "<th>User</th>\n";

    for($i = 0, $noedit = 0, $text = '', $showcreateimage = 0, $cluster = 0, $col3 = 0;
		   $i < $count;
		   $i++, $noedit = 0, $text = '', $cluster = 0, $col3 = 0) {
            
            $text .= "<tr>\n";
            $text .= "<td>{$requests[$i]['public_ip']}</td>\n";
            $text .= "<td>{$requests[$i]['key_name']}</td>\n";
            $text .= "<td>{$requests[$i]['user']}</td>\n";
            $text .= "</tr>\n";

            $outerText .= $text;
    };

    print $outerText;
    
}
?>
