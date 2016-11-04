<?php md_hook_before_footer(); ?>

<?php if ( md_has_footer() ) : ?>

	<footer class="footer<?php echo md_footer_classes(); ?>" itemscope itemtype="http://schema.org/WPFooter">
		<div class="inner">

			<?php md_hook_footer(); ?>

		</div>
	</footer>

<?php endif; ?>

<?php md_hook_after_footer(); ?>

<?php wp_footer(); ?>
<?php md_hook_js(); ?>

</body>
</html>