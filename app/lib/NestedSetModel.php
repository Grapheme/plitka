<?php

class NestedSetModel {

    public $data, $id_left_right, $i;

	public function __construct($data = null) {

		$this->data = $data;
        $this->id_left_right = array();
        $this->i = 0;
	}
	
	public function get_id_left_right($data = null){

        if ($data)
            $this->data = $data;

        #Helper::dd($this->data);

        $this->id_left_right = array();
        $this->i = 0;

        $this->parse_level($this->data);

        #Helper::dd($this->id_left_right);
        return $this->id_left_right;
	}


    private function parse_level($array) {
        if (is_array($array) && count($array))
            foreach ($array as $key => $value) {
                if (isset($value['id'])) {
                    $this->id_left_right[$value['id']] = array();
                    $this->id_left_right[$value['id']]['left'] = ++$this->i;
                }
                if (isset($value['children'])) {
                    $this->parse_level($value['children']);
                }
                if (isset($value['id'])) {
                    $this->id_left_right[$value['id']]['right'] = ++$this->i;
                }
            }
    }

}
