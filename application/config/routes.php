<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* geral */

$route['default_controller'] = 'site';
$route['404_override'] = '';

/* site */

$route['agenda'] = 'site/agenda';
$route['agenda/(:num)/(:num)/(:num)'] = 'site/agenda/$1/$2/$3';

$route['agentes/(:any)'] = 'site/agentes/$1';

$route['busca'] = 'site/busca';

$route['contato'] = 'site/contato';

$route['espacos/(:any)'] = 'site/espacos/$1';

$route['espacos-agentes'] = 'site/espacos_agentes';

$route['evento/(:any)'] = 'site/evento/$1';

$route['login'] = 'site/login';

$route['logout'] = 'site/logout';

$route['recuperar'] = 'site/recuperar';

$route['noticias'] = 'site/noticias';
$route['noticias/(:any)'] = 'site/noticias_post/$1';

$route['projetos'] = 'site/projetos';

$route['quem-somos'] = 'site/quem_somos';

$route['registrar'] = 'site/registrar';

$route['remove-evento/(:num)'] = 'site/muda_agenda/$1';
$route['adiciona-evento/(:num)'] = 'site/muda_agenda/$1';

$route['usuario/(:any)/agenda'] = 'site/usuario_agenda/$1';
$route['usuario/(:any)/agenda-pdf'] = 'site/usuario_agenda_pdf/$1';
$route['usuario/(:any)'] = 'site/usuario_perfil/$1';

$route['agenda_ajax'] = 'site/agenda_ajax';
$route['busca_ajax'] = 'site/busca_ajax';
$route['espacos_agentes_ajax'] = 'site/espacos_agentes_ajax';
$route['noticias_ajax'] = 'site/noticias_ajax';
$route['map_home_ajax'] = 'site/map_home_ajax';
$route['usuario_agenda_ajax/(:any)'] = 'site/usuario_agenda_ajax/$1';

/* admin */

$route['admin/usuarios(.*)'] = 'admin_usuarios$1';

$route['admin/atributos'] = 'admin_atributos/index/index/0/0';
$route['admin/atributos/(:num)'] = 'admin_atributos/index/$1/0';
$route['admin/atributos-descendentes/(:num)'] = 'admin_atributos/index/0/$1';
$route['admin/atributos-descendentes/(:num)/(:num)'] = 'admin_atributos/index/$2/$1';

$route['admin/atributos/create'] = 'admin_atributos/create/0';
$route['admin/atributos-descendentes/create/(:num)'] = 'admin_atributos/create/$1';

$route['admin/atributos/edit/(:num)'] = 'admin_atributos/edit/$1';
$route['admin/atributos-descendentes/edit/(:num)'] = 'admin_atributos/edit/$1';

$route['admin/atributos/sort/(:num)/up'] = 'admin_atributos/sort/$1/-1';
$route['admin/atributos/sort/(:num)/down'] = 'admin_atributos/sort/$1/+1';

$route['admin/atributos/delete/(:num)'] = 'admin_atributos/delete/$1';
$route['admin/atributos-descendentes/delete/(:num)'] = 'admin_atributos/delete/$1';

$route['admin/atributos/delete_many'] = 'admin_atributos/delete_many';
$route['admin/atributos-descendentes/delete_many'] = 'admin_atributos/delete_many';

$route['admin/atributos/upload_ajax'] = 'admin_atributos/upload_ajax';

$route['admin/agentes-culturais/sort/(:num)/up'] = 'admin_agentes_culturais/sort/$1/-1';
$route['admin/agentes-culturais/sort/(:num)/down'] = 'admin_agentes_culturais/sort/$1/+1';

$route['admin/agentes-culturais(.*)'] = 'admin_agentes_culturais$1';

$route['admin/espacos-culturais/(:num)/eventos'] = 'admin_eventos/index/0/$1';
$route['admin/espacos-culturais/(:num)/eventos/(:num)'] = 'admin_eventos/index/$2/$1';

$route['admin/espacos-culturais/(:num)/eventos/create'] = 'admin_eventos/create/$1';

$route['admin/espacos-culturais/(:num)/eventos/edit/(:num)'] = 'admin_eventos/edit/$2';

$route['admin/espacos-culturais/(:num)/eventos/delete/(:num)'] = 'admin_eventos/delete/$2';

$route['admin/espacos-culturais/(:num)/eventos/delete_many'] = 'admin_eventos/delete_many';

$route['admin/espacos-culturais/(:num)/eventos/upload_ajax'] = 'admin_eventos/upload_ajax';

$route['admin/espacos-culturais/sort/(:num)/up'] = 'admin_espacos_culturais/sort/$1/-1';
$route['admin/espacos-culturais/sort/(:num)/down'] = 'admin_espacos_culturais/sort/$1/+1';

$route['admin/espacos-culturais(.*)'] = 'admin_espacos_culturais$1';

$route['admin/noticias(.*)'] = 'admin_noticias$1';

$route['admin/permissoes/sort/(:num)/up'] = 'admin_permissoes/sort/$1/-1';
$route['admin/permissoes/sort/(:num)/down'] = 'admin_permissoes/sort/$1/+1';

$route['admin/permissoes(.*)'] = 'admin_permissoes$1';

$route['admin/sobre'] = 'admin_sobre';

$route['admin/projetos(.*)'] = 'admin_projetos$1';

$route['admin/login'] = 'publico/login';
$route['admin/logout'] = 'publico/logout';

$route['admin/files/ckupload'] = 'files_controller/ck_upload';

$route['atributos_ajax/(:num)'] = 'publico/atributos_ajax/$1';
