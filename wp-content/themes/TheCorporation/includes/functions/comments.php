<?php function mytheme_comment($comment, $args, $depth) {
   $GLOBALS['comment'] = $comment; ?>
   <li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
	  <div class="comment-top-left"></div>
	  <div class="comment-top-main"></div>
	  <div class="bubble"></div>
	  <div class="comment-top-right"></div>
   <div id="comment-<?php comment_ID(); ?>" class="comment-body">
      <div class="comment-author vcard">
         <?php echo get_avatar($comment,$size='50'); ?>
		 <div class="comment-info">		
            <?php printf(__('<cite class="fn">%s</cite> <span class="says">says:</span>','TheCorporation'), get_comment_author_link()) ?>
			<div class="comment-meta commentmetadata"><a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>"><?php printf(__('%1$s at %2$s','TheCorporation'), get_comment_date(),  get_comment_time()) ?></a><?php edit_comment_link(__('(Edit)','TheCorporation'),'  ','') ?></div>
		 </div> <!-- end comment-info-->  
      </div>
      <?php if ($comment->comment_approved == '0') : ?>
         <em class="moderation"><?php _e('Your comment is awaiting moderation.') ?></em>
         <br />
      <?php endif; ?>
		
	  <div class="comment-content"><?php comment_text() ?></div> <!-- end comment-content-->
	  <div class="reply-container"><?php comment_reply_link(array_merge( $args, array('reply_text' => __('reply','TheCorporation'),'depth' => $depth, 'max_depth' => $args['max_depth']))) ?></div>
	</div> <!-- end comment-body-->
	<div class="comment-bottom-left"></div><div class="comment-bottom-right"></div>	
<?php } ?>
<?php function list_pings($comment, $args, $depth) {
       $GLOBALS['comment'] = $comment;
?>
	<li id="comment-<?php comment_ID(); ?>"><?php comment_author_link(); ?> - <?php comment_excerpt(); ?> 
<?php } ?>
<?php //modify the comment counts to only reflect the number of comments minus pings
if( phpversion() >= '4.4' ) add_filter('get_comments_number', 'comment_count', 0);

function comment_count( $count ) {
		if ( ! is_admin() ) {
			global $id;
			$get_comments = get_comments('post_id=' . $id);
			$comments_by_type = &separate_comments($get_comments);
			return count($comments_by_type['comment']);	
		} else {
            return $count;
        }
}
?>