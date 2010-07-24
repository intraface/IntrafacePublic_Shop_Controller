    <table id="basket">
        <caption><?php e(t('Products')); ?></caption>
        <thead>
            <tr>
                <th></th>
                <th><?php e(t('Name')); ?></th>
                <th><?php e(t('Quantity')); ?></th>
                <th><?php e(t('Amount')); ?></th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td class="total"><strong><?php e(t('Total')); ?></strong></td>
                <td class="total" style="text-align: right;"><?php e($currency); ?> <?php $total_price_incl_vat = new Ilib_Variable_Float($total_price[$currency]['incl_vat']); e($total_price_incl_vat->getAsLocal($this->document->locale, 2)); ?></td>
            </tr>
        </tfoot>

        <tbody>
        <?php foreach($items AS $item): ?>
            <tr>
                <td class="picture">
                    <?php if (array_key_exists(0, $item['pictures'])): ?>
                        <img src="<?php e($item['pictures'][0]['thumbnail']['file_uri']); ?>" alt="<?php e($item['name']); ?>" height="<?php e($item['pictures'][0]['thumbnail']['height']); ?>" width="<?php e($item['pictures'][0]['thumbnail']['width']); ?>" />
                    <?php endif; ?>
                </td>
                <td class="name"><?php e($item["name"]); ?></td>
                <td class="quantity"><?php e($item["quantity"]); ?></td>
                <td class="price"><?php e($currency); ?> <?php $item_price = new Ilib_Variable_Float($item['currency'][$currency]["totalprice_incl_vat"]); e($item_price->getAsLocal($this->document->locale, 2)); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>