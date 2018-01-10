<?php
defined('_JEXEC') or die('Restricted access');

$cfg	 = JEVConfig::getInstance();

// Note that using a $limit value of -1 the limit is ignored in the query
$this->data = $data = $this->datamodel->getYearData($this->year,$this->limit, $this->limitstart);

// previous and following month names and links
$followingYear = $this->getFollowingYear($this->year, $this->month, $this->day);
$precedingYear = $this->getPrecedingYear($this->year, $this->month, $this->day);

?>
<div class="btn-group w-100 my-2 jev_toprow" role="group" aria-label="Month nav">
	<?php if ($precedingYear): ?>
		<a href="<?php echo $precedingYear ?>" role="button" title="<?php echo JText::_("PRECEEDING_Year") ?>" class="btn btn-primary w-25 no-shadow"><i class="fas fa-chevron-circle-left mr-1"></i><?php echo JText::_("PRECEEDING_Year") ?></a>
	<?php endif; ?>
	<div class="w-50 d-inline-block text-center" style="padding:6px; border:1px #007bff solid;"><?php echo $data["year"] ?></div>
	<?php if ($followingYear): ?>
		<a href="<?php echo $followingYear ?>" role="button" title="<?php echo JText::_("FOLLOWING_Year") ?>" class="btn btn-primary w-25 no-shadow"><?php echo JText::_("FOLLOWING_Year") ?><i class="fas fa-chevron-circle-right ml-1"></i></a>
	<?php endif; ?>
</div>

<div id='jev_maincal' class="row jev_listview">
	<?php $hasevents = false; ?>
	<?php for($month = 1; $month <= 12; $month++) : ?>
		<?php $num_events = count($data["months"][$month]["rows"]); ?>
		<?php if ($num_events>0) : ?>
			<?php $hasevents = true; ?>


		<?php endif; ?>
	<?php endfor; ?>

</div>



    <div id='jev_maincal' class='jev_listview'>





<?php
$hasevents = false;
for($month = 1; $month <= 12; $month++) {
	$num_events = count($data["months"][$month]["rows"]);
	if ($num_events>0){
$hasevents = true;
		?>
		<div class="jev_daysnames">
	    <?php echo JEventsHTML::getDateFormat($this->year,$month,'',3);?>
		</div>
		<div class="jev_listrow">
		<?php
		echo "<ul class='ev_ul'>\n";
		for ($r = 0; $r < $num_events; $r++) {
			if (!isset($data["months"][$month]["rows"][$r])) continue;
			$row =& $data["months"][$month]["rows"][$r];
			$listyle = 'style="border-color:'.$row->bgcolor().';"';

			echo "<li class='ev_td_li' $listyle>\n";
			$this->loadedFromTemplate('icalevent.list_row', $row, 0);
			echo "</li>\n";
		}
		echo "</ul>\n";
		echo '</div>';
	}

}
if (! $hasevents) {
	echo '<div class="list_no_e">' . "\n";
	echo JText::_ ( 'JEV_NO_EVENTS_FOUND' );
	echo "</div>\n";
}
?>
<div class="jev_clear" ></div>
</div>
<?php
$this->paginationForm($data["total"], $data["limitstart"], $data["limit"]);
