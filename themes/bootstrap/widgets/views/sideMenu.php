<?php
/**
 * Created by PhpStorm.
 * User: psyhonut
 * Date: 2/6/14
 * Time: 12:05 AM
 * @var array $menu
 */

?>
<div role="navigation" id="navigation" class="navbar navbar-default">
    <div class="navbar-header">
        <button data-target=".navbar-responsive-collapse" data-toggle="collapse" class="navbar-toggle" type="button">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
    </div>

    <div class="container collapse navbar-collapse navbar-responsive-collapse">
        <ul class="nav navbar-nav">
            <?php foreach ($menu AS $elKey => $elValue): ?>
                <?php
                $elUrl = explode('/', $elValue['url']);
                $elController = !empty($elUrl[0]) ? $elUrl[0] : '';
                $elAction = !empty($elUrl[1]) ? $elUrl[1] : '';

                $elActionsForActive = array();
                if(!empty($elValue['actions'])){
                    $elActionsForActive = $elValue['actions'];
                }

                ?>

                <li class="dropdown <?php echo((!empty($elController) && ($this->getController()->getId() == strtolower($elController) && in_array($this->getController()->action->id, $elActionsForActive) || (empty($elActionsForActive) && $this->getController()->getId() == strtolower($elController))) ) ? 'active' : ''); ?>">
                    <a data-close-others="false" data-delay="0" data-hover="dropdown" data-toggle="dropdown" class="dropdown-toggle"
                       href="<?php echo $elController != '#' ? Yii::app()->createUrl($elController . '/' . $elAction) : '#'; ?>">
                        <?php echo $elValue['name']; ?>
                        <i class="icon-angle-down"></i>
                    </a>

                    <ul class="dropdown-menu">
                    <?php foreach ($elValue['menu'] AS $k => $param): ?>
                        <?php
                        $paramUrl = array_filter(explode('/', $param['url']));

                        $index1 = isset($paramUrl[0]) ? 0 : 1;
                        $index2 = $index1 + 1;
                        $index3 = $index1 + 2;

                        $paramModule = !empty($paramUrl[$index1]) ? $paramUrl[$index1] : '';
                        $paramController = !empty($paramUrl[$index2]) ? $paramUrl[$index2] : '';
                        $paramAction = !empty($paramUrl[$index3]) ? $paramUrl[$index3] : '';
                        ?>

                        <li class="<?php echo((!empty($paramController) && $this->getController()->getId() == strtolower($paramController))
                        && (!empty($paramAction) && $this->getController()->action->id == strtolower($paramAction)) ? 'active' : ''); ?>">
                            <a href="<?php echo $paramModule != '#' ? Yii::app()->createUrl($paramModule.'/'.$paramController.'/'.$paramAction) : '#'; ?>">
                                <i class="<?php echo $param['icon'];?>"></i> <?php echo $param['name'];?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                    </ul>
                </li>
            <?php endforeach; ?>
        </ul>
        <ul class="nav navbar-nav navbar-right">
            <li><a href="#" class="toggleContentWidth"><i class="icon-resize-horizontal"></i></a></li>
        </ul>
    </div><!-- /navbar-collapse -->
</div>
