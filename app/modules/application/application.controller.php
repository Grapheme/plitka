<?php

class ApplicationController extends BaseController {

    public static $name = 'application';
    public static $group = 'application';

    /****************************************************************************/

    ## Routing rules of module
    public static function returnRoutes($prefix = null) {

        Route::group(array(), function() {
            Route::get('/application/get', array('as' => 'application.get', 'uses' => __CLASS__.'@getApplicationData'));

            Route::any('/ajax/feedback', array('as' => 'ajax.feedback', 'uses' => __CLASS__.'@postFeedback'));
            Route::any('/ajax/search', array('as' => 'ajax.search', 'uses' => __CLASS__.'@postSearch'));
        });
    }


    /****************************************************************************/

	public function __construct(){

        $this->module = array(
            'name' => self::$name,
            'group' => self::$group,
            'tpl' => static::returnTpl(),
            'gtpl' => static::returnTpl(),
            'class' => __CLASS__,

            #'entity' => self::$entity,
            #'entity_name' => self::$entity_name,
        );
        View::share('module', $this->module);
	}

    public function getApplicationData() {

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
                $query->with('related_dicvals');
            });
            $data[$dic->slug] = DicVal::extracts($data[$dic->slug], 1);
            $data[$dic->slug] = Dic::modifyKeys($data[$dic->slug], 'id');
        }

        #Helper::tad($data['collections']);

        $data['galleries'] = Gallery::all();
        $data['galleries'] = Dic::modifyKeys($data['galleries'], 'id');

        $gal = array();
        $data['photos'] = Photo::orderBy('order', 'ASC')->get();
        $data['photos'] = Dic::modifyKeys($data['photos'], 'id');
        foreach ($data['photos'] as $p => $photo) {

            $data['photos'][$p]['thumb'] = $photo->thumb();
            $data['photos'][$p]['full'] = $photo->full();

            if (!$photo->gallery_id)
                continue;

            if (!isset($gal[$photo->gallery_id]) || !is_array($gal[$photo->gallery_id]))
                $gal[$photo->gallery_id] = array();

            $gal[$photo->gallery_id][] = $photo->id;
        }

        foreach ($gal as $gallery_id => $photos) {
            $data['galleries'][$gallery_id]->photos = $photos;
        }

        Helper::tad($data['galleries']);

        $collections_prices = array();
        $collections_colors = array();
        $collections_surface_types = array();
        $collections_formats = array();
        if (isset($data['products']) && count($data['products'])) {
            foreach ($data['products'] as $product) {

                #Helper::tad($product);

                $price = (int)$product->price;
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
                        $collections_colors[$color_id] = array();
                    }
                    if (!in_array($collection_id, $collections_colors[$color_id])) {
                        $collections_colors[$color_id][] = $collection_id;
                    }
                }

                if ($surface_type_id && $collection_id) {
                    if (!isset($collections_surface_types[$surface_type_id])) {
                        $collections_surface_types[$surface_type_id] = array();
                    }
                    if (!in_array($collection_id, $collections_surface_types[$surface_type_id])) {
                        $collections_surface_types[$surface_type_id][] = $collection_id;
                    }
                }

                if ($format_id && $collection_id) {
                    if (!isset($collections_formats[$format_id])) {
                        $collections_formats[$format_id] = array();
                    }
                    if (!in_array($collection_id, $collections_formats[$format_id])) {
                        $collections_formats[$format_id][] = $collection_id;
                    }
                }

            }
        }
        #Helper::dd($prices);

        $data['collections_prices'] = $collections_prices;
        $data['collections_colors'] = $collections_colors;
        $data['collections_surface_types'] = $collections_surface_types;
        $data['collections_formats'] = $collections_formats;

        $scope_ids = array();
        $collections_surfaces = array();
        foreach ($data['collections'] as $collection) {
            #$scope_ids[]

            if ($collection->surface_id) {
                if (!isset($collections_surfaces[$collection->surface_id]) || !is_array($collections_surfaces[$collection->surface_id]))
                    $collections_surfaces[$collection->surface_id] = array();
                if (!in_array($collection->id, $collections_surfaces[$collection->surface_id]))
                    $collections_surfaces[$collection->surface_id][] = $collection->id;
            }

            if (count($collection->related_dicvals)) {
                #Helper::tad($collection);
                foreach ($collection->related_dicvals as $scope) {

                    if (!isset($scope_ids[$scope->id]) || !is_array($scope_ids[$scope->id])) {
                        $scope_ids[$scope->id] = array();
                    }

                    if (!in_array($collection->id, $scope_ids[$scope->id])) {
                        $scope_ids[$scope->id][] = $collection->id;
                    }
                }
            }
        }
        #Helper::tad($scope_ids);
        $data['collections_scopes'] = $scope_ids;
        $data['collections_surfaces'] = $collections_surfaces;

        if (Input::get('nojson') == 1)
            Helper::tad($data);

        return Response::json($data, 200, array(
            'Access-Control-Allow-Origin' => '*',
        ));
    }

    public function postFeedback() {

        if(Request::method() != 'POST')
            App::abort(404);

        $json_request = array('status' => FALSE, 'responseText' => '');

        ## Send confirmation to user - with password
        $data = Input::all();

        if (!count($data)) {
            $json_request['responseText'] = 'Недостаточно переданных данных';
            return Response::json($json_request, 200, array(
                'Access-Control-Allow-Origin' => '*',
            ));
        }

        Mail::send('emails.feedback', $data, function ($message) use ($data) {

            #$message->from(Config::get('mail.from.address'), Config::get('mail.from.name'));

            $from_email = Dic::valueBySlugs('options', 'from_email');
            $from_email = is_object($from_email) ? $from_email->name : 'no@reply.ru';
            $from_name = Dic::valueBySlugs('options', 'from_name');
            $from_name = is_object($from_name) ? $from_name->name : 'No-reply';

            $message->from($from_email, $from_name);
            $message->subject('Новое сообщение обратной связи - ' . @$data['name']);

            #$email = Config::get('mail.feedback.address');
            $email = Dic::valueBySlugs('options', 'email');
            $email = is_object($email) ? $email->name : 'dev@null.ru';

            $emails = array();
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
        return Response::json($json_request, 200, array(
            'Access-Control-Allow-Origin' => '*',
        ));
    }


    public function postSearch() {

        #if(Request::method() != 'POST')
        #    App::abort(404);

        $json_request = array('status' => FALSE, 'responseText' => '');

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
        return Response::json($json_request, 200, array(
            'Access-Control-Allow-Origin' => '*',
        ));
    }

}