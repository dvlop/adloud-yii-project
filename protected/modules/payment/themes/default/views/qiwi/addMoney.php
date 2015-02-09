<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 28.04.14
 * Time: 11:29
 * @var MoneyForm $model
 * @var PaymentController $this
 * @var string $requestUrl
 * @var string $method
 * @var string $currency
 * @var string $modelFields
 * @var string $requestUrl
 * @var string $content
 */
?>

<?php if(isset($content)){
    $this->renderPartial($this->moduleName.'.views._partials.iframeForm', [
        'content' => $content,
    ]);
}else{
    $this->renderPartial($this->moduleName.'.views._partials.addMoney', [
        'currency' => $currency,
        'model' => $model,
        'modelFields' => $modelFields,
        'requestUrl' => $requestUrl,
        'method' => $method
    ]);
} ?>