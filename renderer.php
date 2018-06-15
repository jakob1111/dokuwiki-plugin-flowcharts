<?php

class renderer_plugin_flowcharts extends Doku_Renderer_xhtml {
    function underline_open() {
        $xhtml = '<em class="u">';

        $xhtml = htmlentities($xhtml,ENT_NOQUOTES);
        $xhtml = str_replace(array('"', '='), array('&#39;', '&#61;'), $xhtml);

        $this->doc .= $xhtml;
    }

    function underline_close() {
        $xhtml = '</em>';

        $xhtml = htmlentities($xhtml,ENT_NOQUOTES);
        $xhtml = str_replace(array('"', '='), array('&#39;', '&#61;'), $xhtml);

        $this->doc .= $xhtml;
    }

    function internallink($id, $name = null, $search = null, $returnonly = false, $linktype = 'content') {
        $xhtml = parent::internallink($id, $name, $search, true, $linktype);


        $xhtml = htmlentities($xhtml,ENT_NOQUOTES);
        $xhtml = str_replace(array('"', '='), array('&#39;', '&#61;'), $xhtml);

        //output formatted
        if($returnonly) {
            return $xhtml;
        } else {
            $this->doc .= $xhtml;
        }
    }

    function externallink($url, $name = null, $returnonly = false) {
        $xhtml = parent::externallink($url, $name = null, true);


        $xhtml = htmlentities($xhtml,ENT_NOQUOTES);
        $xhtml = str_replace(array('"', '='), array('&#39;', '&#61;'), $xhtml);

        //output formatted
        if($returnonly) {
            return $xhtml;
        } else {
            $this->doc .= $xhtml;
        }
    }

    function internalmedia($src, $title = null, $align = null, $width = null,
                           $height = null, $cache = null, $linking = null, $return = false) {
        $xhtml = parent::internalmedia($src, $title, $align, $width, $height, $cache, $linking, true);

        $xhtml = htmlentities($xhtml,ENT_NOQUOTES);
        $xhtml = str_replace(array('"', '='), array('&#39;', '&#61;'), $xhtml);

        //output formatted
        if($return) {
            return $xhtml;
        } else {
            $this->doc .= $xhtml;
        }
    }

    /**
     * Render plain text data
     *
     * @param $text
     */
    function cdata($text) {
        $this->doc .= $text;
    }
}