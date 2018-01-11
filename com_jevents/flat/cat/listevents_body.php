<?php
defined('_JEXEC') or die('Restricted access');

use Joomla\String\StringHelper;

$cfg = JEVConfig::getInstance();

$data = $this->datamodel->getCatData($this->catids, $cfg->get('com_showrepeats', 0), $this->limit, $this->limitstart);
$this->data = $data;

$Itemid = JEVHelper::getItemid();
?>
<div id='jev_maincal' class='jev_listview category'>
  <div class="jev_listrow">
    <div class="jev_daysnames category">
      <?php $this->viewNavCatText($this->catids, JEV_COM_COMPONENT, 'cat.listevents', $this->Itemid); ?>
      <?php $hasevents = false; ?>
      <h6><?php echo $data ['catname']; ?></h6>
    </div>
    <?php if (JString::strlen($data['catdesc']) > 0) : ?>
      <div class='jev_catdesc'><?php echo $data ['catdesc'] ?></div>
    <?php endif; ?>
  </div><!-- .jev_listrow -->

  <?php $num_events = count($data ['rows']); ?>
  <?php $chdate = ""; ?>

  <?php if ($num_events > 0) : ?>
    <?php $hasevents = true; ?>
    <?php for ($r = 0; $r < $num_events; $r ++) : ?>
      <?php $row = $data ['rows'] [$r]; ?>

      <?php $event_day_month_year = $row->dup() . $row->mup() . $row->yup(); ?>

      <?php if (($event_day_month_year != $chdate) && $chdate != '') : ?>
        </ul></div>
      <?php endif; ?>

      <?php if ($event_day_month_year != $chdate) : ?>
        <?php $date = JEventsHTML::getDateFormat($row->yup(), $row->mup(), $row->dup(), 1); ?>
        <div class="jev_listrow"><ul class="ev_ul list-group list-small">
      <?php endif; ?>

      <?php $listyle = 'style="border-color:' . $row->bgcolor() . ';"'; ?>
      <li class="ev_td_li list-group-item" <?php echo $listyle ?>>
        <?php $this->loadedFromTemplate('icalevent.list_row', $row, 0); ?>
      </li>

      <?php $chdate = $event_day_month_year; ?>
    <?php endfor; ?>
    </ul></div>
  <?php endif; ?>
</div>
<?php if (!$hasevents) : ?>
  <?php if (count($this->catids) == 0 || $data ['catname'] == "") : ?>
    <?php echo JLayoutHelper::render('joomla.content.message.message_info', JText::_('JEV_EVENT_CHOOSE_CATEG')); ?>
  <?php else : ?>
    <?php echo JLayoutHelper::render('joomla.content.message.message_info', JText::_('JEV_NO_EVENTFOR').' <strong>'.$data ['catname'].'</strong>'); ?>
  <?php endif; ?>
<?php endif; ?>

<?php if ($data ["total"] > $data ["limit"]) : ?>
  <?php $this->paginationForm($data ["total"], $data ["limitstart"], $data ["limit"]); ?>
<?php endif; ?>
