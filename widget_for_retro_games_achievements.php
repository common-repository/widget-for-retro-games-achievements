<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
/*
Plugin Name: Widget for Retro Games Achievements
Description: This enables a widget to display achievements from retroachievements.org (RA). To get started : 1) Create an Account on <a href="http://www.retroachievements.org">retroachievements.org</a> 2) Obtain an API-Key  3) Configure the Widget to match with your Retroachievements Username (case sensitive!) and API-Key. The number of recent entries is limited to 50. Customisable through your WP Theme's Custom CSS.
Author: Yannick Hinger
Version: 1.1
Author URI: http://www.hiscorebob.lu
*/
/* Start Adding Functions Below this Line */
// Creating the widget 
class rtrgmsach_widget extends WP_Widget {
function __construct() {
parent::__construct(
// Base ID of your widget
'rtrgmsach_widget', 
// Widget name will appear in UI
__('Retro Games Achievements', 'rtrgmsach_widget_domain'), 
// Widget description
array( 'description' => __( 'Widget to display Retro Games Achievements', 'rtrgmsach_widget_domain' ), ) 
);
}
// Creating widget front-end
// This is where the action happens
// Some part of the code is based on work by Daniel Lemes 2013 - www.memoriabit.com.br
public function widget( $args, $instance ) {
$title = apply_filters( 'widget_title', $instance['title'] );
$ra_user = $instance['ra-user'] ;
$ra_apikey =  $instance['ra-api-key'] ;
$howmanygames =  $instance['how-many-games'] ;
// before and after widget arguments are defined by themes
echo $args['before_widget'];
if ( ! empty( $title ) )
echo $args['before_title'] . $title . $args['after_title'];
// This is where you run the code and display the output
//function time elapsed
function rtrgmsach_time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);
    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;
    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}	
