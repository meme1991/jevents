<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

$cfg = JEVConfig::getInstance ();

$this->data = $data = $this->datamodel->getDayData ( $this->year, $this->month, $this->day );
$this->Redirectdetail ();

$cfg = JEVConfig::getInstance ();
$Itemid = JEVHelper::getItemid ();

// previous and following month names and links
$followingDay = $this->datamodel->getFollowingDay ( $this->year, $this->month, $this->day );
$precedingDay = $this->datamodel->getPrecedingDay ( $this->year, $this->month, $this->day );

?>

<div class="btn-group w-100 my-2 jev_toprow" role="group" aria-label="Month nav">
	<?php if ($precedingDay): ?>
		<a href="<?php echo $precedingDay ?>" role="button" title="<?php echo JText::_("PRECEEDING_Day") ?>" class="btn btn-primary w-25 no-shadow"><i class="fas fa-chevron-circle-left mr-1"></i><span class="d-none d-md-inline-block"><?php echo JText::_("PRECEEDING_Day") ?></span></a>
	<?php endif; ?>
	<div class="w-50 d-inline-block text-center" style="padding:6px; border:1px #007bff solid;"><?php echo JEventsHTML::getDateFormat( $this->year, $this->month, $this->day, 0) ;?></div>
	<?php if ($followingDay): ?>
		<a href="<?php echo $followingDay ?>" role="button" title="<?php echo JText::_("FOLLOWING_Day") ?>" class="btn btn-primary w-25 no-shadow"><span class="d-none d-md-inline-block"><?php echo JText::_("FOLLOWING_Day") ?></span><i class="fas fa-chevron-circle-right ml-1"></i></a>
	<?php endif; ?>
</div>

<div id="jev_maincal" class="jev_listview">
	<div class="jev_listrow">
		<?php $hasevents = false; ?>
		<?php if (count ( $data ['hours'] ['timeless'] ['events'] ) > 0) : ?>
			<?php $hasevents = true; ?>
			<?php $start_time = JText::_ ( 'TIMELESS' ); ?>

			<ul class="ev_ul list-group list-small">
			<?php foreach ( $data['hours']['timeless']['events'] as $row ) : ?>
				<?php $listyle = 'style="border-color:' . $row->bgcolor () . ';"'; ?>
				<li class="ev_td_li list-group-item" <?php echo $listyle ?>>
					<?php $this->loadedFromTemplate('icalevent.list_row', $row, 0); ?>
				</li>
			<?php endforeach; ?>
			</ul>
		<?php endif; ?>

		<?php for($h = 0; $h < 24; $h ++) : ?>
			<?php if (count ( $data['hours'][$h]['events'] ) > 0) : ?>
				<?php $hasevents = true; ?>
				<?php $start_time = JEVHelper::getTime ( $data ['hours'] [$h] ['hour_start'] ); ?>

				<ul class="ev_ul list-group list-small">
				<?php foreach ( $data['hours'][$h]['events'] as $row ) : ?>
					<?php $listyle = 'style="border-color:' . $row->bgcolor () . ';"'; ?>
					<li class="ev_td_li list-group-item" <?php echo $listyle ?>>
						<?php $this->loadedFromTemplate('icalevent.list_row', $row, 0); ?>
					</li>
				<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		<?php endfor; ?>

	</div>
</div>

<?php if (! $hasevents) : ?>
  <?php echo JLayoutHelper::render('joomla.content.message.message_info', JText::_('JEV_NO_EVENTS_FOUND')); ?>
<?php endif; ?>
