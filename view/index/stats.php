<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="<?php echo $this->viewdir; ?>/styles/style.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="http://code.jquery.com/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo JS; ?>/survey.js"></script>
<title>您的期盼，我们的行动</title>
</head>

<body>
<div class="waper">

<div class="top"></div>
<div class="bt">
<Div class="bt_font">
	
&nbsp;&nbsp;&nbsp;&nbsp;为深入贯彻落实中央和省委、市委党的群团工作会议精神，切实有效发挥好机关工会和妇女组织的桥梁和纽带作用，保持和增强市直机关群团组织和群团工作的政治性、先进性、群众性，市直机关工会与妇女工委面向所属基层工会和妇女组织以及广大工会会员设计了<strong>"您的期盼·我们的行动"</strong>调查问卷，旨在通过这种方式，充分了解基层工会、妇女组织和广大职工群众的所思所想所盼，查找梳理我们在密切联系和有效服务基层组织、职工群众方面存在的突出问题，研究提出整改思路和措施。希望您能在百忙之中抽出时间认真阅读并填写调查问卷。本调查问卷为无记名性质，您可以不用有任何的顾虑，遵从您的内心做出选择，因为，您的期盼，就是我们的行动。
</Div>
</div>
<div class="main">
<p class="main_bt">以下是单项选择</p>
<table width="780" border="0" cellspacing="0" cellpadding="0">

<?php
  $questions=$params['convey']->questions;
  $stats=$params['stats'];
  $part1=$questions->part1;
  $part2=$questions->part2;
  $part3=$questions->part3;
  $j=0;
  $i=0;

  function getQuestionById($id,$questions){
    foreach($questions as $q){
      if($q['id']==$id){
        return $q;
      }
    }
    return null;
  }
 ?>

<?php
  foreach($part1 as $k=>$v):
 ?>
  <tr>
    <td width="52" align="center"><?php echo ++$i; ?>.</td>
    <td width="828" class="question-title" height="35"><?php echo $v->title ?></td>
  </tr>
  <tr>
    <td width="52" align="center">&nbsp;</td>
    <td height="35" data-id="<?php echo $v->id; ?>" class="items-wrapper" data-type="<?php echo $v->type ?>" data-required="<?php echo $v->required ?>" data-otherfield="<?php echo $v->otherfields ?>">
    <?php foreach($v->items as $idx=>$item): ?>
 <label> 
 <input type="radio" class="question-item" name="radio-<?php echo $v->id; ?>" id="radioid-<?php echo $v->id; ?>"  />
      <?php echo $item ?>(<span style="color:red;"><?php $quest=getQuestionById($v->id,$stats);echo $quest["item".$idx]; ?></span>)
      </label>&nbsp;&nbsp;
    <?php endforeach ?>
<?php if($v->otherfields): ?>
  <input placeholder="其他" />
<?php endif ?>
      </td>
  </tr>
<?php endforeach ?>
</table>

<p class="main_bt">以下为多项选择（选择其他选项的需要将具体内容填写在后面的空格内）</p>
<table width="780" border="0" cellspacing="0" cellpadding="0">
  <?php
  foreach($part2 as $v):
 ?>
  <tr>
    <td width="52" align="center"><?php echo ++$i; ?>.</td>
    <td class="question-title" width="828" height="35"><?php echo $v->title ?></td>
  </tr>
  <tr>
    <td width="52" align="center">&nbsp;</td>
    <td height="35" data-id="<?php echo $v->id; ?>" class="items-wrapper" data-type="<?php echo $v->type ?>" data-required="<?php echo $v->required ?>" data-otherfield="<?php echo $v->otherfields ?>">
    <?php foreach($v->items as $idx=>$item): ?>
 <label> <input type="checkbox" class="question-item" name="chkbox" id="radio" value="radio" />
      <?php echo $item ?>(<span style="color:red;"><?php $quest=getQuestionById($v->id,$stats);echo $quest["item".$idx]; ?></span>)
      </label><br>
    <?php endforeach ?>
    <?php if($v->otherfields): ?>
      <?php if($v->type<3): ?>
  <input type="checkbox" name="chkbox" class="question-item item-other" value="radio" /><input class="question-item-text" placeholder="其他" />
<?php else: ?>
<textarea class="question-item-text" placehoder="您的回答"></textarea>
<?php endif ?>
<?php endif ?>
      </td>
  </tr>
<?php endforeach ?>
</table>

<p class="main_bt">以下内容为基层工会填写</p>

<table width="780" border="0" cellspacing="0" cellpadding="0">
  <?php
  foreach($part3 as $v):
 ?>
  <tr>
    <td width="52" align="center"><?php echo ++$j; ?>.</td>
    <td class="question-title" width="828" height="35"><?php echo $v->title ?></td>
  </tr>
  <tr>
    <td width="52" align="center">&nbsp;</td>
    <td height="35" data-id="<?php echo $v->id; ?>" class="items-wrapper" data-type="<?php echo $v->type ?>" data-required="<?php echo $v->required ?>" data-otherfield="<?php echo $v->otherfields ?>">
    <?php foreach($v->items as $idx=>$item): ?>
 <label> 
  <?php if($v->type==1): ?>
  <input type="radio" class="question-item" name="radio-<?php $v->id ?>" />
<?php else: ?>
  <input type="checkbox" class="question-item" name="chkbox" />
<?php endif ?>
      <?php echo $item ?>(<span style="color:red;"><?php $quest=getQuestionById($v->id,$stats);echo $quest["item".$idx]; ?></span>)
      </label><br>
    <?php endforeach ?>
    <?php if($v->otherfields): ?>
      <?php if($v->type<3): ?>
  <input type="checkbox" name="chkbox"  class="question-item item-other" value="radio" /><input  class="question-item-text" placeholder="其他" />
<?php else: ?>
<textarea  class="question-item-text" placeholder="您的回答"></textarea>
<?php endif ?>
<?php endif ?>
      </td>
  </tr>
<?php endforeach ?>
  <tr>
    
  </tr>
  <tr>
   
  </tr>
  </table>

</div>

</div>

</body>
</html>
