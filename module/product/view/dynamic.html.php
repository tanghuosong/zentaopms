<?php
/**
 * The action->dynamic view file of dashboard module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     dashboard
 * @version     $Id: action->dynamic.html.php 1477 2011-03-01 15:25:50Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php foreach($lang->action->periods as $period => $label):?>
    <?php
    $label  = "<span class='text'>$label</span>";
    $active = '';
    if($period == $type)
    {
        $active = 'btn-active-text';
        $label .= " <span class='label label-light label-badge'>{$pager->recTotal}</span>";
    }
    echo html::a(inlink('dynamic', "productID=$productID&type=$period"), $label, '', "class='btn btn-link $active' id='{$period}'")
    ?>
    <?php endforeach;?>
    <div class="input-control space w-150px">
      <?php echo html::select('account', $users, $account, "onchange='changeUser(this.value, $productID)' class='form-control chosen'");?>
    </div>
  </div>
</div>
<div id="mainContent" class="main-content">
  <div id="dynamics">
    <?php if(!empty($dateGroups)):?>
    <?php $firstAction = '';?>
    <?php foreach($dateGroups as $date => $actions):?>
    <?php $isToday = date(DT_DATE4) == $date;?>
    <div class="dynamic <?php if($isToday) echo 'active';?>">
      <div class="dynamic-date">
        <?php if($isToday):?>
        <span class="date-label"><?php echo $lang->action->dynamic->today;?></span>
        <?php endif;?>
        <span class="date-text"><?php echo $date;?></span>
        <button type="button" class="btn btn-info btn-icon btn-sm dynamic-btn"><i class="icon icon-caret-up"></i></button>
      </div>
      <ul class="timeline timeline-tag-left">
        <?php if($direction == 'next') $actions = array_reverse($actions);?>
        <?php foreach($actions as $i => $action):?>
        <?php if(empty($firstAction)) $firstAction = $action;?>
        <li <?php if($action->major) echo "class='active'";?>>
          <div>
            <span class="timeline-tag"><?php echo $action->time?></span>
            <span class="timeline-text">
              <?php echo zget($users, $action->actor) . ' ' . $action->actionLabel;?>
              <span class="text-muted"><?php echo $action->objectLabel;?></span>
              <span class="label label-id"><?php echo $action->objectID;?></span>
              <?php echo html::a($action->objectLink, $action->objectName);?>
            </span>
          </div>
        </li>
        <?php endforeach;?>
      </ul>
    </div>
    <?php endforeach;?>
    <?php else:?>
    <div class="text-center text-muted">
      <?php echo $lang->action->noDynamic;?>
    </div>
    <?php endif;?>

  </div>
  <?php if(!empty($firstAction)):?>
  <?php
  $firstDate = date('Y-m-d', strtotime($firstAction->originalDate) + 24 * 3600);
  $lastDate  = substr($action->originalDate, 0, 10);
  $hasPre    = $this->action->hasPreOrNext($firstDate, 'pre');
  $hasNext   = $this->action->hasPreOrNext($lastDate, 'next');
  ?>
  <?php if($hasPre or $hasNext):?>
  <div class='table-footer'>
    <ul class='pager'>
      <?php $class = $hasPre ? '' : 'disabled';?>
      <li class='<?php echo $class;?> pager-item-left'>
        <?php
        $link = '###';
        if($hasPre) $link = inlink('dynamic', "productID=$productID&type=$type&param=$param&recTotal={$pager->recTotal}&date=" . strtotime($firstDate) . '&direction=pre');
        echo html::a($link, '<i class="icon icon-angle-left"></i>', '', "class='pager-item'");
        ?>
      </li>
      <?php $class = $hasNext ? '' : 'disabled';?>
      <li class='<?php echo $class;?> pager-item-left'>
        <?php
        $link = '###';
        if($hasNext) $link = inlink('dynamic', "productID=$productID&type=$type&param=$param&recTotal={$pager->recTotal}&date=" . strtotime($lastDate) . '&direction=next');
        echo html::a($link, '<i class="icon icon-angle-right"></i>', '', "class='pager-item'");
        ?>
      </li>
    </ul>
  </div>
  <?php endif;?>
  <?php endif;?>
</div>
<script>
$(function()
{
    $('#dynamics').on('click', '.dynamic-btn', function()
    {
        $(this).closest('.dynamic').toggleClass('collapsed');
    });
})
</script>
<?php include '../../common/view/footer.html.php';?>
