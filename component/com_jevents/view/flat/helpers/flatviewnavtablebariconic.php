<?php
defined('_JEXEC') or die('Restricted access');

class FlatViewNavTableBarIconic {

    var $view = null;

    function __construct($view, $today_date, $view_date, $dates, $alts, $option, $task, $Itemid) {
        global $catidsOut;
        $jinput = JFactory::getApplication()->input;

        if ($jinput->getInt('pop', 0))
            return;
        $cfg = JEVConfig::getInstance();
        $compname = JEV_COM_COMPONENT;

        //Lets check if we should show the nav on event details
        if ($task == "icalrepeat.detail" && $cfg->get('shownavbar_detail', 1) == 0) {
            return;
        }

        $this->iconstoshow = $cfg->get('iconstoshow', array('byyear', 'bymonth', 'byweek', 'byday', 'search'));
        $viewimages = JURI::root() . "components/" . JEV_COM_COMPONENT . "/views/" . $view->getViewName() . "/assets/images";

        $cat = "";
        $hiddencat = "";
        if ($catidsOut != 0) {
            $cat = '&catids=' . $catidsOut;
            $hiddencat = '<input type="hidden" name="catids" value="' . $catidsOut . '"/>';
        }

        $link = 'index.php?option=' . $option . '&task=' . $task . $cat . '&Itemid=' . $Itemid . '&';
        $month_date = ( JevDate::mktime(0, 0, 0, $view_date->month, $view_date->day, $view_date->year));
        ?>

        <!-- <?php //if ($task == "month.calendar") : ?>
          <div class="month_date">
            <?php //echo JEV_CommonFunctions::jev_strftime("%B", $month_date) ?>, <?php echo JEV_CommonFunctions::jev_strftime("%Y", $month_date) ?>
          </div>
        <?php //endif; ?> -->

        <nav class="navbar-expand-lg jevent-nav">
          <button class="navbar-toggler p-0 py-2" type="button" data-toggle="collapse" data-target="#jevents-nav" aria-controls="jevents-nav" aria-expanded="false" aria-label="Toggle navigation">
            <i class="far fa-bars d-inline-block"></i>
            <span class="d-inline-block">MENU'</span>
          </button>

          <div class="collapse navbar-collapse" id="jevents-nav">
            <div class="new-navigation w-100">

              <ul class="list-inline">
                <?php if ($cfg->get('com_print_icon_view', 1)) : ?>
                  <?php
                  $jevtype	= $jinput->get('jevtype', null, null);
                  $evid		= $jinput->getInt('evid');
                  $pop		= $jinput->getInt('pop', '0');
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

                  if ($pop) :
                    $onclick = "javascript:window.print(); return false";
                  else:
                    $onclick = "window.open('".$print_link."', 'win2', 'status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=600,height=600,directories=no,location=no');";
                  endif;
              		?>
                  <li class="list-inline-item">
                    <a href="javascript:void(0);" onclick="<?php echo $onclick ?>">
                      <i class="fal fa-print fa-2x"></i>
                    </a>
                  </li>
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

                  <li class="list-inline-item">
                    <a href="javascript:void(0);" rel="nofollow" onclick="javascript:window.open('<?php echo $url;?>','emailwin','width=400,height=350,menubar=yes,resizable=yes'); return false;" title="<?php echo JText::_( 'EMAIL' ); ?>">
                      <i class="fal fa-envelope fa-2x"></i>
                    </a>
                  </li>
                <?php endif; ?>

                <li class="list-inline-item">
                  <a title="Ricerca evento" href="<?php echo JRoute::_('index.php?option=' . $option . $cat . '&task=search.form&' . $view_date->toDateURL() . '&Itemid=' . $Itemid); ?>">
                    <i class="fal fa-search fa-2x"></i>
                  </a>
                </li>
              </ul>

              <ul class="list-group list-striped list-hover">
                <?php if (in_array("byyear", $this->iconstoshow)) : ?>
                  <li class="list-group-item<?php if ($task == "year.listevents") : echo ' active'; endif; ?>">
                    <a id="nav-year" role="button" href="<?php echo JRoute::_('index.php?option=' . $option . $cat . '&task=year.listevents&' . $view_date->toDateURL() . '&Itemid=' . $Itemid); ?>" title="<?php echo JText::_('JEV_VIEWBYYEAR'); ?>">
                      <?php echo JText::_('JEV_VIEWBYYEAR'); ?>
                    </a>
                  </li>
                <?php endif; ?>

                <?php if (in_array("bymonth", $this->iconstoshow)) : ?>
                  <li class="list-group-item<?php if ($task == "month.calendar") : echo ' active'; endif; ?>">
                    <a id="nav-month" role="button" href="<?php echo JRoute::_('index.php?option=' . $option . $cat . '&task=month.calendar&' . $view_date->toDateURL() . '&Itemid=' . $Itemid); ?>" title="<?php echo JText::_('JEV_VIEWBYMONTH'); ?>" >
                      <?php echo JText::_('JEV_VIEWBYMONTH'); ?>
                    </a>
                  </li>
                <?php endif; ?>

                <?php if (in_array("byweek", $this->iconstoshow)) : ?>
                  <li class="list-group-item<?php if ($task == "week.listevents") : echo ' active'; endif; ?>">
                    <a id="nav-week" role="button" href="<?php echo JRoute::_('index.php?option=' . $option . $cat . '&task=week.listevents&' . $view_date->toDateURL() . '&Itemid=' . $Itemid); ?>" title="<?php echo JText::_('JEV_VIEWBYWEEK'); ?>" >
                      <?php echo JText::_('JEV_VIEWBYWEEK'); ?>
                    </a>
                  </li>
                <?php endif; ?>

                <?php if (in_array("byday", $this->iconstoshow)) : ?>
                  <li class="list-group-item<?php if ($task == "day.listevents") : echo ' active'; endif; ?>">
                    <a id="nav-today" role="button" href="<?php echo JRoute::_('index.php?option=' . $option . $cat . '&task=day.listevents&' . $today_date->toDateURL() . '&Itemid=' . $Itemid); ?>" title="<?php echo JText::_('JEV_VIEWTODAY'); ?>" >
                      <?php echo JText::_('JEV_VIEWTODAY'); ?>
                    </a>
                  </li>
                <?php endif; ?>

                <?php if (in_array("bymonth", $this->iconstoshow)) : ?>
                  <?php echo $this->_viewJumptoIcon($view_date, $viewimages); ?>
                <?php endif; ?>

                <?php if ($cfg->get('com_hideshowbycats', 0) == '0') : ?>
                  <?php if (in_array("bycat", $this->iconstoshow)) : ?>
                    <li class="list-group-item<?php if ($task == "cat.listevents") : echo ' active'; endif; ?>">
                      <a id="nav-cat" role="button" href="<?php echo JRoute::_('index.php?option=' . $option . $cat . '&task=cat.listevents&' . $view_date->toDateURL() . '&Itemid=' . $Itemid); ?>" title="<?php echo JText::_('JEV_VIEWBYCAT'); ?>" >
                        <?php echo JText::_('JEV_VIEWBYCAT'); ?>
                      </a>
                    </li>
                  <?php endif; ?>
                <?php endif; ?>

                <!-- <?php if (in_array("search", $this->iconstoshow)) : ?>
                  <li class="list-group-item<?php if ($task == "search.form") : echo ' active'; endif; ?>">
                    <a id="nav-search" href="<?php echo JRoute::_('index.php?option=' . $option . $cat . '&task=search.form&' . $view_date->toDateURL() . '&Itemid=' . $Itemid); ?>" title="<?php echo JText::_('JEV_SEARCH_TITLE'); ?>" >
                      Ricerca <i class="far fa-search"></i>
                    </a>
                  </li>
                <?php endif; ?> -->
              </ul>
            </div>

            <div class="w-100">
              <?php if (in_array("bymonth", $this->iconstoshow)) : ?>
                <?php echo $this->_viewHiddenJumpto($view_date, $view, $Itemid); ?>
              <?php endif; ?>
            </div>

          </div>
        </nav>

<?php
    }

