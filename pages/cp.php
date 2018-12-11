<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/private/config.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/private/init/mysql.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/private/init/memcache.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/private/init/session.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/private/init/var.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/private/func.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/private/auth.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/private/minify.php');

if(!$user){
	_message2('Unauthorized user', 'error');
}

if(!empty($user['2fa'])){
	$tmpMes = 'ВЫКЛЮЧИТЬ 2FA';
}else{
	$tmpMes = 'ВКЛЮЧИТЬ 2FA';
}

if(!empty($user['avatar'])){
	$tmpAvatar = "{$user['dir']}/{$user['avatar']}.jpg";
}else{
	$tmpAvatar = "noavatar.jpg";
}

function profileMes($name){
	global $user, $var;
	$x = empty($user['user_values'][$name]) ? false : $user['user_values'][$name];
	if($x){
		switch($name){
			case 'sex': $x = $var['sex'][$x]; break;
			case 'age': $x = getAge($x); break;
		}
	}
	if(!$x) $x = 'Не указано';
	return $x;
}

function tableSess($data){
	$text = ''; $i = 0;	$td = count($data)-1;
	foreach($data as $key => $val){
		$i++; $status = 'Closed';
		if($data[$key][2]){
			$status = '<font color="green">Active</a>';
		}
		$text .= "<tr><td>$i</td><td>{$data[$key][0]}</td><td>".geoip_country_name_by_name($data[$key][0])."</td><td>".date("Y-m-d h:s", $key)."</td><td>$status</td>
		<td><a href=\"#\" style=\"color: #383838;\" data-history-show-header=\"{$data[$key][1]}\"><span class=\"glyphicon glyphicon-edit\"></span></a>";
		if($data[$key][2]){
			$text .= "&nbsp;<a href=\"#\" style=\"color: #383838;\" data-session-id=\"{$data[$key][3]}\" data-session-td=\"$td\"><span class=\"glyphicon glyphicon-remove\"></span>";
		}
		$text .= "</td></tr>";
		$td--;
	}
	return $text;
}

require_once($_SERVER['DOCUMENT_ROOT'].'/private/header.php');
?>

<div class="news-block">		
	<div class="profile-info-left-side">
		<div class="profile-left-block-wrapper">
			<span class="profile-nickname"><b>VKuser323907417</b></span>			
			<div class="profile-avatar-wrapper">
				<a href="#" data-modal-show title="Изменить"><img src="/upload/avatars/<?php echo $tmpAvatar; ?>" id="profile-avatar" alt="" width="150" height="150"></a>
			</div>
			<div class="user-status">
				Зритель<br/>
				Сообщений: 550
			</div>
		</div>
	</div>
	
	<div class="profile-line"></div>
	
	<div class="profile-info-right-side">
		<div class="profile-right-block-content">
			<h3 class="profile-content-title">Личные данные</h3>
				<p>Имя: <span id="name"><?php echo profileMes('name'); ?></span></p>
				<p>Пол: <span id="sex"><?php echo profileMes('sex'); ?></span></p>
				<p>Возраст: <span id="age"><?php echo profileMes('age'); ?></span></p>
				<p>Был в сети: <span><?php echo date('d.m.Y', $user['register_date']); ?></span></p>
				<p>Регистрация: <span><?php echo date('d.m.Y', $user['last_activity']); ?></span></p>
				<br/>
				<h3 class="profile-content-title">Статистика</h3>
				<p>Раздал: <span>200 TB</span></p>
				<p>Скачал: <span>100 TB</span></p>
				<p>Рейтинг: <span>10</span></p>
		</div>
	</div>
		
	<div class="profile-info-right-side">
		<div class="profile-right-block-content">
			<h3 class="profile-content-title">Контактная информация</h3>
				<p>Steam: <span id="steam"><?php echo profileMes('steam'); ?></span></p>
				<p>Skype: <span id="skype"><?php echo profileMes('skype'); ?></span></p>
				<p>Twitch: <span id="twitch"><?php echo profileMes('twitch'); ?></span></p>
				<p>Twitter: <span id="twitter"><?php echo profileMes('twitter'); ?></span></p>
				<p>YouTube: <span id="youtube"><?php echo profileMes('youtube'); ?></span></p>
				<p>Телефон: <span id="phone"><?php echo profileMes('phone'); ?></span></p>
				<p>Telegram: <span id="telegram"><?php echo profileMes('telegram'); ?></span></p>
				<p>Facebook: <span id="facebook"><?php echo profileMes('facebook'); ?></span></p>
				<p>Instagram: <span id="instagram"><?php echo profileMes('instagram'); ?></span></p>
				<p>ВКонтакте: <span id="vk"><?php echo profileMes('vk'); ?></span></p>
		</div>
	</div>
	<div class="profile-edit">
	<a href="#" data-edit-profile style="color: #383838;" title="Редактировать"><span class="glyphicon glyphicon-edit"></span></a>
	</div>
</div>

<div class="news-block">
		<div class="news-header">
			<h2 class="news-name" style="float:left;">
				Изменить почту
			</h2>
			<h2 class="news-name" id="changeEmailMes" style="float:left; padding-left: 10px;"></h2>
			<div class="clear"></div>	
		</div>
		<div class="clear"></div>
		<div>			
			<input class="form-control" id="changeEmail" type="text" placeholder="Новый email">
			<input class="form-control" id="changeEmailPasswd" style="margin-top: 10px;" type="password" placeholder="Пароль">
			<input class="btn btn btn-success btn-block" style="margin-top: 10px;" data-change-email type="submit" value="ОТПРАВИТЬ">
		</div>
		<div class="clear"></div>
		<div class="news_footer"></div>
