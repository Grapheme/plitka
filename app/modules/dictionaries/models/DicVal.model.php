<?php

class DicVal extends BaseModel {

	protected $guarded = array();

    public $table = 'dictionary_values';
    #public $timestamps = false;

	public static $order_by = "name ASC";

    protected $fillable = array(
        'version_of',
        'dic_id',
        'slug',
        'name',
        'order',
        'lft',
        'rgt',
    );

	public static $rules = array(
		'name' => 'required',
	);


    /**
     * Связь возвращает словарь записи
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function dic() {
        return $this->belongsTo('Dictionary', 'dic_id');
    }

    /**
     * Связь возвращает все META-данные записи (для всех языков)
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function metas() {
        return $this->hasMany('DicValMeta', 'dicval_id', 'id');
    }

    /**
     * Связь возвращает META для записи, для текущего языка запроса
     *
     * @return mixed
     */
    public function meta() {
        return $this->hasOne('DicValMeta', 'dicval_id', 'id')->where('language', Config::get('app.locale'));
    }

    /**
     * Связь многие-ко-многим между элементами DicVal, с привязкой к dic_id
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function related_dicvals() {
        return $this->belongsToMany('DicVal', 'dictionary_values_rel', 'dicval_parent_id', 'dicval_child_id');
    }

    /**
     * Связь возвращает все доп. поля записи (как зависящие от локали, так и нет)
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function allfields() {
        return $this->hasMany('DicFieldVal', 'dicval_id', 'id')
        ;
    }

    /**
     * Связь возвращает доп. поля записи: как независящие от языка, так и зависящие (совпадающие с текущей локалью запроса)
     *
     * @return mixed
     */
    public function fields() {

        #Helper::dd($this);

        return $this
            ->hasMany('DicFieldVal', 'dicval_id', 'id')
            ->where('language', Config::get('app.locale'))
            ->orWhere('language', NULL)

            #->whereIn('name', array_keys((array)Config::get('dic.dic_name.fields')))

            ;
    }

    /**
     * Связь возвращает все текстовые поля записи (как зависящие от локали, так и нет)
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function alltextfields() {
        return $this->hasMany('DicTextFieldVal', 'dicval_id', 'id')
            ;
    }

    /**
     * Связь возвращает текстовые поля записи: как независящие от языка, так и зависящие (совпадающие с текущей локалью запроса)
     *
     * @return mixed
     */
    public function textfields() {
        return $this->hasMany('DicTextFieldVal', 'dicval_id', 'id')
            ->where('language', Config::get('app.locale'))
            ->orWhere('language', NULL)
            ;
    }

    /**
     * Связь возвращает все резервные копии записи
     *
     * @return mixed
     */
    public function versions() {
        return $this->hasMany('DicVal', 'version_of', 'id')->orderBy('updated_at', 'DESC');
    }

    /**
     * Связь возвращает оригинальную текущую версию записи
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function original_version() {
        return $this->hasOne('DicVal', 'id', 'version_of');
    }

    /**
     * Возвращает SEO-данные записи, для текущего языка запроса
     *
     * @return mixed
     */
    public function seo() {
        return $this->hasOne('Seo', 'unit_id', 'id')->where('module', 'dicval')
            ->where('language', Config::get('app.locale'))
            #->where('language', NULL)
            ;
    }

    /**
     * Связь возвращает все SEO-данные записи, для каждого из языков
     *
     * @return mixed
     */
    public function seos() {
        return $this->hasMany('Seo', 'unit_id', 'id')->where('module', 'dicval');
    }


