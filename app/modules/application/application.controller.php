<?php

class ApplicationController extends BaseController {

    public static $name = 'application';
    public static $group = 'application';

    /****************************************************************************/

    ## Routing rules of module
    public static function returnRoutes($prefix = null) {

        Route::group([], function() {
            Route::get('/application/get', ['as' => 'application.get', 'uses' => __CLASS__.'@getApplicationData']);

            Route::any('/ajax/feedback', ['as' => 'ajax.feedback', 'uses' => __CLASS__.'@postFeedback']);
            Route::any('/ajax/search', ['as' => 'ajax.search', 'uses' => __CLASS__.'@postSearch']);

            Route::any('/sitemap.xml', ['as' => 'app.sitemap.xml', 'uses' => __CLASS__.'@getSitemapXml']);
        });
    }


    /****************************************************************************/

	public function __construct(){

        $this->cache_key = 'application.data';
        $this->cache_time = 1;

        #$cache_key = 'application.data';
        #$cache_time = 5;

        $this->module = [
            'name' => self::$name,
            'group' => self::$group,
            'tpl' => static::returnTpl(),
            'gtpl' => static::returnTpl(),
            'class' => __CLASS__,

            #'entity' => self::$entity,
            #'entity_name' => self::$entity_name,
        ];
        View::share('module', $this->module);
	}

	public function getSitemapXml(){

        $data = Cache::get($this->cache_key);
        $data = json_decode($data, 1);

        if (Input::get('nojson') == 1)
            Helper::tad($data);

        return Response::json($data, 200, [
            'Access-Control-Allow-Origin' => '*',
        ]);
 	}

