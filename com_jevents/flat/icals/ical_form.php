<?php
defined('_JEXEC') or die('Restricted access');

$cfg = JEVConfig::getInstance();

$view = $this->getViewName();

echo $this->ExportScript();

$accessiblecats = explode(",", $this->datamodel->accessibleCategoryList());

$jinput = JFactory::getApplication()->input;

echo "<h2 id='cal_title'>" . JText::_('JEV_ICAL_EXPORT') . "</h2>\n";

if ($jinput->getString("submit","") != "")
{

	$categories = $jinput->post->get('categories', array(0), null);

	$cats = array();
	foreach ($categories AS $cid)
	{
		$cid = intval($cid);
		// Make sure the user is authorised to view this category and the menu item doesn't block it!
		if (!in_array($cid, $accessiblecats) && $cid = !0)
			continue;
		$cats[] = $cid;
	}
	if (count($cats) == 0)
		$cats[] = 0;


	//$years  = str_replace(",","|",JEVHelper::forceIntegerArray(JRequest::getVar('years','','POST'),true));
	//$cats = implode("|",$cats);
	$years = JEVHelper::forceIntegerArray($jinput->post->get('years', array(0), null), true);
	$cats = implode(",", $cats);

	$link = JURI::root() . "index.php?option=com_jevents&task=icals.export&format=ical";
	if (count($cats) > 0)
	{
		$link .="&catids=" . $cats;
	}
	$link .="&years=" . $years;
	if ($jinput->getInt("icalformatted", 0))
	{
		$link .="&icf=1";
	}

	$params = JComponentHelper::getParams(JEV_COM_COMPONENT);
	if ($params->get("constrained", 0))
	{
		$link .="&Itemid=" . $jinput->getInt("Itemid", 1);
	}

	$icalkey = $params->get("icalkey", "secret phrase");
	$publiclink = $link . "&k=" . md5($icalkey . $cats . $years);

	$user = JFactory::getUser();
	if ($user->id != 0)
	{
		$privatelink = $link . "&pk=" . md5($icalkey . $cats . $years . $user->password . $user->username . $user->id) . "&i=" . $user->id;
	} else {
		$privatelink = "";
	}

	echo "<h3 class='ical_generated'>" . JText::_("JEV_ICAL_GENERATED") . "</h3>";

	?>
	<div class="row my-3">
	  <div class="col-6">
	    <h5 class="export_pub"><?php echo JText::_("JEV_PUBLIC_EVENTS") ?></h5>
			<?php if ($cfg->get("show_ical_download", 1) == 1): ?>
				<div class="ical_form_button export_public">
					<a class="btn btn-success btn-block" href="<?php echo $publiclink ?>"><?php echo JText::_('JEV_REP_ICAL_PUBLIC') ?></a>
				</div>
			<?php endif; ?>

			<?php if ($cfg->get("outlook2003icalexport", 0) == 1): ?>
				<div class="ical_form_button export_public mt-1">
					<a class="btn btn-success btn-block" href="<?php echo $publiclink.'&outlook2003=1' ?>"><?php echo JText::_('Per Outlook 2003') ?></a>
				</div>
			<?php endif; ?>

			<?php if ($cfg->get("show_webcal_url", 0) == 1): ?>
				<?php $webcalurl_pub = str_replace(array('http:', 'https:'), 'webcal:', $publiclink); ?>
				<div class="ical_form_button export_public mt-1">
					<a class="btn btn-info btn-block" href="<?php echo $webcalurl_pub ?>"><?php echo JText::_('JEV_REP_ICAL_PUBLIC_WEBCAL') ?></a>
				</div>
			<?php endif; ?>

	  </div>
	  <?php if ($user->id != 0): ?>
	    <div class="col-6">
	      <h5 class="export_priv"><?php echo JText::_("JEV_PUBLIC_AND_PRIVATE_EVENTS") ?></h5>
				<?php if ($cfg->get("show_ical_download", 1) == 1): ?>
	        <div class="ical_form_button export_private">
						<a class="btn btn-success btn-block" href="<?php echo $privatelink ?>"><?php echo JText::_('JEV_REP_ICAL_PRIVATE') ?></a>
					</div>
				<?php endif; ?>

				<?php if ($cfg->get("outlook2003icalexport", 0) == 1): ?>
					<div class="ical_form_button export_private mt-1">
						<a class="btn btn-success btn-block" href="<?php echo $privatelink.'&outlook2003=' ?>"><?php echo JText::_('Per Outlook 2003') ?></a>
					</div>
				<?php endif; ?>

				<?php if ($cfg->get("show_webcal_url", 0) == 1): ?>
					<?php $webcalurl_priv = str_replace(array('http:', 'https:'), 'webcal:', $privatelink); ?>
					<div class="ical_form_button export_private mt-1">
						<a class="btn btn-info btn-block" href="<?php echo $webcalurl_priv ?>"><?php echo JText::_('JEV_REP_ICAL_PRIVATE_WEBCAL') ?></a>
					</div>
				<?php endif; ?>

	    </div>
	  <?php endif; ?>
	</div>

	<?php
	// echo "<h5 class='export_pub'>" . JText::_("JEV_PUBLIC_EVENTS") . "</h5>";
	// if ($user->id != 0)
	// {
	// 	echo "<h5 class='export_priv'>" . JText::_("JEV_PUBLIC_AND_PRIVATE_EVENTS") . "</h5>";
	// }

	// if ($cfg->get("show_webcal_url", 0) == 1){
	// 	echo $this->ExportWebCal($publiclink, $privatelink);
	// }

	// if ($cfg->get("show_ical_download", 1) == 1){
	// 	echo $this->ExportIcalDownload($publiclink, $privatelink);
	// }

	// if ($cfg->get("outlook2003icalexport", 0) == 1)
	// {
	// 	echo $this->ExportOutlook2003($publiclink, $privatelink);
	// }

	// New ICAL Export Options for Google,
	// if ($cfg->get("show_webcal_google", 0) == 1){
	// 	echo $this->ExportGoogle($publiclink, $privatelink);
	// }
}
if ($cfg->get("outlook2003icalexport", 0) == 0 && $cfg->get("show_ical_download", 1) == 0 && $cfg->get("show_webcal_url", 0) == 0 && $cfg->get("show_webcal_google", 0) && $cfg->get("outlook2003icalexport", 0)) {
	//If non are enabled we don't want to have user thinking the script is buggy as nothing is produced.
	echo "<div style='margin:15px;font-weight:bold;'>" . JText::_("JEV_ICAL_ALL_DISABLED") . "</div>";
} else {
?>
<div class="export_form">
<form id="ical" class="w-100 row" name="ical" method="post" class="<?php isset($_POST['submit']) ? 'icalexportresults' : ''; ?>">
	<?php
	$categories = JEV_CommonFunctions::getCategoryData();

	?>
	<div class="choosecat col-6">
		<?php
		echo "<h5>" . JText::_('JEV_EVENT_CHOOSE_CATEG') . "</h5>\n";
// All categories
		$cb = "<input name=\"categories[]\" value=\"0\" type=\"checkbox\" onclick='clearIcalCategories(this);' ";
		$checked = false;
		if (!$jinput->post->get('categories', 0, null))
		{
			$cb = $cb . " CHECKED";
			$checked = true;
		}
		else if ($jinput->post->get('categories', 0, null) && in_array(0, $jinput->post->get('categories', '', null)))
		{
			$cb = $cb . " CHECKED";
			$checked = true;
		}
		echo $cb . " />" . JText::_("JEV_EVENT_ALLCAT") . "<br/>\n";
		?>
		<div id='othercats' <?php echo $checked ? 'style="display:none;max-height:100px;overflow-y:auto;"' : ''; ?> >
			<?php
			foreach ($categories AS $c)
			{
				// Make sure the user is authorised to view this category and the menu item doesn't block it!
				if (!in_array($c->id, $accessiblecats))
					continue;
				$cb = "<input name=\"categories[]\" value=\"" . $c->id . "\" type=\"checkbox\" onclick='clearAllIcalCategories(this);' ";
				if (!$jinput->get('categories', 0, null))
				{
					//$cb=$cb." CHECKED";
				}
				else if ($jinput->get('categories', 0, null) && in_array($c->id, $jinput->post->get('categories', '', null)))
				{
					$cb = $cb . " CHECKED";
				}
				$cb = $cb . " /><span style=\"background:" . $c->color . "\">&nbsp;&nbsp;&nbsp;&nbsp;</span> " . str_repeat(" - ", $c->level - 1) . $c->title . "<br/>\n";
				echo $cb;
			}
			?>
		</div>
	</div>
	<div class="chooseyear col-6">
		<?php
		echo "<h5>" . JText::_('JEV_SELECT_REP_YEAR') . "</h5>\n";

// All years
		$yt = "<input name=\"years[]\" type=\"checkbox\" value=\"0\"  onclick='clearIcalYears(this);' ";
		$checked = false;
		if (!$jinput->get('years', 0, null))
		{
			$yt = $yt . " CHECKED";
			$checked = true;
		}
		else if ($jinput->get('years', 0, null) && in_array(0, $jinput->post->get('years', '', null)))
		{
			$yt = $yt . " CHECKED";
			$checked = true;
		}
		$yt = $yt . " />" . JText::_("JEV_EVENT_ALLYEARS") . "<br/>\n";
		echo $yt;
		?>
		<div id='otheryears' <?php echo $checked ? 'style="display:none;max-height:100px;overflow-y:auto;"' : ''; ?> >
			<?php
//consturc years array, easy to add own kind of selection
			$params = JComponentHelper::getParams(JEV_COM_COMPONENT);
			$year = array();
			for ($y = JEVHelper::getMinYear(); $y <= JEVHelper::getMaxYear(); $y++)
			{
				if (!in_array($y, $year))
					$year[] = $y;
			}

			foreach ($year AS $y)
			{
				$yt = "<input name=\"years[]\" type=\"checkbox\" value=\"" . $y . "\" onclick='clearAllIcalYears(this);' ";
				if (!$jinput->get('years', 0, null))
				{
					//$yt = $yt . " CHECKED";
				}
				else if ($jinput->get('years', 0, null) && in_array($y, $jinput->post->get('years', '', null)))
				{
					$yt = $yt . " CHECKED";
				}
				$yt = $yt . " />" . $y . "<br/>\n";
				echo $yt;
			}
			?>
		</div>
	</div>
	<?php

	echo "<div class='icalformat' style='clear:left; padding-top:5px;'>";
	if ($params->get("icalformatted", 1) == 1){
	echo "<h3>" . JText::_('JEV_ICAL_FORMATTING') . "</h3>\n";
	?>
	<div class="col-12 mt-3">
		<input name="icalformatted" class="btn btn-primary btn-block" type="checkbox" value="1" <?php echo $jinput->getInt("icalformatted", 0) ? "checked='checked'" : ""; ?> />
		<label>		<?php echo JText::_("JEV_PRESERVE_HTML_FORMATTING") ; ?>	</label>
	</div>
<?php }
	echo "</div>";
?>
	<div class="col-12 mt-3">
		<input id="submit" class="ical_submit btn btn-primary btn-block" type="submit" name="submit" value="<?php echo JText::_('JEV_GENERATE_ICALS'); ?>" />
	</div>
</form>
</div>
<?php } ?>