    /**
     * Функция принимает в качестве аргументов ID словаря и массив с условиями для выборки из таблицы значений словарей.
     * Условия представляют собой одномерный массив, у которого:
     * - ключи: соответствуют столбцу key таблицы dictionary_fields_values
     * - значения: соответствуют столбцу value таблицы dictionary_fields_values
     * Функция делает выборку из БД, подсчитывая кол-во подходящих записей "значений словарей" под заданные условия.
     *
     * @param integer $dic_id
     * @param array $array
     * @return $this|DicFieldVal
     *
     * @author Alexander Zelensky
     */
    public static function count_by_fields($dic_id, $array) {
        #SELECT *  FROM `dictionary_fields_values` WHERE `dicval_id` = 162 AND `key` = 'collection_id' AND `value` = 161
        $tbl_dicval = new DicVal;
        $tbl_dicval = $tbl_dicval->getTable();
        $result = new DicFieldVal;
        $tbl_dicfieldval = $result->getTable();
        foreach ($array as $key => $value) {
            $result = $result->where('key', $key)->where('value', $value);
        }
        $result = $result
            ->join($tbl_dicval, $tbl_dicval.'.id', '=', $tbl_dicfieldval.'.dicval_id')
            ->where($tbl_dicval.'.dic_id', $dic_id)
        ;
        $result = $result->select($tbl_dicfieldval.'.*')->count();
        #Helper::ta($result);
        return $result;
    }

    /**
     * Функция принимает в качестве аргументов массив с ID словарей и массив с условиями для выборки из таблицы значений словарей.
     * Условия представляют собой одномерный массив, у которого:
     * - ключи: соответствуют столбцу key таблицы dictionary_fields_values
     * - значения: соответствуют столбцу value таблицы dictionary_fields_values
     * Функция делает выборку из БД, подсчитывая кол-во подходящих записей "значений словарей" под заданные условия для каждого словаря.
     *
     * @param array $dic_ids
     * @param array $array
     * @return $this|DicFieldVal
     *
     * @author Alexander Zelensky
     */
    public static function counts_by_fields($dic_ids = array(), $array = array()) {
        $tbl_dicval = new DicVal;
        $tbl_dicval = $tbl_dicval->getTable();
        $result = new DicFieldVal;
        $tbl_dicfieldval = $result->getTable();
        #Helper::d($array);
        foreach ($array as $key => $value) {
            #Helper::dd($value);
            if (is_array($value))
                $result = $result->where($tbl_dicfieldval.'.key', $key)->whereIn($tbl_dicfieldval.'.value', $value);
            else
                $result = $result->where($tbl_dicfieldval.'.key', $key)->where($tbl_dicfieldval.'.value', $value);
        }
        $result = $result
            ->join($tbl_dicval, $tbl_dicval.'.id', '=', $tbl_dicfieldval.'.dicval_id')
            ->whereIn($tbl_dicval.'.dic_id', $dic_ids)
            ->where($tbl_dicval.'.version_of', NULL)
        ;

        ## Делаем выборку всех подходящих записей...
        $result = $result->select($tbl_dicfieldval.'.*', $tbl_dicval.'.dic_id')->get();

        ## DEBUG
        $queries = DB::getQueryLog();
        #Helper::smartQuery(end($queries), 1); die;
        #Helper::ta($result);
        #Helper::smartQueries(1);

        ## Собираем числа в массив и группируем по dicval_id -> dic_id
        $counts = array();
        foreach ($result as $r => $record) {

            if (!@is_array($counts[$record->value]))
                $counts[$record->value] = array();

            if (!@is_array($counts[$record->value][$record->dic_id]))
                $counts[$record->value][$record->dic_id] = array();

            #@++$counts[$record->dicval_id][$record->dic_id];
            $counts[$record->value][$record->dic_id][$record->dicval_id] = 1;
        }
        foreach ($counts as $dicval_id => $data) {
            foreach ($data as $dic_id => $elements) {
                $counts[$dicval_id][$dic_id] = count($elements);
            }
        }
        #Helper::dd($counts);

        return $counts;
    }

    /**
     * Заготовка запроса для админской части - загрузка всех множественных связей о записи словаря
     *
     * @param $query
     * @return mixed
     */
    public function scopeAlldata_admin($query) {

        return
            $query
                ->with('metas')
                ->with('allfields', 'alltextfields')
                ->with('seos')
            ;
    }

    /**
     * Заготовка запроса для получения всех одиночных связей для публичной части
     *
     * @param $query
     * @return mixed
     */
    public function scopeAlldata($query) {

        return
            $query
                ->with('meta')
                ->with('fields', 'textfields')
                ->with('seo')
            ;
    }

