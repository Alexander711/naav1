<?php

// старичный пагинатор
Route::set('admin_users', 'admin/users(/<page>)', array('page' => '[0-9]+',))
		->defaults(
			array(
				'directory'  => 'admin',
				'controller' => 'users',
				'action'     => 'index',
			)
		);


Route::set('admin_invoice_log', 'admin/payment/invoicelog(/<page>)', array('page' => '[0-9]+',))
		->defaults(
			array(
				'directory'  => 'admin/payment',
				'controller' => 'invoicelog',
				'action'     => 'index',
			)
		);

Route::set('admin_payment_log', 'admin/payment/paymentlog(/<page>)', array('page' => '[0-9]+',))
		->defaults(
			array(
				'directory'  => 'admin/payment',
				'controller' => 'paymentlog',
				'action'     => 'index',
			)
		);

Route::set('admin_email', 'admin/email(/<page>)', array('page' => '[0-9]+',))
		->defaults(
			array(
				'directory'  => 'admin',
				'controller' => 'email',
				'action'     => 'index',
			)
		);
// старичный пагинатор - конец




Route::set('cabinet', 'cabinet(/<controller>(/<action>(/<id>)))')
		->defaults(
			array(
				'directory'  => 'cabinet',
				'controller' => 'main',
				'action'     => 'index',
			)
		);


Route::set('payment', 'payment(/<controller>(/<action>(/<id>)))')
		->defaults(
			array(
				'directory'  => 'payment',
				'controller' => 'main',
				'action'     => 'index',
			)
		);
Route::set('rest', 'rest(/<controller>(/<action>(/<id>)))')
		->defaults(
			array(
				'directory'  => 'rest',
				'controller' => 'main',
				'action'     => 'index',
			)
		);
// Admin routes
Route::set('admin_news', 'admin/news(/<controller>(/<action>(/<id>)))')
		->defaults(
			array(
				'directory'  => 'admin/news',
				'controller' => 'main',
				'action'     => 'index',
			)
		);



Route::set('admin_payment', 'admin/payment(/<controller>(/<action>(/<id>)))')
		->defaults(
			array(
				'directory'  => 'admin/payment',
				'controller' => 'main',
				'action'     => 'index',
			)
		);




Route::set('admin_content', 'admin/content(/<controller>(/<action>(/<id>)))')
		->defaults(
			array(
				'directory'  => 'admin/content',
				'controller' => 'main',
				'action'     => 'index',
			)
		);
Route::set('admin_item', 'admin/item(/<controller>(/<action>(/<id>)))')
		->defaults(
			array(
				'directory'  => 'admin/item',
				'controller' => 'main',
				'action'     => 'index',
			)
		);
Route::set('admin_service', 'admin/service(/<controller>(/<action>(/<id>)))')
		->defaults(
			array(
				'directory'  => 'admin/service',
				'controller' => 'main',
				'action'     => 'index',
			)
		);
Route::set('admin_develop', 'admin/development(/<controller>(/<action>(/<id>)))')
		->defaults(
			array(
				'directory'  => 'admin/development',
				'controller' => 'task',
				'action'     => 'index',
			)
		);
Route::set('admin_service_all', 'admin/services(/type_<type>)(/city_<city>)')
		->defaults(
			array(
				'directory'  => 'admin',
				'controller' => 'services',
				'action'     => 'index',
			)
		);

Route::set('admin', 'admin(/<controller>(/<action>(/<id>)))')
		->defaults(
			array(
				'directory'  => 'admin',
				'controller' => 'main',
				'action'     => 'index',
			)
		);

Route::set('auto_filter', 'filter/auto(/city_<city>)(/district_<district>)(/metro_<metro>)')
		->defaults(
			array(
				'controller' => 'filter',
				'action'     => 'auto'
			)
		);

Route::set('work_filter', 'filter/work(/city_<city>)(/district_<district>)(/metro_<metro>)')
		->defaults(
			array(
				'controller' => 'filter',
				'action'     => 'work'
			)
		);

Route::set('metro_filter', 'filter/metro(/city_<city>)')
		->defaults(
			array(
				'controller' => 'filter',
				'action'     => 'metro'
			)
		);

Route::set('district_filter', 'filter/district(/city_<city>)')
		->defaults(
			array(
				'controller' => 'filter',
				'action'     => 'district'
			)
		);

Route::set('service_search_by_metro', 'services/search(/metro_<metro>)')
		->defaults(
			array(
				'directory'  => 'services',
				'controller' => 'main',
				'action'     => 'search_by_metro',
			)
		);

Route::set('service_search_by_district', 'services/search(/district_<district>)')
		->defaults(
			array(
				'directory'  => 'services',
				'controller' => 'main',
				'action'     => 'search_by_district',
			)
		);

Route::set('service_search_by_car', 'services/search(/car_<car>)(/city_<city>)(/district_<district>)(/metro_<metro>)(/discount_<discount>)')
		->defaults(
			array(
				'directory'  => 'services',
				'controller' => 'main',
				'action'     => 'search_by_car'
			)
		);

Route::set('service_search_by_work', 'services/search(/work_<work>)(/city_<city>)(/district_<district>)(/metro_<metro>)(/discount_<discount>)')
		->defaults(
			array(
				'directory'  => 'services',
				'controller' => 'main',
				'action'     => 'search_by_work'
			)
		);

Route::set('company_info', '<company_type>/<id>', array('company_type' => '(services|shops)'))
		->defaults(
			array(
				'directory'  => 'services',
				'controller' => 'main',
				'action'     => 'view',
			)
		);
/*
 * Новости ассоциации
 */
