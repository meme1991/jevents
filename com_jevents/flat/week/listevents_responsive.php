<?php
defined('_JEXEC') or die('Restricted access');

$cfg = JEVConfig::getInstance();

$cfg = JEVConfig::getInstance();
$option = JEV_COM_COMPONENT;
$Itemid = JEVHelper::getItemid();

$compname = JEV_COM_COMPONENT;
$viewname = $this->getViewName();
$viewpath = JURI::root() . "components/$compname/views/" . $viewname . "/assets";
$viewimages = $viewpath . "/images";

$view = $this->getViewName();

$this->data = $data = $this->datamodel->getWeekData($this->year, $this->month, $this->day);

// previous and following month names and links
$followingWeek = $this->datamodel->getFollowingWeek($this->year, $this->month, $this->day);
$precedingWeek = $this->datamodel->getPrecedingWeek($this->year, $this->month, $this->day);
?>

<div class="btn-group w-100 my-2 jev_toprow" role="group" aria-label="Month nav">
	<?php if ($precedingWeek): ?>
		<a href="<?php echo $precedingWeek ?>" role="button" title="<?php echo JText::_("PRECEEDING_Week") ?>" class="btn btn-primary w-25 no-shadow"><i class="fas fa-chevron-circle-left mr-1"></i><?php echo JText::_("PRECEEDING_Week") ?></a>
	<?php endif; ?>
	<div class="w-50 d-inline-block text-center" style="padding:6px; border:1px #007bff solid;">
    <?php $week_start = $data ['days'] ['0']; ?>
    <?php $week_end = $data ['days'] ['6']; ?>
    <?php $starttime = JevDate::mktime(0, 0, 0, $week_start ['week_month'], $week_start ['week_day'], $week_start ['week_year']); ?>
    <?php $endtime = JevDate::mktime(0, 0, 0, $week_end ['week_month'], $week_end ['week_day'], $week_end ['week_year']); ?>
    <?php if ($week_start ['week_month'] == $week_end ['week_month']) { ?>
      <?php $startformat = "%d"; ?>
      <?php $endformat = "%d %B, %Y"; ?>
    <?php } else if ($week_start ['week_year'] == $week_end ['week_year']) { ?>
        <?php $startformat = "%d %B"; ?>
        <?php $endformat = "%d %B, %Y"; ?>
    <?php } else { ?>
        <?php $startformat = "%d. %B  %Y"; ?>
        <?php $endformat = "%d. %B %Y"; ?>
    <?php } ?>
    <?php echo JEV_CommonFunctions::jev_strftime($startformat, $starttime) . ' - ' . JEV_CommonFunctions::jev_strftime($endformat, $endtime); ?>
  </div>
	<?php if ($followingWeek): ?>
		<a href="<?php echo $followingWeek ?>" role="button" title="<?php echo JText::_("FOLLOWING_Week") ?>" class="btn btn-primary w-25 no-shadow"><?php echo JText::_("FOLLOWING_Week") ?><i class="fas fa-chevron-circle-right ml-1"></i></a>
	<?php endif; ?>
</div>

<div id='jev_maincal' class="row jev_listview">
  <?php $hasevents = false; ?>
  <?php for ($d = 0; $d < 7; $d ++) : ?>
    <?php $num_events = count($data['days'][$d]['rows']); ?>
    <?php if ($num_events == 0) : ?>
      <?php continue; ?>
    <?php endif; ?>

      <a class="ev_link_weekday" href=" <?php echo $data['days'][$d]['link'] ?>" title="<?php echo JText::_('JEV_CLICK_TOSWITCH_DAY') ?>">
        <div class="jev_daysnames">
          <?php echo JEventsHTML::getDateFormat($data ['days'] [$d] ['week_year'], $data ['days'] [$d] ['week_month'], $data ['days'] [$d] ['week_day'], 2); ?>
        </div>
      </a>

      <div class="jev_listrow">
        <?php if ($num_events > 0) : ?>
            <?php $hasevents = true; ?>
            <ul class='ev_ul'>
            <?php for ($r = 0; $r < $num_events; $r ++) : ?>
              <?php $row = $data ['days'] [$d] ['rows'] [$r]; ?>
              <?php $listyle = 'style="border-color:' . $row->bgcolor() . ';"'; ?>
              <li class='ev_td_li' <?php echo $listyle ?>>
                <?php $this->loadedFromTemplate('icalevent.list_row', $row, 0); ?>
              </li>
            <?php endfor; ?>
            </ul>
        <?php endif; ?>
      </div>
  <?php endfor; ?>
</div>
<?php if (! $hasevents) : ?>
  <?php echo JLayoutHelper::render('joomla.content.message.message_info', JText::_('JEV_NO_EVENTS_FOUND')); ?>
<?php endif; ?>