    /**
     * Заготовка запроса - получение всех резервных копий записи словаря, для админской части
     *
     * @param $query
     * @return mixed
     */
    public function scopeWith_versions($query) {

        return $query->with('versions', 'original_version.versions');
    }


    /**
     * Функция позволяет отфильтровать записи словаря по доп. полю записи.
     * Только для использования в функции-замыкании доп. условий при получении записей словаря
     *
     * @param $query
     * @param $key
     * @param string $condition
     * @param null $value
     * @param bool $do_nothing_if_null
     * @return mixed
     */
    public function scopeFilter_by_field($query, $key, $condition = '=', $value = NULL, $do_nothing_if_null = false) {

        if ($value === NULL)
            if ($do_nothing_if_null)
                return $query;
            else
                $condition = '=';

        $tbl_dicval = (new DicVal())->getTable();
        $tbl_dic_field_val = (new DicFieldVal())->getTable();
        $rand_tbl_alias = md5(time() . rand(999999, 9999999));
        $query->join($tbl_dic_field_val . ' AS ' . $rand_tbl_alias, $rand_tbl_alias . '.dicval_id', '=', $tbl_dicval . '.id')
            ->where($rand_tbl_alias . '.key', '=', $key)
            ->where($rand_tbl_alias . '.value', $condition, $value);

        return $query;
    }

    /**
     * Функция позволяет отсортировть записи словаря по доп. полю записи.
     * Только для использования в функции-замыкании доп. условий при получении записей словаря
     *
     * @param $query
     * @param $key
     * @param string $order_method
     * @return mixed
     */
    public function scopeOrder_by_field($query, $key, $order_method = 'ASC') {
        $tbl_dicval = (new DicVal())->getTable();
        $tbl_dic_field_val = (new DicFieldVal())->getTable();
        $rand_tbl_alias = md5(time() . rand(999999, 9999999));
        $query->join($tbl_dic_field_val . ' AS ' . $rand_tbl_alias, $rand_tbl_alias . '.dicval_id', '=', $tbl_dicval . '.id')
            ->where($rand_tbl_alias . '.key', '=', $key)
            ->orderBy($rand_tbl_alias . '.value', $order_method)
        ;
        return $query;
    }


    /**
     * Подключаем доп. поле с помощью LEFT JOIN
     *
     * @param $query
     * @param $key
     * @param bool $as_alias
     * @param callable $additional_rules
     * @return string
     */
    public function scopeLeftJoin_field($query, $key, $as_alias = false, Closure $additional_rules = NULL) {

        return $this->scopeHook_up_field($query, $key, $as_alias, $additional_rules, 'leftJoin');
    }


    /**
     * Подключаем доп. поле с помощью JOIN
     *
     * @param $query
     * @param $key
     * @param bool $as_alias
     * @param callable $additional_rules
     * @return string
     */
    public function scopeJoin_field($query, $key, $as_alias = false, Closure $additional_rules = NULL) {

        return $this->scopeHook_up_field($query, $key, $as_alias, $additional_rules, 'join');
    }