Route::set('portal_news', 'news/association')
		->defaults(
			array(
				'directory'  => 'news',
				'controller' => 'association',
				'action'     => 'index',
			)
		);
/*
 * Просмотр новости ассоциации
 */
Route::set('portal_news_view', 'news/association/<id>')
		->defaults(
			array(
				'directory'  => 'news',
				'controller' => 'association',
				'action'     => 'view',
			)
		);
/**
 * Новости автомира
 */
Route::set('world_news', 'news/world')
		->defaults(
			array(
				'directory'  => 'news',
				'controller' => 'world',
				'action'     => 'index'
			)
		);
/**
 * Просмотр новости автомира
 */
Route::set('world_news_view', 'news/world/<id>')
		->defaults(
			array(
				'directory'  => 'news',
				'controller' => 'world',
				'action'     => 'view'
			)
		);
/**
 * Все новости автосервисов
 */
Route::set('all_services_news', 'news')
		->defaults(
			array(
				'directory'  => 'news',
				'controller' => 'services',
				'action'     => 'index',
			)
		);
/*
 * Старая ссылка на новость автосервиса для 301 редиректа
 */
Route::set('service_news_view_old', 'news/<id>')
		->defaults(
			array(
				'directory'  => 'news',
				'controller' => 'services',
				'action'     => 'view',
			)
		);
/**
 * Новости автосервиса
 */
Route::set('service_news_all', '<company_type>/<service_id>/news', array('company_type' => '(services|shops)'))
		->defaults(
			array(
				'directory'  => 'services',
				'controller' => 'news',
				'action'     => 'index',
			)
		);
Route::set('service_news_view_once', '<company_type>/<service_id>/news/<news_id>', array('company_type' => '(services|shops)'))
		->defaults(
			array(
				'directory'  => 'services',
				'controller' => 'news',
				'action'     => 'view',
			)
		);
/**
 * Акции автосервисов
 */
Route::set('service_stocks_all', '<company_type>/<service_id>/stocks', array('company_type' => '(services|shops)'))
		->defaults(
			array(
				'directory'  => 'services',
				'controller' => 'stocks',
				'action'     => 'index',
			)
		);
Route::set('service_stocks_view_once', '<company_type>/<service_id>/stocks/<stock_id>', array('company_type' => '(services|shops)'))
		->defaults(
			array(
				'directory'  => 'services',
				'controller' => 'stocks',
				'action'     => 'view',
			)
		);
/**
 * Вакансии автосервисов
 */
Route::set('service_vacancies_all', '<company_type>/<service_id>/vacancies', array('company_type' => '(services|shops)'))
		->defaults(
			array(
				'directory'  => 'services',
				'controller' => 'vacancies',
				'action'     => 'index',
			)
		);
/*
 * Старая ссылка на вакансию, для 301 редиректа
 */
Route::set('vacancy_view', 'vacancies/<id>')
		->defaults(
			array(
				'controller' => 'vacancies',
				'action'     => 'view',
			)
		);
/*
 * Новая ссылка на вакансию
 */
Route::set('service_vacancies_view_once', '<company_type>/<service_id>/vacancies/<vacancy_id>', array('company_type' => '(services|shops)'))
		->defaults(
			array(
				'directory'  => 'services',
				'controller' => 'vacancies',
				'action'     => 'view',
			)
		);
/**
 * Отхывы автосервисов
 */
Route::set('service_reviews_all', '<company_type>/<service_id>/reviews', array('company_type' => '(services|shops)'))
		->defaults(
			array(
				'directory'  => 'services',
				'controller' => 'reviews',
				'action'     => 'index',
			)
		);
Route::set('service_reviews_view_once', '<company_type>/<service_id>/reviews/<review_id>', array('company_type' => '(services|shops)'))
		->defaults(
			array(
				'directory'  => 'services',
				'controller' => 'reviews',
				'action'     => 'view',
			)
		);


Route::set('auth', '<action>(/<id>)', array('action' => '(login|logout|edit_profile|forgot_password|activate_password)'))
		->defaults(
			array(
				'controller' => 'auth',
				'action'     => 'login',
			)
		);
$urls = Kohana::$config->load('pages.urls');

Route::set('content', '<url>', array('url' => '(' . implode('|', $urls) . ')'))
		->defaults(
			array(
				'controller' => 'content',
				'action'     => 'index'
			)
		);
/**
 * Sitemap
 */
Route::set('sitemap', 'sitemap.<action>')
		->defaults(
			array(
				'controller' => 'sitemap',
				'action'     => 'index',
			)
		);


/**
 * Просмотр статьи
 */
Route::set('article_view', 'articles/<id>')
		->defaults(
			array(
				'controller' => 'articles',
				'action'     => 'view',
			)
		);

// Add review
Route::set('review_add', 'reviews/add_review')
		->defaults(
			array(
				'controller' => 'reviews',
				'action'     => 'add',
			)
		);

// Review view
Route::set('review_view', 'reviews/<id>')
		->defaults(
			array(
				'controller' => 'reviews',
				'action'     => 'view',
			)
		);


// Stock view
Route::set('stock_view', 'stocks/<id>')
		->defaults(
			array(
				'controller' => 'stocks',
				'action'     => 'view',
			)
		);
// Add QA
Route::set('qa_add', 'messages/add')
		->defaults(
			array(
				'controller' => 'messages',
				'action'     => 'add',
			)
		);
// View QA
Route::set('qa_view', 'messages/<id>')
		->defaults(
			array(
				'controller' => 'messages',
				'action'     => 'view',
			)
		);


Route::set('default', '(<controller>(/<action>(/<id>)))')
		->defaults(
			array(
				'controller' => 'main',
				'action'     => 'index',
			)
		);





?>