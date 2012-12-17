<?php
/**
 * The main template file
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file 
 *
 * Please see /external/starkers-utilities.php for info on Starkers_Utilities::get_template_parts()
 *
 * @package 	WordPress
 * @subpackage 	Starkers
 * @since 		Starkers 4.0
 */
?>
<?php Starkers_Utilities::get_template_parts( array( '_/parts/shared/html-header', '_/parts/shared/header' ) ); ?>

<?php if ( have_posts() ): ?>
<ol>
<?php while ( have_posts() ) : the_post(); ?>
	<li>
		<article class="review">
			<?php $verdict = get_field('verdict'); ?>
			<?php $verdict_class = 'is-' . strtolower( str_replace( ' ', '', $verdict ) ); ?>
			<?php $attachment_id = get_field('thumbnail'); ?>
			<?php $size = "thumbnail"; ?>
			<?php	$thumbnail = wp_get_attachment_image( $attachment_id, $size ); ?>
			<div class="row fightthepower">
				<picture class="thumbnail">
					<?php echo( $thumbnail ); ?>
				</picture>
				<span class="album"><p><?php the_field('album'); ?></p></span>
			</div>
			<div class="row">
				<span class="verdict <?php echo $verdict_class; ?>"><?php echo $verdict; ?></span>
				<span class="artist"><p><?php the_field('artist'); ?></p></span>
			</div>
		</article>
	</li>
<?php endwhile; ?>
</ol>
<?php else: ?>
<h2>No reviews to display</h2>
<?php endif; ?>

<?php Starkers_Utilities::get_template_parts( array( '_/parts/shared/footer','_/parts/shared/html-footer') ); ?>
