<form action="<?php echo $action; ?>" method="post" id="payment">
  <input type="hidden" name="payxpert_validate" value="true" />
  <div class="buttons">
    <div class="pull-right">
     <input type="button" value="<?php echo $button_confirm; ?>" id="button-confirm" class="btn btn-primary" data-loading-text="Loading..." onclick="$('#payment').submit();">
    </div>
  </div>
</form>
