<?php
class ControllerModuleLangMan extends Controller {

    public function index() {
		/* Подгрузка языка */
		$this->language->load('module/langman');
		foreach($this->language->data() as $key => $value) {
			$this->data[$key] = $value;
		}

		/* Проверка прав */
		$this->load->model('setting/setting');

		if ($this->validate() && ($this->request->server['REQUEST_METHOD'] === 'POST')) {
			// $this->model_setting_setting->editSetting('langman', $this->request->post);

			// $this->session->data['success'] = $this->language->get('text_success');

			// $this->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		}

		/* Передать ошибку, если есть */
 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		/* <head></head> */
        $this->document->setTitle(strip_tags($this->language->get('heading_title')));
		$this->document->addLink('view/stylesheet/langman.css', 'stylesheet');

		/* Установить пути */
  		$this->data['breadcrumbs'] = [];
   		$this->data['breadcrumbs'][] = [
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		];
   		$this->data['breadcrumbs'][] = [
       		'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		];
		$this->data['breadcrumbs'][] = [
       		'text'      => strip_tags($this->language->get('heading_title')),
			'href'      => $this->url->link('module/langman', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		];

		/* Кнопки */
		$this->data['action'] = $this->url->link('module/langman', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

		/* Приём активного меню */
		$menu = (isset($this->request->get['m'])) ? $this->request->get['m'] :
				((isset($this->request->post['m'])) ? $this->request->post['m'] :
				'admin');

		/* Подключение файлов и регистрация объектов модуля */
		$this->load->model('tool/langman');
		$this->load->library('langman_cache');
		$this->registry->set('langman_cache', new LangManCache());
		$this->load->library('langman');
		$this->registry->set('langman', new LangMan($this, $menu));


		/* Если драйвер БД не поддерживается */
		if ($this->langman->error_db_driver) {
			$this->data['error_db_driver'] = sprintf($this->data['error_db_driver'], DB_DRIVER, $this->langman->getDBDrivers());

		/* Если драйвер БД поддерживается */
		} else {
			$this->data['error_db_driver'] = '';

			/* Меню */
			$this->data['langman_menu'] = [];

			foreach($this->langman->menu as $menu) {
				$this->data['langman_menu'][$menu] = [
					'href' => $this->url->link('module/langman', 'm=' . $menu . '&token=' . $this->session->data['token'], 'SSL'),
					'text' => $this->language->get('text_' . $menu),
					'active' => ($this->langman->menu_active == $menu)
				];
			}


			/* Данные */
			$this->data['data'] = new stdClass;

			/* База данных */
			if ($this->langman->menu_active == 'db') {
				/* Приём запроса POST */
				

				/* Фильтр */
				

				if ($view == 1) {
					
				} elseif ($view == 2) {
					
				}

			/* Файловая система */
			} else {
				/* Приём запроса POST */
				$directory = (isset($this->request->post['d'])) ? (int)$this->request->post['d'] : 0;
				$directory_pre = (isset($this->request->post['p'])) ? (int)$this->request->post['p'] : 0;
				$file = ($directory == $directory_pre) ? (isset($this->request->post['f'])) ? (int)$this->request->post['f'] : 1 : 1;
				$view = (isset($this->request->post['v'])) ? (int)$this->request->post['v'] : 1;

				$this->data['post'] = [
					'm' => $this->langman->menu_active,
					'd' => $directory,
					'p' => $directory_pre,
					'f' => $file,
					'v' => $view
				];

				/* Фильтр */
				$filter = [];

				foreach($this->langman->data as $i => $data){
					$filter[$i] = new stdClass;
					$filter[$i]->directory = $data[0];

					$files	= [];

					foreach($data as $j => $file_data){
						if ($j) {
							$files[] = $file_data->file_name;
						}
					}

					$filter[$i]->files = splFixedArray::fromArray($files);
				}

				$this->data['filter'] = splFixedArray::fromArray($filter);


				if ($view == 1) {
					$this->data['data']->dir_name = $this->langman->data[$directory][0];
					$this->data['data']->file_name = $this->langman->data[$directory][$file]->file_name;
					$this->data['data']->content = new stdClass;

					foreach($this->langman->data[$directory][$file] as $group => $langs) {
						if (is_object($langs)) {
							$this->data['data']->content->$group = $langs;
						}
					}
				}  elseif ($view == 2) {
					$this->data['data']->dir_name = $this->langman->data[$directory][0];
					$content = [];

					foreach($this->langman->data[$directory] as $file) {
						if (is_object($file)) {
							$content[] = $file;
						}
					}

					$this->data['data']->content = splFixedArray::fromArray($content);
				} elseif ($view == 3) {
					$this->data['data'] = $this->langman->data;
				}
			}

			/* Языки */
			$this->data['languages'] = $this->langman->languages;
		}




		$this->template = 'module/langman/index.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());

    }

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'module/langman')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return ($this->error) ? false : true;
	}

}
?>