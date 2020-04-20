<?php
function awsdetailsFunc() {
    global $user;
    $unityid = $user['unityid'];
    
    $requests = getAwsRequests();
    $count = count($requests);
   
    if($count == 0)
    {
      print("No current reservations in AWS");
      return;
    } 
    $outerText = '';

    $outerText .= "<table>\n";
    $outerText .= "<tr>\n";
    $outerText .= "<th>Instance ID</th>\n";
    $outerText .= "<th>Public IP</th>\n";
    $outerText .= "<th>Private Key</th>\n";
    $outerText .= "<th>Login CMD</th>\n";

    for($i = 0, $noedit = 0, $text = '', $showcreateimage = 0, $cluster = 0, $col3 = 0;
		   $i < $count;
		   $i++, $noedit = 0, $text = '', $cluster = 0, $col3 = 0) {
            
            $text .= "<tr>\n";
            $text .= "<td>{$requests[$i]['instance_id']}</td>\n";
            $text .= "<td>{$requests[$i]['public_ip']}</td>\n";
            $key_name = $requests[$i]['key_name'];
            $text .= "<td> <a download='{$key_name}.pem' href=/vcl/cloud_bursting/user_keys/{$key_name}.pem>{$key_name}</a></td>\n";
            $cmd = "ssh -i \"".$key_name.".pem\" ";
            $text .= "<td>{$cmd} ";
            $text .= "ec2-user@{$requests[$i]['dns']}</td>\n";

            $text .= "<td>";
            $options = array(0 => "{$unityid}",
                    1 => "{$requests[$i]['instance_id']}");
            $text .= "<FORM action=\"" . BASEURL . SCRIPT . "\" method=post>\n";
            $cont = addContinuationsEntry("awsdeleteform", $options);
            $text .= "<INPUT type=hidden name=continuation value=\"$cont\">\n";
            $text .= "<INPUT type=submit value=Delete>\n";
            $text .= "</FORM>\n";
            $text .= "</td></tr>";
            $outerText .= $text;
    };
   
    $outerText .= "</table>\n";
    print $outerText;
}

function awsdeleteform() {
    $data = getContinuationVar();
    #print "We got user: {$data[0]}";
    #print "We got instance: {$data[1]}";
    
    shell_exec("bash /var/www/html/vcl-2.5.1/cloud_bursting/remove_aws_instance.sh {$data[0]} {$data[1]}");
    awsdetailsFunc();
 } 
?>
