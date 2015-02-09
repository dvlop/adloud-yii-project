<?php
/**
 * Created by PhpStorm.
 * User: rem
 * Date: 18.09.14
 * Time: 11:05
 * @var \application\components\ControllerAdmin $this
 * @var string $content
 */
?>

<?php
    $this->scriptFiles[] = \Yii::app()->theme->baseUrl.'/assets/plugins/advanced-datatable/media/js/jquery.dataTables.js';
    $this->scriptFiles[] = \Yii::app()->theme->baseUrl.'/assets/plugins/data-tables/DT_bootstrap.js';

    $this->cssFiles[] = \Yii::app()->theme->baseUrl.'/assets/plugins/advanced-datatable/media/css/demo_page.css';
    $this->cssFiles[] = \Yii::app()->theme->baseUrl.'/assets/plugins/advanced-datatable/media/css/demo_table.css';
?>

<div class="row">
    <div class="col-sm-12">
        <section class="panel">

            <header class="panel-heading">
                <?php echo $this->pageName; ?>
            </header>

            <div class="panel-body">
                <div class="adv-table">
                    <?php echo $content; ?>
                </div>
            </div>

        </section>
    </div>
</div>