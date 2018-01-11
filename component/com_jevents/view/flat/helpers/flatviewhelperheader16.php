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

<?php $view->loadModules("jevprejevents2"); ?>
<?php $view->loadModules("jevprejevents2_".$task); ?>
<?php } ?>
