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
            $data[$dic->slug] = Dic::valuesBySlug($dic->slug);
            $data[$dic->slug] = DicVal::extracts($data[$dic->slug], 1);
            $data[$dic->slug] = Dic::modifyKeys($data[$dic->slug], 'id');
        }

        $data['galleries'] = Gallery::all();
        $data['galleries'] = Dic::modifyKeys($data['galleries'], 'id');

        $gal = array();
        $data['photos'] = Photo::all();
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

        if (Input::get('nojson') == 1)
            Helper::tad($data);

        return Response::json($data, 200, array(
            'Access-Control-Allow-Origin' => '*',
        ));
    }

    public function postFeedback() {

        if(!Request::ajax())
            App::abort(404);

        $json_request = array('status' => TRUE, 'responseText' => '');

        ## Send confirmation to user - with password
        $data = Input::all();

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

        #Helper::dd($result);
        return Response::json($json_request, 200);
    }

}