//initialise Retroachievements  API...
require_once( 'RA_API.php' ); 
$RAConn = new RetroAchievements($ra_user, $ra_apikey);
// set RA profile URL
$ra_user_url = 'http://retroachievements.org/user/'.$ra_user;
// capitalise username
$ra_user_cap =  ucfirst ($ra_user );
// initialise array for user summary
$ra_usersummary = $RAConn->GetUserSummary( $ra_user, 1 );
// retrieve and display status from array
$ra_status = $ra_usersummary->Status;
if ($ra_status == 'Online') {
	$ra_status = 'online';
}
else if ($ra_status == 'Offline') {
	$ra_status = 'offline';
} 
// retrieve and display LastLogin from array
$ra_lastlogin = $ra_usersummary->LastLogin;	
echo '<table border="0" id ="table-retrogamesachievements"><tbody><tr><td colspan="2"><h4>';
echo $ra_user_cap . '\'s RetroAchievements</h4>';
echo '<img src="http://retroachievements.org/UserPic/' .$ra_user .'.png" width="60" align="left">&nbsp;<a href="'. $ra_user_url .'" target="_blank" title="Visit '. $ra_user .' on RetroAchievements.org"><font align="middle">'. $ra_usersummary->LastActivity->User .'</font></a> ('. $ra_status .')';
// retrieve and display Rank
echo '<br> <span>&nbsp;Rank:</span> <span>' .$ra_usersummary->Rank .'</span><span><br>&nbsp;Last online: ' .rtrgmsach_time_elapsed_string($ra_lastlogin); 
echo '</span><br clear="all"></td></tr>';
// retrieve and display Score
echo '<tr><td colspan="2">Score: <span>'.$ra_usersummary->Points .' </span>(since: '. (strftime('%d/%m/%Y',strtotime($ra_usersummary->MemberSince))) .')</td></tr>';
echo '<tr><td colspan="2"> '.$howmanygames. ' last played games:</td></tr>';
//retrieve and display Last Played
$ra_recentlyplayed = $RAConn->GetUserRecentlyPlayedGames( $ra_user, $howmanygames );
// loop each game
foreach ($ra_recentlyplayed as $recentlyplayed){
	echo '<tr id="tr-retrogamesachievements">
<td colspan="2">
	<div class="content-retrogamesachievements">
		<div class="pic-retrogamesachievements">
			<img src="http://www.retroachievements.org'.$recentlyplayed->ImageIcon .'"/>
		</div>
	<div class="gametext-retrogamesachievements">
		<a href="http://www.retroachievements.org/Game/' .$recentlyplayed->GameID .'" target="_blank" title="View more Information on ' .$recentlyplayed->Title .' on RetroAchievements.org">
			<p class="h2-hsb">'.$recentlyplayed->Title  .'</p>
		</a>
	';
	// This is code initially created by Daniel Lemes 2013 - www.memoriabit.com.br	
	// Get values to calculate percent complete	
	$total_achievements = $recentlyplayed->NumPossibleAchievements;
	$achieved = $recentlyplayed->NumAchieved;
	$value = $recentlyplayed->NumAchieved;
	$max = $recentlyplayed->NumPossibleAchievements;
	$scale = 1;
	// some math to get percent complete
	!empty($max) ? $percent = ($value * 100) / $max : $percent = 0;
	$percent > 100 ?  $percent = 100 : $percent = $percent;
	$thepct = round($percent * $scale);
	echo '<p>Achieved: '. $thepct .'% ('. $value .' of '. $max .')</p>
</div><br clear="all">';
	echo '</td></tr>';
}
//Closing Table must be outside the Foreach loop	
echo '</table>';
//Let Wordpress handle the rest
echo $args['after_widget'];
}
// Widget Backend 
public function form( $instance ) {
if ( isset( $instance[ 'title' ] ) ) {
$title = $instance[ 'title' ];
}
else {
$title = __( 'New title', 'rtrgmsach_widget_domain' );
}
if ( isset( $instance[ 'ra-user' ] ) ) {
$rauser = $instance[ 'ra-user' ];
}
else {
$rauser = __( 'RA User', 'rtrgmsach_widget_domain' );
}
if ( isset( $instance[ 'ra-api-key' ] ) ) {
$raapikey = $instance[ 'ra-api-key' ];
}
else {
$raapikey = __( 'Your API KEY here', 'rtrgmsach_widget_domain' );
}
if ( isset( $instance[ 'how-many-games' ] ) ) {
$howmanygames = $instance[ 'how-many-games' ];
}
else {
$howmanygames = __( '5', 'rtrgmsach_widget_domain' );
}
// Widget admin form
?>
<p>
<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
</p>
<p>
<label for="<?php echo $this->get_field_id( 'ra-user' ); ?>"><?php _e( 'RA User' ); ?></label> 
<input class="widefat" id="<?php echo $this->get_field_id( 'ra-user' ); ?>" name="<?php echo $this->get_field_name( 'ra-user' ); ?>" type="text" value="<?php echo esc_attr( $rauser ); ?>"/>
</p>
<label for="<?php echo $this->get_field_id( 'ra-api-key' ); ?>"><?php _e( 'RA API Key' ); ?></label> 
<input class="widefat" id="<?php echo $this->get_field_id( 'ra-api-key' ); ?>" name="<?php echo $this->get_field_name( 'ra-api-key' ); ?>" type="text" value="<?php echo esc_attr( $raapikey ); ?>"/>
</p>
</p>
<label for="<?php echo $this->get_field_id( 'how-many-games' ); ?>"><?php _e( 'Number of recent entries to display:' ); ?></label> 
<input class="widefat" id="<?php echo $this->get_field_id( 'how-many-games' ); ?>" name="<?php echo $this->get_field_name( 'how-many-games' ); ?>" type="text" value="<?php echo esc_attr( $howmanygames ); ?>"/>
</p>
<?php 
}
// Updating widget replacing old instances with new
public function update( $new_instance, $old_instance ) {
$instance = array();
$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
$instance['ra-user'] = ( ! empty( $new_instance['ra-user'] ) ) ? strip_tags( $new_instance['ra-user'] ) : '';
$instance['ra-api-key'] = ( ! empty( $new_instance['ra-api-key'] ) ) ? strip_tags( $new_instance['ra-api-key'] ) : '';
$instance['how-many-games'] = ( ! empty( $new_instance['how-many-games'] ) ) ? strip_tags( $new_instance['how-many-games'] ) : '';
return $instance;
}
} // Class rtrgmsach_widget ends here
// Register and load the widget
function rtrgmsach_load_widget() {
	register_widget( 'rtrgmsach_widget' );
}
add_action( 'widgets_init', 'rtrgmsach_load_widget' );
/* Stop Adding Functions Below this Line */
?>