    public function getApplicationData() {

        $debug = Config::get('app.debug');
        $nocache = Input::get('nocache');

        if (Cache::has($this->cache_key) && !@$debug && !$nocache) {
            #Helper::dd('111');

            $data = Cache::get($this->cache_key);
            $data = json_decode($data, 1);

            if (Input::get('nojson') == 1)
                Helper::tad($data);

            return Response::json($data, 200, [
                'Access-Control-Allow-Origin' => '*',
            ]);
        }

        $data = new Collection();

        /**
         * Load pages
         */
        $pages = Page::with('meta', 'blocks.meta', 'seo')->get();
        foreach ($pages as $p => $page) {
            #$page->extract(true);
            $pages[$p] = $page->extract(true);
        }
        $pages = Dic::modifyKeys($pages, 'slug');
        #Helper::tad($pages);
        $data['pages'] = $pages;

        /**
         * Load all dics & data
         */
        $dics = Dic::all();
        #Helper::tad($dics);

        foreach ($dics as $dic) {
            $data[$dic->slug] = Dic::valuesBySlug($dic->slug, function($query){
                $query->with('related_dicvals', 'seo');
            });
            $data[$dic->slug] = DicVal::extracts($data[$dic->slug], 1);
            $data[$dic->slug] = Dic::modifyKeys($data[$dic->slug], 'id');
        }

        #Helper::ta($data['product_type']);
        $product_types = Dic::modifyKeys($data['product_type'], 'slug');
        #Helper::ta($product_types);
        $dic_product_type_others_id = @$product_types['others']['id'] ?: false;
        #echo $dic_product_type_others_id;
        #die;

        #Helper::tad($data['collections']);

        $data['galleries'] = Gallery::all();
        $data['galleries'] = Dic::modifyKeys($data['galleries'], 'id');

        $gal = [];
        $data['photos'] = Photo::orderBy('order', 'ASC')->get();
        $data['photos'] = Dic::modifyKeys($data['photos'], 'id');
        foreach ($data['photos'] as $p => $photo) {

            $data['photos'][$p]['thumb'] = $photo->thumb();
            $data['photos'][$p]['full'] = $photo->full();

            if (!$photo->gallery_id)
                continue;

            if (!isset($gal[$photo->gallery_id]) || !is_array($gal[$photo->gallery_id]))
                $gal[$photo->gallery_id] = [];

            $gal[$photo->gallery_id][] = $photo->id;
        }

        foreach ($gal as $gallery_id => $photos) {
            if (isset($data['galleries'][$gallery_id]))
                $data['galleries'][$gallery_id]->photos = $photos;
        }

        #Helper::tad($data['galleries']);

        $course_euro_rub = Dic::valueBySlugs('options', 'course_euro_rub');
        if (is_object($course_euro_rub))
            $course_euro_rub = $course_euro_rub->name;
        else
            $course_euro_rub = 0;

        $collections_prices = [];
        $collections_colors = [];
        $collections_surface_types = [];
        $collections_formats = [];

        if (Input::get('dbg-min-collection-price')) {
            var_dump('$course_euro_rub => ' . $course_euro_rub);
        }

        if (isset($data['products']) && count($data['products'])) {
            foreach ($data['products'] as $product) {


                if (Input::get('dbg-min-collection-price')) {
                    if ($product->collection_id == Input::get('dbg-min-collection-price')) {
                        Helper::ta($product);
                    }
                }


                $price = (int)(str_replace(' ', '', $product->price));


                if (Input::get('dbg-min-collection-price')) {
                    if ($product->collection_id == Input::get('dbg-min-collection-price')) {
                        Helper::d('$price ($product->price) = ' . $price);
                    }
                }


                if (isset($product->price_euro) && (int)$product->price_euro > 0 && $course_euro_rub > 0) {
                    $price = (int)$product->price_euro * $course_euro_rub;
                }


                if (Input::get('dbg-min-collection-price')) {
                    if ($product->collection_id == Input::get('dbg-min-collection-price')) {
                        Helper::d(
                            'isset($product->price_euro) => ' . isset($product->price_euro)
                            . ' && (int)$product->price_euro > 0 => ' . ((int)$product->price_euro > 0)
                            . ' && $course_euro_rub > 0 => ' . ($course_euro_rub > 0)
                        );
                        Helper::d('$price = (int)$product->price_euro * $course_euro_rub; => ' . $price);
                    }
                }


                $color_id = (int)$product->color_id;
                $surface_type_id = (int)$product->surface_type_id;
                $collection_id = (int)$product->collection_id;
                $format_id = (int)$product->format_id;

                if (
                    $product->collection_id
                    && $product->basic
                    && $price > 0
                    && (!isset($collections_prices[$product->collection_id]) || $price < $collections_prices[$product->collection_id])
                )
                    $collections_prices[$product->collection_id] = $price;

                if ($color_id && $collection_id) {
                    if (!isset($collections_colors[$color_id])) {
                        $collections_colors[$color_id] = [];
                    }
                    if (!in_array($collection_id, $collections_colors[$color_id])) {
                        $collections_colors[$color_id][] = $collection_id;
                    }
                }

                if ($surface_type_id && $collection_id) {
                    if (!isset($collections_surface_types[$surface_type_id])) {
                        $collections_surface_types[$surface_type_id] = [];
                    }
                    if (!in_array($collection_id, $collections_surface_types[$surface_type_id])) {
                        $collections_surface_types[$surface_type_id][] = $collection_id;
                    }
                }

                if ($format_id && $collection_id) {
                    if (!isset($collections_formats[$format_id])) {
                        $collections_formats[$format_id] = [];
                    }
                    if (!in_array($collection_id, $collections_formats[$format_id])) {
                        $collections_formats[$format_id][] = $collection_id;
                    }
                }

            }
        }
        #Helper::dd($prices);

        if (Input::get('dbg-min-collection-price')) {
            Helper::dd($collections_prices);
        }

        $data['collections_prices'] = $collections_prices;
        $data['collections_colors'] = $collections_colors;
        $data['collections_surface_types'] = $collections_surface_types;
        $data['collections_formats'] = $collections_formats;

        $scope_ids = [];
        $collections_surfaces = [];
        $dic_others_collections = new Collection();
        foreach ($data['collections'] as $collection) {
            #$scope_ids[]

            if ($collection->surface_id) {
                if (!isset($collections_surfaces[$collection->surface_id]) || !is_array($collections_surfaces[$collection->surface_id]))
                    $collections_surfaces[$collection->surface_id] = [];
                if (!in_array($collection->id, $collections_surfaces[$collection->surface_id]))
                    $collections_surfaces[$collection->surface_id][] = $collection->id;
            }

            if (count($collection->related_dicvals)) {
                #Helper::tad($collection);
                foreach ($collection->related_dicvals as $scope) {

                    if (!isset($scope_ids[$scope->id]) || !is_array($scope_ids[$scope->id])) {
                        $scope_ids[$scope->id] = [];
                    }

                    if (!in_array($collection->id, $scope_ids[$scope->id])) {
                        $scope_ids[$scope->id][] = $collection->id;
                    }
                }
            }

            if ($dic_product_type_others_id && $collection->product_type_id == $dic_product_type_others_id) {
                $dic_others_collections[$collection->name] = $collection;
            }

        }

        $dic_others_collections = $dic_others_collections->toArray();
        ksort($dic_others_collections);
        $dic_others_collections = Dic::modifyKeys($dic_others_collections, 'id');
        #Helper::tad($dic_others_collections);
        $data['product_type_others_collections'] = $dic_others_collections;

        #Helper::tad($scope_ids);
        $data['collections_scopes'] = $scope_ids;
        $data['collections_surfaces'] = $collections_surfaces;

        if (Cache::has($this->cache_key)) {
            Cache::put($this->cache_key, json_encode($data), $this->cache_time);
        } else {
            Cache::add($this->cache_key, json_encode($data), $this->cache_time);
        }

        if (Input::get('nojson') == 1)
            Helper::tad($data);

        return Response::json($data, 200, [
            'Access-Control-Allow-Origin' => '*',
        ]);
    }

