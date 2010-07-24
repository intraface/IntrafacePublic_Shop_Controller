
<fieldset id="details-customer-coupon" class="details-customer">
    <legend><?php e(t('Customer coupon')); ?></legend>
    <p><?php e(t('If you have any promotion coupon, please fill it in here.')); ?></p>
    <div class="row">
        <label for="customer_coupon"><?php e(t('Customer coupon')); ?></label>
        <input type="text" name="customer_coupon" id="customer_coupon" value="<?php if(isset($customer_coupon)) echo $customer_coupon; ?>" />
    </div>
</fieldset>