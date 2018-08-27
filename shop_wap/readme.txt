多语言使用

<?php include __DIR__.'/messages/I18N.php';?>
<?php echo __('首页');?>

翻译包在 messages目录下，对应的语言包。

注意把.html文件改成.php 原.html文件将不存在。
URL将不会发生变化。


简体转繁体 https://github.com/tszming/mediawiki-zhconverter

I18N.php  修改 youdaotran 函数，实现自动转换