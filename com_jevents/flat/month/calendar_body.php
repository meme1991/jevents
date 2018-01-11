<?php
defined('_JEXEC') or die('Restricted access');

use Joomla\String\StringHelper;

$cfg	 = JEVConfig::getInstance();

if ($cfg->get("tooltiptype",'joomla')=='overlib'){
	JEVHelper::loadOverlib();
}

$view =  $this->getViewName();
echo $this->loadTemplate('cell' );
$eventCellClass = "EventCalendarCell_".$view;

// previous and following month names and links
$followingMonth = $this->datamodel->getFollowingMonth($this->data);
$precedingMonth = $this->datamodel->getPrecedingMonth($this->data);
?>
	<div class="btn-group w-100 my-2" role="group" aria-label="Month nav">
	  <a href="<?php echo $precedingMonth['link'] ?>" role="button" title="<?php echo $precedingMonth['name'] ?>" class="btn btn-primary w-25 no-shadow"><i class="fas fa-chevron-circle-left mr-1"></i><span class="d-none d-md-inline-block"><?php echo $precedingMonth['name'] ?></span></a>
	  <div class="w-50 d-inline-block text-center" style="padding:6px; border:1px #007bff solid;"><?php echo $this->data['fieldsetText']; ?></div>
	  <a href="<?php echo $followingMonth['link'] ?>" role="button" title="<?php echo $followingMonth['name'] ?>" class="btn btn-primary w-25 no-shadow"><span class="d-none d-md-inline-block"><?php echo $followingMonth['name'] ?></span><i class="fas fa-chevron-circle-right ml-1"></i></a>
	</div>

  <table border="0" cellpadding="0" class="cal_top_day_names table">
		<tr valign="top" style="border-bottom: 2px #007bff solid;">
			<?php foreach ($this->data["daynames"] as $dayname): ?>
				<?php $cleaned_day = strip_tags($dayname, ''); ?>
				<td class="cal_daysnames">
					<span class="<?php echo strtolower($cleaned_day); ?>">
						<?php echo JString::substr($cleaned_day, 0, 3);?>
					</span>
				</td>
			<?php endforeach; ?>
		</tr>
  </table>

	<table border="0" cellpadding="0" class="cal_table table">
    <?php $datacount = count($this->data["dates"]); ?>
    <?php $dn=0; ?>
    <?php for ($w=0;$w<6 && $dn<$datacount;$w++): ?>
		<tr class="cal_cell_rows">
      <?php
      for ($d=0;$d<7 && $dn<$datacount;$d++){
      	$currentDay = $this->data["dates"][$dn];
      	switch ($currentDay["monthType"]){
      		case "prior":
      		case "following":
        		?>
            <td width="14%" class="cal_daysoutofmonth" valign="top">
              <?php echo $currentDay["d"]; ?>
            </td>
          	<?php
          	break;
      		case "current":
      			$cellclass = $currentDay["today"]?'class="cal_today"':(count($currentDay["events"])>0?'class="cal_dayshasevents"':'class="cal_daysnoevents"');
	        ?>
          <td <?php echo $cellclass;?>>
           <?php   $this->_datecellAddEvent($this->year, $this->month, $currentDay["d"]);?>
          	<a class="cal_daylink" href="<?php echo $currentDay["link"]; ?>" title="<?php echo JText::_('JEV_CLICK_TOSWITCH_DAY'); ?>"><?php echo $currentDay['d']; ?></a>
              <?php

              if (count($currentDay["events"])>0){
              	foreach ($currentDay["events"] as $key=>$val){
              		if( $currentDay['countDisplay'] < $cfg->get('com_calMaxDisplay',5)) {
              			echo '<div class="event_div_1">';
              		} else {
              			// float small icons left
              			echo '<div class="event_div_2">';
              		}
              		echo "\n";
              		$ecc = new $eventCellClass($val,$this->datamodel, $this);
              		echo $ecc->calendarCell($currentDay,$this->year,$this->month,$key);
              		echo '</div>' . "\n";
              		$currentDay['countDisplay']++;
              	}
              }
              echo "</td>\n";
              break;
      	}
      	$dn++;
      }
    echo "</tr>\n";
  endfor;
  echo "</table>\n";
  $this->eventsLegend();
