<?php

$prefix = Auth::check() ? AuthAccount::getStartPage() : 'guest';

/*
| Общие роуты, независящие от условий
*/
//Route::get('image/{image_group}/{id}', 'ImageController@showImage')->where('id','\d+');
Route::get('redactor/get-uploaded-images', 'DownloadsController@redactorUploadedImages');
Route::post('redactor/upload', 'DownloadsController@redactorUploadImage');

#################################################################
## Все, что ниже - можно вынести в модуль system - Пользователи.
## Но, возможно, придется следить за порядком загрузки модулей...
#################################################################

## В случае, если неавторизованный пользователь зайдет на /admin, то он будет переадресован на /login.
Route::get('admin', array('before' => 'auth2login', 'uses' => 'BaseController@redirectToLogin'));
/*
| Роуты, доступные для всех авторизованных пользователей - dashboard
*/
Route::group(array('before' => 'auth', 'prefix' => $prefix), function(){
    Route::get('/', 'BaseController@dashboard');
});

/*
| Роуты, доступные только для неавторизованных пользователей
*/
Route::group(array('before' => 'guest', 'prefix' => ''), function(){
	Route::post('signin', array('as' => 'signin', 'uses' => 'GlobalController@signin'));
	Route::post('signup', array('as' => 'signup', 'uses' => 'GlobalController@signup'));
	Route::get('activation', array('as' => 'activation', 'uses' => 'GlobalController@activation'));
});

/*
| Роуты, доступные для гостей и авторизованных пользователей
*/
Route::get('login', array('before' => 'login', 'as' => 'login', 'uses' => 'GlobalController@loginPage'));
Route::get('logout', array('before' => 'auth', 'as' => 'logout', 'uses' => 'GlobalController@logout'));

#################################################################



/***********************************************************************/
/******************** ЗАГРУЗКА РЕСУРСОВ ИЗ МОДУЛЕЙ *********************/
/***********************************************************************/
## For debug
$load_debug = 0;
## Reserved methods for return resourses of controller
$returnRoutes = "returnRoutes";
$returnActions = "returnActions";
$returnShortCodes = "returnShortCodes";
$returnExtFormElements = "returnExtFormElements";
$returnInfo = "returnInfo";
$returnMenu = "returnMenu";
## Find all controllers & load him resoures: routes, shortcodes & others...
$postfix = ".controller.php";
$mod_path = "../app/modules/*/*".$postfix;
$files = glob($mod_path);
#print_r($files); die;
## Work with each module
$mod_actions = array();
$mod_info = array();
$mod_menu = array();
$mod_new = array();
$default_actions = Config::get('actions');
foreach ($files as $file) {

    #$dir_name = basename(dirname($file));

    $file_name = basename($file);
    $tmp_module_name = $module_name = str_replace($postfix, "", $file_name);
    
    if (strpos($module_name, ".")) {
        $blocks = explode(".", $module_name);
        foreach ($blocks as $b => $block) {
            $blocks[$b] = ucfirst($block);
        }
        $module_name = implode("", $blocks);
    }
    
    $module_prefix = "";
    $module_postname = $module_name;
    if (strpos($module_name, "_"))
        list($module_prefix, $module_postname) = explode("_", $module_name, 2);
    $module_prefix = strtolower($module_prefix);

    $module_fullname = ucfirst($module_prefix).ucfirst($module_postname)."Controller";

    if ($load_debug)
        echo $file_name . ": " . $module_prefix . " | " . $module_name . " | " . $module_fullname . " > "; #die;

    ## If class have right name...
    if (class_exists($module_fullname)) {

        ## Load routes...
        if (method_exists($module_fullname, $returnRoutes)) {
            if ($load_debug) echo " [ load routes... ] ";
            $module_fullname::$returnRoutes($module_prefix);
        }
        ## Load shortcodes...
        if (method_exists($module_fullname, $returnShortCodes)) {
            if ($load_debug) echo " [ load shortcodes... ] ";
            $module_fullname::$returnShortCodes();
        }
        ## Load Extended Form elements...
        if (method_exists($module_fullname, $returnExtFormElements)) {
            if ($load_debug) echo " [ load extended form elements... ] ";
            $module_fullname::$returnExtFormElements();
        }
        
        #if (!isset($module_fullname::$name))
        #    continue;

        ## Get module name...
        $module_name = $module_fullname::$name;

        ## Load module info...
        if (method_exists($module_fullname, $returnInfo)) {

            if ($load_debug) echo " [ load info... ] ";
            #$mod_info[$module_name] = $module_fullname::$returnInfo();

            $module_info = $module_fullname::$returnInfo();
            if (!$module_info)
                continue;

            $mod_info[$module_name] = $module_info;

            $module = new Module;
            $module->name = $module_info['name'];
            $module->on = 0;
            $module->order = NULL;

            $mod_new[$module_name] = $module;

        }
        
        ## Load module actions...
        $actions = array();
        if (method_exists($module_fullname, $returnActions)) {
            if ($load_debug) echo " [ load actions... ] ";
            $actions = $module_fullname::$returnActions();
        }
        #$mod_actions[$module_name] = $actions === false ? $default_actions : $actions; #array_merge($default_actions, $actions);
        $mod_actions[$module_name] = $actions;

        ## Load module admin menu elements...
        if (method_exists($module_fullname, $returnMenu)) {
            if ($load_debug) echo " [ load menus... ] ";
            $mod_menu[$module_name] = $module_fullname::$returnMenu();
        }

    } else {

        if ($load_debug) echo " CLASS NOT FOUND: {$module_fullname} | composer dump-autoload OR php-file has unusual codepage OR file name start with DIGIT!";
        
    }
    
    if ($load_debug) echo "<br/>\n";
}
#Helper::dd($mod_actions);
#Helper::dd($mod_info);
#Helper::dd($mod_menu);

