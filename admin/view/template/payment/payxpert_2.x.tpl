<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-payxpert" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
<?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
<?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $heading_title; ?></h3>
      </div>
      <div class="panel-body">
       <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-payxpert" class="form-horizontal">
        <div class="form-group">
         <label class="col-sm-4 col-md-3 col-lg-2 control-label" for="payxpert_status"><?php echo $entry_status; ?></label>
         <div class="col-sm-5 col-md-3">
          <select id="payxpert_status" name="payxpert_status">
            <option value="1"<?php if (isset($payxpert_status) && $payxpert_status) { ?> selected="selected"<?php } ?>><?php echo $text_enabled; ?></option>
            <option value="0"<?php if (isset($payxpert_status) && !$payxpert_status) { ?> selected="selected"<?php } ?>><?php echo $text_disabled; ?></option>
          </select>
         </div>
        </div>
        <div class="form-group required<?php if ($error_originator) { ?> has-error<?php } ?>">
         <label class="col-sm-4 col-md-3 col-lg-2 control-label" for="payxpert_originator"><?php echo $entry_originator; ?></label>
         <div class="col-sm-4 col-md-2">
           <input type="text" id="payxpert_originator" name="payxpert_originator" value="<?php echo $payxpert_originator; ?>" class="form-control" />
<?php if ($error_originator) { ?>
                <span class="error"><?php echo $error_originator; ?></span>
<?php } ?>
         </div>
        </div>
        <div class="form-group<?php if ($error_password) { ?> has-error<?php } ?>">
         <label class="col-sm-4 col-md-3 col-lg-2 control-label" for="payxpert_password"><span data-toggle="tooltip" title="<?php echo $entry_password_help; ?>"><?php echo $entry_password; ?></span></label>
         <div class="col-sm-4 col-md-2">
           <input type="text" id="payxpert_password" name="payxpert_password" class="form-control" autocomplete="off" />
<?php if ($error_password) { ?>
            <span class="error"><?php echo $error_password; ?></span>
<?php } ?>
         </div>
        </div>
        <div class="form-group">
         <label class="col-sm-4 col-md-3 col-lg-2 control-label" for="payxpert_connect2pay_url"><span data-toggle="tooltip" title="<?php echo $entry_connect2pay_url_help; ?>"><?php echo $entry_connect2pay_url; ?></span></label>
         <div class="col-sm-5 col-md-3">
           <input type="text" id="payxpert_connect2pay_url" name="payxpert_connect2pay_url" value="<?php echo $payxpert_connect2pay_url; ?>" class="form-control" />
         </div>
        </div>
        <div class="form-group">
        <label class="col-sm-4 col-md-3 col-lg-2 control-label"><span data-toggle="tooltip" title="<?php echo $entry_3dsecure_help; ?>"><?php echo $entry_3dsecure; ?></span></label>
         <div class="col-sm-5 col-md-3">
          <div class="radio">
           <label>
            <input type="radio" name="payxpert_3dsecure" value="1"<?php if (isset($payxpert_3dsecure) && $payxpert_3dsecure) { ?> checked="checked"<?php } ?> />
            <?php echo $text_enabled; ?>
           </label>
          </div>
          <div class="radio">
           <label>
            <input type="radio" name="payxpert_3dsecure" value="0"<?php if (isset($payxpert_3dsecure) && !$payxpert_3dsecure) { ?> checked="checked"<?php } ?> />
            <?php echo $text_disabled; ?>
           </label>
          </div>
         </div>
        </div>
        <div class="form-group<?php if (isset($payxpert_merchant_notifications_error)) { ?> has-error<?php } ?>">
         <label class="col-sm-4 col-md-3 col-lg-2 control-label"><span data-toggle="tooltip" title="<?php echo $entry_merchant_notifications_help; ?>"><?php echo $entry_merchant_notifications; ?></span></label>
         <div class="col-sm-4 col-md-2">
          <select id="payxpert_merchant_notifications" name="payxpert_merchant_notifications">
<?php foreach ($payxpert_merchant_notifications_statuses as $status) { ?>
           <option value="<?php echo $status; ?>"<?php if ($status == $payxpert_merchant_notifications) { ?> selected="selected"<?php } ?>><?php echo $entry_merchant_notifications_status[$status]; ?></option>
<?php } ?>
          </select>
         </div>
<?php if (isset($payxpert_merchant_notifications_error)) { ?>
         <span class="error"><?php echo $payxpert_merchant_notifications_error; ?></span>
<?php } ?>
        </div>
        <div class="form-group<?php if (isset($payxpert_merchant_notifications_to_error)) { ?> has-error<?php } ?>">
         <label class="col-sm-4 col-md-3 col-lg-2 control-label" for="payxpert_originator"><span data-toggle="tooltip" title="<?php echo $entry_merchant_notifications_to_help; ?>"><?php echo $entry_merchant_notifications_to; ?></span></label>
         <div class="col-sm-4 col-md-2">
           <input type="text" id="payxpert_merchant_notifications_to" name="payxpert_merchant_notifications_to" value="<?php echo $payxpert_merchant_notifications_to; ?>" class="form-control" />
         </div>