    function _viewJumptoIcon($today_date, $viewimages) {
        ?>
        <li class="list-group-item">
          <a id="nav-jumpto" href="#" onclick="if (jevjq('#jumpto').hasClass('jev_none')) {jevjq('#jumpto').removeClass('jev_none');} else {jevjq('#jumpto').addClass('jev_none')};return false;" title="<?php echo   JText::_('JEV_JUMPTO');?>">
            <?php echo JText::_('JEV_JUMPTO'); ?>
          </a>
        </li>
        <?php
    }

    function _viewHiddenJumpto($this_date, $view, $Itemid) {
        $cfg = JEVConfig::getInstance();
        $hiddencat = "";
        if ($view->datamodel->catidsOut != 0) {
            $hiddencat = '<input type="hidden" name="catids" value="' . $view->datamodel->catidsOut . '"/>';
        }
        $index = JRoute::_("index.php");
        ?>
        <div id="jumpto"  class="jev_none">
          <form name="BarNav" action="<?php echo $index; ?>" method="get">
            <input type="hidden" name="option" value="<?php echo JEV_COM_COMPONENT; ?>" />
            <input type="hidden" name="task" value="month.calendar" />
            <?php
            echo $hiddencat;
            /* Day Select */
            // JEventsHTML::buildDaySelect( $this_date->getYear(1), $this_date->getMonth(1), $this_date->getDay(1), ' style="font-size:10px;"' );
            /* Month Select */
            JEventsHTML::buildMonthSelect($this_date->getMonth(1), '');
            /* Year Select */
            JEventsHTML::buildYearSelect($this_date->getYear(1), '');
            ?>
            <button class="btn btn-primary btn-block mt-2" type="button" onclick="submit(this.form)"><?php echo JText::_('JEV_JUMPTO'); ?></button>
            <input type="hidden" name="Itemid" value="<?php echo $Itemid; ?>" />
          </form>
        </div>
        <?php
    }

}
?>
