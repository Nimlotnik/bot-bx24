<?php
error_reporting(0); 



#####################
### CONFIG OF BOT ###
#####################
define('DEBUG_FILE_NAME', 'bot.log'); // if you need read debug log, you should write unique log name
define('CLIENT_ID', 'local.5c7fd19ae35cf6.40644804'); // like 'app.67efrrt2990977.85678329' or 'local.57062d3061fc71.97850406' - This code should take in a partner's site, needed only if you want to write a message from Bot at any time without initialization by the user
define('CLIENT_SECRET', 'yeUcv4JMclKU72bKeD7YRjNfWCYCTKRkjJvnlet5WVWkvgud5q'); // like '8bb00435c88aaa3028a0d44320d60339' - TThis code should take in a partner's site, needed only if you want to write a message from Bot at any time without initialization by the user
#####################
define('EMAIL', 'kes@web-fixer.ru');
define('DB_HOST', 'localhost');
define('DB_USER', 'bot');
define('DB_PASS', 'bot12345');
define('DB_NAME', 'bot_bd');



writeToLog($_REQUEST, 'ImBot Event Query');

$appsConfig = Array();



if (file_exists(__DIR__.'/config.php'))
	include(__DIR__.'/config.php');

	// receive event "new message for bot"
	if ($_REQUEST['event'] == 'ONIMBOTMESSAGEADD')
	{
		// check the event - authorize this event or not
		if (!isset($appsConfig[$_REQUEST['auth']['application_token']]))
			return false;
			
			if ($_REQUEST['data']['PARAMS']['CHAT_ENTITY_TYPE'] != 'LINES')
				return false;
				
				
				

				function get_stage($dialog_id){
					$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
					$result = $mysqli->query("SELECT stage FROM bot WHERE dialog_id = '".$dialog_id."'");
					$row = $result->fetch_row();
					if(empty($result) or empty($row)){
						$mysqli->query("INSERT INTO bot (dialog_id, stage, first_name, last_name, phone, city, gragdanstvo, age, vagno_v_rabote, zarplata, pochemu,vacancy) VALUES ('".$_REQUEST['data']['PARAMS']['DIALOG_ID']."', 1, '','', '','','','','','','','')");
						$stage = 1;
					}else{
						$stage = $row[0];
					}
					return $stage;
				}
				function next_stage($dialog_id, $stage){
					$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
					$result = $mysqli->query("UPDATE bot SET stage = ".$stage." WHERE dialog_id = '".$dialog_id."'");
				}
				function get_client($dialog_id){
					$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
					$result = $mysqli->query("SELECT * FROM bot WHERE dialog_id = '".$dialog_id."'");
					$row = $result->fetch_assoc();
					return $row;
				}
				function set_name($last_name, $first_name, $dialog_id){
					$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
					$result = $mysqli->query("UPDATE bot SET first_name = '".$first_name."', last_name= '".$last_name."' WHERE dialog_id = '".$dialog_id."'");
				}
				function get_name($dialog_id){
					$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
					$result = $mysqli->query("SELECT first_name FROM bot WHERE dialog_id = '".$dialog_id."'");
					$row = $result->fetch_row();
					return $row[0];
				}
				function set_phone($phone, $dialog_id){
					$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
					$result = $mysqli->query("UPDATE bot SET phone = '".$phone."' WHERE dialog_id = '".$dialog_id."'");
				}
				function set_vacancy($vacancy, $dialog_id){
					$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
					$result = $mysqli->query("UPDATE bot SET vacancy = '".$vacancy."' WHERE dialog_id = '".$dialog_id."'");
				}
				function set_city($city, $dialog_id){
					$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
					$result = $mysqli->query("UPDATE bot SET city = '".$city."' WHERE dialog_id = '".$dialog_id."'");
				}
				function set_age($age, $dialog_id){
					$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
					$result = $mysqli->query("UPDATE bot SET age = '".$age."' WHERE dialog_id = '".$dialog_id."'");
				}
				function set_vvr($vvr, $dialog_id){
					$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
					$result = $mysqli->query("UPDATE bot SET vagno_v_rabote = '".$vvr."' WHERE dialog_id = '".$dialog_id."'");
				}
				function set_grag($g, $d){
					$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
					$result = $mysqli->query("UPDATE bot SET gragdanstvo = '".$g."' WHERE dialog_id = '".$d."'");
				}
				function set_zarplata($zarplata, $dialog_id){
					$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
					$result = $mysqli->query("UPDATE bot SET zarplata = '".$zarplata."' WHERE dialog_id = '".$dialog_id."'");
				}
				function set_pochemu($pochemu, $dialog_id){
					$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
					$result = $mysqli->query("UPDATE bot SET pochemu = '".$pochemu."' WHERE dialog_id = '".$dialog_id."'");
				}
				function validate_phone_number( $string ) {
					if ( preg_match( '/^[+]?([\d]{0,3})?[\(\.\-\s]?([\d]{3})[\)\.\-\s]*([\d]{3})[\.\-\s]?([\d]{4})$/', $string ) ) {
						return true;
					} else {
						return false;
					}
				}
				
				$stage = get_stage($_REQUEST['data']['PARAMS']['DIALOG_ID']);
				
				switch($stage){
					case 1:{
						$result = restCommand('imbot.message.add', [
								"DIALOG_ID" => $_REQUEST['data']['PARAMS']['DIALOG_ID'],
								"MESSAGE"   => 'Привет, я Ольга, специалист по подбору персонала компании "Стройландия". Я помогу тебе найти работу в нашей компании.[br][send=Продолжить]Продолжить[/send]',
						], $_REQUEST["auth"]);
						$stage++;
						next_stage($_REQUEST['data']['PARAMS']['DIALOG_ID'], $stage);
						break;
					}
					case 2:{
						$result = restCommand('imbot.message.add', [
								"DIALOG_ID" => $_REQUEST['data']['PARAMS']['DIALOG_ID'],
								"MESSAGE"   => 'Вы хотите подобрать подходящую вакансию или сначала подробнее узнать о компании?[br][send=Найти вакансии]Найти вакансии[/send][br][send=Информация о компании]Информация о компании[/send]',
						], $_REQUEST["auth"]);
						$stage++;
						next_stage($_REQUEST['data']['PARAMS']['DIALOG_ID'], $stage);
						break;
					}
					case 3:{
						switch($_REQUEST['data']['PARAMS']['MESSAGE']){
							case 'Найти вакансии':{
								$result = restCommand('imbot.message.add', [
										"DIALOG_ID" => $_REQUEST['data']['PARAMS']['DIALOG_ID'],
										"MESSAGE"   => 'Чтобы я могла помочь тебе найти подходящую вакансию, пожалуйста, напиши номер вакансии, которая тебя заинтересовала:[br][send=1]1.Продавец-консультант[/send][br][send=2]2.Оператор-кассир[/send][br][send=3]3.Грузчик-комплектовщик[/send][br][send=4]4.Кладовщик[/send][br][send=5]5.Нужной вакансии нет в списке[/send]',
								], $_REQUEST["auth"]);
								$stage++;
								next_stage($_REQUEST['data']['PARAMS']['DIALOG_ID'], $stage);
								break;}
							case 'Информация о компании':{
								$result = restCommand('imbot.message.add', [
										"DIALOG_ID" => $_REQUEST['data']['PARAMS']['DIALOG_ID'],
										"MESSAGE"   => '"Стройландия" — одна из крупнейших сетевых компаний российского рынка строительных и отделочных материалов и товаров для дома. В 1996 году мы открыли первый магазин. На сегоднящний день наш магазины успешно работают в Оренбургской области, Башкортостане, Татарстане, Удмуртии, Чувашии, Саратовской, Белгородской, Липецкой области. «Стройландия» - это эффективная команда единомышленников. В компании работает более 2000 сотрудников. Мы стремимся быть одной из тех компаний, которые заботятся о своих сотрудниках: официальное оформление, стабильная и достойная заработная плата, удобные графики работы, стремительный карьерный рост, социальные гарантии, комфортные условия работы - все это делают работу в «Стройландии» востребованной и престижной! Мы не просто продаем товар, мы стремимся делать это на высочайшем уровне! Без грамотных рук, профессионализма, искреннего участия Компания «Стройландия» невозможна. В каждом из наших подразделений работает множество людей: директора, менеджеры, продавцы, кассиры, мерчендайзеры, экономисты, программисты.И труд каждого человека – драгоценен. Вы тоже можете стать частью общего Успеха![br][send=Найти вакансии]Найти вакансии[/send][br][send=Досвидания]Досвидания[/send]',
								], $_REQUEST["auth"]);
								break;}
							case 'Досвидания':{
								$result = restCommand('imbot.message.add', [
										"DIALOG_ID" => $_REQUEST['data']['PARAMS']['DIALOG_ID'],
										"MESSAGE"   => 'Досвидания',
								], $_REQUEST["auth"]);
								$stage=1;
								next_stage($_REQUEST['data']['PARAMS']['DIALOG_ID'], $stage);
								break;
							}
							default:{
								$result = restCommand('imbot.message.add', [
										"DIALOG_ID" => $_REQUEST['data']['PARAMS']['DIALOG_ID'],
										"MESSAGE"   => 'Вы хотите подобрать подходящую вакансию или сначала подробнее узнать о компании?[br][send=Найти вакансии]Найти вакансии[/send][br][send=Информация о компании]Информация о компании[/send]',
								], $_REQUEST["auth"]);
							}
						}
						
						
						break;
					}
					case 4:{
						switch($_REQUEST['data']['PARAMS']['MESSAGE']){
							case "1":{
								set_vacancy('Продавец-консультант',$_REQUEST['data']['PARAMS']['DIALOG_ID']);
								$result = restCommand('imbot.message.add', [
										"DIALOG_ID" => $_REQUEST['data']['PARAMS']['DIALOG_ID'],
										"MESSAGE"   => 'ПРОДАВЕЦ – КОНСУЛЬТАНТ[br]Итак, на позиции продавца-консультанта Тебе предстоит:[br]- консультировать покупателей по ассортименту магазина[br]- выкладывать товар на витрину согласно стандартам мерчендайзинга[br]- следить за наличием товара на полках[br]- оформлять документы для отпуска товара[br]- участвовать в ревизии товара[br][send=Вакансия интересна]Вакансия интересна[/send][br][send=Назад]Назад[/send][br][send=Досвидания]Досвидания[/send]',
								], $_REQUEST["auth"]);
								break;}
							case "2":{
								set_vacancy('Оператор-кассир',$_REQUEST['data']['PARAMS']['DIALOG_ID']);
								$result = restCommand('imbot.message.add', [
										"DIALOG_ID" => $_REQUEST['data']['PARAMS']['DIALOG_ID'],
										"MESSAGE"   => 'ОПЕРАТОР-КАССИР[br]Итак, на позиции оператора-кассира Тебе предстоит:[br]- рассчитывать покупателей кассе[br]- проводить наличный расчет[br]- проводить расчет по банковским картам[br]- оформлять возврата товара[br][send=Вакансия интересна]Вакансия интересна[/send][br][send=Назад]Назад[/send][br][send=Досвидания]Досвидания[/send]',
								], $_REQUEST["auth"]);
								break;}
							case "3":{
								set_vacancy('Кладовщик',$_REQUEST['data']['PARAMS']['DIALOG_ID']);
								$result = restCommand('imbot.message.add', [
										"DIALOG_ID" => $_REQUEST['data']['PARAMS']['DIALOG_ID'],
										"MESSAGE"   => 'КЛАДОВЩИК[br]Итак, на позиции кладовщика Тебе предстоит:[br]- принимать товара на склад[br]- вести учета складских операций в программе  1С[br]- контролировать  погрузку/выгрузку товара грузчиками[br]- соблюдать режимы хранения[br][send=Вакансия интересна]Вакансия интересна[/send][br][send=Назад]Назад[/send][br][send=Досвидания]Досвидания[/send]',
								], $_REQUEST["auth"]);
								break;}
							case "4":{
								set_vacancy('Грузчик-комплектовщик',$_REQUEST['data']['PARAMS']['DIALOG_ID']);
								$result = restCommand('imbot.message.add', [
										"DIALOG_ID" => $_REQUEST['data']['PARAMS']['DIALOG_ID'],
										"MESSAGE"   => 'ГРУЗЧИК-КОМПЛЕКТОВЩИК[br]Итак, на позиции грузчика-комплектовщика Тебе предстоит:[br]- разгружать фуры с товаром (стройматериалы)[br]- помогать покупателям загружать товар в автомобиль[br]- сортировать и перемещать вручную товара на складе[br][send=Вакансия интересна]Вакансия интересна[/send][br][send=Назад]Назад[/send][br][send=Досвидания]Досвидания[/send]',
								], $_REQUEST["auth"]);
								break;}
							case "5":{
								$result = restCommand('imbot.message.add', [
										"DIALOG_ID" => $_REQUEST['data']['PARAMS']['DIALOG_ID'],
										"MESSAGE"   => 'Пройдите пожалуйста по этой ссылке и заполните [URL=https://orenburg.str.st/career/resume/]резюме[/URL]',
								], $_REQUEST["auth"]);
								break;}
							case "Вакансия интересна":{
								$result = restCommand('imbot.message.add', [
										"DIALOG_ID" => $_REQUEST['data']['PARAMS']['DIALOG_ID'],
										"MESSAGE"   => 'Напиши, пожалуйста, имя и фамилию, чтобы я могла представить тебя сотрудникам "Стройландия". Например Иванов Иван',
								], $_REQUEST["auth"]);
								$stage++;
								next_stage($_REQUEST['data']['PARAMS']['DIALOG_ID'], $stage);
								break;}
							case "Назад":{
								$result = restCommand('imbot.message.add', [
										"DIALOG_ID" => $_REQUEST['data']['PARAMS']['DIALOG_ID'],
										"MESSAGE"   => 'Чтобы я могла помочь тебе найти подходящую вакансию, пожалуйста, напиши номер вакансии, которая тебя заинтересовала:[br][send=1]1.Продавец-консультант[/send][br][send=2]2.Оператор-кассир[/send][br][send=3]3.Грузчик-комплектовщик[/send][br][send=4]4.Кладовщик[/send][br][send=5]5.Нужной вакансии нет в списке[/send]',
								], $_REQUEST["auth"]);
								break;}
							case "Досвидания":{
								$result = restCommand('imbot.message.add', [
										"DIALOG_ID" => $_REQUEST['data']['PARAMS']['DIALOG_ID'],
										"MESSAGE"   => 'Досвидания',
								], $_REQUEST["auth"]);
								$stage=1;
								next_stage($_REQUEST['data']['PARAMS']['DIALOG_ID'], $stage);
								break;}
							default:{
								$result = restCommand('imbot.message.add', [
										"DIALOG_ID" => $_REQUEST['data']['PARAMS']['DIALOG_ID'],
										"MESSAGE"   => 'Чтобы я могла помочь тебе найти подходящую вакансию, пожалуйста, напиши номер вакансии, которая тебя заинтересовала:[br][send=1]1.Продавец-консультант[/send][br][send=2]2.Оператор-кассир[/send][br][send=3]3.Грузчик-комплектовщик[/send][br][send=4]4.Кладовщик[/send][br][send=5]5.Нужной вакансии нет в списке[/send]',
								], $_REQUEST["auth"]);
							}
							
						}
						break;
					}
					case 5:{
						switch($_REQUEST['data']['PARAMS']['MESSAGE']){
							case "1":{
								$name = get_name($_REQUEST['data']['PARAMS']['DIALOG_ID']);
								$result = restCommand('imbot.message.add', [
										"DIALOG_ID" => $_REQUEST['data']['PARAMS']['DIALOG_ID'],
										"MESSAGE"   => $name.' введи свой номер телефона, чтобы быть на связи. Формат телефона:[br] +7 (_ _ _ )_ _ _ _ _ _ _',
								], $_REQUEST["auth"]);
								$stage++;
								
								next_stage($_REQUEST['data']['PARAMS']['DIALOG_ID'], $stage);
								break;}
							case "2":{
								$result = restCommand('imbot.message.add', [
										"DIALOG_ID" => $_REQUEST['data']['PARAMS']['DIALOG_ID'],
										"MESSAGE"   => 'Напиши, пожалуйста, фамилию и имя, чтобы я могла представить тебя сотрудникам "Стройландия". Например Иванов Иван',
								], $_REQUEST["auth"]);
								break;}
							default:{
								$fio = explode(" ", trim($_REQUEST['data']['PARAMS']['MESSAGE']));
								if(count($fio) == 2){
									$last_name = $fio[0];
									$first_name = $fio[1];
									set_name($last_name, $first_name, $_REQUEST['data']['PARAMS']['DIALOG_ID']);
									$result = restCommand('imbot.message.add', [
											"DIALOG_ID" => $_REQUEST['data']['PARAMS']['DIALOG_ID'],
											"MESSAGE"   => "Я правильно поняла что Вас зовут ".$last_name." ".$first_name."[br] [send=1]1.Да[/send][br][send=2]2.Нет, в имени ошибка[/send] ",
									], $_REQUEST["auth"]);
									
								}else{
									$result = restCommand('imbot.message.add', [
											"DIALOG_ID" => $_REQUEST['data']['PARAMS']['DIALOG_ID'],
											"MESSAGE"   => 'Не введены фамилия или имя. Напиши, пожалуйста, имя и фамилию, чтобы я могла представить тебя сотрудникам "Стройландия". Например Иванов Иван',
									], $_REQUEST["auth"]);
								}
							}
						}
						
						
						break;
					}
					case 6:{
						switch($_REQUEST['data']['PARAMS']['MESSAGE']){
							case "1":{
								$name = get_name($_REQUEST['data']['PARAMS']['DIALOG_ID']);
								$result = restCommand('imbot.message.add', [
										"DIALOG_ID" => $_REQUEST['data']['PARAMS']['DIALOG_ID'],
										"MESSAGE"   => 'Пожалуйста, '.$name.' напиши номер города, в котором ты ищешь работу:[br][send=1]1.Оренбург[/send][br][send=2]2.Уфа[/send][br][send=3]3.Липецк[/send][br][send=4]4.Ижевск[/send][br][send=5]5.Белгород[/send][br][send=6]6.Старый Оскол[/send][br][send=7]7.Саратов[/send][br][send=8]8.Энгельс[/send][br][send=9]9.Балаково[/send][br][send=10]10.Нижнекамск[/send][br][send=11]11.Альметьевск[/send][br][send=12]12.Лениногорск[/send][br][send=13]13.Набережные Челны[/send][br][send=14]14.Чебоксары[/send][br][send=15]15.Новочебоксарск[/send][br][send=16]16.Орск[/send][br][send=17]17.Бузулук[/send][br][send=18]18.Новотроицк[/send][br][send=19]19.Стерлитамак[/send][br][send=10]20.Нефтекамск[/send][br][send=21]21.Салават[/send][br][send=22]22.Ишимбай[/send][br][send=23]23.Мелеуз[/send][br][send=24]24.Октябрьский[/send][br][send=25]25.Туймазы[/send]',
								], $_REQUEST["auth"]);
								$stage++;
								next_stage($_REQUEST['data']['PARAMS']['DIALOG_ID'], $stage);
								break;}
							case "2":{
								$result = restCommand('imbot.message.add', [
										"DIALOG_ID" => $_REQUEST['data']['PARAMS']['DIALOG_ID'],
										"MESSAGE"   => $name.' введи свой номер телефона, чтобы быть на связи. Формат телефона:[br] +7 (_ _ _ )_ _ _ _ _ _ _',
								], $_REQUEST["auth"]);
								break;
							}
							default:{
								if(validate_phone_number($_REQUEST['data']['PARAMS']['MESSAGE'])){
									set_phone($_REQUEST['data']['PARAMS']['MESSAGE'], $_REQUEST['data']['PARAMS']['DIALOG_ID']);
									$result = restCommand('imbot.message.add', [
											"DIALOG_ID" => $_REQUEST['data']['PARAMS']['DIALOG_ID'],
											"MESSAGE"   => "Проверьте правильно ли набран номер: ".$_REQUEST['data']['PARAMS']['MESSAGE']."[br] [send=1]1.Да[/send][br][send=2]2.Нет, в номере ошибка[/send] ",
									], $_REQUEST["auth"]);
								}else{
									$result = restCommand('imbot.message.add', [
											"DIALOG_ID" => $_REQUEST['data']['PARAMS']['DIALOG_ID'],
											"MESSAGE"   => $name.' введи свой номер телефона, чтобы быть на связи. Формат телефона:[br] +7 (_ _ _ )_ _ _ _ _ _ _',
									], $_REQUEST["auth"]);
								}
								
								break;}
						}
						break;
					}
					case 7:{
						switch($_REQUEST['data']['PARAMS']['MESSAGE']){
							case "1":{
								set_city('Оренбург', $_REQUEST['data']['PARAMS']['DIALOG_ID']);
								
								$result = restCommand('imbot.message.add', [
										"DIALOG_ID" => $_REQUEST['data']['PARAMS']['DIALOG_ID'],
										"MESSAGE"   => 'Какое у Тебя гражданство?[br][send=1]1.Российская федерация[/send][br][send=2]2.Другое[/send]',
								], $_REQUEST["auth"]);
								
								$stage++;
								next_stage($_REQUEST['data']['PARAMS']['DIALOG_ID'], $stage);
								break;}
							case "2":{
								set_city('Уфа', $_REQUEST['data']['PARAMS']['DIALOG_ID']);
								
								$result = restCommand('imbot.message.add', [
										"DIALOG_ID" => $_REQUEST['data']['PARAMS']['DIALOG_ID'],
										"MESSAGE"   => 'Какое у Тебя гражданство?[br][send=1]1.Российская федерация[/send][br][send=2]2.Другое[/send]',
								], $_REQUEST["auth"]);
								
								$stage++;
								next_stage($_REQUEST['data']['PARAMS']['DIALOG_ID'], $stage);
								break;}
							case "3":{
								set_city('Липецк', $_REQUEST['data']['PARAMS']['DIALOG_ID']);
								
								$result = restCommand('imbot.message.add', [
										"DIALOG_ID" => $_REQUEST['data']['PARAMS']['DIALOG_ID'],
										"MESSAGE"   => 'Какое у Тебя гражданство?[br][send=1]1.Российская федерация[/send][br][send=2]2.Другое[/send]',
								], $_REQUEST["auth"]);
								
								$stage++;
								next_stage($_REQUEST['data']['PARAMS']['DIALOG_ID'], $stage);
								break;}
							case "4":{
								set_city('Ижевск', $_REQUEST['data']['PARAMS']['DIALOG_ID']);
								
								$result = restCommand('imbot.message.add', [
										"DIALOG_ID" => $_REQUEST['data']['PARAMS']['DIALOG_ID'],
										"MESSAGE"   => 'Какое у Тебя гражданство?[br][send=1]1.Российская федерация[/send][br][send=2]2.Другое[/send]',
								], $_REQUEST["auth"]);
								
								$stage++;
								next_stage($_REQUEST['data']['PARAMS']['DIALOG_ID'], $stage);
								break;}
							case "5":{
								set_city('Белгород', $_REQUEST['data']['PARAMS']['DIALOG_ID']);
								
								$result = restCommand('imbot.message.add', [
										"DIALOG_ID" => $_REQUEST['data']['PARAMS']['DIALOG_ID'],
										"MESSAGE"   => 'Какое у Тебя гражданство?[br][send=1]1.Российская федерация[/send][br][send=2]2.Другое[/send]',
								], $_REQUEST["auth"]);
								
								$stage++;
								next_stage($_REQUEST['data']['PARAMS']['DIALOG_ID'], $stage);
								break;}
							case "6":{
								set_city('Старый Оскол', $_REQUEST['data']['PARAMS']['DIALOG_ID']);
								
								$result = restCommand('imbot.message.add', [
										"DIALOG_ID" => $_REQUEST['data']['PARAMS']['DIALOG_ID'],
										"MESSAGE"   => 'Какое у Тебя гражданство?[br][send=1]1.Российская федерация[/send][br][send=2]2.Другое[/send]',
								], $_REQUEST["auth"]);
								
								$stage++;
								next_stage($_REQUEST['data']['PARAMS']['DIALOG_ID'], $stage);
								break;}
							case "7":{
								set_city('Саратов', $_REQUEST['data']['PARAMS']['DIALOG_ID']);
								
								$result = restCommand('imbot.message.add', [
										"DIALOG_ID" => $_REQUEST['data']['PARAMS']['DIALOG_ID'],
										"MESSAGE"   => 'Какое у Тебя гражданство?[br][send=1]1.Российская федерация[/send][br][send=2]2.Другое[/send]',
								], $_REQUEST["auth"]);
								
								$stage++;
								next_stage($_REQUEST['data']['PARAMS']['DIALOG_ID'], $stage);
								break;}
							case "8":{
								set_city('Энгельс', $_REQUEST['data']['PARAMS']['DIALOG_ID']);
								
								$result = restCommand('imbot.message.add', [
										"DIALOG_ID" => $_REQUEST['data']['PARAMS']['DIALOG_ID'],
										"MESSAGE"   => 'Какое у Тебя гражданство?[br][send=1]1.Российская федерация[/send][br][send=2]2.Другое[/send]',
								], $_REQUEST["auth"]);
								
								$stage++;
								next_stage($_REQUEST['data']['PARAMS']['DIALOG_ID'], $stage);
								break;}
							case "9":{
								set_city('Балаково', $_REQUEST['data']['PARAMS']['DIALOG_ID']);
								
								$result = restCommand('imbot.message.add', [
										"DIALOG_ID" => $_REQUEST['data']['PARAMS']['DIALOG_ID'],
										"MESSAGE"   => 'Какое у Тебя гражданство?[br][send=1]1.Российская федерация[/send][br][send=2]2.Другое[/send]',
								], $_REQUEST["auth"]);
								
								$stage++;
								next_stage($_REQUEST['data']['PARAMS']['DIALOG_ID'], $stage);
								break;}
							case "10":{
								set_city('Нижнекамск', $_REQUEST['data']['PARAMS']['DIALOG_ID']);
								
								$result = restCommand('imbot.message.add', [
										"DIALOG_ID" => $_REQUEST['data']['PARAMS']['DIALOG_ID'],
										"MESSAGE"   => 'Какое у Тебя гражданство?[br][send=1]1.Российская федерация[/send][br][send=2]2.Другое[/send]',
								], $_REQUEST["auth"]);
								
								$stage++;
								next_stage($_REQUEST['data']['PARAMS']['DIALOG_ID'], $stage);
								break;}
							case "11":{
								set_city('Альметьевск', $_REQUEST['data']['PARAMS']['DIALOG_ID']);
								
								$result = restCommand('imbot.message.add', [
										"DIALOG_ID" => $_REQUEST['data']['PARAMS']['DIALOG_ID'],
										"MESSAGE"   => 'Какое у Тебя гражданство?[br][send=1]1.Российская федерация[/send][br][send=2]2.Другое[/send]',
								], $_REQUEST["auth"]);
								
								$stage++;
								next_stage($_REQUEST['data']['PARAMS']['DIALOG_ID'], $stage);
								break;}
							case "12":{
								set_city('Лениногорск', $_REQUEST['data']['PARAMS']['DIALOG_ID']);
								
								$result = restCommand('imbot.message.add', [
										"DIALOG_ID" => $_REQUEST['data']['PARAMS']['DIALOG_ID'],
										"MESSAGE"   => 'Какое у Тебя гражданство?[br][send=1]1.Российская федерация[/send][br][send=2]2.Другое[/send]',
								], $_REQUEST["auth"]);
								
								$stage++;
								next_stage($_REQUEST['data']['PARAMS']['DIALOG_ID'], $stage);
								break;}
							case "13":{
								set_city('Набережные Челны', $_REQUEST['data']['PARAMS']['DIALOG_ID']);
								
								$result = restCommand('imbot.message.add', [
										"DIALOG_ID" => $_REQUEST['data']['PARAMS']['DIALOG_ID'],
										"MESSAGE"   => 'Какое у Тебя гражданство?[br][send=1]1.Российская федерация[/send][br][send=2]2.Другое[/send]',
								], $_REQUEST["auth"]);
								
								$stage++;
								next_stage($_REQUEST['data']['PARAMS']['DIALOG_ID'], $stage);
								break;}
							case "14":{
								set_city('Чебоксары', $_REQUEST['data']['PARAMS']['DIALOG_ID']);
								
								$result = restCommand('imbot.message.add', [
										"DIALOG_ID" => $_REQUEST['data']['PARAMS']['DIALOG_ID'],
										"MESSAGE"   => 'Какое у Тебя гражданство?[br][send=1]1.Российская федерация[/send][br][send=2]2.Другое[/send]',
								], $_REQUEST["auth"]);
								
								$stage++;
								next_stage($_REQUEST['data']['PARAMS']['DIALOG_ID'], $stage);
								break;}
							case "15":{
								set_city('Новочебоксарск', $_REQUEST['data']['PARAMS']['DIALOG_ID']);
								
								$result = restCommand('imbot.message.add', [
										"DIALOG_ID" => $_REQUEST['data']['PARAMS']['DIALOG_ID'],
										"MESSAGE"   => 'Какое у Тебя гражданство?[br][send=1]1.Российская федерация[/send][br][send=2]2.Другое[/send]',
								], $_REQUEST["auth"]);
								
								$stage++;
								next_stage($_REQUEST['data']['PARAMS']['DIALOG_ID'], $stage);
								break;}
							case "16":{
								set_city('Орск', $_REQUEST['data']['PARAMS']['DIALOG_ID']);
								
								$result = restCommand('imbot.message.add', [
										"DIALOG_ID" => $_REQUEST['data']['PARAMS']['DIALOG_ID'],
										"MESSAGE"   => 'Какое у Тебя гражданство?[br][send=1]1.Российская федерация[/send][br][send=2]2.Другое[/send]',
								], $_REQUEST["auth"]);
								
								$stage++;
								next_stage($_REQUEST['data']['PARAMS']['DIALOG_ID'], $stage);
								break;}
							case "17":{
								set_city('Бузулук', $_REQUEST['data']['PARAMS']['DIALOG_ID']);
								
								$result = restCommand('imbot.message.add', [
										"DIALOG_ID" => $_REQUEST['data']['PARAMS']['DIALOG_ID'],
										"MESSAGE"   => 'Какое у Тебя гражданство?[br][send=1]1.Российская федерация[/send][br][send=2]2.Другое[/send]',
								], $_REQUEST["auth"]);
								
								$stage++;
								next_stage($_REQUEST['data']['PARAMS']['DIALOG_ID'], $stage);
								break;}
							case "18":{
								set_city('Новотроицк', $_REQUEST['data']['PARAMS']['DIALOG_ID']);
								
								$result = restCommand('imbot.message.add', [
										"DIALOG_ID" => $_REQUEST['data']['PARAMS']['DIALOG_ID'],
										"MESSAGE"   => 'Какое у Тебя гражданство?[br][send=1]1.Российская федерация[/send][br][send=2]2.Другое[/send]',
								], $_REQUEST["auth"]);
								
								$stage++;
								next_stage($_REQUEST['data']['PARAMS']['DIALOG_ID'], $stage);
								break;}
							case "19":{
								set_city('Стерлитамак', $_REQUEST['data']['PARAMS']['DIALOG_ID']);
								
								$result = restCommand('imbot.message.add', [
										"DIALOG_ID" => $_REQUEST['data']['PARAMS']['DIALOG_ID'],
										"MESSAGE"   => 'Какое у Тебя гражданство?[br][send=1]1.Российская федерация[/send][br][send=2]2.Другое[/send]',
								], $_REQUEST["auth"]);
								
								$stage++;
								next_stage($_REQUEST['data']['PARAMS']['DIALOG_ID'], $stage);
								break;}
							case "20":{
								set_city('Нефтекамск', $_REQUEST['data']['PARAMS']['DIALOG_ID']);
								
								$result = restCommand('imbot.message.add', [
										"DIALOG_ID" => $_REQUEST['data']['PARAMS']['DIALOG_ID'],
										"MESSAGE"   => 'Какое у Тебя гражданство?[br][send=1]1.Российская федерация[/send][br][send=2]2.Другое[/send]',
								], $_REQUEST["auth"]);
								
								$stage++;
								next_stage($_REQUEST['data']['PARAMS']['DIALOG_ID'], $stage);
								break;}
							case "21":{
								set_city('Салават', $_REQUEST['data']['PARAMS']['DIALOG_ID']);
								
								$result = restCommand('imbot.message.add', [
										"DIALOG_ID" => $_REQUEST['data']['PARAMS']['DIALOG_ID'],
										"MESSAGE"   => 'Какое у Тебя гражданство?[br][send=1]1.Российская федерация[/send][br][send=2]2.Другое[/send]',
								], $_REQUEST["auth"]);
								
								$stage++;
								next_stage($_REQUEST['data']['PARAMS']['DIALOG_ID'], $stage);
								break;}
							case "22":{
								set_city('Ишимбай', $_REQUEST['data']['PARAMS']['DIALOG_ID']);
								
								$result = restCommand('imbot.message.add', [
										"DIALOG_ID" => $_REQUEST['data']['PARAMS']['DIALOG_ID'],
										"MESSAGE"   => 'Какое у Тебя гражданство?[br][send=1]1.Российская федерация[/send][br][send=2]2.Другое[/send]',
								], $_REQUEST["auth"]);
								
								$stage++;
								next_stage($_REQUEST['data']['PARAMS']['DIALOG_ID'], $stage);
								break;}
							case "23":{
								set_city('Мелеуз', $_REQUEST['data']['PARAMS']['DIALOG_ID']);
								
								$result = restCommand('imbot.message.add', [
										"DIALOG_ID" => $_REQUEST['data']['PARAMS']['DIALOG_ID'],
										"MESSAGE"   => 'Какое у Тебя гражданство?[br][send=1]1.Российская федерация[/send][br][send=2]2.Другое[/send]',
								], $_REQUEST["auth"]);
								
								$stage++;
								next_stage($_REQUEST['data']['PARAMS']['DIALOG_ID'], $stage);
								break;}
							case "24":{
								set_city('Октябрьский', $_REQUEST['data']['PARAMS']['DIALOG_ID']);
								
								$result = restCommand('imbot.message.add', [
										"DIALOG_ID" => $_REQUEST['data']['PARAMS']['DIALOG_ID'],
										"MESSAGE"   => 'Какое у Тебя гражданство?[br][send=1]1.Российская федерация[/send][br][send=2]2.Другое[/send]',
								], $_REQUEST["auth"]);
								
								$stage++;
								next_stage($_REQUEST['data']['PARAMS']['DIALOG_ID'], $stage);
								break;}
							case "25":{
								set_city('Туймазы', $_REQUEST['data']['PARAMS']['DIALOG_ID']);
								
								$result = restCommand('imbot.message.add', [
										"DIALOG_ID" => $_REQUEST['data']['PARAMS']['DIALOG_ID'],
										"MESSAGE"   => 'Какое у Тебя гражданство?[br][send=1]1.Российская федерация[/send][br][send=2]2.Другое[/send]',
								], $_REQUEST["auth"]);
								$stage++;
								next_stage($_REQUEST['data']['PARAMS']['DIALOG_ID'], $stage);
								break;}
							default:{
								$name = get_name($_REQUEST['data']['PARAMS']['DIALOG_ID']);
								$result = restCommand('imbot.message.add', [
										"DIALOG_ID" => $_REQUEST['data']['PARAMS']['DIALOG_ID'],
										"MESSAGE"   => 'Пожалуйста, '.$name.' напиши номер города, в котором ты ищешь работу:[br][send=1]1.Оренбург[/send][br][send=2]2.Уфа[/send][br][send=3]3.Липецк[/send][br][send=4]4.Ижевск[/send][br][send=5]5.Белгород[/send][br][send=6]6.Старый Оскол[/send][br][send=7]7.Саратов[/send][br][send=8]8.Энгельс[/send][br][send=9]9.Балаково[/send][br][send=10]10.Нижнекамск[/send][br][send=11]11.Альметьевск[/send][br][send=12]12.Лениногорск[/send][br][send=13]13.Набережные Челны[/send][br][send=14]14.Чебоксары[/send][br][send=15]15.Новочебоксарск[/send][br][send=16]16.Орск[/send][br][send=17]17.Бузулук[/send][br][send=18]18.Новотроицк[/send][br][send=19]19.Стерлитамак[/send][br][send=10]20.Нефтекамск[/send][br][send=21]21.Салават[/send][br][send=22]22.Ишимбай[/send][br][send=23]23.Мелеуз[/send][br][send=24]24.Октябрьский[/send][br][send=25]25.Туймазы[/send]',
								], $_REQUEST["auth"]);
							}
						}
						break;
					}
					case 8:{
						switch($_REQUEST['data']['PARAMS']['MESSAGE']){
							case "1":{
								set_grag('Российская Федерация', $_REQUEST['data']['PARAMS']['DIALOG_ID']);
								$result = restCommand('imbot.message.add', [
										"DIALOG_ID" => $_REQUEST['data']['PARAMS']['DIALOG_ID'],
										"MESSAGE"   => 'Вы не ошиблись и у Вас действительно Российское гражданство?[br][send=Да]Да действительно[/send][br][send=Нет]Нет ошибся[/send]',
								], $_REQUEST["auth"]);
								
								break;}
							case "2":{
								set_grag('Другое', $_REQUEST['data']['PARAMS']['DIALOG_ID']);
								$result = restCommand('imbot.message.add', [
										"DIALOG_ID" => $_REQUEST['data']['PARAMS']['DIALOG_ID'],
										"MESSAGE"   => 'Вы не ошиблись и у Вас действительно Российское гражданство?[br][send=Да]Да действительно[/send][br][send=Нет]Нет ошибся[/send]',
								], $_REQUEST["auth"]);
								break;}
							case "Да":{
								$result = restCommand('imbot.message.add', [
										"DIALOG_ID" => $_REQUEST['data']['PARAMS']['DIALOG_ID'],
										"MESSAGE"   => 'Сколько Тебе лет? Напиши, пожалуйста, цифрой',
								], $_REQUEST["auth"]);
								$stage++;
								next_stage($_REQUEST['data']['PARAMS']['DIALOG_ID'], $stage);
								break;}
							case "Нет":{
								$result = restCommand('imbot.message.add', [
										"DIALOG_ID" => $_REQUEST['data']['PARAMS']['DIALOG_ID'],
										"MESSAGE"   => 'Какое у Тебя гражданство?[br][send=1]1.Российская федерация[/send][br][send=2]2.Другое[/send]',
								], $_REQUEST["auth"]);
								break;}
							default:{
								$result = restCommand('imbot.message.add', [
										"DIALOG_ID" => $_REQUEST['data']['PARAMS']['DIALOG_ID'],
										"MESSAGE"   => 'Какое у Тебя гражданство?[br][send=1]1.Российская федерация[/send][br][send=2]2.Другое[/send]',
								], $_REQUEST["auth"]);
								break;}
						}
						break;
					}
					case 9:{
						switch($_REQUEST['data']['PARAMS']['MESSAGE']){
							case "Да":{
								$result = restCommand('imbot.message.add', [
										"DIALOG_ID" => $_REQUEST['data']['PARAMS']['DIALOG_ID'],
										"MESSAGE"   => 'Что в работе для Тебя важнее всего, укажи цифру (выбрать можно только один вариант):[br][send=1]1.Коллектив[/send][br][send=2]2.Заработная плата[/send][br][send=3]3.График[/send][br][send=4]4.Карьерный рост[/send][br][send=5]5.Хорошие условия труда[/send][br][send=6]6.Похвала со стороны руководства[/send][br][send=7]7.Имидж компании[/send]',
								], $_REQUEST["auth"]);
								$stage++;
								next_stage($_REQUEST['data']['PARAMS']['DIALOG_ID'], $stage);
								break;}
							case "Нет":{
								$result = restCommand('imbot.message.add', [
										"DIALOG_ID" => $_REQUEST['data']['PARAMS']['DIALOG_ID'],
										"MESSAGE"   => 'Сколько Тебе лет? Напиши, пожалуйста, цифрой',
								], $_REQUEST["auth"]);
								break;}
							default:{
								if($age=intval($_REQUEST['data']['PARAMS']['MESSAGE'])){
									set_age($age, $_REQUEST['data']['PARAMS']['DIALOG_ID']);
									$result = restCommand('imbot.message.add', [
											"DIALOG_ID" => $_REQUEST['data']['PARAMS']['DIALOG_ID'],
											"MESSAGE"   => "Вам действительно ".$age." лет?[br] [send=Да]Да[/send][br][send=Нет]Ой, ошибся[/send] ",
									], $_REQUEST["auth"]);
								}else{
									$result = restCommand('imbot.message.add', [
											"DIALOG_ID" => $_REQUEST['data']['PARAMS']['DIALOG_ID'],
											"MESSAGE"   => 'Сколько Тебе лет? Напиши, пожалуйста, цифрой',
									], $_REQUEST["auth"]);
								}
							}
						}
						
						break;
					}
					case 10:{
						switch($_REQUEST['data']['PARAMS']['MESSAGE']){
							case "1":{
								$vvr = 'Коллектив';
								set_vvr($vvr, $_REQUEST['data']['PARAMS']['DIALOG_ID']);
								$result = restCommand('imbot.message.add', [
										"DIALOG_ID" => $_REQUEST['data']['PARAMS']['DIALOG_ID'],
										"MESSAGE"   => 'На какую заработную плату Ты рассчитываешь? Напиши, пожалуйста, сумму',
								], $_REQUEST["auth"]);
								$stage++;
								next_stage($_REQUEST['data']['PARAMS']['DIALOG_ID'], $stage);
								
								break;}
							case "2":{
								$vvr = 'Заработная плата';
								set_vvr($vvr, $_REQUEST['data']['PARAMS']['DIALOG_ID']);
								$result = restCommand('imbot.message.add', [
										"DIALOG_ID" => $_REQUEST['data']['PARAMS']['DIALOG_ID'],
										"MESSAGE"   => 'На какую заработную плату Ты рассчитываешь? Напиши, пожалуйста, сумму',
								], $_REQUEST["auth"]);
								$stage++;
								next_stage($_REQUEST['data']['PARAMS']['DIALOG_ID'], $stage);
								break;}
							case "3":{
								$vvr = 'График';
								set_vvr($vvr, $_REQUEST['data']['PARAMS']['DIALOG_ID']);
								$result = restCommand('imbot.message.add', [
										"DIALOG_ID" => $_REQUEST['data']['PARAMS']['DIALOG_ID'],
										"MESSAGE"   => 'На какую заработную плату Ты рассчитываешь? Напиши, пожалуйста, сумму',
								], $_REQUEST["auth"]);
								$stage++;
								next_stage($_REQUEST['data']['PARAMS']['DIALOG_ID'], $stage);
								break;}
							case "4":{
								$vvr = 'Карьерный рост';
								set_vvr($vvr, $_REQUEST['data']['PARAMS']['DIALOG_ID']);
								$result = restCommand('imbot.message.add', [
										"DIALOG_ID" => $_REQUEST['data']['PARAMS']['DIALOG_ID'],
										"MESSAGE"   => 'На какую заработную плату Ты рассчитываешь? Напиши, пожалуйста, сумму',
								], $_REQUEST["auth"]);
								$stage++;
								next_stage($_REQUEST['data']['PARAMS']['DIALOG_ID'], $stage);
								break;}
							case "5":{
								$vvr = 'Хорошие условия труда';
								set_vvr($vvr, $_REQUEST['data']['PARAMS']['DIALOG_ID']);
								$result = restCommand('imbot.message.add', [
										"DIALOG_ID" => $_REQUEST['data']['PARAMS']['DIALOG_ID'],
										"MESSAGE"   => 'На какую заработную плату Ты рассчитываешь? Напиши, пожалуйста, сумму',
								], $_REQUEST["auth"]);
								$stage++;
								next_stage($_REQUEST['data']['PARAMS']['DIALOG_ID'], $stage);
								break;}
							case "6":{
								$vvr = 'Похвала со стороны руководства';
								set_vvr($vvr, $_REQUEST['data']['PARAMS']['DIALOG_ID']);
								$result = restCommand('imbot.message.add', [
										"DIALOG_ID" => $_REQUEST['data']['PARAMS']['DIALOG_ID'],
										"MESSAGE"   => 'На какую заработную плату Ты рассчитываешь? Напиши, пожалуйста, сумму',
								], $_REQUEST["auth"]);
								$stage++;
								next_stage($_REQUEST['data']['PARAMS']['DIALOG_ID'], $stage);
								break;}
							case "7":{
								$vvr = 'Имидж компании';
								set_vvr($vvr, $_REQUEST['data']['PARAMS']['DIALOG_ID']);
								$result = restCommand('imbot.message.add', [
										"DIALOG_ID" => $_REQUEST['data']['PARAMS']['DIALOG_ID'],
										"MESSAGE"   => 'На какую заработную плату Ты рассчитываешь? Напиши, пожалуйста, сумму',
								], $_REQUEST["auth"]);
								$stage++;
								next_stage($_REQUEST['data']['PARAMS']['DIALOG_ID'], $stage);
								break;}
							default:{
								$result = restCommand('imbot.message.add', [
										"DIALOG_ID" => $_REQUEST['data']['PARAMS']['DIALOG_ID'],
										"MESSAGE"   => 'Что в работе для Тебя важнее всего, укажи цифру (выбрать можно только один вариант):[br][send=1]Коллектив[/send][br][send=2]заработная плата[/send][br][send=3]график[/send][br][send=4]карьерный рост[/send][br][send=5]хорошие условия труда[/send][br][send=6]похвала со стороны руководства[/send][br][send=7]имидж компании[/send]',
								], $_REQUEST["auth"]);
							}
						}
						break;
					}
					case 11:{
						if($zarplata = intval($_REQUEST['data']['PARAMS']['MESSAGE'])){
							set_zarplata($zarplata,$_REQUEST['data']['PARAMS']['DIALOG_ID']);
							$result = restCommand('imbot.message.add', [
									"DIALOG_ID" => $_REQUEST['data']['PARAMS']['DIALOG_ID'],
									"MESSAGE"   => 'Почему Ты хочешь работать в "Стройландии"?',
							], $_REQUEST["auth"]);
							$stage++;
							next_stage($_REQUEST['data']['PARAMS']['DIALOG_ID'], $stage);
						}else{
							$result = restCommand('imbot.message.add', [
									"DIALOG_ID" => $_REQUEST['data']['PARAMS']['DIALOG_ID'],
									"MESSAGE"   => 'На какую заработную плату Ты рассчитываешь? Напиши, пожалуйста, сумму',
							], $_REQUEST["auth"]);
						}
						break;
					}
					case 12:{
						$name = get_name($_REQUEST['data']['PARAMS']['DIALOG_ID']);
						$pochemu = strip_tags($_REQUEST['data']['PARAMS']['MESSAGE']);
						set_pochemu($pochemu, $_REQUEST['data']['PARAMS']['DIALOG_ID']);
						
						
						$client = get_client($_REQUEST['data']['PARAMS']['DIALOG_ID']);
						
						
						$to  = EMAIL; // кому отправляем
						
						
						// содержание письма
						$subject = "Новый соискатель от бота Ольги";
						$message = '<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><title>Новый соискатель от бота Ольги</title></head><body><h1>Новый Соискатель</h1><ul><li><strong>ФИО: </strong>'.$client['first_name'].' '.$client['last_name'].'</li><li><strong>Возраст: </strong>'.$client['age'].'лет.</li><li><strong>Телефон: </strong>'.$client['phone'].'</li><li><strong>Гражданство: </strong>'.$client['gragdanstvo'].'</li><li><strong>Желаемая вакансия: </strong>'.$client['vacancy'].'</li><li><strong>Город для работы: </strong>'.$client['city'].'</li><li><strong>Важное для работы: </strong>'.$client['vagno_v_rabote'].'</li><li><strong>Желаемая зарплата: </strong>'.$client['zarplata'].'</li><li><strong>Почему я: </strong>'.$client['pochemu'].'</li></ul></body></html>';
						// устанавливаем тип сообщения Content-type, если хотим
						$headers  = 'MIME-Version: 1.0' . "\r\n";
						$headers .= "Content-type: text/html; charset=utf-8 \r\n";
						
						// дополнительные данные
						$headers .= "From: HR BOT OLGA <hr@stroylandiya.ru>\r\n"; // от кого
						mail($to, $subject, $message, $headers); 
						
						if($client['gragdanstvo'] == 'Российская Федерация'){
							if($client['age']>18){
								switch($client['vagno_v_rabote']){
									case 'Карьерный рост':
									case 'График':
									case 'Заработная плата':{
										$result = restCommand('imbot.message.add', [
												"DIALOG_ID" => $_REQUEST['data']['PARAMS']['DIALOG_ID'],
												"MESSAGE"   => 'Отлично, '.$name.'! Первый шаг к успешной карьере в "Стройландия" сделан! Будь на связи, наш специалист свяжется с тобой в течении 3-х дней и пригласит на собеседование к директору магазина.',
										], $_REQUEST["auth"]);
										break;}
									default:{
										$result = restCommand('imbot.message.add', [
												"DIALOG_ID" => $_REQUEST['data']['PARAMS']['DIALOG_ID'],
												"MESSAGE"   => 'Отлично, '.$name.', спасибо за твои ответы! В случае заинтересованности наш специалист свяжется с тобой в течение 5 рабочих дней и назначит дату и формат встречи. Хорошего дня, оставайтесь на связи!',
										], $_REQUEST["auth"]);
									}
								}
							}else{
								$result = restCommand('imbot.message.add', [
										"DIALOG_ID" => $_REQUEST['data']['PARAMS']['DIALOG_ID'],
										"MESSAGE"   => 'Отлично, '.$name.', спасибо за твои ответы! В случае заинтересованности наш специалист свяжется с тобой в течение 5 рабочих дней и назначит дату и формат встречи. Хорошего дня, оставайтесь на связи!',
								], $_REQUEST["auth"]);
							}
						}else{
							$result = restCommand('imbot.message.add', [
									"DIALOG_ID" => $_REQUEST['data']['PARAMS']['DIALOG_ID'],
									"MESSAGE"   => 'Отлично, '.$name.', спасибо за твои ответы! В случае заинтересованности наш специалист свяжется с тобой в течение 5 рабочих дней и назначит дату и формат встречи. Хорошего дня, оставайтесь на связи!',
							], $_REQUEST["auth"]);
						}
						$stage++;
						next_stage($_REQUEST['data']['PARAMS']['DIALOG_ID'], $stage);
						break;
					}
					case 13:{
						$client = get_client($_REQUEST['data']['PARAMS']['DIALOG_ID']);
						if($client['gragdanstvo'] == 'Российская Федерация'){
							if($client['age']>18){
								switch($client['vagno_v_rabote']){
									case 'Карьерный рост':
									case 'График':
									case 'Заработная плата':{
										$result = restCommand('imbot.message.add', [
												"DIALOG_ID" => $_REQUEST['data']['PARAMS']['DIALOG_ID'],
												"MESSAGE"   => 'Отлично, '.$name.'! Первый шаг к успешной карьере в "Стройландия" сделан! Будь на связи, наш специалист свяжется с тобой в течении 3-х дней и пригласит на собеседование к директору магазина.',
										], $_REQUEST["auth"]);
										break;}
									default:{
										$result = restCommand('imbot.message.add', [
												"DIALOG_ID" => $_REQUEST['data']['PARAMS']['DIALOG_ID'],
												"MESSAGE"   => 'Отлично, '.$name.', спасибо за твои ответы! В случае заинтересованности наш специалист свяжется с тобой в течение 5 рабочих дней и назначит дату и формат встречи. Хорошего дня, оставайтесь на связи!',
										], $_REQUEST["auth"]);
									}
								}
							}else{
								$result = restCommand('imbot.message.add', [
										"DIALOG_ID" => $_REQUEST['data']['PARAMS']['DIALOG_ID'],
										"MESSAGE"   => 'Отлично, '.$name.', спасибо за твои ответы! В случае заинтересованности наш специалист свяжется с тобой в течение 5 рабочих дней и назначит дату и формат встречи. Хорошего дня, оставайтесь на связи!',
								], $_REQUEST["auth"]);
							}
						}else{
							$result = restCommand('imbot.message.add', [
									"DIALOG_ID" => $_REQUEST['data']['PARAMS']['DIALOG_ID'],
									"MESSAGE"   => 'Отлично, '.$name.', спасибо за твои ответы! В случае заинтересованности наш специалист свяжется с тобой в течение 5 рабочих дней и назначит дату и формат встречи. Хорошего дня, оставайтесь на связи!',
							], $_REQUEST["auth"]);
						}
					}
					
				}
				
					//itrRun($_REQUEST['auth']['application_token'], $_REQUEST['data']['PARAMS']['DIALOG_ID'], $_REQUEST['data']['PARAMS']['FROM_USER_ID'], $_REQUEST['data']['PARAMS']['MESSAGE']);
	}
	if ($_REQUEST['event'] == 'ONIMBOTJOINCHAT')
	{
		// check the event - authorize this event or not
		if (!isset($appsConfig[$_REQUEST['auth']['application_token']]))
			return false;
			
			if ($_REQUEST['data']['PARAMS']['CHAT_ENTITY_TYPE'] != 'LINES')
				return false;
				
				itrRun($_REQUEST['auth']['application_token'], $_REQUEST['data']['PARAMS']['DIALOG_ID'], $_REQUEST['data']['PARAMS']['USER_ID']);
				itrRun($_REQUEST['auth']['application_token'], $_REQUEST['data']['PARAMS']['DIALOG_ID'], $_REQUEST['data']['PARAMS']['FROM_USER_ID'], 'Привет');
	}
	// receive event "delete chat-bot"
	else if ($_REQUEST['event'] == 'ONIMBOTDELETE')
	{
		// check the event - authorize this event or not
		if (!isset($appsConfig[$_REQUEST['auth']['application_token']]))
			return false;
			
			// unset application variables
			unset($appsConfig[$_REQUEST['auth']['application_token']]);
			
			// save params
			saveParams($appsConfig);
			
			// write debug log
			writeToLog($_REQUEST['event'], 'ImBot unregister');
	}
	// receive event "Application install"
	else if ($_REQUEST['event'] == 'ONAPPINSTALL')
	{
		// handler for events
		$handlerBackUrl = ($_SERVER['SERVER_PORT']==443||$_SERVER["HTTPS"]=="on"? 'https': 'http')."://".$_SERVER['SERVER_NAME'].(in_array($_SERVER['SERVER_PORT'], Array(80, 443))?'':':'.$_SERVER['SERVER_PORT']).$_SERVER['SCRIPT_NAME'];
		
		// If your application supports different localizations
		// use $_REQUEST['data']['LANGUAGE_ID'] to load correct localization
		
		// register new bot
		$result = restCommand('imbot.register', Array(
				'CODE' => 'itrbot',
				'TYPE' => 'O',
				'EVENT_MESSAGE_ADD' => $handlerBackUrl,
				'EVENT_WELCOME_MESSAGE' => $handlerBackUrl,
				'EVENT_BOT_DELETE' => $handlerBackUrl,
				'OPENLINE' => 'Y',
				'PROPERTIES' => Array(
						'NAME' => 'Ольга #'.(count($appsConfig)+1),
						'WORK_POSITION' => "Get ITR menu for you open channel",
						'COLOR' => 'RED',
						)
				), $_REQUEST["auth"]);
		$botId = $result['result'];
		
		$result = restCommand('event.bind', Array(
				'EVENT' => 'OnAppUpdate',
				'HANDLER' => $handlerBackUrl
				), $_REQUEST["auth"]);
		
		// save params
		$appsConfig[$_REQUEST['auth']['application_token']] = Array(
				'BOT_ID' => $botId,
				'LANGUAGE_ID' => $_REQUEST['data']['LANGUAGE_ID'],
				'AUTH' => $_REQUEST['auth'],
				);
		saveParams($appsConfig);
		
		// write debug log
		writeToLog(Array($botId), 'ImBot register');
	}
	// receive event "Application install"
	else if ($_REQUEST['event'] == 'ONAPPUPDATE')
	{
		// check the event - authorize this event or not
		if (!isset($appsConfig[$_REQUEST['auth']['application_token']]))
			return false;
			
			if ($_REQUEST['data']['VERSION'] == 2)
			{
				// Some logic in update event for VERSION 2
				// You can execute any method RestAPI, BotAPI or ChatAPI, for example delete or add a new command to the bot
				/*
				 $result = restCommand('...', Array(
				 '...' => '...',
				 ), $_REQUEST["auth"]);
				 */
				
				/*
				 For example delete "Echo" command:
				 
				 $result = restCommand('imbot.command.unregister', Array(
				 'COMMAND_ID' => $appsConfig[$_REQUEST['auth']['application_token']]['COMMAND_ECHO'],
				 ), $_REQUEST["auth"]);
				 */
			}
			else
			{
				// send answer message
				$result = restCommand('app.info', array(), $_REQUEST["auth"]);
			}
			
			// write debug log
			writeToLog($result, 'ImBot update event');
	}
	
	/**
	 * Run ITR menu
	 *
	 * @param $portalId
	 * @param $dialogId
	 * @param $userId
	 * @param string $message
	 * @return bool
	 */
	function itrRun($portalId, $dialogId, $userId, $message = '')
	{
		
		
		if ($userId <= 0)
			return false;
			
			
			$menu0 = new ItrMenu(0);
			$menu0->setText('Привет, я Ольга, специалист по подбору персонала компании "Стройландия". Я помогу тебе найти работу в нашей компании.');
			$menu0->addItem(1, 'Продолжить', ItrItem::openMenu(1));
			
			$menu1 = new ItrMenu(1);
			$menu1->setText('Вы хотите подобрать подходящую вакансию или сначала подробнее узнать о компании?');
			$menu1->addItem(1, 'Найти вакансии', ItrItem::openMenu(4));
			$menu1->addItem(2, 'Информация о компании', ItrItem::openMenu(2));
			
			
			
			$menu2 = new ItrMenu(2);
			$menu2->setText('"Стройландия" — одна из компаний российского рынка строительных и отделочных материалов и товаров для дома. Мы стремимся быть одной из тех компаний, которые заботятся о своих сотрудниках: официальное оформление, стабильная и достойная заработная плата, удобные графики работы, стремительный карьерный рост, социальные гарантии, комфортные условия работы - все это делают работу в "Стройландия" востребованной и престижной! Мы не просто продаем товар, мы стремимся делать это на высочайшем уровне! И труд каждого человека – драгоценен. Вы тоже можете стать частью общего Успеха!');
			$menu2->addItem(1, 'Найти вакансии',ItrItem::openMenu(4));
			$menu2->addItem(2, 'Досвидания',ItrItem::openMenu(3));
			
			$menu3 = new ItrMenu(3);
			$menu3->setText('Досвидания.');
			//$menu3->setText('И еще раз Досвидания');
			
			
			$menu4 = new ItrMenu(4);
			$menu4->setText('Чтобы я могла помочь тебе найти подходящую вакансию, пожалуйста, напиши номер вакансии, которая тебя заинтересовала:');
			$menu4->addItem(1, 'Продавец-консультант', ItrItem::openMenu(5));
			$menu4->addItem(2, 'Оператор-кассир', ItrItem::openMenu(6));
			$menu4->addItem(3, 'Грузчик-комплектовщик', ItrItem::openMenu(7));
			$menu4->addItem(4, 'Кладовщик', ItrItem::openMenu(8));
			$menu4->addItem(5, 'Нужной вакансии нет в списке', ItrItem::openMenu(9));
			
			$menu5 = new ItrMenu(5);
			$menu5->setText('ПРОДАВЕЦ – КОНСУЛЬТАНТ
Итак, на позиции продавца-консультанта Тебе предстоит:
- консультировать покупателей по ассортименту магазина
- выкладывать товар на витрину согласно стандартам мерчендайзинга
- следить за наличием товара на полках
- оформлять документы для отпуска товара
- участвовать в ревизии товара
');
			$menu5->addItem(1, 'Вакансия интересна', ItrItem::openMenu(10));
			$menu5->addItem(2, 'Назад', ItrItem::openMenu(4));
			$menu5->addItem(3, 'Досвидания', ItrItem::openMenu(3));
			
			$menu6 = new ItrMenu(6);
			$menu6->setText('ОПЕРАТОР-КАССИР
Итак, на позиции оператора-кассира Тебе предстоит:
- рассчитывать покупателей кассе
- проводить наличный расчет
- проводить расчет по банковским картам
- оформлять возврата товара
');
			$menu6->addItem(1, 'Вакансия интересна', ItrItem::openMenu(10));
			$menu6->addItem(2, 'Назад', ItrItem::openMenu(4));
			$menu6->addItem(3, 'Досвидания', ItrItem::openMenu(3));
			$menu7 = new ItrMenu(7);
			$menu7->setText('КЛАДОВЩИК
Итак, на позиции кладовщика Тебе предстоит:
- принимать товара на склад
·       - вести учета складских операций в программе  1С
- контролировать  погрузку/выгрузку товара грузчиками
- соблюдать режимы хранения
');
			$menu7->addItem(1, 'Вакансия интересна', ItrItem::openMenu(10));
			$menu7->addItem(2, 'Назад', ItrItem::openMenu(4));
			$menu7->addItem(3, 'Досвидания', ItrItem::openMenu(3));
			$menu8 = new ItrMenu(8);
			$menu8->setText('ГРУЗЧИК-КОМПЛЕКТОВЩИК
Итак, на позиции грузчика-комплектовщика Тебе предстоит:
- разгружать фуры с товаром (стройматериалы)
- помогать покупателям загружать товар в автомобиль
- сортировать и перемещать вручную товара на складе
');
			$menu8->addItem(1, 'Вакансия интересна', ItrItem::openMenu(10));
			$menu8->addItem(2, 'Назад', ItrItem::openMenu(4));
			$menu8->addItem(3, 'Досвидания', ItrItem::openMenu(3));
			$menu9 = new ItrMenu(9);
			$menu9->setText('Пройдите пожалуйста по этой ссылке и заполните [URL=https://orenburg.str.st/career/resume/]резюме[/URL]');
			$menu9->addItem(2, 'Назад', ItrItem::openMenu(4));
			
			$menu10 = new ItrMenu(10);
			$menu10->setText('Напиши, пожалуйста, имя и фамилию, чтобы я могла представить тебя сотрудникам "Стройландия":');
			
			
			/*$menu1->addItem(4, 'Transfer to bot', ItrItem::transferToBot('marta', true, 'Transfer to bot Marta', 'Marta not found :('));
			 $menu1->addItem(5, 'Finish session', ItrItem::finishSession('Finish session'));
			 $menu1->addItem(6, 'Exec function', ItrItem::execFunction(function($context){
			 $result = restCommand('imbot.message.add', Array(
			 "DIALOG_ID" => $_REQUEST['data']['PARAMS']['DIALOG_ID'],
			 "MESSAGE" => 'Function executed (action)',
			 ), $_REQUEST["auth"]);
			 writeToLog($result, 'Exec function');
			 }, 'Function executed (text)'));
			 $menu1->addItem(9, 'Back to main menu', ItrItem::openMenu(0));*/
			
			
			$itr = new Itr($portalId, $dialogId, 0, $userId);
			$itr->addMenu($menu0);
			$itr->addMenu($menu1);
			$itr->addMenu($menu2);
			$itr->addMenu($menu3);
			$itr->addMenu($menu4);
			$itr->addMenu($menu5);
			$itr->addMenu($menu6);
			$itr->addMenu($menu7);
			$itr->addMenu($menu8);
			$itr->addMenu($menu9);
			$itr->addMenu($menu10);
			
			$itr->run(prepareText($message));
			
			return true;
	}
	
	
	/**
	 * Save application configuration.
	 * WARNING: this method is only created for demonstration, never store config like this
	 *
	 * @param $params
	 * @return bool
	 */
	function saveParams($params)
	{
		$config = "<?php\n";
		$config .= "\$appsConfig = ".var_export($params, true).";\n";
		$config .= "?>";
		
		file_put_contents(__DIR__."/config.php", $config);
		
		return true;
	}
	
	/**
	 * Send rest query to Bitrix24.
	 *
	 * @param $method - Rest method, ex: methods
	 * @param array $params - Method params, ex: Array()
	 * @param array $auth - Authorize data, received from event
	 * @param boolean $authRefresh - If authorize is expired, refresh token
	 * @return mixed
	 */
	function restCommand($method, array $params = Array(), array $auth = Array(), $authRefresh = true)
	{
		$queryUrl = $auth["client_endpoint"].$method;
		$queryData = http_build_query(array_merge($params, array("auth" => $auth["access_token"])));
		
		writeToLog(Array('URL' => $queryUrl, 'PARAMS' => array_merge($params, array("auth" => $auth["access_token"]))), 'ImBot send data');
		
		$curl = curl_init();
		
		curl_setopt_array($curl, array(
				CURLOPT_POST => 1,
				CURLOPT_HEADER => 0,
				CURLOPT_RETURNTRANSFER => 1,
				CURLOPT_SSL_VERIFYPEER => 1,
				CURLOPT_URL => $queryUrl,
				CURLOPT_POSTFIELDS => $queryData,
		));
		
		$result = curl_exec($curl);
		curl_close($curl);
		
		$result = json_decode($result, 1);
		
		if ($authRefresh && isset($result['error']) && in_array($result['error'], array('expired_token', 'invalid_token')))
		{
			$auth = restAuth($auth);
			if ($auth)
			{
				$result = restCommand($method, $params, $auth, false);
			}
		}
		
		return $result;
	}
	
	/**
	 * Get new authorize data if you authorize is expire.
	 *
	 * @param array $auth - Authorize data, received from event
	 * @return bool|mixed
	 */
	function restAuth($auth)
	{
		if (!CLIENT_ID || !CLIENT_SECRET)
			return false;
			
			if(!isset($auth['refresh_token']))
				return false;
				
				$queryUrl = 'https://oauth.bitrix.info/oauth/token/';
				$queryData = http_build_query($queryParams = array(
						'grant_type' => 'refresh_token',
						'client_id' => CLIENT_ID,
						'client_secret' => CLIENT_SECRET,
						'refresh_token' => $auth['refresh_token'],
				));
				
				writeToLog(Array('URL' => $queryUrl, 'PARAMS' => $queryParams), 'ImBot request auth data');
				
				$curl = curl_init();
				
				curl_setopt_array($curl, array(
						CURLOPT_HEADER => 0,
						CURLOPT_RETURNTRANSFER => 1,
						CURLOPT_URL => $queryUrl.'?'.$queryData,
				));
				
				$result = curl_exec($curl);
				curl_close($curl);
				
				$result = json_decode($result, 1);
				if (!isset($result['error']))
				{
					$appsConfig = Array();
					if (file_exists(__DIR__.'/config.php'))
						include(__DIR__.'/config.php');
						
						$result['application_token'] = $auth['application_token'];
						$appsConfig[$auth['application_token']]['AUTH'] = $result;
						saveParams($appsConfig);
				}
				else
				{
					$result = false;
				}
				
				return $result;
	}
	
	/**
	 * Write data to log file. (by default disabled)
	 * WARNING: this method is only created for demonstration, never store log file in public folder
	 *
	 * @param mixed $data
	 * @param string $title
	 * @return bool
	 */
	function writeToLog($data, $title = '')
	{
		if (!DEBUG_FILE_NAME)
			return false;
			
			$log = "\n------------------------\n";
			$log .= date("Y.m.d G:i:s")."\n";
			$log .= (strlen($title) > 0 ? $title : 'DEBUG')."\n";
			$log .= print_r($data, 1);
			$log .= "\n------------------------\n";
			
			file_put_contents(__DIR__."/".DEBUG_FILE_NAME, $log, FILE_APPEND);
			
			return true;
	}
	
	/**
	 * Clean text before select ITR item
	 *
	 * @param $message
	 * @return string
	 */
	function prepareText($message)
	{
		$message = preg_replace("/\[s\].*?\[\/s\]/i", "-", $message);
		$message = preg_replace("/\[[bui]\](.*?)\[\/[bui]\]/i", "$1", $message);
		$message = preg_replace("/\\[url\\](.*?)\\[\\/url\\]/i", "$1", $message);
		$message = preg_replace("/\\[url\\s*=\\s*((?:[^\\[\\]]++|\\[ (?: (?>[^\\[\\]]+) | (?:\\1) )* \\])+)\\s*\\](.*?)\\[\\/url\\]/ixs", "$2", $message);
		$message = preg_replace("/\[USER=([0-9]{1,})\](.*?)\[\/USER\]/i", "$2", $message);
		$message = preg_replace("/\[CHAT=([0-9]{1,})\](.*?)\[\/CHAT\]/i", "$2", $message);
		$message = preg_replace("/\[PCH=([0-9]{1,})\](.*?)\[\/PCH\]/i", "$2", $message);
		$message = preg_replace('#\-{54}.+?\-{54}#s', "", str_replace(array("#BR#"), Array(" "), $message));
		$message = strip_tags($message);
		
		return trim($message);
	}
	
	
	/**
	 * Class Itr
	 * @package Bitrix\ImBot\Bot
	 */
	class Itr
	{
		public $botId = 0;
		public $userId = 0;
		public $dialogId = '';
		public $portalId = '';
		
		private $cacheId = '';
		private static $executed = false;
		
		private $menuItems = Array();
		private $menuText = Array();
		
		private $currentMenu = 0;
		private $skipShowMenu = false;
		
		public function __construct($portalId, $dialogId, $botId, $userId)
		{
			$this->portalId = $portalId;
			$this->userId = $userId;
			$this->botId = $botId;
			$this->dialogId = $dialogId;
			
			$this->getCurrentMenu();
		}
		
		public function addMenu(ItrMenu $items)
		{
			$this->menuText[$items->getId()] = $items->getText();
			$this->menuItems[$items->getId()] = $items->getItems();
			
			return true;
		}
		
		/**
		 * Get menu state.
		 * WARNING: this method is only created for demonstration, never store cache like this
		 */
		private function getCurrentMenu()
		{
			$this->cacheId = md5($this->portalId.$this->botId.$this->dialogId);
			
			if (file_exists(__DIR__.'/cache') && file_exists(__DIR__.'/cache/'.$this->cacheId.'.cache'))
			{
				$this->currentMenu = intval(file_get_contents(__DIR__.'/cache/'.$this->cacheId.'.cache'));
			}
			else
			{
				if (!file_exists(__DIR__.'/cache'))
				{
					mkdir(__DIR__.'/cache');
					chmod(__DIR__.'/cache', 0777);
				}
				file_put_contents(__DIR__.'/cache/'.$this->cacheId.'.cache', 0);
			}
		}
		
		/**
		 * Save menu state.
		 * WARNING: this method is only created for demonstration, never store cache like this
		 */
		private function setCurrentMenu($id)
		{
			$this->currentMenu = intval($id);
			file_put_contents(__DIR__.'/cache/'.$this->cacheId.'.cache', $this->currentMenu);
		}
		
		private function execMenuItem($itemId = '')
		{
			if ($itemId === '')
			{
				return true;
			}
			else if ($itemId === "0")
			{
				$this->skipShowMenu = true;
			}
			
			if (!isset($this->menuItems[$this->currentMenu][$itemId]))
			{
				return false;
			}
			
			$menuItemAction = $this->menuItems[$this->currentMenu][$itemId]['ACTION'];
			
			if ($menuItemAction['HIDE_MENU'])
			{
				$this->skipShowMenu = true;
			}
			
			if (isset($menuItemAction['TEXT']))
			{
				$messageText = str_replace('#USER_NAME#', $_REQUEST["data"]["USER"]["NAME"], $menuItemAction['TEXT']);
				restCommand('imbot.message.add', Array(
						"DIALOG_ID" => $this->dialogId,
						"MESSAGE" => $messageText,
						), $_REQUEST["auth"]);
			}
			
			if ($menuItemAction['TYPE'] == ItrItem::TYPE_MENU)
			{
				$this->setCurrentMenu($menuItemAction['MENU']);
			}
			else if ($menuItemAction['TYPE'] == ItrItem::TYPE_QUEUE)
			{
				restCommand('imopenlines.bot.session.operator', Array(
						"CHAT_ID" => substr($this->dialogId, 4),
						), $_REQUEST["auth"]);
			}
			else if ($menuItemAction['TYPE'] == ItrItem::TYPE_USER)
			{
				restCommand('imopenlines.bot.session.transfer', Array(
						"CHAT_ID" => substr($this->dialogId, 4),
						"USER_ID" => $menuItemAction['USER_ID'],
						"LEAVE" => $menuItemAction['LEAVE']? 'Y': 'N',
						), $_REQUEST["auth"]);
			}
			else if ($menuItemAction['TYPE'] == ItrItem::TYPE_BOT)
			{
				$botId = 0;
				$result = restCommand('imbot.bot.list', Array(), $_REQUEST["auth"]);
				foreach ($result['result'] as $botData)
				{
					if ($botData['CODE'] == $menuItemAction['BOT_CODE'] && $botData['OPENLINE'] == 'Y')
					{
						$botId = $botData['ID'];
						break;
					}
				}
				if ($botId)
				{
					restCommand('imbot.chat.user.add', Array(
							'CHAT_ID' => substr($this->dialogId, 4),
							'USERS' => Array($botId)
							), $_REQUEST["auth"]);
					if ($menuItemAction['LEAVE'])
					{
						restCommand('imbot.chat.leave', Array(
								'CHAT_ID' => substr($this->dialogId, 4)
								), $_REQUEST["auth"]);
					}
				}
				else if ($menuItemAction['ERROR_TEXT'])
				{
					$messageText = str_replace('#USER_NAME#', $_REQUEST["data"]["USER"]["NAME"], $menuItemAction['ERROR_TEXT']);
					restCommand('imbot.message.add', Array(
							"DIALOG_ID" => $this->dialogId,
							"MESSAGE" => $messageText,
							), $_REQUEST["auth"]);
					$this->skipShowMenu = false;
				}
			}
			else if ($menuItemAction['TYPE'] == ItrItem::TYPE_FINISH)
			{
				restCommand('imopenlines.bot.session.finish', Array(
						"CHAT_ID" => substr($this->dialogId, 4)
						), $_REQUEST["auth"]);
			}
			else if ($menuItemAction['TYPE'] == ItrItem::TYPE_FUNCTION)
			{
				$menuItemAction['FUNCTION']($this);
			}
			
			return true;
		}
		
		private function getMenuItems()
		{
			$messageText = '';
			if ($this->skipShowMenu)
			{
				$this->skipShowMenu = false;
				return $messageText;
			}
			
			if (isset($this->menuText[$this->currentMenu]))
			{
				$messageText = $this->menuText[$this->currentMenu].'[br]';
			}
			
			foreach ($this->menuItems[$this->currentMenu] as $itemId => $data)
			{
				$messageText .= '[send='.$itemId.']'.$itemId.'. '.$data['TITLE'].'[/send][br]';
			}
			
			$messageText = str_replace('#USER_NAME#', $_REQUEST["data"]["USER"]["NAME"], $messageText);
			restCommand('imbot.message.add', Array(
					"DIALOG_ID" => $this->dialogId,
					"MESSAGE" => $messageText,
					), $_REQUEST["auth"]);
			
			return true;
		}
		
		public function run($text)
		{
			if (self::$executed)
				return false;
				
				list($itemId) = explode(" ", $text);
				
				$this->execMenuItem($itemId);
				
				$this->getMenuItems();
				
				self::$executed = true;
				
				return true;
		}
	}
	
	class ItrMenu
	{
		private $id = 0;
		private $text = '';
		private $items = Array();
		
		/**
		 * ItrMenu constructor.
		 * @param $id
		 */
		public function __construct($id)
		{
			$this->id = intval($id);
		}
		
		public function getId()
		{
			return $this->id;
		}
		
		public function getText()
		{
			return $this->text;
		}
		
		public function getItems()
		{
			return $this->items;
		}
		
		public function setText($text)
		{
			$this->text = trim($text);
		}
		
		public function addItem($id, $title, array $action)
		{
			$id = intval($id);
			if ($id <= 0 && !in_array($action['TYPE'], Array(ItrItem::TYPE_VOID, ItrItem::TYPE_TEXT)))
			{
				return false;
			}
			
			$title = trim($title);
			
			$this->items[$id] = Array(
					'ID' => $id,
					'TITLE' => $title,
					'ACTION' => $action
					);
			
			return true;
		}
	}
	
	class ItrItem
	{
		const TYPE_VOID = 'VOID';
		const TYPE_TEXT = 'TEXT';
		const TYPE_MENU = 'MENU';
		const TYPE_USER = 'USER';
		const TYPE_BOT = 'BOT';
		const TYPE_QUEUE = 'QUEUE';
		const TYPE_FINISH = 'FINISH';
		const TYPE_FUNCTION = 'FUNCTION';
		
		public static function void($hideMenu = true)
		{
			return Array(
					'TYPE' => self::TYPE_VOID,
					'HIDE_MENU' => $hideMenu? true: false
					);
		}
		
		public static function sendText($text = '', $hideMenu = false)
		{
			return Array(
					'TYPE' => self::TYPE_TEXT,
					'TEXT' => $text,
					'HIDE_MENU' => $hideMenu? true: false
					);
		}
		
		public static function openMenu($menuId)
		{
			return Array(
					'TYPE' => self::TYPE_MENU,
					'MENU' => $menuId
					);
		}
		
		public static function transferToQueue($text = '', $hideMenu = true)
		{
			return Array(
					'TYPE' => self::TYPE_QUEUE,
					'TEXT' => $text,
					'HIDE_MENU' => $hideMenu? true: false
					);
		}
		
		public static function transferToUser($userId, $leave = false, $text = '', $hideMenu = true)
		{
			return Array(
					'TYPE' => self::TYPE_USER,
					'TEXT' => $text,
					'HIDE_MENU' => $hideMenu? true: false,
					'USER_ID' => $userId,
					'LEAVE' => $leave? true: false,
					);
		}
		
		public static function transferToBot($botCode, $leave = true, $text = '', $errorText = '')
		{
			return Array(
					'TYPE' => self::TYPE_BOT,
					'TEXT' => $text,
					'ERROR_TEXT' => $errorText,
					'HIDE_MENU' => true,
					'BOT_CODE' => $botCode,
					'LEAVE' => $leave? true: false,
					);
		}
		
		public static function finishSession($text = '')
		{
			return Array(
					'TYPE' => self::TYPE_FINISH,
					'TEXT' => $text,
					'HIDE_MENU' => true
					);
		}
		
		public static function execFunction($function, $text = '', $hideMenu = false)
		{
			return Array(
					'TYPE' => self::TYPE_FUNCTION,
					'FUNCTION' => $function,
					'TEXT' => $text,
					'HIDE_MENU' => $hideMenu? true: false
					);
		}
	}
