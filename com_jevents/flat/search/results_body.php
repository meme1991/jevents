<?php
defined('_JEXEC') or die('Restricted access');

$params = JComponentHelper::getParams(JEV_COM_COMPONENT);
$useRegX = intval($params->get("regexsearch",0));
$this->data = $data = $this->datamodel->getKeywordData($this->keyword, $this->limit, $this->limitstart, $useRegX);

$Itemid = JEVHelper::getItemid();
$searchisValid =true;
$chdate	= '';
?>
<div id="jev_maincal" class="jev_listview">
	<div class="jev_toprow jev_toprowcat">
		<div class="jev_header jev_headercat">
			<h2><?php echo JText::_("JEV_SEARCHRESULTS");?></h2>
		</div>
	</div>

	<div class="jev_listrow">
	   <div class='jev_catdesc'><?php echo $this->keyword  ;?></div>
	</div>

	<?php if( $data['num_events'] > 0 ) : ?>
		<?php for( $r = 0; $r < $data['num_events']; $r++ ) : ?>
			<?php $row = $data['rows'][$r]; ?>

			<?php $event_day_month_year = $row->dup().$row->mup().$row->yup(); ?>

			<?php if(( $event_day_month_year <> $chdate ) && $chdate <> '' ) : ?>
				</ul></div>
			<?php endif; ?>

			<?php if( $event_day_month_year <> $chdate ): ?>
				<?php $date =JEventsHTML::getDateFormat( $row->yup(), $row->mup(), $row->dup(), 1 ); ?>
				<div class="jev_listrow"><ul class="ev_ul">
			<?php endif; ?>

			<?php $listyle = 'style="border-color:'.$row->bgcolor().';"'; ?>
			<li class='ev_td_li' <?php echo $listyle ?>>
				<?php $this->loadedFromTemplate('icalevent.list_row', $row, 0); ?>
			</li>

			<?php $chdate = $event_day_month_year; ?>

		<?php endfor; ?>
		</div>
	<?php else: ?>
		<div class="jev_listrow jev_noresults">
		<?php if( $searchisValid ) : ?>
			<?php echo JLayoutHelper::render('joomla.content.message.message_info', JText::_('JEV_NO_EVENTFOR').' <strong>'.$this->keyword.'</strong>'); ?>
		<?php else : ?>
			<b><?php echo $this->keyword ?></b>
			<?php $this->keyword = ''; ?>
		<?php endif; ?>
		</div>
	<?php endif; ?>
</div>

<div class="jev_pagination row justify-content-center mt-3">
	<div class="col-8">
		<form class="w-100" action="<?php echo JRoute::_("index.php?option=".JEV_COM_COMPONENT."&task=search.results&Itemid=".$this->Itemid);?>" method="post" style="font-size:1;">
			<div class="form-group mb-0">
				<input type="text" name="keyword" size="30" maxlength="50" class="inputbox" value="<?php echo $this->keyword;?>" />
				<input type="hidden" name="pop" value="<?php echo JRequest::getInt("pop",0);?>" />
				<?php if (JRequest::getString("tmpl","")=="component") : ?>
					<?php echo '<input type="hidden" name="tmpl" value="component" />'; ?>
				<?php endif; ?>
			</div>
			<div class="form-group my-2">
				<label for="showpast"><?php echo JText::_("JEV_SHOW_PAST");?></label>
				<input type="checkbox" id="showpast" name="showpast" value="1" <?php echo JRequest::getInt('showpast',0)?'checked="checked"':''?> />
			</div>
			<div class="form-group">
				<input class="btn btn-primary btn-block" type="submit" name="push" value="<?php echo JText::_('JEV_SEARCH_TITLE'); ?>" />
			</div>
		</form>
	</div>
</div>

<?php if ($data["total"]>$data["limit"]) : ?>
	<?php $this->paginationForm($data["total"], $data["limitstart"], $data["limit"]); ?>
<?php endif; ?>
