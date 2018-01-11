<?php
defined('_JEXEC') or die('Restricted access');
$jinput = JFactory::getApplication()->input;
?>

<div class="jev_pagination row justify-content-center">
	<div class="col-8">
		<form class="w-100" action="<?php echo JRoute::_("index.php?option=".JEV_COM_COMPONENT."&task=search.results&Itemid=".$this->Itemid);?>" method="post">
			<div class="form-group mb-0">
		    <label for="keyword" class="sr-only">Ricerca</label>
				<input type="text" placeholder="Ricerca" name="keyword" size="30" maxlength="50" class="inputbox" value="<?php echo $this->keyword;?>" />
			</div>
			<div class="form-group my-2">
				<label for="showpast" class="mb-0"><?php echo JText::_("JEV_SHOW_PAST");?></label>
				<input type="checkbox" id="showpast" name="showpast" value="1" <?php echo $jinput->getInt('showpast',0) ? 'checked="checked"' : ''?> />
			</div>
			<div class="form-group">
				<input class="btn btn-primary btn-block" type="submit" name="push" value="<?php echo JText::_('JEV_SEARCH_TITLE'); ?>" />
			</div>
		</form>
	</div>
</div>
