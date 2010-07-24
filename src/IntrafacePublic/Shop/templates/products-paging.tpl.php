<?php if (!empty($paging['offset']) AND is_array($paging['offset']) AND count($paging['offset']) > 0): ?>
    <p class="paging" style="clear: both;">
    <?php foreach($paging['offset'] as $key => $paging): ?>
        <?php if ((empty($this->GET['start']) && $paging == 0) || (!empty($this->GET['start']) && $this->GET['start'] == $paging)): ?>
            | <strong><?php e($key + 1); ?></strong>
        <?php else: ?>
            | <a href="<?php e(url('.', array('start' => $paging))); ?>"><?php e($key + 1); ?></a>
        <?php endif; ?>
    <?php endforeach; ?>
    |</p>
<?php endif; ?>
