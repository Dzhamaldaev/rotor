@extends('layout')

@section('title')
    Собственные страницы сайта
@stop

@section('content')

	<b>Как создать свои страницы</b><br>

	1. Перейдите в директорию /resources/views/files, эта директория автоматически генерирует страницы сайта<br>
	2. Создайте в ней директорию с произвольным латинским названием (к примеру library)<br>
	3. Положите в созданную директорию обычный файл с расширением .blade.php (к примеру index.blade.php)<br>
	4. Напишите любой текст на этой странице, это может быть как html код, так и php<br>
	5. Теперь попробуйте перейти на созданную станицу, введите в браузере <?= siteUrl() ?>/files?page=library<br>
	6. Если страница отобразилась, значит вы все сделали правильно<br>

	<div class="help-block">
		В одной директории может быть неограниченное число файлов, расширение указывать не нужно, только имя папки и имя файла через слеш, к примеру /?page=library/simplepage, /?page=library/index то же что и просто /?page=library <br><br>

		Также можно указать заголовок страницы, который автоматически подставится в блок title, для этого нужно написать следующий код

	<pre class="prettyprint linenums">
	&lt;?php
		//show_title('Новый заголовок страницы');
	?&gt;
	</pre>

		Дополнительно можно указать произвольные ключевые слова и описание заполнив переменные setting('keywords') и setting('description')

	<pre class="prettyprint linenums">
	&lt;?php
		setting('keywords')    = 'Ключевые слова';
		//setting('description') =  'Описание страницы';
	?&gt;
	</pre>
	</div>

	Посмотрите пример страниц в виде <a href="/files/docs">документации RotorCMS</a><br>
@stop