/*
foreach ($mod_actions as $module_name => $actions) {
    if (!count($actions))
        continue;
    $title = isset($mod_info[$module_name]['title']) ? $mod_info[$module_name]['title'] : $module_name;
    echo "<h2>{$title} - ОТКЛЮЧИТЬ МОДУЛЬ ДЛЯ ТЕКУЩЕЙ ГРУППЫ | РАЗРЕШИТЬ / ЗАПРЕТИТЬ ВСЕ ДЕЙСТВИЯ</h2>\n";
    foreach ($actions as $a => $action) {
        echo "<p>{$action} - РАЗРЕШЕНО / ЗАПРЕЩЕНО</p>";
    }
}
*/
#Helper::dd($mod_info);
#Helper::dd($mod_actions);
#Helper::dd($mod_menu);

Config::set('mod_info', $mod_info);
Config::set('mod_actions', $mod_actions);
Config::set('mod_menu', $mod_menu);
Config::set('mod_new', $mod_new);
#View::share('mod_actions', $mod_actions);
#print_r($app);

/***********************************************************************/


	#Route::controller('/admin/videogid/dic/{learning_forms}', 'AdminVideogidDicsController');
    #Route::resource('/admin/videogid/dic', 'AdminVideogidDicsController');
    #Route::controller('', 'PublicVideogidController');
    #Route::controller('', 'PublicVideogidController');

/***********************************************************************/

