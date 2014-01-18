<?php
/**
 * A select of Pages list
 *
 * version 1.0
 *
 * @author Michal J Musial
 */
class PerchFieldType_pageslist extends PerchAPI_FieldType
{
    public function render_inputs($details=array())
    {
        $id  = $this->Tag->input_id();
        $val = '';

        if (isset($details[$id]) && $details[$id]!='') {
            $json = $details[$id];
            $val  = $json['contentPagePath'];
        }

        $opts   = array();
        $opts[] = array('label'=>'', 'value'=>'');

        $pages = $this->_get_pages();

        if (count($pages)) {
            foreach($pages as $page) {
                $opts[] = array('label'=>str_repeat('-', $page['pageDepth']-1) . ' ' . $page['pageNavText'], 'value'=>$page['pagePath']);
            }
        }

        if(PerchUtil::count($opts)) {
        	$s = $this->Form->select($id, $opts, $val);
        } else {
        	$s = '-';
        }

        return $s;
    }

    public function get_raw($post=false, $Item=false)
    {
        $store  = array();
        $id     = $this->Tag->id();

        if ($post===false) $post = $_POST;

        if (isset($post[$id])) {
            $this->raw_item = trim($post[$id]);
            $store['contentPagePath'] = $this->raw_item;
            $store['_default'] = $this->raw_item;
        }

        return $store;
    }

    public function get_processed($raw=false)
    {
        if (is_array($raw) && isset($raw['contentPagePath'])) {
            return $raw['contentPagePath'];
        }

        return $raw;
    }

    public function get_search_text($raw=false)
    {
        return false;
    }

    /**
     * @return array
     */
    private function _get_pages()
    {
        $Pages      = new PerchContent_Pages;
        $opts = array(
            'from-path'            => '/',
            'levels'               => 0,
            'hide-extensions'      => true,
            'hide-default-doc'     => false,
            'flat'                 => true,
            'template'             => array('item.html'),
            'include-parent'       => false,
            'skip-template'        => true,
            'siblings'             => false,
            'only-expand-selected' => false,
            'add-trailing-slash'   => false,
            'navgroup'             => false,
            'access-tags'          => false,
            'include-hidden'       => true,
            'from-level'           => false,
            'use-attributes'       => false,
        );
        $navigation = $Pages->get_navigation($opts, '');
        return $navigation;
    }
}
