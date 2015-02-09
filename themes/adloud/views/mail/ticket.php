<?php
/**
 * @var array $data
 *
    $body = [];
    $data['date'] = $model->date;
    $data['userId'] = \Yii::app()->user->id;
    $data['userName'] = \Yii::app()->user->name;
    $data['text'] = $message->content;
    $data['title'] = $model->name;
    $data['url'] = \Yii::app()->createAbsoluteUrl('ticket/index/index', ['id' => $model->id]);
 */
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head></head>
<body>
    Дата: <?php echo $data['date']; ?><br />
    Название: <?php echo $data['title']; ?><br />
    Пользователь: <?php echo $data['userId']; ?> (<?php echo $data['userName']; ?>)<br />
    Сообщение: <?php echo $data['text']; ?><br />
    <a href="<?php echo $data['url']; ?>">Ссылка</a>
</body>
</html>