Route::get('import/plitka', function(){

    /**
     * Используемые таблицы
     */
    $tbl_cat = DB::table('category');
    $tbl_cat_desc = DB::table('category_description');
    $tbl_cat_images = DB::table('categ_image');

    $tbl_product = DB::table('product');
    $tbl_product_desc = DB::table('product_description');
    $tbl_product_cat = DB::table('product_to_category');

    $tbl_attr = DB::table('attribute');
    $tbl_attr_desc = DB::table('attribute_description');
    $tbl_product_attr = DB::table('product_attribute');

    /**
     * Очищаем словари
     */
    Dic::clear('countries');
    Dic::clear('factory');
    Dic::clear('collections');

    Dic::clear('products');
    Dic::clear('colors');
    Dic::clear('surface_type');

    Dic::clear('interiors');
    Dic::clear('projects');
    #die;

    Photo::truncate();
    Gallery::truncate();

    /**
     * Подготовка
     */
    $cat_oldid_newid = array();
    $product_type_plitka = Dic::valueBySlugs('product_type', 'plitka');

    /**
     * Предзагрузка данных категорий
     */
    $cats = $tbl_cat->orderBy('category_id', 'ASC')->get();
    $temp = new Collection();
    foreach ($cats as $cat)
        $temp[$cat->category_id] = $cat;
    $cats = $temp;
    #Helper::d($cats);

    $cats_desc = $tbl_cat_desc->orderBy('category_id', 'ASC')->get();
    $temp = new Collection();
    foreach ($cats_desc as $cat_desc)
        $temp[$cat_desc->category_id] = $cat_desc;
    $cats_desc = $temp;
    #Helper::d($cats_desc);

    $cats_images = $tbl_cat_images->orderBy('category_id', 'ASC')->get();
    $temp = array();
    foreach ($cats_images as $cat_image) {
        if (!isset($temp[$cat_image->category_id]))
            $temp[$cat_image->category_id] = array();
        $temp[$cat_image->category_id][] = $cat_image->image;
    }
    $cats_images = $temp;
    #Helper::dd($cats_images);

    /**
     * Предзагрузка данных продукции
     */
    $products = $tbl_product->orderBy('product_id', 'ASC')->get();
    $temp = new Collection();
    foreach ($products as $product)
        $temp[$product->product_id] = $product;
    $products = $temp;
    #Helper::tad($products);

    $products_desc = $tbl_product_desc->orderBy('product_id', 'ASC')->get();
    $temp = new Collection();
    foreach ($products_desc as $product_desc)
        $temp[$product_desc->product_id] = $product_desc;
    $products_desc = $temp;
    #Helper::tad($products_desc);

    $products_cat = $tbl_product_cat->orderBy('product_id', 'ASC')->get();
    $temp = new Collection();
    foreach ($products_cat as $product_cat)
        $temp[$product_cat->product_id] = $product_cat;
    $products_cat = $temp;
    #Helper::tad($products_cat);

    /**
     * Предзагрузка аттрибутов
     *
     */
    $attributes_desc = $tbl_attr_desc->get();
    $temp = new Collection();
    foreach ($attributes_desc as $attribute_desc)
        $temp[$attribute_desc->attribute_id] = $attribute_desc->name;
    $attributes_desc = $temp;

    $attributes_info = $tbl_attr->orderBy('attribute_id', 'ASC')->get();
    $temp = new Collection();
    foreach ($attributes_info as $attribute)
        $temp[$attribute->attribute_id] = $attributes_desc[$attribute->attribute_id];
    $attributes_info = $temp;
    #Helper::tad($attributes_info);
    /*
    [12] => Тип поверхности
    [13] => Цвет
    [14] => Упаковка
    [15] => Размер
    [16] => Цена
     */

    $attributes = $tbl_product_attr->get();
    #Helper::tad($attributes);
    $temp = array();
    foreach ($attributes as $attribute) {
        if (!isset($temp[$attribute->product_id]))
            $temp[$attribute->product_id] = array();

        $temp[$attribute->product_id][$attribute->attribute_id] = $attribute->text;
        ksort($temp[$attribute->product_id]);
    }
    ksort($temp);
    $attributes = $temp;
    #Helper::tad($attributes);



    /**
     * Импорт "категорий"
     * Первый уровень - Страна
     * Второй уровень - Фабрика
     * Третий уровень - Коллекция
     */

    /*
    foreach ($cats as $c => $cat) {
        #Helper::d($cat);
        $level = get_level($cats, $cat->category_id);

        if ($level == 0)
            continue;
        elseif ($level == 1)
            $model = 'country';
        elseif ($level == 2)
            $model = 'factory';
        elseif ($level == 3)
            $model = 'collection';

        Helper::d($cat);
        Helper::d($cats_desc[$cat->category_id]);
        Helper::d($model . ' / level = ' . $level);
        echo "<hr/>";
    }
    die;
    #*/

    /**
     * Import countries
     */
    foreach ($cats as $c => $cat) {
        #Helper::d($cat);
        $level = get_level($cats, $cat->category_id);

        if ($level != 1)
            continue;

        $name = trim($cats_desc[$cat->category_id]->name);
        $slug = Helper::translit($name);

        $dicval = DicVal::inject('countries', array(
            'slug' => $slug,
            'name' => $name,
        ));

        $cat_oldid_newid[$cat->category_id] = $dicval->id;

        Helper::d('Import country: ' . $name . ' = ' . $slug . ' / ' . $cat->category_id . ' => ' . $dicval->id);
        #Helper::ta($dicval);
    }

    echo "<hr/>";

    /**
     * Import factories
     */
    foreach ($cats as $c => $cat) {
        #Helper::d($cat);
        $level = get_level($cats, $cat->category_id);

        if ($level != 2)
            continue;

        $name = trim($cats_desc[$cat->category_id]->name);
        $slug = Helper::translit($name);
        $country_id = $cat_oldid_newid[$cat->parent_id];

        $dicval = DicVal::inject('factory', array(
            'slug' => $slug,
            'name' => $name,
            'fields' => array(
                'country_id' => $country_id
            )
        ));

        $cat_oldid_newid[$cat->category_id] = $dicval->id;

        Helper::d('Import factory: ' . $name . ' = ' . $slug . ' / ' . $cat->category_id . ' => ' . $dicval->id);
        #Helper::ta($dicval);
    }

    echo "<hr/>";

    /**
     * Import collections
     */
    foreach ($cats as $c => $cat) {
        #Helper::d($cat);
        $level = get_level($cats, $cat->category_id);

        if ($level != 3)
            continue;

        $parents = get_parents($cats, $cat->category_id);
        #Helper::d($parents);

        $name = trim($cats_desc[$cat->category_id]->name);
        $slug = Helper::translit($name);
        $country_id = $cat_oldid_newid[$parents[1]];
        $factory_id = $cat_oldid_newid[$parents[0]];


        /**
         * Import "interiors" - images of category (collection)
         */
        $gallery_id = 0;
        if (isset($cats_images[$cat->category_id]) && is_array($cats_images[$cat->category_id]) && count($cats_images[$cat->category_id])) {

            $gallery = new Gallery;
            $gallery->name = 'collection ' . $name;
            $gallery->save();
            $gallery_id = $gallery->id;
            #Helper::tad($gallery);

            foreach ($cats_images[$cat->category_id] as $c => $cat_image) {

                #$full_image_source = $cat_image;
                #$full_image_source = public_path('uploads/' . $cat_image);

                $temp = explode('.', $cat_image);
                $ext = array_pop($temp);
                $full_image_source = public_path('uploads/' . implode('.', $temp) . '-500x500.' . $ext);
                #echo $full_image_source; die;

                $photo_id = 0;
                if (file_exists($full_image_source)) {
                    /**
                     * Create image
                     */
                    $temp = explode('.', $full_image_source);
                    $ext = array_pop($temp);
                    $fileName = md5($cat->category_id) . '_' . ($c+1) . '.' . $ext;
                    copy($full_image_source, public_path('uploads/galleries/' . $fileName));
                    copy($full_image_source, public_path('uploads/galleries/thumbs/' . $fileName));
                    $photo = new Photo;
                    $photo->name = $fileName;
                    $photo->gallery_id = $gallery_id;
                    $photo->save();
                    #$photo_id = $photo->id;

                    #Helper::tad($photo);
                }
            }
        }


        $dicval = DicVal::inject('collections', array(
            'slug' => $slug,
            'name' => $name,
            'fields' => array(
                'description' => '',
                'gallery_id' => $gallery_id,
                'product_type_id' => $product_type_plitka->id,
                'country_id' => $country_id,
                'factory_id' => $factory_id,
            )
        ));

        if (isset($gallery) && is_object($gallery) && $gallery->id) {
            $gallery->name = $gallery->name . ' (' . $dicval->id . ')';
            $gallery->save();
        }
        unset($gallery);

        $cat_oldid_newid[$cat->category_id] = $dicval->id;

        Helper::d('Import collection: ' . $name . ' = ' . $slug . ' / ' . $cat->category_id . ' => ' . $dicval->id);
        #Helper::d($cat);
        #Helper::ta($dicval);
    }

    echo "<hr/>";

    #Helper::d($cat_oldid_newid);


    /**
     * Prepare products data
     */
    #Helper::ta($attributes);
    #Helper::tad($products);
    $surfaces = array();
    $colors = array();
    $sizes = array();
    foreach ($products as $product_id => $product) {

        if (!isset($attributes[$product_id]))
            continue;

        $attr = $attributes[$product_id];
        #Helper::dd($attr);

        if (trim($attr[12]))
            $surface_types[] = trim($attr[12]);
        if (trim($attr[13]))
            $colors[] = trim($attr[13]);
        if (trim($attr[15]))
            $sizes[] = trim($attr[15]);
    }

    $surface_types = array_unique($surface_types);
    $colors = array_unique($colors);
    #$sizes = array_unique($sizes);
    #Helper::d($surface_types);
    #Helper::d($colors);
    #Helper::dd($sizes);

    /**
     * Import surface types
     */
    $surface_types_ids = array();
    foreach ($surface_types as $surface_type) {

        $dicval = DicVal::inject('surface_type', array(
            'slug' => Helper::translit($surface_type),
            'name' => $surface_type,
        ));
        $surface_types_ids[$surface_type] = $dicval->id;
    }

    /**
     * Import colors
     */
    $colors_ids = array();
    foreach ($colors as $color) {

        $dicval = DicVal::inject('colors', array(
            'slug' => Helper::translit($color),
            'name' => $color,
        ));
        $colors_ids[$color] = $dicval->id;
    }

    /**
     * Import products
     */
    $count = 0;
    foreach ($products as $product_id => $product) {

        #Helper::d($product);
        #Helper::d($products_cat[$product_id]);
        #Helper::d($products_desc[$product_id]);
        #die;

        # Русская буква Х блеать!
        $product->image = str_replace('х', 'x', $product->image);

        #$full_image_source = public_path('uploads/' . $product->image);
        $full_image_source = public_path('uploads/data/plitka/' . $product_id . '-500x500.jpg');
        if (!file_exists($full_image_source)) {
            $temp = explode('.', $product->image);
            $ext = array_pop($temp);
            $full_image_source = public_path('uploads/' . implode('.', $temp) . '-500x500.' . $ext);
        }
        if (!file_exists($full_image_source)) {
            $temp = explode('.', $product->image);
            $ext = array_pop($temp);
            $full_image_source = public_path('uploads/' . implode('.', $temp) . '-228x228.' . $ext);
        }
        if (!file_exists($full_image_source)) {
            $temp = explode('.', $product->image);
            $ext = array_pop($temp);
            $full_image_source = public_path('uploads/' . implode('.', $temp) . '-100x100.' . $ext);
        }
        #Helper::d($full_image_source . ' - ' . (int)file_exists($full_image_source));

        $photo_id = 0;
        if (file_exists($full_image_source)) {
            /**
             * Create images
             */
            $temp = explode('.', $full_image_source);
            $ext = array_pop($temp);
            $fileName = md5($product_id) . '.' . $ext;
            copy($full_image_source, public_path('uploads/galleries/' . $fileName));
            copy($full_image_source, public_path('uploads/galleries/thumbs/' . $fileName));
            $photo = new Photo;
            $photo->name = $fileName;
            $photo->gallery_id = 0;
            $photo->save();
            $photo_id = $photo->id;
        }


        $product_category_id = @$products_cat[$product_id]->category_id;

        #$parents = get_parents($cats, $product_category_id);
        #Helper::d($parents);

        #$real_product_country_id = $cat_oldid_newid[$parents[1]];
        #$real_product_factory_id = $cat_oldid_newid[$parents[0]];
        $real_product_collection_id = @$cat_oldid_newid[$product_category_id];

        #Helper::d('Product country = ' . $real_product_country_id);
        #Helper::d('Product factory = ' . $real_product_factory_id);
        #Helper::d('Product collection = ' . $real_product_collection_id);
        #die;

        $name = @trim($products_desc[$product_id]->name);
        $slug = Helper::translit($name);

        $dicval = DicVal::inject('products', array(
            'slug' => $slug,
            'name' => $name,
            'fields' => array(
                'article' => '',
                'image_id' => $photo_id,
                'collection_id' => $real_product_collection_id,
                'format_id' => 0, ## not provided

                'size_text' => @trim($attributes[$product_id][15]),
                'package_text' => @trim($attributes[$product_id][14]),

                'color_id' => @$colors_ids[$attributes[$product_id][13]],
                'surface_type_id' => @$surface_types_ids[$attributes[$product_id][12]],

                'price' => @trim($attributes[$product_id][16]),
                'basic' => 1, ## not provided
            )
        ));

        Helper::d('Import product: ' . (++$count) . ' (' . $product_id . ')) ' . $name . ' = ' . $slug); # . ' / ' . $cat->category_id . ' => ' . $dicval->id);
    }

    die();
    #return '';
});

function get_level($elements, $id) {
    $level = 0;

    #Helper::dd($elements);

    $valid = true;
    $pid = $id;
    do {
        if (!isset($elements[$pid])) {
            $valid = false;
            break;
        }

        $element = $elements[$pid];
        $pid = $element->parent_id;
        ++$level;
    } while($pid > 0);

    return $valid ? $level : 0;
}

function get_parents($elements, $id) {
    $level = 0;

    #Helper::dd($elements);

    $return = array();

    $valid = true;
    $pid = $id;
    do {
        if (!isset($elements[$pid])) {
            $valid = false;
            break;
        }

        $element = $elements[$pid];
        $pid = $element->parent_id;
        $return[] = $pid;

        #++$level;
    } while($pid > 0);

    return $valid ? $return : false;
}

