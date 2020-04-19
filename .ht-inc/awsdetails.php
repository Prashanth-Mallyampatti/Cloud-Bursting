<?php
function awsdetailsFunc() {
    $requests = getAwsRequests();
    print "awsdetails successfully called.<br>\n";
    
    $count = count($requests);
    // for ($i = 0, $i < $count; i++, $text = ''){

    // }
    $outerText = '';
    for($i = 0, $noedit = 0, $text = '', $showcreateimage = 0, $cluster = 0, $col3 = 0;
		   $i < $count;
		   $i++, $noedit = 0, $text = '', $cluster = 0, $col3 = 0) {
            
            $text .= "{$requests[$i]['public_ip']}<br>\n";
            $text .= "{$requests[$i]['key_name']}<br>\n";
            $text .= "{$requests[$i]['user']}<br>\n";

            $outerText .= $text;
    };

    print $outerText;
    
}
?>
