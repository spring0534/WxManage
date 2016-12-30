<?php
/* @var $this SysUserGhController */
/* @var $data SysUserGh */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ghid')); ?>:</b>
	<?php echo CHtml::encode($data->ghid); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('icon_url')); ?>:</b>
	<?php echo CHtml::encode($data->icon_url); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('qrcode')); ?>:</b>
	<?php echo CHtml::encode($data->qrcode); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('qrcode_small')); ?>:</b>
	<?php echo CHtml::encode($data->qrcode_small); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('type')); ?>:</b>
	<?php echo CHtml::encode($data->type); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('wxh')); ?>:</b>
	<?php echo CHtml::encode($data->wxh); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('company')); ?>:</b>
	<?php echo CHtml::encode($data->company); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('desc')); ?>:</b>
	<?php echo CHtml::encode($data->desc); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tenancy')); ?>:</b>
	<?php echo CHtml::encode($data->tenancy); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('login_name')); ?>:</b>
	<?php echo CHtml::encode($data->login_name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('login_pwd')); ?>:</b>
	<?php echo CHtml::encode($data->login_pwd); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('api_url')); ?>:</b>
	<?php echo CHtml::encode($data->api_url); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('api_token')); ?>:</b>
	<?php echo CHtml::encode($data->api_token); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('zf_api_url')); ?>:</b>
	<?php echo CHtml::encode($data->zf_api_url); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('zf_api_token')); ?>:</b>
	<?php echo CHtml::encode($data->zf_api_token); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('appid')); ?>:</b>
	<?php echo CHtml::encode($data->appid); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('appsecret')); ?>:</b>
	<?php echo CHtml::encode($data->appsecret); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('notes')); ?>:</b>
	<?php echo CHtml::encode($data->notes); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</b>
	<?php echo CHtml::encode($data->status); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('open_portal')); ?>:</b>
	<?php echo CHtml::encode($data->open_portal); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('open_msite')); ?>:</b>
	<?php echo CHtml::encode($data->open_msite); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ctm')); ?>:</b>
	<?php echo CHtml::encode($data->ctm); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('utm')); ?>:</b>
	<?php echo CHtml::encode($data->utm); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('operator_uid')); ?>:</b>
	<?php echo CHtml::encode($data->operator_uid); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('interact')); ?>:</b>
	<?php echo CHtml::encode($data->interact); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tenant_id')); ?>:</b>
	<?php echo CHtml::encode($data->tenant_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ec_cid')); ?>:</b>
	<?php echo CHtml::encode($data->ec_cid); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('oauth')); ?>:</b>
	<?php echo CHtml::encode($data->oauth); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('access_token')); ?>:</b>
	<?php echo CHtml::encode($data->access_token); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('at_expires')); ?>:</b>
	<?php echo CHtml::encode($data->at_expires); ?>
	<br />

	*/ ?>

</div>