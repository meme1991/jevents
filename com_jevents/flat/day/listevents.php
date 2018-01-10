<?php defined('_JEXEC') or die('Restricted access'); ?>

<div class="wrapper jevent-dayview">
	<div class="container">
		<div class="row">

			<div class="col-12">
				<?php //$this->_header(); ?>
			</div>
			<div class="col-3 sidebar-alt">
				<?php $this->_showNavTableBar(); ?>
			</div>
			<div class="col-9">
				<?php echo $this->loadTemplate("body"); ?>
			</div>
			<div class="col-12">
				<?php $this->_viewNavAdminPanel(); ?>
				<?php $this->_footer(); ?>
			</div>
		</div>
	</div>
</div>
