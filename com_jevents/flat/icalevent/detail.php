<?php defined('_JEXEC') or die('Restricted access'); ?>

<div class="wrapper jevent-detailview">
	<div class="container">
		<div class="row">

			<div class="col-12">
				<?php //$this->_header(); ?>
			</div>
			<div class="col-3 sidebar-alt">
				<?php $this->_showNavTableBar(); ?>
			</div>
			<div class="col-9" id="jevents_body">
				<?php ob_start(); ?>
				<?php echo $this->loadTemplate("body"); ?>
				<?php $body = ob_get_clean(); ?>
				<?php echo $body; ?>
			</div>
			<div class="col-12">
				<?php if( !$this->pop ): ?>
					<?php $this->_viewNavAdminPanel(); ?>
				<?php endif; ?>
				<?php $this->_footer(); ?>
			</div>
		</div>
	</div>
</div>
