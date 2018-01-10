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

        <?php if ($task == "month.calendar") : ?>
          <div class="month_date">
            <?php echo JEV_CommonFunctions::jev_strftime("%B", $month_date) ?>, <?php echo JEV_CommonFunctions::jev_strftime("%Y", $month_date) ?>
          </div>
        <?php endif; ?>

        <div class="new-navigation d-flex justify-content-between my-3">
          <div class="btn-group nav-items" role="group" aria-label="Nav items">
            <?php if (in_array("byyear", $this->iconstoshow)) : ?>
              <a id="nav-year" class="btn btn-outline-primary<?php if ($task == "year.listevents") : echo ' active'; endif; ?>" role="button" href="<?php echo JRoute::_('index.php?option=' . $option . $cat . '&task=year.listevents&' . $view_date->toDateURL() . '&Itemid=' . $Itemid); ?>" title="<?php echo JText::_('JEV_VIEWBYYEAR'); ?>">
                <?php echo JText::_('JEV_VIEWBYYEAR'); ?>
              </a>
            <?php endif; ?>

            <?php if (in_array("bymonth", $this->iconstoshow)) : ?>
              <a id="nav-month" class="btn btn-outline-primary<?php if ($task == "month.calendar") : echo ' active'; endif; ?>" role="button" href="<?php echo JRoute::_('index.php?option=' . $option . $cat . '&task=month.calendar&' . $view_date->toDateURL() . '&Itemid=' . $Itemid); ?>" title="<?php echo JText::_('JEV_VIEWBYMONTH'); ?>" >
                <?php echo JText::_('JEV_VIEWBYMONTH'); ?>
              </a>
            <?php endif; ?>

            <?php if (in_array("byweek", $this->iconstoshow)) : ?>
              <a id="nav-week" class="btn btn-outline-primary<?php if ($task == "week.listevents") : echo ' active'; endif; ?>" role="button" href="<?php echo JRoute::_('index.php?option=' . $option . $cat . '&task=week.listevents&' . $view_date->toDateURL() . '&Itemid=' . $Itemid); ?>" title="<?php echo JText::_('JEV_VIEWBYWEEK'); ?>" >
                <?php echo JText::_('JEV_VIEWBYWEEK'); ?>
              </a>
            <?php endif; ?>

            <?php if (in_array("byday", $this->iconstoshow)) : ?>
              <a id="nav-today" class="btn btn-outline-primary<?php if ($task == "day.listevents") : echo ' active'; endif; ?>" role="button" href="<?php echo JRoute::_('index.php?option=' . $option . $cat . '&task=day.listevents&' . $today_date->toDateURL() . '&Itemid=' . $Itemid); ?>" title="<?php echo JText::_('JEV_VIEWTODAY'); ?>" >
                <?php echo JText::_('JEV_VIEWTODAY'); ?>
              </a>
            <?php endif; ?>

            <?php if (in_array("bymonth", $this->iconstoshow)) : ?>
              <?php echo $this->_viewJumptoIcon($view_date, $viewimages); ?>
            <?php endif; ?>

            <?php if ($cfg->get('com_hideshowbycats', 0) == '0') : ?>
              <?php if (in_array("bycat", $this->iconstoshow)) : ?>
                <a id="nav-cat" class="btn btn-outline-primary<?php if ($task == "cat.listevents") : echo ' active'; endif; ?>" role="button" href="<?php echo JRoute::_('index.php?option=' . $option . $cat . '&task=cat.listevents&' . $view_date->toDateURL() . '&Itemid=' . $Itemid); ?>" title="<?php echo JText::_('JEV_VIEWBYCAT'); ?>" >
                  <?php echo JText::_('JEV_VIEWBYCAT'); ?>
                </a>
              <?php endif; ?>
            <?php endif; ?>
          </div>

          <?php if (in_array("search", $this->iconstoshow)) : ?>
            <a id="nav-search" href="<?php echo JRoute::_('index.php?option=' . $option . $cat . '&task=search.form&' . $view_date->toDateURL() . '&Itemid=' . $Itemid); ?>" title="<?php echo JText::_('JEV_SEARCH_TITLE'); ?>" >
              <i class="far fa-search"></i>
            </a>
          <?php endif; ?>

        </div>

        <div class="">
          <?php if (in_array("bymonth", $this->iconstoshow)) : ?>
            <?php echo $this->_viewHiddenJumpto($view_date, $view, $Itemid); ?>
          <?php endif; ?>
        </div>
<?php
    }

    function _viewJumptoIcon($today_date, $viewimages) {
        ?>
          <a id="nav-jumpto" class="btn btn-outline-primary" role="button" href="#" onclick="if (jevjq('#jumpto').hasClass('jev_none')) {jevjq('#jumpto').removeClass('jev_none');} else {jevjq('#jumpto').addClass('jev_none')};return false;" title="<?php echo   JText::_('JEV_JUMPTO');?>">
            <?php echo JText::_('JEV_JUMPTO'); ?>
          </a>
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
            <button class="btn btn-primary" type="button" onclick="submit(this.form)"><?php echo JText::_('JEV_JUMPTO'); ?></button>
            <input type="hidden" name="Itemid" value="<?php echo $Itemid; ?>" />
          </form>
        </div>
        <?php
    }

}
?>
