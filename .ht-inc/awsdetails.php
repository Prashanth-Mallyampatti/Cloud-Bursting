<?php
function awsdetailsFunc() {
    global $user;
    $unityid = $user['unityid'];
    
    $requests = getAwsRequests();
    $count = count($requests);
    
    $outerText = '';

    $outerText .= "<table>\n";
    $outerText .= "<tr>\n";
    $outerText .= "<th>Instance ID</th>\n";
    $outerText .= "<th>Public ID</th>\n";
    $outerText .= "<th>Private Key</th>\n";
    $outerText .= "<th>DNS</th>\n";
    $outerText .= "<th>Login CMD</th>\n";

    for($i = 0, $noedit = 0, $text = '', $showcreateimage = 0, $cluster = 0, $col3 = 0;
		   $i < $count;
		   $i++, $noedit = 0, $text = '', $cluster = 0, $col3 = 0) {
            
            $text .= "<tr>\n";
            $text .= "<td>{$requests[$i]['instance_id']}</td>\n";
            $text .= "<td>{$requests[$i]['public_ip']}</td>\n";
            // optional: pop up
            // print "<a href=\"" . BASEURL . SCRIPT . "?mode=selectauth&clearselection=1\">";
            $key_name = $requests[$i]['key_name'];
            $text .= "<td> <a href=/{$key_name} target=\"_blank\">{$key_name}</a></td>\n";
            // $text .= "<td>{$requests[$i]['dns']}</td>\n";
            $text .= "<td>dns should be here</td>\n";
            
            $cmd = "ssh -i 'file.pem' {$unityid}@{$requests[$i]['public_ip']}";
            $text .= "<td>{$cmd}</td>\n";

            $text .= "</tr>\n";

            $outerText .= $text;
    };

    $outerText .= "</table>\n";

    print $outerText;
    
}
?>
