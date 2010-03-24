<form method="POST" class="buy" action="<?php e(url('add'));?>">
    <?php foreach($attribute_groups as $key => $group): ?>
        <div class="attribute-group">
            <label><?php e($group['name']); ?>:</label>
            <select name="attribute[<?php e($key); ?>]">
                <option value="0"><?php e(__('Select...')); ?></option>
                <?php foreach ($group['attributes'] as $attribute): ?>
                    <?php
                    $selected = '';
                    if((isset($variation) && isset($variation['variation']) && isset($variation['variation']['attributes']) && $variation['variation']['attributes'][$key]['id'] == $attribute['id']) OR count($group['attributes']) == 1) {
                        $selected = 'selected="selected"';
                    }
                    
                    $disabled = '';
                    if(isset($attribute['is_used']) && $attribute['is_used'] == 0) {
                        $disabled = 'disabled="disabled"';
                    }
                    
                    ?>
                    <option value="<?php e($attribute['id']); ?>" <?php echo $selected; ?> <?php echo $disabled; ?> ><?php e($attribute['name']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    <?php endforeach; ?>

    <?php if(isset($variation)): ?>
        <?php if($variation === false): ?>
            <?php e(__('The selected variation does not exist. Please select another.')); ?>
        <?php else: ?>
            <?php if($variation['variation']['currency'][$currency]['before_price_incl_vat'] != 0.00): ?><p class="before_price"><?php e($currency); ?> <?php $before_price = new Ilib_Variable_Float($variation['variation']['currency'][$currency]['before_price_incl_vat']); e($before_price->getAsLocal($this->document->locale, 2)); ?></p><?php endif; ?>
            <p class="price"><?php e($currency); ?> <?php $price = new Ilib_Variable_Float($variation['variation']['currency'][$currency]['price_incl_vat']); e($price->getAsLocal($this->document->locale, 2)); ?></p>
            <?php if ($product['stock'] == 0 || $variation['stock']['for_sale'] > 0): ?>
                <input type="submit" name="add_product_id" value="<?php e(__('Buy')); ?>" />
            <?php else: ?>
                <p class="shop-sold-out"><?php e(__('Variation is sold out')); ?>.</p>
            <?php endif; ?>
        <?php endif; ?>
    <?php else: ?>
        <?php if($product['currency'][$currency]['before_price_incl_vat'] != 0.00): ?><p class="before_price"><?php e($currency); ?> <?php $before_price = new Ilib_Variable_Float($product['currency'][$currency]['before_price_incl_vat']); e($before_price->getAsLocal($this->document->locale, 2)); ?></p><?php endif; ?>
        <p class="price"><?php e($currency); ?> <?php $price = new Ilib_Variable_Float($product['currency'][$currency]['price_incl_vat']); e($price->getAsLocal($this->document->locale, 2)); ?></p>
        <input type="submit" name="add_product_id" value="<?php e(__('Buy')); ?>" />
    <?php endif; ?>
</form>


