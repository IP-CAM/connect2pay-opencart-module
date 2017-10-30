<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/payment.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
         <tr>
          <td><?php echo $entry_status; ?></td>
          <td><select name="payxpert_status">
              <?php if ($payxpert_status) { ?>
              <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
              <option value="0"><?php echo $text_disabled; ?></option>
              <?php } else { ?>
              <option value="1"><?php echo $text_enabled; ?></option>
              <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
              <?php } ?>
            </select></td>
        </tr>
        <tr>
            <td width="25%"><span class="required">*</span> <?php echo $entry_originator; ?></td>
            <td><input type="text" name="payxpert_originator" value="<?php echo $payxpert_originator; ?>" />
<?php if ($error_originator) { ?>
            <br />
            <span class="error"><?php echo $error_originator; ?></span>
<?php } ?></td>
        </tr>
        <tr>
            <td width="25%"><span class="required">*</span> <?php echo $entry_password; ?></td>
            <td><input type="text" name="payxpert_password" autocomplete="off" />
<?php if ($error_password) { ?>
            <br />
            <span class="error"><?php echo $error_password; ?></span>
<?php } ?></td>
        </tr>
        <tr>
          <td><?php echo $entry_connect2pay_url; ?></td>
          <td><input type="text" name="payxpert_connect2pay_url" value="<?php echo $payxpert_connect2pay_url; ?>" /></td>
        </tr>
        <tr>
          <td><?php echo $entry_3dsecure; ?></td>
          <td><?php if ($payxpert_3dsecure) { ?>
            <input type="radio" name="payxpert_3dsecure" value="1" checked="checked" />
            <?php echo $text_enabled; ?>
            <input type="radio" name="payxpert_3dsecure" value="0" />
            <?php echo $text_disabled; ?>
            <?php } else { ?>
            <input type="radio" name="payxpert_3dsecure" value="1" />
            <?php echo $text_enabled; ?>
            <input type="radio" name="payxpert_3dsecure" value="0" checked="checked" />
            <?php echo $text_disabled; ?>
            <?php } ?></td>
        </tr>
        <tr>
         <td><?php echo $entry_merchant_notifications; ?></td>
         <td>
          <select id="payxpert_merchant_notifications" name="payxpert_merchant_notifications">
<?php foreach ($payxpert_merchant_notifications_statuses as $status) { ?>
           <option value="<?php echo $status; ?>"<?php if ($status == $payxpert_merchant_notifications) { ?> selected="selected"<?php } ?>><?php echo $entry_merchant_notifications_status[$status]; ?></option>
<?php } ?>
          </select>
<?php if (isset($payxpert_merchant_notifications_error)) { ?>
          <br />
          <span class="error"><?php echo $payxpert_merchant_notifications_error; ?></span>
<?php } ?>
         </td>
        </tr>
        <tr>
         <td><?php echo $entry_merchant_notifications_to; ?></td>
         <td><input type="text" id="payxpert_merchant_notifications_to" name="payxpert_merchant_notifications_to" value="<?php echo $payxpert_merchant_notifications_to; ?>" class="form-control" />
<?php if (isset($payxpert_merchant_notifications_to_error)) { ?>
          <br />
          <span class="error"><?php echo $payxpert_merchant_notifications_to_error; ?></span>
<?php } ?>
         </td>
        </tr>
        <tr>
         <td><?php echo $entry_merchant_notifications_lang; ?></td>
         <td>
          <select id="payxpert_merchant_notifications_lang" name="payxpert_merchant_notifications_lang">
<?php foreach ($payxpert_merchant_notifications_langs as $lang) { ?>
           <option value="<?php echo $lang; ?>"<?php if ($lang == $payxpert_merchant_notifications_lang) { ?> selected="selected"<?php } ?>><?php echo $entry_lang[$lang]; ?></option>
<?php } ?>
          </select>
<?php if (isset($payxpert_merchant_notifications_lang_error)) { ?>
          <br />
          <span class="error"><?php echo $payxpert_merchant_notifications_lang_error; ?></span>
<?php } ?>
         </td>
        </tr>
        <tr>
          <td><?php echo $entry_failure_page; ?></td>
          <td><?php if ($payxpert_failure_page) { ?>
            <input type="radio" name="payxpert_failure_page" value="1" checked="checked" />
            <?php echo $text_enabled; ?>
            <input type="radio" name="payxpert_failure_page" value="0" />
            <?php echo $text_disabled; ?>
            <?php } else { ?>
            <input type="radio" name="payxpert_failure_page" value="1" />
            <?php echo $text_enabled; ?>
            <input type="radio" name="payxpert_failure_page" value="0" checked="checked" />
            <?php echo $text_disabled; ?>
            <?php } ?></td>
        </tr>
        <tr>
          <td><?php echo $entry_debuglog; ?></td>
          <td><?php if ($payxpert_debuglog) { ?>
            <input type="radio" name="payxpert_debuglog" value="1" checked="checked" />
            <?php echo $text_enabled; ?>
            <input type="radio" name="payxpert_debuglog" value="0" />
            <?php echo $text_disabled; ?>
            <?php } else { ?>
            <input type="radio" name="payxpert_debuglog" value="1" />
            <?php echo $text_enabled; ?>
            <input type="radio" name="payxpert_debuglog" value="0" checked="checked" />
            <?php echo $text_disabled; ?>
            <?php } ?></td>
        </tr>
        <tr>
          <td><?php echo $entry_order_status; ?></td>
          <td><select name="payxpert_order_status_id">
              <?php foreach ($order_statuses as $order_status) { ?>
              <?php if ($order_status['order_status_id'] == $payxpert_order_status_id) { ?>
              <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select></td>
        </tr>
        <tr>
          <td><?php echo $entry_order_denied_status; ?></td>
          <td><select name="payxpert_order_denied_status_id">
              <?php foreach ($order_statuses as $order_status) { ?>
              <?php if ($order_status['order_status_id'] == $payxpert_order_denied_status_id) { ?>
              <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select></td>
        </tr>
        <tr>
          <td><?php echo $entry_geo_zone; ?></td>
          <td><select name="payxpert_geo_zone_id">
              <option value="0"><?php echo $text_all_zones; ?></option>
              <?php foreach ($geo_zones as $geo_zone) { ?>
              <?php if ($geo_zone['geo_zone_id'] == $payxpert_geo_zone_id) { ?>
              <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select></td>
        </tr>
          <tr>
            <td><?php echo $entry_sort_order; ?></td>
            <td><input type="text" name="payxpert_sort_order" value="<?php echo $payxpert_sort_order; ?>" size="1" /></td>
          </tr>
        </table>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?> 
