<?php
/**
 * Created by PhpStorm.
 * User: rem
 * Date: 15.08.14
 * Time: 13:52
 * @var \application\components\ControllerBase $this
 * @var array $data
 */
?>

<?php
$title = '';
$subtitle = '';
$content = '';
$buttonOk = '';
$buttonCancel = '';
$okUrl = '';
$cancelUrl = '#';
$onClickOk = '';
$onClickCancel = '';
$okSpanClass = 'input-icon fui-check pull-left';
$cancelSpanClass = 'input-icon fui-exit pull-left';
$afterOkText = '';
$contentContainer = '';

if($data){
    if(isset($data['title']))
        $title = $data['title'];
    if(isset($data['subtitle']))
        $subtitle = $data['subtitle'];
    if(isset($data['content']))
        $content = $data['content'];
    if(isset($data['buttonOk']))
        $buttonOk = $data['buttonOk'];
    if(isset($data['buttonCancel']))
        $buttonCancel = $data['buttonCancel'];
    if(isset($data['cancelUrl']))
        $cancelUrl = $data['cancelUrl'];
    if(isset($data['onClickOk']))
        $onClickOk = $data['onClickOk'];
    if(isset($data['onClickCancel']))
        $onClickCancel = $data['onClickCancel'];
    if(isset($data['okSpanClass']))
        $okSpanClass = $data['okSpanClass'];
    if(isset($data['cancelSpanClass']))
        $cancelSpanClass = $data['cancelSpanClass'];
    if(isset($data['afterOkText']))
        $afterOkText = $data['afterOkText'];
    if(isset($data['contentContainer']))
        $contentContainer = $data['contentContainer'];
}
?>

<div class="adloud-blocks-js-code-bg" id="main-modal-window">

    <div id="main-modal-content-block" class="adloud-blocks-js-code">

        <span id="main-modal-window-closer" class="fui-cross">X</span>

        <p class="h6 modal-title"><?php echo $title; ?></p>
        <?php if($subtitle): ?>
            <p class="note modal-subtitle"><?php echo $subtitle; ?></p>
        <?php endif; ?>

        <?php if($contentContainer): ?>
            <p class="note">
                <?php echo $contentContainer; ?>
            </p>
        <?php else: ?>
            <textarea id="main-modal-text" class="form-control flat modal-content" rows="6"><?php echo $content; ?></textarea>
        <?php endif; ?>

        <?php if($buttonOk): ?>
            <button
                id="main-modal-button-ok"
                data-clipboard-target="main-modal-text"
                data-clipboard-text=""
                class="btn adloud_btn modal-button-ok<?php if($okUrl) echo ' auto-url'; ?>"
                type="button"
                <?php if($onClickOk) echo 'onClick="'.$onClickOk.'"'; ?>
                <?php if($okUrl) echo 'data-url="'.$okUrl.'"'; ?>
                >
                <span class="<?php echo $okSpanClass; ?>"></span>
                <?php echo $buttonOk; ?>
            </button>
        <?php endif; ?>

        <?php if($buttonCancel): ?>
            <a
                id="main-modal-button-cancel"
                href="<?php echo $cancelUrl; ?>"
                class="btn btn-default close-btn modal-button-cancel"
                <?php if($onClickCancel) echo 'onClick="'.$onClickCancel.'"'; ?>
                >
                <span class="<?php echo $cancelSpanClass; ?>"></span>
                <?php echo $buttonCancel; ?>
            </a>
        <?php endif; ?>

    </div>

</div>