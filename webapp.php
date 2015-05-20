<?php
/**
 * Template Name: webapp 
 *
 * @package EvoLve
 * @subpackage Template
 */
 
 get_header(); 
 $evolve_recaptcha_public = evolve_get_option('evl_recaptcha_public','');
 $evolve_recaptcha_private = evolve_get_option('evl_recaptcha_private','');
 $evolve_email_address = evolve_get_option('evl_email_address','');
 $evolve_sent_email_header = evolve_get_option('evl_sent_email_header',get_bloginfo('name')); 


//If the form is submitted
if(isset($_POST['submit'])) {
	$emailSent = '';
	//Check to make sure that the name field is not empty
	if(trim($_POST['contact_name']) == '' || trim($_POST['contact_name']) == 'Name (required)') {
		$hasError = true;
	} else {
		$name = trim($_POST['contact_name']);
	}

	//Subject field is not required
	if(function_exists('stripslashes')) {
		$subject = stripslashes(trim($_POST['url']));
	} else {
		$subject = trim($_POST['url']);
	}

	//Check to make sure sure that a valid email address is submitted
	$pattern = '/^(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){255,})(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){65,}@)(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22))(?:\\.(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-+[a-z0-9]+)*\\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-+[a-z0-9]+)*)|(?:\\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\\]))$/iD';

	if(trim($_POST['email']) == '' || trim($_POST['email']) == 'Email (required)')  {
		$hasError = true;
	} else if ( preg_match($pattern, $_POST['email']) === 0 ) {
		$hasError = true;
	} else {
		$email = trim($_POST['email']);
	}

	//Check to make sure comments were entered
	if(trim($_POST['msg']) == '' || trim($_POST['msg']) == 'Message') {
		$hasError = true;
	} else {
		if(function_exists('stripslashes')) {
			$comments = stripslashes(trim($_POST['msg']));
		} else {
			$comments = trim($_POST['msg']);
		}
	}

	// reCaptch v2
	if( $evolve_recaptcha_public && $evolve_recaptcha_private ) {
	
		$response = $_POST['g-recaptcha-response'];
		
		if( !empty( $response ) ) {
			$reCaptcha 	= new evolve_GoogleRecaptcha();
			$verified	= $reCaptcha->VerifyCaptcha($response);
			
			if( !$verified ) {
				$hasError = true;
			}
		} else {
			$hasError = true;
		}
	}

	//If there is no error, send the email
	if(!isset($hasError)) {
		$name = wp_filter_kses( $name );
		$email = wp_filter_kses( $email );
		$subject = wp_filter_kses( $subject );
		$comments = wp_filter_kses( $comments );  

		if(function_exists('stripslashes')) {
			$subject = stripslashes($subject);
			$comments = stripslashes($comments);
		}		
  
		$emailTo = $evolve_email_address; //Put your own email address here
		$emailFrom = $evolve_sent_email_header;    
	
		$body = __('Name:', 'evolve')." $name \n\n";
		$body .= __('Email:', 'evolve')." $email \n\n";
		$body .= __('Subject:', 'evolve')." $subject \n\n";
		$body .= __('Comments:', 'evolve')."\n $comments";
	
		$headers = 'Reply-To: ' . $name . ' <' . $email . '>' . "\r\n";
    
		if($evolve_sent_email_header) {
			$headers .= 'From: '. $emailFrom . ' <' . $email . '>' . "\r\n";
		} else {
			$headers .= 'From: '. $emailTo . ' <' . $email . '>' . "\r\n";
		}  
		
		$mail = wp_mail($emailTo, $subject, $body, $headers);
		$emailSent = true;
	}

	if($emailSent == true) {
		$_POST['contact_name'] = '';
		$_POST['email'] = '';
		$_POST['url'] = '';
		$_POST['msg'] = '';
	}    
}
?>
<!--BEGIN #primary .hfeed-->
<div id="primary" class="hfeed full-width contact-page">

    <?php if ( have_posts() ) : ?>
			<?php while ( have_posts() ) : the_post(); ?>

			<!--BEGIN .hentry-->
			<div id="post-<?php the_ID(); ?>" class="<?php semantic_entries(); ?>"> 
				<h1 class="entry-title"><?php if ( get_the_title() ){ the_title(); }else{ } ?></h1>  

					<?php if ( current_user_can( 'edit_post', $post->ID ) ): ?>
       
						<?php edit_post_link( __( 'EDIT', 'evolve' ), '<span class="edit-page">', '</span>' ); ?>
            
				
                    <?php endif; ?>

					<!--BEGIN .entry-content .article-->
					<div class="entry-content article">
				<?php the_content(); ?>
<div id="webAppDiv" style="background-color:#cb8;">webAppDiv</div>		<script type="text/javascript">
            <?php global $current_user;
      get_currentuserinfo();

      echo 'var userObject={"userLogin":"' . $current_user->user_login . '",';
      echo ' "userEmail":"' . $current_user->user_email . '",';
      echo ' "userFirstName":"' . $current_user->user_firstname . '",';
      echo ' "userLastName":"' . $current_user->user_lastname . '",';
      echo ' "userDisplayName":"' . $current_user->display_name . '",';
      echo ' "userId":"' . $current_user->ID . '"};';
?>
 var userString=JSON.stringify(userObject);
 var temp=userString.split(",");
 var userString=temp.join(",<br />");
document.getElementById("webAppDiv").innerHTML="userObject="+userString;
</script>
            			<?php if(isset($hasError)) { //If errors are found ?>
							<div class="alert alert-danger"><i class="fa fa-ban"></i> <?php echo __("Please check if you've filled all the fields with valid information. Thank you.", 'evolve'); ?></div>
							<br />
						<?php } ?>

						<?php if(isset($emailSent) && $emailSent == true) { //If email is sent ?>
						<div class="alert alert-success"><i class="fa fa-check"></i> <?php echo __('Thank you', 'evolve'); ?> <strong><?php echo $name;?></strong> <?php echo __('for using my contact form! Your email was successfully sent!', 'evolve'); ?></div></div>
						<br />
					<?php } ?>
			</div>
            
            
            
            
            
		    <!--END .entry-content .article-->
            <div class="clearfix"></div> 

			<!-- Auto Discovery Trackbacks
			<?php trackback_rdf(); ?>
			-->
</div><!--END .hentry-->

			<?php endwhile; endif; ?> 

</div><!--END #primary .hfeed-->
<?php get_footer(); ?>