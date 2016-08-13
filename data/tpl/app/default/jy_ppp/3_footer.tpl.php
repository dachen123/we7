<?php defined('IN_IA') or exit('Access Denied');?><footer class="foot">
<ul>
    <li><a href="<?php  echo $this->createMobileUrl('index')?>">缘分</a></li>
    <li><a href="<?php  echo $this->createMobileUrl('mail')?>">聊天</a></li>
    <li><a href="<?php  echo $this->createMobileUrl('luck')?>">附近</a></li>
    <li><a href="<?php  echo $this->createMobileUrl('geren')?>">我</a></li>
</ul>
<?php  if(!empty($sitem['copyright'])) { ?>
<p>
	<a style="color:#999" <?php  if(!empty($sitem['copyrighturl'])) { ?>href="<?php  echo $sitem['copyrighturl'];?>" target='_blank' <?php  } else { ?>href="javascript:void(0);" <?php  } ?>>© <?php  echo $sitem['copyright'];?></a>
</p>
<?php  } ?>

</footer>