<?php if (isset($payxpert_merchant_notifications_to_error)) { ?>
         <span class="error"><?php echo $payxpert_merchant_notifications_to_error; ?></span>
<?php } ?>
        </div>
        <div class="form-group<?php if (isset($payxpert_merchant_notifications_lang_error)) { ?> has-error<?php } ?>">
         <label class="col-sm-4 col-md-3 col-lg-2 control-label" for="payxpert_merchant_notifications_lang"><span data-toggle="tooltip" title="<?php echo $entry_merchant_notifications_lang_help; ?>"><?php echo $entry_merchant_notifications_lang; ?></span></label>
         <div class="col-sm-4 col-md-2">
          <select id="payxpert_merchant_notifications_lang" name="payxpert_merchant_notifications_lang">
<?php foreach ($payxpert_merchant_notifications_langs as $lang) { ?>
           <option value="<?php echo $lang; ?>"<?php if ($lang == $payxpert_merchant_notifications_lang) { ?> selected="selected"<?php } ?>><?php echo $entry_lang[$lang]; ?></option>
<?php } ?>
          </select>
         </div>
<?php if (isset($payxpert_merchant_notifications_lang_error)) { ?>
         <span class="error"><?php echo $payxpert_merchant_notifications_lang_error; ?></span>
<?php } ?>
        </div>
        <div class="form-group">
         <label class="col-sm-4 col-md-3 col-lg-2 control-label"><span data-toggle="tooltip" title="<?php echo $entry_failure_page_help; ?>"><?php echo $entry_failure_page; ?></span></label>
         <div class="col-sm-5 col-md-3">
          <div class="radio">
           <label>
            <input type="radio" name="payxpert_failure_page" value="1"<?php if (isset($payxpert_failure_page) && $payxpert_failure_page) { ?> checked="checked"<?php } ?> />
            <?php echo $text_enabled; ?>
           </label>
          </div>
          <div class="radio">
           <label>
            <input type="radio" name="payxpert_failure_page" value="0"<?php if (isset($payxpert_failure_page) && !$payxpert_failure_page) { ?> checked="checked"<?php } ?> />
            <?php echo $text_disabled; ?>
           </label>
          </div>
         </div>
        </div>
        <div class="form-group">
         <label class="col-sm-4 col-md-3 col-lg-2 control-label"><?php echo $entry_debuglog; ?></label>
         <div class="col-sm-5 col-md-3">
          <div class="radio">
           <label>
            <input type="radio" name="payxpert_debuglog" value="1"<?php if (isset($payxpert_debuglog) && $payxpert_debuglog) { ?> checked="checked"<?php } ?> />
            <?php echo $text_enabled; ?>
           </label>
          </div>
          <div class="radio">
           <label>
            <input type="radio" name="payxpert_debuglog" value="0"<?php if (isset($payxpert_debuglog) && !$payxpert_debuglog) { ?> checked="checked"<?php } ?> />
            <?php echo $text_disabled; ?>
           </label>
          </div>
         </div>
        </div>
        <div class="form-group">
         <label class="col-sm-4 col-md-3 col-lg-2 control-label" for="payxpert_order_status_id"><span data-toggle="tooltip" title="<?php echo $entry_order_status_help; ?>"><?php echo $entry_order_status; ?></span></label>
         <div class="col-sm-5 col-md-3">
          <select id="payxpert_order_status_id" name="payxpert_order_status_id">
<?php foreach ($order_statuses as $order_status) { ?>
           <option value="<?php echo $order_status['order_status_id']; ?>"<?php if ($order_status['order_status_id'] == $payxpert_order_status_id) { ?> selected="selected"<?php } ?>><?php echo $order_status['name']; ?></option>
<?php } ?>
          </select>
         </div>
        </div>
        <div class="form-group">
         <label class="col-sm-4 col-md-3 col-lg-2 control-label" for="payxpert_order_denied_status_id"><span data-toggle="tooltip" title="<?php echo $entry_order_denied_status_help; ?>"><?php echo $entry_order_denied_status; ?></span></label>
         <div class="col-sm-5 col-md-3">
          <select id="payxpert_order_denied_status_id" name="payxpert_order_denied_status_id">
<?php foreach ($order_statuses as $order_status) { ?>
           <option value="<?php echo $order_status['order_status_id']; ?>"<?php if ($order_status['order_status_id'] == $payxpert_order_denied_status_id) { ?> selected="selected"<?php } ?>><?php echo $order_status['name']; ?></option>
<?php } ?>
          </select>
         </div>
        </div>
        <div class="form-group">
         <label class="col-sm-4 col-md-3 col-lg-2 control-label" for="payxpert_geo_zone_id"><?php echo $entry_geo_zone; ?></label>
         <div class="col-sm-5 col-md-3">
          <select id="payxpert_geo_zone_id" name="payxpert_geo_zone_id">
           <option value="0"><?php echo $text_all_zones; ?></option>
<?php foreach ($geo_zones as $geo_zone) { ?>
            <option value="<?php echo $geo_zone['geo_zone_id']; ?>"<?php if ($geo_zone['geo_zone_id'] == $payxpert_geo_zone_id) { ?> selected="selected"<?php } ?>><?php echo $geo_zone['name']; ?></option>
<?php } ?>
          </select>
         </div>
        </div>
        <div class="form-group">
         <label class="col-sm-4 col-md-3 col-lg-2 control-label" for="payxpert_sort_order"><?php echo $entry_sort_order; ?></label>
         <div class="col-sm-1">
          <input type="text" id="payxpert_sort_order" name="payxpert_sort_order" value="<?php echo $payxpert_sort_order; ?>" maxlength="1" class="form-control" />
         </div>
        </div>
      </form>
     </div>
   </div>
 </div>
</div>
<?php echo $footer; ?> 
