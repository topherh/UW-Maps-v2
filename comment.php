<?php /**
 * Comments Form
 *
 * Form to submit UW Maps comments to uweb@uw.edu
 *
 * @author cheiland
 *
 */

$sResponse = json_decode(stripslashes($_POST['data']), true);

// check the parametersk
if ($sResponse)
{
    // Execute some mail function
    $email = $sResponse['email'];
    $body = $sResponse['message'];

    mail( "uweb@uw.edu", "UW Map Comment",
    $body, "From: $email" );

    // Execute response 
    $sBody = 'Your feedback was received: '.$body;

    mail( $email, "UW Map Comment Received",
    $sBody, "From: $email" );

    echo true;
}
else
{
    echo false;
}
?>
