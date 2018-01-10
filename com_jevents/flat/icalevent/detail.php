<?php defined('_JEXEC') or die('Restricted access'); ?>

<div class="wrapper jevent-detailview">
	<div class="container">
		<div class="row">
			<div class="col-12">
				<?php ob_start(); ?>
				<?php echo $this->loadTemplate("body"); ?>
				<?php $body = ob_get_clean(); ?>

				<?php $this->_header(); ?>
				<?php //  don't show navbar stuff for events detail popup ?>
				<?php if( !$this->pop ): ?>
					<?php $this->_showNavTableBar(); ?>
				<?php endif; ?>
				<?php echo $body; ?>
				<?php if( !$this->pop ): ?>
					<?php $this->_viewNavAdminPanel(); ?>
				<?php endif; ?>

				<?php $this->_footer(); ?>
			</div>
		</div>
	</div>
</div>
