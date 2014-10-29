<?php

class ApplicationController extends BaseController {

    public static $name = 'application';
    public static $group = 'application';

    /****************************************************************************/

    ## Routing rules of module
    public static function returnRoutes($prefix = null) {

        Route::group(array(), function() {
            Route::get('/application/get', array('as' => 'application.get', 'uses' => __CLASS__.'@getApplicationData'));
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

        return Response::json($data, 200);
    }

}