    public function postFeedback() {

        if(Request::method() != 'POST')
            App::abort(404);

        $json_request = ['status' => FALSE, 'responseText' => ''];

        ## Send confirmation to user - with password
        $data = Input::all();

        if (!count($data)) {
            $json_request['responseText'] = 'Недостаточно переданных данных';
            return Response::json($json_request, 200, [
                'Access-Control-Allow-Origin' => '*',
            ]);
        }

        Mail::send('emails.feedback', $data, function ($message) use ($data) {

            #$message->from(Config::get('mail.from.address'), Config::get('mail.from.name'));

            $from_email = Dic::valueBySlugs('options', 'from_email');
            $from_email = is_object($from_email) ? $from_email->name : 'no@reply.ru';
            $from_name = Dic::valueBySlugs('options', 'from_name');
            $from_name = is_object($from_name) ? $from_name->name : 'No-reply';

            $message->from($from_email, $from_name);
            $message->subject('Новое сообщение обратной связи: ' . @$data['name']);

            #$email = Config::get('mail.feedback.address');
            $email = Dic::valueBySlugs('options', 'email');
            $email = is_object($email) ? $email->name : 'dev@null.ru';

            $emails = [];
            if (strpos($email, ',')) {
                $emails = explode(',', $email);
                foreach ($emails as $e => $email)
                    $emails[$e] = trim($email);
                $email = array_shift($emails);
            }

            $message->to($email);

            #$ccs = Config::get('mail.feedback.cc');
            $ccs = $emails;
            if (isset($ccs) && is_array($ccs) && count($ccs))
                foreach ($ccs as $cc)
                    $message->cc($cc);

        });

        $json_request['status'] = TRUE;

        #Helper::dd($result);
        return Response::json($json_request, 200, [
            'Access-Control-Allow-Origin' => '*',
        ]);
    }


    public function postSearch() {

        #if(Request::method() != 'POST')
        #    App::abort(404);

        $json_request = ['status' => FALSE, 'responseText' => ''];

        #Helper::d(Input::all());
        $q = Input::get('q');

        $sphinx_match_mode = \Sphinx\SphinxClient::SPH_MATCH_ANY;

        /**
         * articles
         */
        $results['articles'] = SphinxSearch::search($q, 'plitka_articles_index')->setMatchMode($sphinx_match_mode)->query();
        $results_counts['articles'] = @count($results['articles']['matches']);
        $results_matches['articles'] = @$results['articles']['matches'];

        /**
         * collections
         */
        $results['collections'] = SphinxSearch::search($q, 'plitka_collections_index')->setMatchMode($sphinx_match_mode)->query();
        $results_counts['collections'] = @count($results['collections']['matches']);
        $results_matches['collections'] = @$results['collections']['matches'];

        #Helper::dd($results_matches);

        $results = $results_matches;

        $json_request['status'] = TRUE;
        $json_request['results'] = $results;

        if (Input::get('nojson') == 1)
            Helper::tad($results);

        #Helper::dd($result);
        return Response::json($json_request, 200, [
            'Access-Control-Allow-Origin' => '*',
        ]);
    }

}