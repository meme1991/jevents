<?php
defined('_JEXEC') or die('Restricted access');

use Joomla\String\StringHelper;

function FlatViewHelperHeader16($view){

	$jinput = JFactory::getApplication()->input;
	$task = $jinput->getString('jevtask');
	$view->loadModules("jevprejevents");
	$view->loadModules("jevprejevents_".$task);

	$dispatcher	= JEventDispatcher::getInstance();
	$dispatcher->trigger( 'onJEventsHeader', array($view));

	$cfg		= JEVConfig::getInstance();
	$version	= JEventsVersion::getInstance();
	$jevtype	= $jinput->get('jevtype', null, null);
	$evid		= $jinput->getInt('evid');
	$pop		= $jinput->getInt('pop', '0');
	$params = JComponentHelper::getParams(JEV_COM_COMPONENT);

	$view->copyrightComment();

	// stop crawler and set meta tag
	JEVHelper::checkRobotsMetaTag();

	// Call the MetaTag setter function.
	if (is_callable(array("JEVHelper","SetMetaTags"))){
		JEVHelper::SetMetaTags();
	}

	$lang = JFactory::getLanguage();
?>
<?php if ($params->get('show_page_heading', 0)) : ?>
	<?php echo JLayoutHelper::render('joomla.content.title.title_page', $view->escape($params->get('page_heading'))); ?>
<?php endif; ?>

<?php
$t_headline = '';
switch ($cfg->get('com_calHeadline', 'comp')) {
	case 'none':
		$t_headline = '';
		break;
	case 'menu':
		$menu2   = JFactory::getApplication()->getMenu();
		$menu    = $menu2->getActive();
		if (isset($menu) && isset($menu->title)) {
			$t_headline = $menu->title;
		}
		break;
	default:
		$t_headline = JText::_('JEV_EVENT_CALENDAR');
		break;
}
?>

<?php if ($t_headline!="") : ?>
	<?php echo JLayoutHelper::render('joomla.content.title.title_heading', $t_headline); ?>
<?php endif; ?>

<?php $task = $jinput->getString('jevtask', ''); ?>
<?php $info = ""; ?>
<?php if ($cfg->get('com_print_icon_view', 1) || $cfg->get('com_email_icon_view', 1) || strpos($info, "<li>")!==false ) : ?>
<ul class="actions">
	<?php if ($cfg->get('com_print_icon_view', 1)) : ?>
		<?php
		$print_link = 'index.php?option=' . JEV_COM_COMPONENT
		. '&task=' . $task
		. ($evid ? '&evid=' . $evid : '')
		. ($jevtype ? '&jevtype=' . $jevtype : '')
		. ($view->year ? '&year=' . $view->year : '')
		. ($view->month ? '&month=' . $view->month : '')
		. ($view->day ? '&day=' . $view->day : '')
		. $view->datamodel->getItemidLink()
		. $view->datamodel->getCatidsOutLink()
		. '&pop=1'
		. '&tmpl=component';
		$print_link = JRoute::_($print_link);
		?>

		<?php if ($pop) : ?>
			<li class="print-icon">
				<a href="javascript:void(0);" rel="nofollow" onclick="javascript:window.print(); return false;" title="<?php echo JText::_('JEV_CMN_PRINT'); ?>">
					<i class="far fa-print"></i>
				</a>
			</li>
		<?php else : ?>
			<li class="print-icon">
				<a href="javascript:void(0);" rel="nofollow" onclick="window.open('<?php echo $print_link; ?>', 'win2', 'status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=600,height=600,directories=no,location=no');" title="<?php echo JText::_('JEV_CMN_PRINT'); ?>">
					<i class="far fa-print"></i>
				</a>
			</li>
		<?php endif; ?>
	<?php endif; ?>

	<?php if ($cfg->get('com_email_icon_view', 1)) : ?>

		<?php $task = $jinput->getString('jevtask', ''); ?>

		<?php $link = 'index.php?option=' . JEV_COM_COMPONENT
		. '&task=' . $task
		. ($evid ? '&evid=' . $evid : '')
		. ($jevtype ? '&jevtype=' . $jevtype : '')
		. ($view->year ? '&year=' . $view->year : '')
		. ($view->month ? '&month=' . $view->month : '')
		. ($view->day ? '&day=' . $view->day : '')
		. $view->datamodel->getItemidLink()
		. $view->datamodel->getCatidsOutLink();
		?>
		<?php $link =JRoute::_($link); ?>
		<?php //if (strpos($link,"/")===0) $link = JString::substr($link,1); ?>
		<?php $uri	        = JURI::getInstance(JURI::base()); ?>
		<?php $root = $uri->toString( array('scheme', 'host', 'port') ); ?>

		<?php $link = $root.$link; ?>
		<?php require_once(JPATH_SITE.'/'.'components'.'/'.'com_mailto'.'/'.'helpers'.'/'.'mailto.php'); ?>
		<?php $url	= JRoute::_('index.php?option=com_mailto&tmpl=component&link='.MailToHelper::addLink( $link )); ?>

		<li class="email-icon">
			<a href="javascript:void(0);" rel="nofollow" onclick="javascript:window.open('<?php echo $url;?>','emailwin','width=400,height=350,menubar=yes,resizable=yes'); return false;" title="<?php echo JText::_( 'EMAIL' ); ?>">
				<i class="far fa-envelope"></i>
			</a>
		</li>
	<?php endif; ?>
	<?php echo $info; ?>
</ul>
<?php endif; ?>

<?php $view->loadModules("jevprejevents2"); ?>
<?php $view->loadModules("jevprejevents2_".$task); ?>
<?php } ?>