    /*
        Пример использования:

        $query->hook_up_field('published_at', 'published_at', function($join, $value){
            # Подключаем только новости, у которых дата публикации меньше или совпадает с текущей датой
            $join->where($value, '<=', date('Y-m-d'));
        });
     */
    /**
     * Функция с помощью JOIN "подключает" доп. поле записи к выборке, после чего можно добавлять условия в запрос.
     * Условия для JOIN должны передаваться в функции-замыкании:
     *
     * @param $query
     * @param $key - название доп. поля; значение столбца key
     * @param bool $as_alias - название поля, которое будет присвоено после подключания; можно оставить пустым
     * @param callable $additional_rules - функция-замыкание с доп. условиями для JOIN
     * @param string $method - 'join' или 'leftJoin'
     * @return string - случайное имя таблицы DicFieldVal, используемое для осуществления JOIN
     */
    public function scopeHook_up_field($query, $key, $as_alias = false, Closure $additional_rules = NULL, $method) {

        if (!$as_alias)
            $as_alias = $key;

        $tbl_dicval = (new DicVal())->getTable();
        $tbl_dic_field_val = (new DicFieldVal())->getTable();
        $rand_tbl_alias = md5(time() . rand(999999, 9999999));
        $query
            ->addSelect(DB::raw('`' . $rand_tbl_alias . '`.`value` AS ' . $as_alias))

            ->$method($tbl_dic_field_val . ' AS ' . $rand_tbl_alias, function ($join) use ($rand_tbl_alias, $tbl_dicval, $tbl_dic_field_val, $key, $additional_rules) {
                $join->on($rand_tbl_alias . '.dicval_id', '=', $tbl_dicval . '.id');
                $join->where($rand_tbl_alias . '.key', '=', $key);

                if (is_callable($additional_rules)) {
                    /**
                     * Правильный способ применения доп. условий через функцию-замыкание
                     */
                    call_user_func($additional_rules, $join, $rand_tbl_alias . '.value');
                }
            })

            #->where($rand_tbl_alias . '.key', '=', $key)
        ;
        #return $query;

        return $rand_tbl_alias;
    }
    /**
     * Экстрактит все записи словаря внутри коллекции
     *
     * $collection = DicVal::extracts($collection);
     *
     * @param $elements
     * @param bool $unset
     * @param bool $extract_ids
     * @return Collection
     */
    public static function extracts($elements, $unset = false, $extract_ids = true) {
        $return = new Collection;
        #Helper::dd($return);
        foreach ($elements as $e => $element) {
            $return[($extract_ids ? $element->id : $e)] = $element->extract($unset);
        }
        return $return;
    }

    /**
     * Экстрактит одну запись словаря
     *
     * $value->extract();
     *
     * @param bool $unset
     * @return $this
     */
    public function extract($unset = false) {

        #Helper::ta($this);

        ## Extract all fields (without language & all i18n)
        if (isset($this->allfields) && @is_object($this->allfields) && count($this->allfields)) {

            foreach ($this->allfields as $field) {
                $this->{$field->key} = $field->value;
            }
            if ($unset)
                unset($this->allfields);

        } elseif (isset($this->fields) && @is_object($this->fields) && count($this->fields)) {

            ## Extract fields (with NULL language or language = default locale)
            foreach ($this->fields as $field) {
                $this->{$field->key} = $field->value;
            }
            if ($unset)
                unset($this->fields);

        }

        ## Extract all text fields (without language & all i18n)
        if (isset($this->alltextfields) && @is_object($this->alltextfields) && count($this->alltextfields)) {

            foreach ($this->alltextfields as $textfield) {
                $this->{$textfield->key} = $textfield->value;
            }
            if ($unset)
                unset($this->alltextfields);

        } elseif (isset($this->textfields) && @is_object($this->textfields) && count($this->textfields)) {

            ## Extract text fields (with NULL language or language = default locale)
            foreach ($this->textfields as $textfield) {
                $this->{$textfield->key} = $textfield->value;
            }
            if ($unset)
                unset($this->textfields);

        }

        ## Extract SEOs
        if (isset($this->seos)) {
            #Helper::tad($this->seos);
            if (count($this->seos) == 1 && count(Config::get('app.locales')) == 1) {
                $app_locales = Config::get('app.locales');
                foreach ($app_locales as $locale_sign => $locale_name)
                    break;
                foreach ($this->seos as $s => $seo) {
                    $this->seos[$locale_sign] = $seo;
                    break;
                }
                unset($this->seos[0]);
                #Helper::tad($this->seos);
            } else {
                foreach ($this->seos as $s => $seo) {
                    $this->seos[$seo->language] = $seo;
                    #Helper::d($s . " != " . $seo->language);
                    if ($s != $seo->language || $s === 0)
                        unset($this->seos[$s]);
                }
            }
        }

        ## Extract metas
        if (isset($this->metas)) {
            foreach ($this->metas as $m => $meta) {
                $this->metas[$meta->language] = $meta;
                if ($m != $meta->language || $m === 0)
                    unset($this->metas[$m]);
            }
        }

        ## Extract meta
        if (isset($this->meta)) {

            if (
                is_object($this->meta)
                && ($this->meta->language == Config::get('app.locale') || $this->meta->language == NULL)
            ) {
                if ($this->meta->name != '')
                    $this->name = $this->meta->name;

            }

            if ($unset)
                unset($this->meta);
        }

        #Helper::ta($this);

        ## Extract versions
        if (isset($this->versions)) {
            foreach ($this->versions as $v => $version) {
                $this->versions[$version->id] = $version;
                if ($v != $version->id || (int)$v === 0)
                    unset($this->versions[$v]);
            }
        }

        return $this;
    }


