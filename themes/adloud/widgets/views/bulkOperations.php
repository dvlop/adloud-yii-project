<?php
/**
 * Created by PhpStorm.
 * User: rem
 * Date: 03.07.14
 * Time: 14:01
 * @var array $operations
 * @var array $urlOptions
 */
?>


<div class="col-sm-3">
    <div class="btn-group select mbn mass_operations">
        <button class="btn dropdown-toggle clearfix" data-toggle="dropdown">
            <?php echo Yii::t('main', 'Массовые операции'); ?><span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
            <?php foreach($operations as $operation): ?>
                <li>
                    <a
                        class="action-button"
                        data-confirm="<?php echo $operation['title']; ?>"
                        success-action="<?php echo isset($operation['successAction']) ? $operation['successAction'] : 'location.reload();'; ?>"
                        data-id="<? echo isset($operation['dataId']) ? $operation['dataId'] : '.checkbox.checked input'; ?>"
                        data-url="<?php echo Yii::app()->createUrl($operation['url'], $urlOptions); ?>"
                        href="#"
                    >
                        <?php echo Yii::t('main', $operation['name']); ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>