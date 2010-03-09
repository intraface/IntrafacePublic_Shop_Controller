
<fieldset id="details-customer" class="details-customer clearfix">
    
    <legend><span><?php e(__('Fill in information about yourself')); ?></span></legend>
    
    <div class="row">
        <label for="name"><?php e(__('Name')); ?></label>
        <input type=text name="name" value="<?php if(!empty($name)) e($name); ?>" />
    </div>
    
    <div class="row">
        <label for="contact_person"><?php e(__('Contact person')); ?></label>
        <input type="text" name="contactperson" value="<?php if(!empty($contactperson)) e($contactperson); ?>" />
    </div>
    
    <div class="row">
        <label for="address"><?php e(__('Address')); ?></label>
        <textarea name="address"><?php if(!empty($address)) e($address); ?></textarea>
    </div>

    
    <div class="row">
        <label for="postcode"><?php e(__('Postcode')); ?></label>
        <input type="text" name="postcode" value="<?php if(!empty($postcode)) e($postcode); ?>" />
    </div>
    
    <div class="row">
        <label for="city"><?php e(__('City')); ?></label>
        <input type="text" name="city" value="<?php if(!empty($city)) e($city); ?>" />
    </div>

    <div class="row">
        <label for="country"><?php e(__('Country')); ?></label>
        <select name="country">
            <option value=""><?php e(__('Select').'...')?></option>
            <?php foreach($countries AS $key => $value): ?>
                <option value="<?php e($key)?>" <?php if((!empty($country) && $key == $country)) e('selected="selected"'); ?> ><?php e($value)?></option>
            <?php endforeach; ?>
            
        </select>
    </div>    
    
    <div class="row">
        <label for="email"><?php e(__('E-mail')); ?></label>
        <input type="text" name="email" value="<?php if(!empty($email)) e($email); ?>" />
    </div>
    
    <div class="row">
        <label for="phone"><?php e(__('Phone')); ?></label>
        <input type="text" name="phone" value="<?php if(!empty($phone)) e($phone); ?>" />
    </div>
    
</fieldset>