    public static function extracts_related($elements, $dicval_data = false, $extract_ids = true) {
        $return = new Collection;
        #Helper::dd($return);
        foreach ($elements as $e => $element) {
            $return[($extract_ids ? $element->id : $e)] = $element->extract_related($dicval_data, $extract_ids);
        }
        return $return;
    }


    public function extract_related($dicval_data = false, $extract_ids = true) {

        ## Extract relations
        if (isset($this->related_dicvals) && count($this->related_dicvals)) {
            $array = array();
            #Helper::tad($this->related_dicvals);
            foreach ($this->related_dicvals as $r => $relation) {

                $key = @$dicval_data[$relation->dic_id] ?: $relation->dic_id;
                if (!isset($array[$key]) ||!is_array($array[$key]))
                    $array[$key] = array();

                if ($extract_ids)
                    $array[$key][$relation->id] = $relation;
                else
                    $array[$key][] = $relation;
            }
            #Helper::tad($array);
            unset($this->related_dicvals);
            $this->related_dicvals = $array;
        }
        return $this;
    }

    /*
     * USAGE:
     *
       $dicval = DicVal::inject('transactions', array(
            'slug' => NULL,
            'name' => $nickname,
            'fields' => array(
                'quest_id' => $quest_id,
                'payment_amount' => $amount,
                'payment_date' => date("Y-m-d H:i:s"),
                'payment_method' => 'dengionline',
                'payment_full' => json_encode(array('paymode' => $mode_type)),
            ),
            'fields_i18n' => array(
                'ru' => array(
                    'quest_id' => $quest_id,
                    'payment_amount' => $amount,
                    'payment_date' => date("Y-m-d H:i:s"),
                    'payment_method' => 'dengionline',
                    'payment_full' => json_encode(array('paymode' => $mode_type)),
                ),
            ),
            'textfields' => array(
                'description_text' => 'some long text data',
            ),
            'textfields_i18n' => array(
                'ru' => array(
                    'description_text_i18n' => 'some long i18n text data',
                ),
            ),
            'meta' => array(
                'en' => array(
                    'name' => 'ololo',
                ),
            ),
        ));
     */
    /**
     * Добавляет запись в словарь.
     * Первый параметр - системное имя словаря
     * Второй параметр - массив указанной выше структуры, с данными и полями записи
     *
     * @param $dic_slug
     * @param $array
     * @return DicVal
     */
    public static function inject($dic_slug, $array) {

        #Helper::d($dic_slug);
        #Helper::d($array);

        ## Find DIC
        $dic = Dic::where('slug', $dic_slug)->first();
        if (!is_object($dic))
            return false;

        ## Create DICVAL
        $dicval = new DicVal;
        $dicval->dic_id = $dic->id;
        $dicval->slug = @$array['slug'] ?: NULL;
        $dicval->name = @$array['name'] ?: NULL;
        $dicval->save();


        ## CREATE FIELDS
        if (@isset($array['fields']) && is_array($array['fields']) && count($array['fields'])) {
            $fields = array();
            foreach ($array['fields'] as $key => $value) {
                $dicval_field = new DicFieldVal();
                $dicval_field->dicval_id = $dicval->id;
                $dicval_field->language = is_array($value) && isset($value['language']) ? @$value['language'] : NULL;
                $dicval_field->key = $key;
                $dicval_field->value = is_array($value) ? @$value['value'] : $value;
                $dicval_field->save();

                $fields[] = $dicval_field;
            }
            #$dicval->fields = $fields;
        }

        ## CREATE FIELDS_I18N
        if (@isset($array['fields_i18n']) && is_array($array['fields_i18n']) && count($array['fields_i18n'])) {
            $fields_i18n = array();
            foreach ($array['fields_i18n'] as $locale_sign => $fields) {

                if (!@is_array($fields) || !@count($fields))
                    continue;

                $temp = array();
                foreach ($fields as $key => $value) {

                    $dicval_field_i18n = new DicFieldVal();
                    $dicval_field_i18n->dicval_id = $dicval->id;
                    $dicval_field_i18n->language = $locale_sign;
                    $dicval_field_i18n->key = $key;
                    $dicval_field_i18n->value = is_array($value) ? @$value['value'] : $value;
                    $dicval_field_i18n->save();

                    $temp[] = $dicval_field_i18n;
                }
                $fields_i18n[$locale_sign] = $temp;
            }
            #$dicval->fields_i18n = $fields_i18n;
        }


        ## CREATE TEXT FIELDS
        if (@isset($array['textfields']) && is_array($array['textfields']) && count($array['textfields'])) {
            $textfields = array();
            foreach ($array['textfields'] as $key => $value) {
                $dicval_textfield = new DicTextFieldVal();
                $dicval_textfield->dicval_id = $dicval->id;
                $dicval_textfield->language = is_array($value) && isset($value['language']) ? @$value['language'] : NULL;
                $dicval_textfield->key = $key;
                $dicval_textfield->value = is_array($value) ? @$value['value'] : $value;
                $dicval_textfield->save();

                $textfields[] = $dicval_textfield;
            }
            #$dicval->textfields = $textfields;
        }

        ## CREATE TEXT FIELDS_I18N
        if (@isset($array['textfields_i18n']) && is_array($array['textfields_i18n']) && count($array['textfields_i18n'])) {
            $textfields_i18n = array();
            foreach ($array['textfields_i18n'] as $locale_sign => $textfields) {

                if (!@is_array($textfields) || !@count($textfields))
                    continue;

                $temp = array();
                foreach ($textfields as $key => $value) {

                    $dicval_textfield_i18n = new DicTextFieldVal();
                    $dicval_textfield_i18n->dicval_id = $dicval->id;
                    $dicval_textfield_i18n->language = $locale_sign;
                    $dicval_textfield_i18n->key = $key;
                    $dicval_textfield_i18n->value = is_array($value) ? @$value['value'] : $value;
                    $dicval_textfield_i18n->save();

                    $temp[] = $dicval_textfield_i18n;
                }
                $textfields_i18n[$locale_sign] = $temp;
            }
            #$dicval->textfields_i18n = $textfields_i18n;
        }


        ## CREATE META
        if (@isset($array['meta']) && is_array($array['meta']) && count($array['meta'])) {
            $metas = array();
            foreach ($array['meta'] as $locale_sign => $fields) {

                if (!@is_array($fields) || !@count($fields))
                    continue;

                $temp = array();
                foreach ($fields as $key => $value) {

                    $dicval_meta = new DicValMeta();
                    $dicval_meta->dicval_id = $dicval->id;
                    $dicval_meta->language = $locale_sign;

                    $dicval_meta->name = is_array($value) ? @$value['name'] : $value;
                    $dicval_meta->save();

                    $temp[] = $dicval_meta;
                }
                $metas[$locale_sign] = $temp;
            }
            #$dicval->metas = $metas;
        }

        ## RETURN EXTRACTED DICVAL
        return $dicval;
    }



    /**
     * DEPRECATED
     * Устаревшие и не рекомендуемые к использованию методы
     */

    /**
     * relations - алиас для свзяи, желательно использовать related_dicvals()
     */
    public function relations() {
        return $this->related_dicvals();
    }

}