<?php 

//https://code.tutsplus.com/articles/custom-post-type-helper-class--wp-25104

class HTML_Minify {
    protected $tif_compress_css = true;
    protected $tif_compress_js = true;
    protected $tif_info_comment = true;
    protected $tif_remove_comments = true;
    protected $html;

    public function __construct($html) {
        if (!empty($html)) {
            $this->tif_parseHTML($html);
        }
    }

    public function __toString() {
        return $this->html;
    }

    protected function tif_bottomComment($raw, $compressed) {
        $raw = strlen($raw);
        $compressed = strlen($compressed);
        $savings = ($raw-$compressed) / $raw * 100;
        $savings = round($savings, 2);
        return '<!--HTML compressed, size saved '.$savings.'%. From '.$raw.' bytes, now '.$compressed.' bytes-->';
    }

    protected function tif_minifyHTML($html) {
        $pattern = '/<(?<script>script).*?<\/script\s*>|<(?<style>style).*?<\/style\s*>|<!(?<comment>--).*?-->|<(?<tag>[\/\w.:-]*)(?:".*?"|\'.*?\'|[^\'">]+)*>|(?<text>((<[^!\/\w.:-])?[^<]*)+)|/si';
        preg_match_all($pattern, $html, $matches, PREG_SET_ORDER);
        $overriding = false;
        $raw_tag = false;
        $html = '';
        foreach ($matches as $token) {
            $tag = (isset($token['tag'])) ? strtolower($token['tag']) : null;
            $content = $token[0];
            if (is_null($tag)) {
                if ( !empty($token['script']) ) {
                    $strip = $this->tif_compress_js;
                }
                else if ( !empty($token['style']) ) {
                    $strip = $this->tif_compress_css;
                }
                else if ($content == '<!--wp-html-compression no compression-->') {
                    $overriding = !$overriding; 
                    continue;
                }
                else if ($this->tif_remove_comments) {
                    if (!$overriding && $raw_tag != 'textarea') {
                        $content = preg_replace('/<!--(?!\s*(?:\[if [^\]]+]|<!|>))(?:(?!-->).)*-->/s', '', $content);
                    }
                }
            } else {
                if ($tag == 'pre' || $tag == 'textarea') {
                    $raw_tag = $tag;
                }
                else if ($tag == '/pre' || $tag == '/textarea') {
                    $raw_tag = false;
                }
                else {
                    if ($raw_tag || $overriding) {
                        $strip = false;
                    }
                    else {
                        $strip = true; 
                        $content = preg_replace('/(\s+)(\w++(?<!\baction|\balt|\bcontent|\bsrc)="")/', '$1', $content); 
                        $content = str_replace(' />', '/>', $content);
                    }
                }
            } 
            if ($strip) {
                $content = $this->tif_removeWhiteSpace($content);
            }
            $html .= $content;
        } 
        return $html;
    } 

    public function tif_parseHTML($html) {
        $this->html = $this->tif_minifyHTML($html);
        if ($this->tif_info_comment) {
            $this->html .= "\n" . $this->tif_bottomComment($html, $this->html);
        }
    }

    protected function tif_removeWhiteSpace($str) {
        $str = str_replace("\t", ' ', $str);
        $str = str_replace("\n",  '', $str);
        $str = str_replace("\r",  '', $str);
        while (stristr($str, '  ')) {
            $str = str_replace('  ', ' ', $str);
        }   
        return $str;
    }
}

function tif_wp_html_compression_finish($html){
    return new HTML_Minify($html);
}

function tif_wp_html_compression_start() {
    ob_start('tif_wp_html_compression_finish');
}

add_action('get_header', 'tif_wp_html_compression_start');