</div>

<div class="news-block">
		<div class="news-header">
			<h2 class="news-name" style="float:left;">
				Изменить пароль
			</h2>
			<h2 class="news-name" id="changePasswdMes" style="float:left; padding-left: 10px;"></h2>
			<div class="clear"></div>	
		</div>
		<div class="clear"></div>
		<div>			
			<input class="form-control" id="changePasswd" type="password" placeholder="Старый пароль">
			<input class="btn btn btn-success btn-block" style="margin-top: 10px;" data-change-passwd type="submit" value="ОТПРАВИТЬ">
		</div>
		<div class="clear"></div>
		<div class="news_footer"></div>
</div>

<div class="news-block">
		<div class="news-header">
			<h2 class="news-name" style="float:left;">
				Двухфакторная аутентификация
			</h2>
			<h2 class="news-name" id="2faMes" style="float:left; padding-left: 10px;"></h2>
			<div class="clear"></div>	
		</div>
		<div class="clear"></div>
		<div>
			Установите на мобильный телефон приложение Google Authenticator [<a href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2&hl=ru">android</a>] [<a href="https://itunes.apple.com/ru/app/google-authenticator/id388497605?mt=8">ios</a>]<br/>
			<div id="2fagen" style="<?php if(!empty($user['2fa'])){ echo "display: none;"; }?>">
			<div id="2fakey"></div>
				<input class="btn btn btn-success btn-block" style="margin-top: 10px;" type="submit" data-2fa-generate value="СГЕНЕРИРОВАТЬ КЛЮЧ">
			</div>
			<input class="form-control" id="2fapasswd" style="margin-top: 10px;" type="password" placeholder="Пароль" required="">
			<input class="form-control" id="2facheck" style="margin-top: 10px;" type="text" placeholder="Код" required="">
			<input class="btn btn btn-success btn-block" style="margin-top: 10px;" type="submit" id="send2fa" data-2fa-start value="<?php echo $tmpMes; ?>">
		</div>
		<div class="clear"></div>
		<div class="news_footer"></div>
</div>

<div class="news-block">
	<div class="news-header">
		<h2 class="news-name">
			Активные сессии и авторизации
		</h2>
		<div class="clear"></div>	
	</div>
	<div class="clear"></div>
	<div>
		<style> td,th { text-align: center; vertical-align: middle; } </style>
		<table id="tebleSess" class="table table-striped table-bordered" style="width:100%">
		<thead>
			<tr>
				<th>ID</th>
				<th>IP</th>
				<th>Country</th>
				<th>Time</th>
				<th>Session</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			<?php echo tableSess(auth_history()); ?>
		</tbody>
		</table>
	</div>
	<div class="clear"></div>
	<div class="news_footer"></div>
</div>

<div class="modal fade" id="headerModal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog" style="width: 800px;">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="avatarInfo">Загрузка аватара</h4>
			</div>
			<div  class="modal-body">
			<pre id="showHeader">
				
			</pre>
			
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="avatarModal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="avatarInfo">Загрузка аватара</h4>
			</div>
			<div class="modal-body" style="max-height: 500px; max-width:580px; overflow: hidden;">
				<center><img id="avatarPreview" src="/upload/avatars/<?php echo $tmpAvatar; ?>" ></center>
				<input type="hidden" id="x1" name="x1" />
				<input type="hidden" id="y1" name="y1" />
				<input type="hidden" id="w" name="w" />
				<input type="hidden" id="h" name="h" />
			</div>
			<div class="modal-footer">
				<label class="btn btn-default">Загрузить <input id="uploadAvatar" type="file" name="test" style="display: none;"></label>
				<button data-upload-avatar type="button" class="btn btn-default">Отправить</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="editProfile" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="profileInfo">Редактировать профиль</h4>
			</div>
			<div class="modal-body" style="max-height: 500px; overflow: hidden;">
				<input class="form-control" id="name" type="text" placeholder="Имя">
				<select class="form-control" id="sex" style="margin-top: 7px;">
					<option value="">Выберите пол</option>
					<option value="1">Мужской</option>
					<option value="2">Женский</option>
				</select>				
				<input class="form-control" id="age" type="text" style="margin-top: 7px;" placeholder="Возраст (год)" >
				<input class="form-control" id="phone" type="text" style="margin-top: 7px;" placeholder="Телефон" >
				<input class="form-control" id="steam" type="text" style="margin-top: 7px;" placeholder="Steam" >
				<input class="form-control" id="skype" type="text" style="margin-top: 7px;" placeholder="Skype" >
				<input class="form-control" id="vk" type="text" style="margin-top: 7px;" placeholder="ВКонтакте" >
				<input class="form-control" id="facebook" type="text" style="margin-top: 7px;" placeholder="Facebook" >
				<input class="form-control" id="instagram" type="text" style="margin-top: 7px;" placeholder="Instagram" >
				<input class="form-control" id="youtube" type="text" style="margin-top: 7px;" placeholder="YouTube" >
				<input class="form-control" id="twitch" type="text" style="margin-top: 7px;" placeholder="Twitch" >
				<input class="form-control" id="twitter" type="text" style="margin-top: 7px;" placeholder="Twitter" >
				<input class="form-control" id="telegram" type="text" style="margin-top: 7px;" placeholder="Telegram" >
			</div>
			<div class="modal-footer">
				<button data-reset-user-values type="button" class="btn btn-default">Сбросить</button>
				<button data-save-user-values type="button" class="btn btn-default">Сохранить</button>
			</div>
		</div>
	</div>
</div>

<?require_once($_SERVER['DOCUMENT_ROOT'].'/private/footer.php');?>